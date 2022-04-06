<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Vote\Repository;

use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Repository\BaseRepository;
use Ares\Vote\Entity\Vote;
use Ares\Framework\Model\Query\Collection;

/**
 * Class VoteRepository
 *
 * @package Ares\Vote\Repository
 */
class VoteRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_VOTE_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_VOTE_COLLECTION_';

    /** @var string */
    protected string $entity = Vote::class;

    /**
     * @param int $userId
     *
     * @return Collection
     */
    public function getUserVoteList(int $userId): Collection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where('user_id', $userId);

        return $this->getList($searchCriteria);
    }

    /**
     * @param Vote $vote
     * @param int  $userId
     *
     * @return Vote|null
     * @throws NoSuchEntityException
     */
    public function getExistingVote(Vote $vote, int $userId): ?Vote
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where([
                'entity_id' => $vote->getEntityId(),
                'vote_entity' => $vote->getVoteEntity(),
                'user_id' => $userId
            ]);

        /** @var Vote $vote */
        return $this->getOneBy($searchCriteria, true, false);
    }

    /**
     * @param int $entityId
     * @param int $voteEntity
     * @param int $voteType
     * @param int $userId
     *
     * @return Vote|null
     * @throws NoSuchEntityException
     */
    public function getVoteForDeletion(int $entityId, int $voteEntity, int $voteType, int $userId): ?Vote
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where([
                'entity_id' => $entityId,
                'vote_entity' => $voteEntity,
                'vote_type' => $voteType,
                'user_id' => $userId
            ]);

        /** @var Vote $vote */
        return $this->getOneBy($searchCriteria, true);
    }
}
