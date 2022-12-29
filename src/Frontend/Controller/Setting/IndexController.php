<?php
namespace Orion\Frontend\Controller\Setting;

use Orion\Core\Mapping\Annotation as CR;
use Orion\Core\Controller\BaseController;
use Orion\Core\Exception\DataObjectManagerException;
use Orion\Core\Exception\NoSuchEntityException;
use Odan\Session\SessionInterface;
use Orion\Core\Exception\ValidationException;
use Orion\Core\Service\ValidationService;
use Orion\User\Entity\Contract\UserInterface;
use Orion\User\Entity\Contract\UserSettingInterface;
use Orion\User\Service\Settings\ChangePasswordService;
use Orion\User\Service\Settings\ChangeGeneralSettingsService;
use Orion\User\Entity\User;
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
        private ChangeGeneralSettingsService $changeGeneralSettingsService,
        private ValidationService $validationService
    ) {}

    /**
     * @CR\Route(
     *     name="settings-security",
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
        return $this->twig->render($response, '/Frontend/Views/pages/settings/security.twig', [
            'page'  => 'settings'
        ]);
    }

    /**
     * @CR\Route(
     *     name="settings-personalisation",
     *     methods={"GET"},
     *     pattern="/settings/personalisation"
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
    public function personalisation(Request $request, Response $response): Response
    {
        return $this->twig->render($response, '/Frontend/Views/pages/settings/personalisation.twig', [
            'page'  => 'settings_personalisation'
        ]);
    }

    /**
     * @CR\Route(
     *     name="settings-change-personalisation",
     *     methods={"POST"},
     *     pattern="/settings/change-personalisation"
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

     public function changePersonalisation(Request $request, Response $response): Response
     {
         /** @var array $parsedData */
         $parsedData = $request->getParsedBody();
 
         $this->validationService->validate($parsedData, [
             UserInterface::COLUMN_AVATAR_BG => 'required'
         ]);
 
         /** @var User $user */
         $user = user($request);
 
         $customResponse = $this->changeGeneralSettingsService
            ->execute(
                $user,
                $parsedData
            );
 
         return $this->respond(
             $response,
             $customResponse
         );
     }

    /**
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
