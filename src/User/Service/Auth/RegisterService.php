<?php
namespace Orion\User\Service\Auth;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Framework\Interfaces\HttpResponseCodeInterface;
use Ares\Framework\Service\TokenService;
use Orion\User\Entity\User;
use Orion\User\Exception\RegisterException;
use Orion\User\Interfaces\Response\UserResponseCodeInterface;
use Orion\User\Interfaces\UserCurrencyTypeInterface;
use Orion\User\Repository\UserRepository;
use Orion\User\Service\Currency\CreateCurrencyService;
use Exception;
use Odan\Session\SessionInterface;
use Cosmic\Core\Config;
use ReallySimpleJWT\Exception\ValidateException;

/**
 * Class RegisterService
 *
 * @package Orion\User\Service\Auth
 */
class RegisterService
{
    /**
     * LoginService constructor.
     *
     * @param UserRepository        $userRepository
     * @param TokenService          $tokenService
     * @param TicketService         $ticketService
     * @param HashService           $hashService
     * @param Config                $config
     * @param CreateCurrencyService $createCurrencyService
     */
    public function __construct(
        private UserRepository $userRepository,
        private TokenService $tokenService,
        private TicketService $ticketService,
        private HashService $hashService,
        private Config $config,
        private CreateCurrencyService $createCurrencyService,
        private SessionInterface $session
    ) {}

    /**
     * Registers a new User.
     *
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws RegisterException
     * @throws ValidateException
     * @throws DataObjectManagerException
     * @throws \Exception
     */
    public function register(array $data): CustomResponseInterface
    {
        /** @var User $isAlreadyRegistered */
        $isAlreadyRegistered = $this->userRepository
            ->getRegisteredUser(
                $data['username'],
                $data['mail']
            );

        if ($isAlreadyRegistered) {
            throw new RegisterException(
                __('Username or E-Mail is already taken'),
                UserResponseCodeInterface::RESPONSE_AUTH_REGISTER_TAKEN,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        $this->isEligible($data);

        /** @var User $user */
        $user = $this->userRepository->save($this->getNewUser($data));

        /** @var TokenService $token */
        $token = $this->tokenService->execute($user->getId());

        try {
            $this->createCurrencyService->execute(
                $user->getId(),
                UserCurrencyTypeInterface::CURRENCY_TYPE_POINTS,
                $this->config->get('hotel_settings.start_points')
            );

            $this->createCurrencyService->execute(
                $user->getId(),
                UserCurrencyTypeInterface::CURRENCY_TYPE_PIXELS,
                $this->config->get('hotel_settings.start_pixels')
            );
        } catch (Exception $exception) {
            throw new RegisterException($exception->getMessage(), $exception->getCode());
        }

        $this->session->set('token', $token);
        
        return response()
            ->setData([
                'status' => 'success',
                'message'   => 'Your account has been created! you will be redirectd',
                'pagetime' => '/',
                'token' => $token
            ]);
    }

    /**
     * Returns new user.
     *
     * @param array $data
     *
     * @return User
     * @throws \Exception
     */
    private function getNewUser(array $data): User
    {
        $user = new User();

        return $user
            ->setUsername($data['username'])
            ->setPassword($this->hashService->hash($data['password']))
            ->setMail($data['mail'])
            ->setCredits($this->config->get('hotel_settings.start_credits'))
            ->setMotto($this->config->get('hotel_settings.start_motto'))
            ->setIPRegister($data['ip_register'])
            ->setIpCurrent($data['ip_current'])
            ->setLastLogin(time())
            ->setRank(1)
            ->setAuthTicket($this->ticketService->hash($user))
            ->setCreatedAt(new \DateTime());
    }

    /**
     * @param $data
     *
     * @return bool
     * @throws RegisterException
     */
    private function isEligible($data): bool
    {
        /** @var int $maxAccountsPerIp */
        $maxAccountsPerIp = $this->config->get('hotel_settings.register.max_accounts_per_ip');
        $accountExistence = $this->userRepository->getAccountCountByIp($data['ip_register']);

        if ($accountExistence >= $maxAccountsPerIp) {
            throw new RegisterException(
                __('You can only have %s Accounts',
                    [$maxAccountsPerIp]),
                UserResponseCodeInterface::RESPONSE_AUTH_REGISTER_EXCEEDED,
                HttpResponseCodeInterface::HTTP_RESPONSE_FORBIDDEN
            );
        }

        return true;
    }
}
