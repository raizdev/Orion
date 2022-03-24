<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Core\Controller;

use Ares\Article\Repository\ArticleRepository;
use Ares\Badge\Service\BadgeService;
use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Photo\Repository\PhotoRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class IndexController
 *
 * @package Ares\Core\Controller
 */
class IndexController extends BaseController
{
    /**
     * IndexController constructor.
     * @param Twig                  $twig
     * @param ArticleRepository     $articleRepository
     * @param PhotoRepository       $photoRepository
     */
    public function __construct(
        private Twig $twig,
        private ArticleRepository $articleRepository,
        private PhotoRepository $photoRepository,
        private BadgeService $badgeService
    ) {}

    /**
     * Responds to say hello to Twig
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError|DataObjectManagerException
     */
    public function home(Request $request, Response $response): Response
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

        return $this->twig->render($response, 'Core/View/pages/home.twig', [
            'articles' => $articles,
            'photos' => $photos,
            'badges' => $this->badgeService->execute(),
            'page' => 'home'
        ]);
    }
}