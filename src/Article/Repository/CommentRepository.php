<?php
namespace Orion\Article\Repository;

use Orion\Article\Entity\Comment;
use Orion\Core\Exception\DataObjectManagerException;
use Orion\Core\Model\Query\PaginatedCollection;
use Orion\Core\Repository\BaseRepository;

/**
 * Class CommentRepository
 *
 * @package Ares\Article\Repository
 */
class CommentRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_COMMENT_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_COMMENT_COLLECTION_';

    /** @var string */
    protected string $entity = Comment::class;

    /**
     * @param int $articleId
     * @param int $page
     * @param int $resultPerPage
     *
     * @return PaginatedCollection
     * @throws DataObjectManagerException
     */
    public function getPaginatedCommentList(int $articleId, int $page = 1, int $resultPerPage = 5): PaginatedCollection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where('article_id', $articleId)
            ->orderBy('id', 'DESC')
            ->addRelation('user');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }

    /**
     * @param int $userId
     * @param int $articleId
     *
     * @return int
     */
    public function getUserCommentCount(int $userId, int $articleId): int
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where([
                'user_id' => $userId,
                'article_id' => $articleId
            ]);

        return $this->getList($searchCriteria)->count();
    }
}
