<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Frontend\Controller\Setting;

use Cosmic\Core\Mapping\Annotation as CR;
use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Odan\Session\SessionInterface;
use Ares\Framework\Exception\ValidationException;
use Ares\Framework\Service\ValidationService;
use Ares\User\Entity\Contract\UserInterface;
use Ares\User\Entity\Contract\UserSettingInterface;
use Ares\User\Service\Settings\ChangePasswordService;
use Ares\User\Entity\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 *  @package Ares\Frontend\Controller
 *  @CR\Router
 */
class IndexController extends BaseController
{
    /**
     * IndexController constructor.
     * @param Twig $twig
     * @param ChangePasswordService $changePasswordService
     * @param ValidationService $validationService
     */
    public function __construct(
        private Twig $twig,
        private ChangePasswordService $changePasswordService,
        private ValidationService $validationService
    ) {}

    /**
     * Responds to say hello to Twig
     *
     * @CR\Route(
     *     name="settings",
     *     methods={"GET"},
     *     pattern="/settings"
     * )
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError|DataObjectManagerException|NoSuchEntityException
     */
    public function index(Request $request, Response $response): Response
    {
        return $this->twig->render($response, '/Frontend/Views/pages/settings/main.twig', [
            'page'  => 'settings'
        ]);
    }

    /**
     * Responds to say hello to Twig
     *
     * @CR\Route(
     *     name="settings-password",
     *     methods={"POST"},
     *     pattern="/settings-password"
     * )
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError|DataObjectManagerException|NoSuchEntityException
     */

    public function changePassword(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            UserInterface::COLUMN_PASSWORD => 'required',
            'new_password'      => 'required',
            'repeated_password' => 'required|same:new_password'
        ]);

        /** @var User $user */
        $user = user($request);

        $customResponse = $this->changePasswordService
            ->execute(
                $user,
                $parsedData['new_password'],
                $parsedData['password']
            );

        return $this->respond(
            $response,
            $customResponse
        );
    }
}
