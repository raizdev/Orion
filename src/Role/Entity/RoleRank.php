<?php
namespace Orion\Role\Entity;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Model\DataObject;
use Orion\Role\Entity\Contract\RoleRankInterface;
use Orion\Permission\Repository\PermissionRepository;
use Orion\Permission\Entity\Permission;
use DateTime;

/**
 * Class RoleRank
 *
 * @package Orion\Role\Entity
 */
class RoleRank extends DataObject implements RoleRankInterface
{
    /** @var string */
    public const TABLE = 'ares_roles_rank';

    /** @var array */
    public const RELATIONS = [
        'rank' => 'getRank'
    ];

    /**
     * @return int
     */
    public function getId() : int {
        return $this->getData(RoleRankInterface::COLUMN_ID);
    }

    /**
     * @param int $id
     *
     * @return RoleRank
     */
    public function setId(int $id) : RoleRank {
        return $this->setData(RoleRankInterface::COLUMN_ID, $id);
    }

    /**
     * @return int
     */
    public function getRoleId() : int {
        return $this->getData(RoleRankInterface::COLUMN_ROLE_ID);
    }

    /**
     * @param int $role_id
     *
     * @return RoleRank
     */
    public function setRoleId(int $roleId) : RoleRank {
        return $this->setData(RoleRankInterface::COLUMN_ROLE_ID, $roleId);
    }

    /**
     * @return int
     */
    public function getRankId(): int
    {
        return $this->getData(RoleRankInterface::COLUMN_RANK_ID);
    }

    /**
     * @param int $rankId
     *
     * @return RoleRank
     */
    public function setRankId(int $rankId): RoleRank
    {
        return $this->setData(RoleRankInterface::COLUMN_RANK_ID, $rankId);
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->getData(RoleRankInterface::COLUMN_CREATED_AT);
    }

    /**
     * @param DateTime $createdAt
     *
     * @return RoleRank
     */
    public function setCreatedAt(DateTime $createdAt)
    {
        return $this->setData(RoleRankInterface::COLUMN_CREATED_AT, $createdAt);
    }

    /**
     * @return Rank|null
     *
     * @throws DataObjectManagerException
     */
    public function getRank() : ?Permission {
        $rank = $this->getData('rank');

        if($rank) {
            return $rank;
        }

        if(!isset($this)) {
            return null;
        }

        $roleRankRepository = repository(RoleRankRepository::class);

        $rankRepository = repository(PermissionRepository::class);

        $rank = $roleRankRepository->getOneToOne(
            $rankRepository,
            $this->getRankId(),
            'id'
        );

        if(!$rank) {
            return null;
        }

        $this->setRank($rank);

        return $rank;
    }

    
    /**
    * @param Permission $rank
    *
    * @return RoleRank
    */
    public function setRank(Permission $rank) : RoleRank {
        return $this->setData('rank', $rank);
    }
}