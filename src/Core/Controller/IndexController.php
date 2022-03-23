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
use Ares\Framework\Model\Query\PaginatedCollection;
use Ares\Photo\Repository\PhotoRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Ares\Framework\Exception\NoSuchEntityException;

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
     * @throws DataObjectManagerException
     */
    public function getArticles(int $page = 1, int $resultPerPage = 3): PaginatedCollection {
        return $this->articleRepository
            ->getPaginatedArticleList(
                $page,
                $resultPerPage
            );
    }

    /**
     * @throws NoSuchEntityException
     */
    public function getBadges(): bool|array
    {
        return $this->badgeService->execute();
    }

    /**
     * @param int $page
     * @param int $resultPerPage
     *
     * @throws DataObjectManagerException
     */
    public function getPhotos(int $page = 1, int $resultPerPage = 4) : PaginatedCollection {
        return $this->photoRepository
            ->getPaginatedPhotoList(
                $page,
                $resultPerPage
            );
    }

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
        return $this->twig->render($response, 'Core/View/pages/home.twig', [
            'articles' => $this->getArticles(),
            'photos' => $this->getPhotos(),
            'badges' => $this->getBadges(),
            'page' => 'home'
        ]);
    }
}