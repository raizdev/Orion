<?php
namespace Orion\Role\Repository;

use Orion\Core\Exception\NoSuchEntityException;
use Orion\Core\Repository\BaseRepository;
use Orion\Role\Entity\RolePermission;

/**
 * Class RolePermissionRepository
 *
 * @package Ares\Role\Repository
 */
class RolePermissionRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_ROLE_ROLE_PERMISSION_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_ROLE_ROLE_PERMISSION_COLLECTION_';

    /** @var string */
    protected string $entity = RolePermission::class;

    /**
     * @param int $permissionId
     * @param array $roleIds
     *
     * @return bool
     */
    public function isPermissionAssigned(int $permissionId, array $roleIds): bool
    {
        $searchCriteria = $this->getDataObjectManager()
            ->select('id')
            ->where('permission_id', $permissionId)
            ->whereIn('role_id', $roleIds)
            ->limit(1);

        $result = $this->getList($searchCriteria)->toArray();

        return count($result) > 0;
    }

    /**
     * @param int $roleId
     * @param int $permissionId
     *
     * @return RolePermission|null
     * @throws NoSuchEntityException
     */
    public function getExistingRolePermission(int $roleId, int $permissionId): ?RolePermission
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where([
                'role_id' => $roleId,
                'permission_id' => $permissionId
            ]);

        return $this->getOneBy($searchCriteria, true);
    }
}
