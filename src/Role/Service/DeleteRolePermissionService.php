<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Role\Service;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Framework\Interfaces\HttpResponseCodeInterface;
use Ares\Role\Exception\RoleException;
use Ares\Role\Interfaces\Response\RoleResponseCodeInterface;
use Ares\Role\Repository\PermissionRepository;

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
