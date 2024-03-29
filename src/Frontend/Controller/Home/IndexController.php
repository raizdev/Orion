<?php

namespace Orion\Frontend\Controller\Home;

use Orion\Room\Repository\RoomRepository;
use Orion\Vote\Repository\VoteRepository;
use Orion\Core\Mapping\Annotation as CR;
use Orion\Article\Repository\ArticleRepository;
use Orion\Badge\Service\BadgeAlbumService;
use Orion\Core\Controller\BaseController;
use Orion\Core\Exception\DataObjectManagerException;
use Orion\Core\Exception\NoSuchEntityException;
use Odan\Session\SessionInterface;
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
     * @param ArticleRepository $articleRepository
     * @param BadgeAlbumService $badgeService
     * @param RoomRepository $roomRepository
     */
    public function __construct(
        private Twig $twig,
        private ArticleRepository $articleRepository,
        private BadgeAlbumService $badgeService,
        private RoomRepository $roomRepository
    ) {}

    /**
     * Throw Client
     *
     * @CR\Route(
     *     name="hotel",
     *     methods={"GET"},
     *     pattern="/hotel"
     * )
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function hotel(Request $request, Response $response): Response
    {
        return $this->twig->render($response, '/Frontend/Views/layouts/app.twig');
    }

    /**
     * Responds to say hello to Twig
     *
     * @CR\Route(
     *     name="home",
     *     methods={"GET"},
     *     pattern="/",
     *     priority=0
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
    public function __invoke(Request $request, Response $response): Response
    {
        $badges = $this->badgeService->execute();

        $articles = $this->articleRepository->getPaginatedArticleList();

        return $this->twig->render($response, '/Frontend/Views/pages/home/home.twig', [
            'articles' => $articles,
            'badges' => $badges,
            'page'  => 'home'
        ]);
    }
}
