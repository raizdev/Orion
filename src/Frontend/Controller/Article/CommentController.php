<?php
namespace Orion\Frontend\Controller\Article;

use Cosmic\Core\Mapping\Annotation as CR;
use Orion\Article\Entity\Contract\CommentInterface;
use Orion\Article\Exception\CommentException;
use Orion\Article\Repository\CommentRepository;
use Orion\Article\Service\CreateCommentService;
use Orion\Article\Service\DeleteCommentService;
use Orion\Article\Service\EditCommentService;
use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\AuthenticationException;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Exception\ValidationException;
use Ares\Framework\Model\Query\PaginatedCollection;
use Ares\Framework\Service\ValidationService;
use Orion\User\Entity\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use function response;
use function user;

/**
 * Class CommentController
 *
 * @package Orion\Article\Controller
 *
 * @CR\Router
 *  @CR\Group(
 *     prefix="comment",
 *     pattern="comment",
 * )
 */
class CommentController extends BaseController
{
    /**
     * CommentController constructor.
     *
     * @param CommentRepository    $commentRepository
     * @param ValidationService    $validationService
     * @param CreateCommentService $createCommentService
     * @param EditCommentService   $editCommentService
     * @param DeleteCommentService $deleteCommentService
     */
    public function __construct(
        private CommentRepository $commentRepository,
        private ValidationService $validationService,
        private CreateCommentService $createCommentService,
        private EditCommentService $editCommentService,
        private DeleteCommentService $deleteCommentService
    ) {}

    /**
     *
     * @CR\Route(
     *     name="create",
     *     methods={"POST"},
     *     pattern="/create"
     * )
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws AuthenticationException
     * @throws DataObjectManagerException
     * @throws ValidationException
     * @throws NoSuchEntityException|CommentException
     */
    public function create(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            CommentInterface::COLUMN_CONTENT => 'required|min:4',
            CommentInterface::COLUMN_ARTICLE_ID => 'required|numeric'
        ]);

        /** @var User $user */
        $userId = user($request)->getId();

        $customResponse = $this->createCommentService->execute($userId, $parsedData);

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
     * @throws ValidationException
     */
    public function edit(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            CommentInterface::COLUMN_ID => 'required|numeric',
            CommentInterface::COLUMN_CONTENT => 'required'
        ]);

        $customResponse = $this->editCommentService->execute($parsedData);

        return $this->respond(
            $response,
            $customResponse
        );
    }

    /**
     * @param Request     $request
     * @param Response    $response
     * @param             $args
     *
     * @return Response
     * @throws DataObjectManagerException
     */
    public function list(Request $request, Response $response, array $args): Response
    {
        /** @var int $articleId */
        $articleId = $args['article_id'];

        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        /** @var PaginatedCollection $comments */
        $comments = $this->commentRepository
            ->getPaginatedCommentList(
                $articleId,
                $page,
                $resultPerPage
            );

        return $this->respond(
            $response,
            response()
                ->setData($comments)
        );
    }

    /**
     * @param Request     $request
     * @param Response    $response
     * @param             $args
     *
     * @return Response
     * @throws CommentException
     * @throws DataObjectManagerException
     */
    public function delete(Request $request, Response $response, array $args): Response
    {
        /** @var int $id */
        $id = $args['id'];

        $customResponse = $this->deleteCommentService->execute($id);

        return $this->respond(
            $response,
            $customResponse
        );
    }
}
