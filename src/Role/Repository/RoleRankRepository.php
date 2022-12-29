<?php
namespace Orion\Role\Repository;


use Orion\Core\Exception\NoSuchEntityException;
use Orion\Core\Repository\BaseRepository;
use Orion\Role\Entity\RoleRank;
use Orion\Role\Entity\Contract\RoleRankInterface;
use Illuminate\Database\QueryException;

/**
 * Class RoleRankRepository
 *
 * @package Ares\Role\Repository
 */
class RoleRankRepository extends BaseRepository {

    /** @var string */
    protected string $cachePrefix = 'ARES_ROLE_RANK_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_ROLE_RANK_COLLECTION_';

    /** @var string */
    protected string $entity = RoleRank::class;

    /**
     * @param int $rankId
     *
     * @return array|null
     * @throws QueryException
     */
    public function getRankRoleIds(int $rankId) : ?array {
        $searchCriteria = $this->getDataObjectManager()
        ->select('role_id')
        ->where('rank_id', $rankId);

        return $this->getList($searchCriteria)->get('role_id');
    }

    /**
     * @param int $roleId
     * @param int $rankId
     *
     * @return RoleRank|null
     * @throws NoSuchEntityException
     */
    public function getRankAssignedRole(int $roleId, int $rankId): ?RoleRank {
        $searchCriteria = $this->getDataObjectManager()->where([
            RoleRankInterface::COLUMN_ROLE_ID => $roleId,
            RoleRankInterface::COLUMN_RANK_ID => $rankId
        ]);

        return $this->getOneBy($searchCriteria, true, false);
    }

    /**
     * @param array $allRankRoleIds
     *
     * @return array|null
     */
    public function getRankPermissions(array $allRankRoleIds): ?array
    {
        $searchCriteria = $this->getDataObjectManager()
            ->select([
                'ares_roles_permission.id',
                'ares_roles_permission.role_id',
                'ares_roles_permission.permission_id'
            ])->from('ares_roles_permission')
            ->leftJoin(
                'ares_permissions',
                'ares_permissions.id',
                '=',
                'ares_roles_permission.permission_id'
            )->whereIn(
                'ares_roles_permission.role_id',
                $allRankRoleIds
            )->select('ares_permissions.name');

        return array_values(
            array_unique(
                $this->getList($searchCriteria)->get('name') ?? []
            )
        );
    }
}