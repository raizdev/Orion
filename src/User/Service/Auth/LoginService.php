<?php
namespace Orion\User\Service\Auth;

use Orion\Ban\Entity\Ban;
use Orion\Ban\Exception\BanException;
use Orion\Ban\Repository\BanRepository;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Framework\Interfaces\HttpResponseCodeInterface;
use Ares\Framework\Service\TokenService;
use Orion\User\Entity\Contract\UserInterface;
use Orion\User\Entity\User;
use Orion\User\Exception\LoginException;
use Orion\User\Interfaces\Response\UserResponseCodeInterface;
use Orion\User\Repository\UserRepository;
use Odan\Session\SessionInterface;
use ReallySimpleJWT\Exception\ValidateException;
use Slim\Routing\RouteParser;

/**
 * Class LoginService
 *
 * @package Orion\User\Service\Auth
 */
class LoginService
{
    /**
     * LoginService constructor.
     *
     * @param UserRepository $userRepository
     * @param BanRepository $banRepository
     * @param SessionInterface $session
     * @param RouteParser $routeParser
     * @param TokenService $tokenService
     */
    public function __construct(
        private UserRepository $userRepository,
        private BanRepository $banRepository,
        private SessionInterface $session,
        private RouteParser $routeParser,
        private TokenService $tokenService
    ) {}

    /**
     * Login user.
     *
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws BanException
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
     * @throws ValidateException
     * @throws LoginException
     */
    public function login(array $data): CustomResponseInterface
    {
        /** @var User $user */
        $user = $this->userRepository->get(
            $data['username'],
            'username',
            true
        );

        if (!$user || !password_verify($data['password'], $user->getPassword())) {
            throw new LoginException(
                __('Data combination was not found')
            );
        }

        /** @var Ban $isBanned */
        $isBanned = $this->banRepository->get(
            $user->getId(),
            'user_id',
            true
        );

        if ($isBanned && $isBanned->getBanExpire() > time()) {
            throw new BanException(
                __('You are banned because of %s',
                    [$isBanned->getBanReason()]),
                UserResponseCodeInterface::RESPONSE_AUTH_LOGIN_BANNED,
                HttpResponseCodeInterface::HTTP_RESPONSE_FORBIDDEN
            );
        }

        $user->setLastLogin(time());
        $user->setIpCurrent($data[UserInterface::COLUMN_IP_CURRENT]);

        $this->userRepository->save($user);

        /** @var TokenService $token */
        $token = $this->tokenService->execute($user->getId());
        
        $this->session->set('token', $token);

        return response()
            ->setData([
                'pagetime'  => $this->routeParser->urlFor('home'),
                'status'    => 'success',
                'message'   => __('Logged in successfully'),
            ]);
    }
}

