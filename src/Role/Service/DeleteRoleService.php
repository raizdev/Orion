<?php
namespace Orion\Role\Service;

use Orion\Core\Exception\DataObjectManagerException;
use Orion\Core\Interfaces\CustomResponseInterface;
use Orion\Core\Interfaces\HttpResponseCodeInterface;
use Orion\Role\Exception\RoleException;
use Orion\Role\Interfaces\Response\RoleResponseCodeInterface;
use Orion\Role\Repository\RoleRepository;

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
