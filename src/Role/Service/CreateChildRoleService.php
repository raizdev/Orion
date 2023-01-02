<?php
namespace Orion\Role\Service;

use Orion\Core\Exception\DataObjectManagerException;
use Orion\Core\Exception\NoSuchEntityException;
use Orion\Core\Interfaces\CustomResponseInterface;
use Orion\Core\Interfaces\HttpResponseCodeInterface;
use Orion\Role\Entity\Role;
use Orion\Role\Entity\RoleHierarchy;
use Orion\Role\Exception\RoleException;
use Orion\Role\Interfaces\Response\RoleResponseCodeInterface;
use Orion\Role\Repository\RoleHierarchyRepository;
use Orion\Role\Repository\RoleRepository;

/**
 * Class CreateChildRoleService
 *
 * @package Ares\Role\Service
 */
class CreateChildRoleService
{
    /**
     * CreateChildRoleService constructor.
     *
     * @param RoleHierarchyRepository $roleHierarchyRepository
     * @param RoleRepository          $roleRepository
     */
    public function __construct(
        private RoleHierarchyRepository $roleHierarchyRepository,
        private RoleRepository $roleRepository
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
        /** @var int $parentRoleId */
        $parentRoleId = $data['parent_role_id'];

        /** @var int $childRoleId */
        $childRoleId = $data['child_role_id'];

        $isCycle = $this->checkForCycle($parentRoleId, $childRoleId);

        if ($isCycle) {
            throw new RoleException(
                __('Cycle detected for given Role notations'),
                RoleResponseCodeInterface::RESPONSE_ROLE_CYCLE_DETECTED,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        /** @var Role $parentRole */
        $parentRole = $this->roleRepository->get($parentRoleId);

        /** @var Role $childRole */
        $childRole = $this->roleRepository->get($childRoleId);

        $newChildRole = $this->getNewChildRole(
            $parentRole->getId(),
            $childRole->getId()
        );

        /** @var RoleHierarchy $newChildRole */
        $newChildRole = $this->roleHierarchyRepository->save($newChildRole);

        return response()
            ->setData($newChildRole);
    }

    /**
     * @param int $parentRoleId
     * @param int $childRoleId
     *
     * @return RoleHierarchy
     */
    private function getNewChildRole(int $parentRoleId, int $childRoleId): RoleHierarchy
    {
        $roleHierarchy = new RoleHierarchy();

        $roleHierarchy
            ->setParentRoleId($parentRoleId)
            ->setChildRoleId($childRoleId)
            ->setCreatedAt(new \DateTime());

        return $roleHierarchy;
    }

    /**
     * @param int $parentRoleId
     * @param int $childRoleId
     *
     * @return bool
     * @throws QueryException
     */
    private function checkForCycle(int $parentRoleId, int $childRoleId): bool
    {
        $hasChildRole = $this->roleHierarchyRepository->hasChildRoleId($parentRoleId, $childRoleId);
        $hasParentRole = $this->roleHierarchyRepository->hasParentRoleId($parentRoleId, $childRoleId);
        $areBrothers = $this->roleHierarchyRepository->areBrothers($parentRoleId, $childRoleId);
        $parentIsGrandChild = $this->roleHierarchyRepository->roleIsGrandChild($parentRoleId);

        return $hasChildRole || $hasParentRole || $areBrothers || $parentIsGrandChild;
    }
}
