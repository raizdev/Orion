<?php
namespace Orion\User\Repository;

use Orion\Core\Exception\DataObjectManagerException;
use Orion\Core\Exception\NoSuchEntityException;
use Orion\Core\Model\Query\Collection;
use Orion\Core\Repository\BaseRepository;
use Orion\User\Entity\UserSetting;

/**
 * Class UserSettingRepository
 *
 * @package Orion\User\Repository
 */
class UserSettingRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_USER_SETTING_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_USER_SETTING_COLLECTION_';

    /** @var string */
    protected string $entity = UserSetting::class;

    /**
     * @return Collection
     *
     * @throws DataObjectManagerException
     */
    public function getTopRespects(): Collection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->select(['user_id', 'respects_received'])
            ->orderBy('respects_received', 'DESC')
            ->addRelation('user')
            ->limit(3);

        return $this->getList($searchCriteria);
    }

    /**
     * @return Collection
     *
     * @throws DataObjectManagerException
     */
    public function getTopAchievements(): Collection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->select(['user_id', 'achievement_score'])
            ->orderBy('achievement_score', 'DESC')
            ->addRelation('user')
            ->limit(3);

        return $this->getList($searchCriteria);
    }

    /**
     * @return Collection
     *
     * @throws DataObjectManagerException
     */
    public function getTopOnlineTime(): Collection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->select(['user_id', 'online_time'])
            ->orderBy('online_time', 'DESC')
            ->addRelation('user')
            ->limit(3);

        return $this->getList($searchCriteria);
    }

    /**
     * @return UserSetting|null
     *
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
     */
    public function getUserWithMostRespects(): ?UserSetting
    {
        $searchCriteria = $this->getDataObjectManager()
            ->select(['user_id', 'respects_received'])
            ->orderBy('respects_received', 'DESC')
            ->limit(1)
            ->addRelation('user');

        return $this->getOneBy($searchCriteria);
    }
}
