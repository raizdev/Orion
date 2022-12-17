<?php declare(strict_types=1);
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Frontend\Controller\User;

use Cosmic\Core\Mapping\Annotation as CR;
use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\AuthenticationException;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Exception\ValidationException;
use Ares\Framework\Service\ValidationService;
use Ares\User\Entity\Contract\UserInterface;
use Ares\User\Entity\User;
use Ares\User\Repository\UserRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use function response;
use function user;

/**
 * Class UserController
 *
 * @package Ares\Frontend\Controller\Auth
 * @CR\Router
 * @CR\Group(
 *     prefix="user",
 *     pattern="user"
 * )
 */
class UserController extends BaseController
{
    /**
     * UserController constructor.
     *
     * @param UserRepository    $userRepository
     * @param ValidationService $validationService
     */
    public function __construct(
        private UserRepository $userRepository,
        private ValidationService $validationService
    ) {}

    /**
     * Retrieves the logged in User via JWT - Token
     *
     * @param Request  $request  The current incoming Request
     * @param Response $response The current Response
     *
     * @return Response Returns a Response with the given Data
     * @throws AuthenticationException
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
     */
    public function user(Request $request, Response $response): Response
    {
        /** @var User $user */
        $user = user($request);
        $user->getRoles();
        $user->getCurrencies();
        $user->getPermissions();

        return $this->respond(
            $response,
            response()
                ->setData($user)
        );
    }

    /**
     * Get look from specific user
     *
     * @CR\Route(
     *     name="look",
     *     methods={"POST"},
     *     pattern="/look"
     * )
     * 
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws NoSuchEntityException
     * @throws ValidationException
     */
    public function getLook(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            UserInterface::COLUMN_USERNAME => 'required',
        ]);

        $userLook = $this->userRepository->getUserLook($parsedData['username']);

        return $this->respond(
            $response,
            response()
                ->setData($userLook)
        );
    }

    /**
     * Gets all current Online User and counts them
     *
     * @CR\Route(
     *     name="online",
     *     methods={"GET"},
     *     pattern="/online"
     * )
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     */
    public function onlineUser(Request $request, Response $response): Response
    {
        $onlineUser = $this->userRepository->getUserOnlineCount();

        return $this->respond(
            $response,
            response()
                ->setData([
                    'count' => $onlineUser
                ])
        );
    }
}
