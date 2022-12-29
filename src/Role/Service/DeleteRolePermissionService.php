<?php
namespace Orion\Role\Service;

use Orion\Core\Exception\DataObjectManagerException;
use Orion\Core\Interfaces\CustomResponseInterface;
use Orion\Core\Interfaces\HttpResponseCodeInterface;
use Orion\Role\Exception\RoleException;
use Orion\Role\Interfaces\Response\RoleResponseCodeInterface;
use Orion\Role\Repository\PermissionRepository;

/**
 * Class DeleteRolePermissionService
 *
 * @package Ares\Role\Service
 */
class DeleteRolePermissionService
{
    /**
     * DeleteRolePermissionService constructor.
     *
     * @param PermissionRepository $permissionRepository
     */
    public function __construct(
        private PermissionRepository $permissionRepository
    ) {}

    /**
     * @param int $id
     *
     * @return CustomResponseInterface
     * @throws RoleException
     * @throws DataObjectManagerException
     */
    public function execute(int $id): CustomResponseInterface
    {
        $deleted = $this->permissionRepository->delete($id);

        if (!$deleted) {
            throw new RoleException(
                __('Permission could not be deleted'),
                RoleResponseCodeInterface::RESPONSE_ROLE_PERMISSION_NOT_DELETED,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        return response()
            ->setData(true);
    }
}
