<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Frontend\Controller\Config;

use Ares\User\Entity\User;
use Ares\User\Repository\UserRepository;
use Ares\Vote\Repository\VoteRepository;
use Cosmic\Core\Mapping\Annotation as CR;
use Ares\Framework\Controller\BaseController;
use Cosmic\Core\Config;
use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class ConfigController
 *
 * @package Ares\Frontend\Controller
 * @CR\Router
 */
class ConfigController extends BaseController
{
    /**
     * ConfigController constructor.
     * @param Config $config
     * @param UserRepository $userRepository,
     * @param VoteRepository $voteRepository,
     * @param SessionInterface $session
     */
    public function __construct(
        private Config $config,
        private UserRepository $userRepository,
        private VoteRepository $voteRepository,
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
    public function __invoke(Request $request, Response $response): Response
    {
        /** @var array $config */
        $config = $this->config->get('site_settings');

        $config["online_users"] = $this->userRepository->getUserOnlineCount();

        if($this->session->has('user')) {

            /** @var User $user */
            $user = user($request);

            $config["votes"] = $this->voteRepository->getUserVoteList($user->getId());
        }

        return $this->respond(
            $response,
            response()->setData($config)
        );
    }
}
