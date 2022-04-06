<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Role\Service;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Framework\Interfaces\HttpResponseCodeInterface;
use Ares\Role\Entity\Role;
use Ares\Role\Entity\RoleRank;
use Ares\Role\Exception\RoleException;
use Ares\Role\Interfaces\Response\RoleResponseCodeInterface;
use Ares\Role\Repository\RoleRepository;
use Ares\Role\Repository\RoleRankRepository;
use Ares\Permission\Entity\Permission;
use Ares\Permission\Repository\PermissionRepository;
use DateTime;

/**
 * Class AssignRanktoRoleService
 *
 * @package Ares\Role\Service
 */
class AssignRankToRoleService {
    /**
     * AssignRankToRoleService constructor.
     *
     * @param RoleRankRepository    $roleRankRepository
     * @param RoleRepository        $roleRepository
     * @param PermissionRepository  $permissionRepository
     */
    public function __construct(
        private RoleRankRepository $roleRankRepository,
        private RoleRepository $roleRepository,
        private PermissionRepository $permissionRepository
    ) {}

    /**
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws DataObjectManagerException
     * @throws RoleException
     * @throws NoSuchEntityException
     */
    public function execute(array $data): CustomResponseInterface
    {
        /** @var int $rankId */
        $rankId = $data['rank_id'];

        /** @var int $roleId */
        $roleId = $data['role_id'];

        /** @var Role $role */
        $role = $this->roleRepository->get($roleId);

        /** @var Permission $rank */
        $rank = $this->permissionRepository->get($rankId);

        /** @var RoleRank $isRoleAlreadyAssigned */
        $isRoleAlreadyAssigned = $this->roleRankRepository->getRankAssignedRole($role->getId(), $rank->getId());

        if ($isRoleAlreadyAssigned) {
            throw new RoleException(
                __('There is already a Role assigned to that Rank'),
                RoleResponseCodeInterface::RESPONSE_ROLE_ALREADY_ASSIGNED_TO_RANK,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        $roleRank = $this->getNewRoleRank($role->getId(), $rank->getId());

        $this->roleRankRepository->save($roleRank);

        return response()
            ->setData(true);
    }

        /**
     * @param int $roleId
     * @param int $rankId
     *
     * @return RoleRank
     */
    private function getNewRoleRank(int $roleId, int $rankId): RoleRank
    {
        $roleRank = new RoleRank();

        $roleRank
            ->setRankId($rankId)
            ->setRoleId($roleId)
            ->setCreatedAt(new DateTime());

        return $roleRank;
    }
}