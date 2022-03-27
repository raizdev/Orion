<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Frontend\Controller\Home;

use Cosmic\Core\Mapping\Annotation as CR;
use Ares\Article\Repository\ArticleRepository;
use Ares\Badge\Service\BadgeAlbumService;
use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Photo\Repository\PhotoRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * @CR\Router
 */
class IndexController extends BaseController
{
    /**
     * IndexController constructor.
     * @param Twig $twig
     * @param ArticleRepository $articleRepository
     * @param PhotoRepository $photoRepository
     * @param BadgeAlbumService $badgeService
     */
    public function __construct(
        private Twig $twig,
        private ArticleRepository $articleRepository,
        private PhotoRepository $photoRepository,
        private BadgeAlbumService $badgeService
    ) {}

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
        $articles = $this->articleRepository
            ->getPaginatedArticleList(
                1,
                3
            );

        $photos = $this->photoRepository
            ->getPaginatedPhotoList(
                1,
                4
            );

        return $this->twig->render($response, '/Frontend/Views/pages/home/home.twig', [
            'articles' => $articles,
            'photos' => $photos,
            'badges' => $this->badgeService->execute(),
            'page' => 'home'
        ]);
    }
}