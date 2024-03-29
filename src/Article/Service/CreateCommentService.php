<?php
namespace Orion\Article\Service;

use Orion\Article\Entity\Article;
use Orion\Article\Entity\Comment;
use Orion\Article\Exception\CommentException;
use Orion\Article\Interfaces\Response\ArticleResponseCodeInterface;
use Orion\Article\Repository\ArticleRepository;
use Orion\Article\Repository\CommentRepository;
use Orion\Core\Exception\DataObjectManagerException;
use Orion\Core\Exception\NoSuchEntityException;
use Orion\Core\Interfaces\CustomResponseInterface;
use Orion\Core\Interfaces\HttpResponseCodeInterface;
use Orion\Core\Config;
use Slim\Routing\RouteParser;

/**
 * Class CreateCommentService
 *
 * @package Ares\Article\Service
 */
class CreateCommentService
{
    /**
     * CreateCommentService constructor.
     *
     * @param ArticleRepository $articleRepository
     * @param CommentRepository $commentRepository
     * @param Config            $config
     */
    public function __construct(
        private ArticleRepository $articleRepository,
        private CommentRepository $commentRepository,
        private RouteParser $routeParser,
        private Config $config
    ) {}

    /**
     * Creates new comment.
     *
     * @param int   $userId
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws DataObjectManagerException|NoSuchEntityException
     * @throws CommentException
     */
    public function execute(int $userId, array $data): CustomResponseInterface
    {
        /** @var Article $article */
        $article = $this->articleRepository->get($data['article_id']);

        $commentCount = $this->commentRepository->getUserCommentCount($userId, $article->getId());

        if ($commentCount >= $this->config->get('hotel_settings.news.comment_max')) {
            throw new CommentException(
                __('User exceeded allowed comments'),
                ArticleResponseCodeInterface::RESPONSE_ARTICLE_COMMENT_EXCEEDED,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        $comment = $this->getNewComment($userId, $article->getId(), $data);

        /** @var Comment $comment */
        $comment = $this->commentRepository->save($comment);
        $comment->getUser();

        return response()
            ->setData([
                'replacepage'  => $this->routeParser->urlFor('article-view', [
                    'id' => $article->getId(),
                    'slug' => $article->getSlug()
                ]),
                'status'    => 'success',
                'message'   => __('Comment posted!'),
            ]);
    }

    /**
     * Returns new comment object with data.
     *
     * @param int   $userId
     * @param int   $articleId
     * @param array $data
     *
     * @return Comment
     */
    private function getNewComment(int $userId, int $articleId, array $data): Comment
    {
        $comment = new Comment();

        return $comment
            ->setContent($data['content'])
            ->setIsEdited(0)
            ->setUserId($userId)
            ->setLikes(0)
            ->setDislikes(0)
            ->setArticleId($articleId)
            ->setLikes(0)
            ->setDislikes(0)
            ->setCreatedAt(new \DateTime());
    }
}
