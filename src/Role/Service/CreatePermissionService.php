<?php
namespace Orion\Role\Service;

use Orion\Core\Exception\DataObjectManagerException;
use Orion\Core\Exception\NoSuchEntityException;
use Orion\Core\Interfaces\CustomResponseInterface;
use Orion\Core\Interfaces\HttpResponseCodeInterface;
use Orion\Role\Entity\Permission;
use Orion\Role\Exception\RoleException;
use Orion\Role\Interfaces\Response\RoleResponseCodeInterface;
use Orion\Role\Repository\PermissionRepository;

/**
 * Class CreatePermissionService
 *
 * @package Orion\Role\Service
 */
class CreatePermissionService
{
    /**
     * CreatePermissionService constructor.
     *
     * @param PermissionRepository $permissionRepository
     */
    public function __construct(
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
        /** @var Permission $existingPermission */
        $existingPermission = $this->permissionRepository->get($data['name'], 'name', true);

        if ($existingPermission) {
            throw new RoleException(
                __('Permission %s already exists',
                    [$existingPermission->getName()]),
                RoleResponseCodeInterface::RESPONSE_ROLE_PERMISSION_ALREADY_EXIST,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        $permission = $this->getNewPermission($data);

        /** @var Permission $permission */
        $permission = $this->permissionRepository->save($permission);

        return response()
            ->setData($permission);
    }

    /**
     * @param array $data
     *
     * @return Permission
     */
    private function getNewPermission(array $data): Permission
    {
        $permission = new Permission();

        $permission
            ->setName($data['name'])
            ->setDescription($data['description'])
            ->setCreatedAt(new \DateTime());

        return $permission;
    }
}
