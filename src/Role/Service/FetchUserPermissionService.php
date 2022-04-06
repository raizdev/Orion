<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *  
 * @see LICENSE (MIT)
 */

namespace Ares\Role\Service;

use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Role\Repository\RoleHierarchyRepository;
use Ares\Role\Repository\RoleRankRepository;

/**
 * Class FetchUserPermissionService
 *
 * @package Ares\Role\Service
 */
class FetchUserPermissionService
{
    /**
     * FetchUserPermissionService constructor.
     *
     * @param RoleRankRepository      $roleRankRepository
     * @param RoleHierarchyRepository $roleHierarchyRepository
     */
    public function __construct(
        private RoleRankRepository $roleRankRepository,
        private RoleHierarchyRepository $roleHierarchyRepository
    ) {}

    /**
     * @param int $userId
     *
     * @return CustomResponseInterface
     */
    public function execute(int $rank): CustomResponseInterface
    {
        /** @var array $rankRoleIds */
        $rankRoleIds = $this->roleRankRepository->getRankRoleIds($rank);
        
        if (!$rankRoleIds) {
            return response()->setData([]);
        }

        /** @var array $allRoleIds */
        $allRoleIds = $this->roleHierarchyRepository->getAllRoleIdsHierarchy($rankRoleIds);

        /** @var array $permissions */
        $permissions = $this->roleRankRepository->getRankPermissions($allRoleIds);

        return response()->setData($permissions);
    }
}