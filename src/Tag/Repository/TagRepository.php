<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Tag\Repository;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Model\Query\PaginatedCollection;
use Ares\Framework\Repository\BaseRepository;
use Ares\Tag\Entity\Tag;

/**
 * Class TagRepository
 *
 * @package Ares\Tag\Repository
 */
class TagRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_TAGS_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_TAGS_COLLECTION_';

    /** @var string */
    protected string $entity = Tag::class;

    /**
     * @param int $page
     * @param int $resultPerPage
     *
     * @return PaginatedCollection
     * @throws DataObjectManagerException
     */
    public function getPaginatedTagList(int $page, int $resultPerPage): PaginatedCollection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->orderBy('id', 'DESC')
            ->addRelation('user');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }

    /**
     * @param int $userId
     * @param int $page
     * @param int $resultPerPage
     *
     * @return PaginatedCollection
     * @throws DataObjectManagerException
     */
    public function getPaginatedUserTagList(int $userId, int $page = 0, int $resultPerPage = 10): PaginatedCollection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where('user_id', $userId)
            ->orderBy('id', 'DESC');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }

        /**
     * @param Tag $vote
     * @param int  $userId
     *
     * @return Tag|null
     * @throws NoSuchEntityException
     */
    public function getExistingTag(Tag $vote, int $userId): ?Tag
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where([
                'user_id' => $userId,
                'tag' => $vote->getTag()
            ]);

        /** @var Vote $tag */
        return $this->getOneBy($searchCriteria, true, false);
    }
}
