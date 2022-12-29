<?php
namespace Orion\Role\Service;

use Orion\Core\Exception\NoSuchEntityException;
use Orion\Role\Entity\Permission;
use Orion\Role\Repository\PermissionRepository;
use Orion\Role\Repository\RoleHierarchyRepository;
use Orion\Role\Repository\RolePermissionRepository;
use Orion\Role\Repository\RoleRankRepository;
use Orion\User\Repository\UserRepository;

/**
 * Class CheckAccessService
 *
 * @package Orion\Role\Service
 */
class CheckAccessService
{
    /**
     * CheckAccessService constructor.
     *
     * @param PermissionRepository     $permissionRepository
     * @param RoleRankRepository       $roleRankRepository
     * @param RoleHierarchyRepository  $roleHierarchyRepository
     * @param RolePermissionRepository $rolePermissionRepository
     * @param UserRepository           $userRepository
     */
    public function __construct(
        private PermissionRepository $permissionRepository,
        private RoleRankRepository $roleRankRepository,
        private RoleHierarchyRepository $roleHierarchyRepository,
        private RolePermissionRepository $rolePermissionRepository,
        private UserRepository $userRepository
    ) {}

    /**
     * @param int         $userId
     * @param string|null $permissionName
     *
     * @return bool
     * @throws NoSuchEntityException
     */
    public function execute(int $userRank, ?string $permissionName): bool
    {
        /** @var Permission $permission */
        $permission = $this->permissionRepository->get($permissionName, 'name', true);
        
        // When there's no permission set, set anonymous(logged in) access
        if (!$permission) {
            return true;
        }

        $roleIds = $this->roleRankRepository->getRankRoleIds($userRank);

        if ($roleIds && count($roleIds) > 0) {
            $allRoleIds = $this->roleHierarchyRepository->getAllRoleIdsHierarchy($roleIds);

            return $this->rolePermissionRepository->isPermissionAssigned($permission->getId(), $allRoleIds);
        }

        return false;
    }
}