<?php
namespace Orion\Frontend\Controller\Article;

use Orion\Article\Entity\Comment;
use Orion\Article\Repository\CommentRepository;
use Orion\Core\Mapping\Annotation as CR;
use Orion\Article\Entity\Article;
use Orion\Article\Entity\Contract\ArticleInterface;
use Orion\Article\Exception\ArticleException;
use Orion\Article\Repository\ArticleRepository;
use Orion\Article\Service\CreateArticleService;
use Orion\Article\Service\DeleteArticleService;
use Orion\Article\Service\EditArticleService;
use Orion\Core\Controller\BaseController;
use Orion\Core\Exception\AuthenticationException;
use Orion\Core\Exception\DataObjectManagerException;
use Orion\Core\Exception\NoSuchEntityException;
use Orion\Core\Exception\ValidationException;
use Orion\Core\Service\ValidationService;
use Orion\User\Entity\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use function user;

/**
 * Class ArticleController
 *
 * @package Orion\Article\Controller
 * @CR\Router
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
     * @param CommentRepository     $commentRepository
     */
    public function __construct(
        private Twig $twig,
        private ArticleRepository $articleRepository,
        private CreateArticleService $createArticleService,
        private EditArticleService $editArticleService,
        private ValidationService $validationService,
        private DeleteArticleService $deleteArticleService,
        private CommentRepository $commentRepository
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
     * @CR\Route(
     *     name="article-view",
     *     methods={"GET"},
     *     pattern="/articles/{id}/{slug}"
     * )
     *
     * @param Request $request
     * @param Response $response
     *
     * @param array $args
     *
     * @return Response
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function article(Request $request, Response $response, array $args): Response
    {
        /** @var string $slug */
        $id = $args['id'];

        /** @var Article $article */
        $article = $this->articleRepository->getArticleWithCommentCount($id);

        /** @var Comment $comments */
        $comments = $this->commentRepository->getPaginatedCommentList($id);

        /** @var Comment $list */
        $list = $this->articleRepository->getPaginatedArticleList();
        
        return $this->twig->render($response, 'Frontend/Views/pages/community/article.twig', [
            'article' => $article,
            'comments' => $comments,
            'list' => $list,
            'page' => 'article'
        ]);
    }

    /**
     * @CR\Route(
     *     name="article-list",
     *     methods={"GET"},
     *     pattern="/articles/list/{category}/{slug}"
     * )
     *
     * @param Request $request
     * @param Response $response
     *
     * @param array $args
     *
     * @return Response
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function list(Request $request, Response $response, array $args): Response
    {
        /** @var string $category */
        $id = $args['category'];

        /** @var Article $list */
        $list = $this->articleRepository->getPaginatedArticleList(0, 5, $id);

        /** @var Comment $comments */
        $comments = $this->commentRepository->getPaginatedCommentList($id);

        return $this->twig->render($response, 'Frontend/Views/pages/community/article.twig', [
            'comments' => $comments,
            'list' => $list,
            'page' => 'article'
        ]);
    }
}
