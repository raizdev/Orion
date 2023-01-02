<?php
namespace Orion\Frontend\Controller\Api;

use Orion\User\Entity\User;
use Orion\User\Repository\UserRepository;
use Orion\Core\Mapping\Annotation as CR;
use Orion\Core\Controller\BaseController;
use Orion\Core\Config;
use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class ApiController
 *
 * @package Ares\Frontend\Controller\Config
 * @CR\Router
 * @CR\Group(
 *     prefix="api",
 *     pattern="api"
 * )
 */
class ApiController extends BaseController
{
    /**
     * ConfigController constructor.
     * @param Config $config
     * @param UserRepository $userRepository,
     * @param SessionInterface $session
     */
    public function __construct(
        private Config $config,
        private UserRepository $userRepository,
        private SessionInterface $session
    ) {}

    /**
     * Responds Config
     *
     * @CR\Route(
     *     name="config",
     *     methods={"GET"},
     *     pattern="/config"
     * )
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function config(Request $request, Response $response): Response
    {
        /** @var array $config */
        $config = $this->config->get('website_settings');

        unset($config["recaptcha"]["secretkey"]);

        $config["online_users"] = $this->userRepository->getUserOnlineCount();

        if($this->session->has('user')) {
            /** @var User $user */
            $user = user($request);

            $config["user"]["currencies"] = $user->getCurrencies();
            $config["user"]["credits"] = $user->getCredits();
        }

        return $this->respond(
            $response,
            response()->setData($config)
        );
    }
}
