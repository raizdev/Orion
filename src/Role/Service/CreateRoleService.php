<?php
namespace Orion\Role\Service;

use Orion\Core\Exception\DataObjectManagerException;
use Orion\Core\Exception\NoSuchEntityException;
use Orion\Core\Interfaces\CustomResponseInterface;
use Orion\Core\Interfaces\HttpResponseCodeInterface;
use Orion\Role\Entity\Role;
use Orion\Role\Exception\RoleException;
use Orion\Role\Interfaces\Response\RoleResponseCodeInterface;
use Orion\Role\Repository\RoleRepository;

/**
 * Class CreateRoleService
 *
 * @package Orion\Role\Service
 */
class CreateRoleService
{
    /**
     * CreateRoleService constructor.
     *
     * @param RoleRepository $roleRepository
     */
    public function __construct(
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
        /** @var Role $existingRole */
        $existingRole = $this->roleRepository->get($data['name'], 'name', true);

        if ($existingRole) {
            throw new RoleException(
                __('Role %s already exists',
                    [$existingRole->getName()]),
                RoleResponseCodeInterface::RESPONSE_ROLE_ALREADY_EXIST,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        $role = $this->getNewRole($data);

        /** @var Role $role */
        $role = $this->roleRepository->save($role);

        return response()
            ->setData($role);
    }

    /**
     * @param array $data
     *
     * @return Role
     */
    private function getNewRole(array $data): Role
    {
        $role = new Role();

        $role
            ->setName($data['name'])
            ->setDescription($data['description'])
            ->setCreatedAt(new \DateTime());

        return $role;
    }
}
