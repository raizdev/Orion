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
use Ares\Role\Repository\RoleRepository;

/**
 * Class DeleteRoleService
 *
 * @package Ares\Role\Service
 */
class DeleteRoleService
{
    /**
     * DeleteRoleService constructor.
     *
     * @param RoleRepository $roleRepository
     */
    public function __construct(
        private RoleRepository $roleRepository
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
        $deleted = $this->roleRepository->delete($id);

        if (!$deleted) {
            throw new RoleException(
                __('Role could not be deleted'),
                RoleResponseCodeInterface::RESPONSE_ROLE_NOT_DELETED,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        return response()
            ->setData(true);
    }
}
