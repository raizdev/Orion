<?php
namespace Orion\Role\Repository;

use Orion\Core\Exception\DataObjectManagerException;
use Orion\Core\Model\Query\PaginatedCollection;
use Orion\Core\Repository\BaseRepository;
use Orion\Role\Entity\Permission;

/**
 * Class PermissionRepository
 *
 * @package Orion\Role\Repository
 */
class PermissionRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_ROLE_PERMISSION_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_ROLE_PERMISSION_COLLECTION_';

    /** @var string */
    protected string $entity = Permission::class;

    /**
     * @param int $page
     * @param int $resultPerPage
     *
     * @return PaginatedCollection
     * @throws DataObjectManagerException
     */
    public function getPaginatedPermissionList(int $page, int $resultPerPage): PaginatedCollection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->orderBy('id', 'DESC');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }
}
