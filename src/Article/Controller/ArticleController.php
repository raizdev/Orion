<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Article\Controller;

use Ares\Article\Entity\Contract\ArticleInterface;
use Ares\Article\Service\CreateArticleService;
use Ares\Article\Service\DeleteArticleService;
use Ares\Article\Service\EditArticleService;
use Ares\Framework\Controller\BaseController;
use Ares\Article\Entity\Article;
use Ares\Article\Exception\ArticleException;
use Ares\Article\Repository\ArticleRepository;
use Ares\Framework\Exception\AuthenticationException;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Exception\ValidationException;
use Ares\Framework\Model\Query\PaginatedCollection;
use Ares\Framework\Service\ValidationService;
use Ares\User\Entity\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

/**
 * Class ArticleController
 *
 * @package Ares\Article\Controller
 */
class ArticleController extends BaseController
{
    /**
     * ArticleController constructor.
     *
     * @param Twig                  $twig
     * @param ArticleRepository     $articleRepository
     * @param CreateArticleService  $createArticleService
     * @param EditArticleService    $editArticleService
     * @param ValidationService     $validationService
     * @param DeleteArticleService  $deleteArticleService
     */
    public function __construct(
        private Twig $twig,
        private ArticleRepository $articleRepository,
        private CreateArticleService $createArticleService,
        private EditArticleService $editArticleService,
        private ValidationService $validationService,
        private DeleteArticleService $deleteArticleService
    ) {}

    /**
     * Creates new article.
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws ArticleException
     * @throws DataObjectManagerException
     * @throws ValidationException
     * @throws AuthenticationException
     * @throws NoSuchEntityException
     */
    public function create(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            ArticleInterface::COLUMN_TITLE => 'required',
            ArticleInterface::COLUMN_DESCRIPTION => 'required',
            ArticleInterface::COLUMN_CONTENT => 'required',
            ArticleInterface::COLUMN_IMAGE => 'required',
            ArticleInterface::COLUMN_THUMBNAIL => 'required',
            ArticleInterface::COLUMN_HIDDEN => 'required|numeric',
            ArticleInterface::COLUMN_PINNED => 'required|numeric'
        ]);

        /** @var User $user */
        $userId = user($request)->getId();

        $customResponse = $this->createArticleService->execute($userId, $parsedData);

        return $this->respond(
            $response,
            $customResponse
        );
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
     * @throws ValidationException|ArticleException
     */
    public function editArticle(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            ArticleInterface::COLUMN_ID => 'required|numeric',
            ArticleInterface::COLUMN_TITLE => 'required',
            ArticleInterface::COLUMN_DESCRIPTION => 'required',
            ArticleInterface::COLUMN_CONTENT => 'required',
            ArticleInterface::COLUMN_IMAGE => 'required',
            ArticleInterface::COLUMN_THUMBNAIL => 'required',
            ArticleInterface::COLUMN_HIDDEN => 'required|numeric',
            ArticleInterface::COLUMN_PINNED => 'required|numeric'
        ]);

        $customResponse = $this->editArticleService->execute($parsedData);

        return $this->respond(
            $response,
            $customResponse
        );
    }

    /**
     * Deletes specific article.
     *
     * @param Request     $request
     * @param Response    $response
     * @param             $args
     *
     * @return Response
     * @throws ArticleException
     * @throws DataObjectManagerException
     */
    public function delete(Request $request, Response $response, array $args): Response
    {
        /** @var int $id */
        $id = $args['id'];

        $customResponse = $this->deleteArticleService->execute($id);

        return $this->respond(
            $response,
            $customResponse
        );
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @param array    $args
     *
     * @return Response
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
     */
    public function article(Request $request, Response $response, array $args): Response
    {
        /** @var string $slug */
        $id = $args['id'];

        /** @var Article $article */
        $article = $this->articleRepository->getArticleWithCommentCount($id);

        return $this->twig->render($response, 'Article/Views/pages/article.twig', [
            'article' => $article,
            'page' => 'article'
        ]);
    }
}
