<?php
namespace Orion\Setting\Repository;

use Orion\Core\Exception\DataObjectManagerException;
use Orion\Core\Model\Query\PaginatedCollection;
use Orion\Core\Repository\BaseRepository;
use Orion\Setting\Entity\Setting;

/**
 * Class SettingsRepository
 *
 * @package Orion\Setting\Repository
 */
class SettingRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_SETTING_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_SETTING_COLLECTION_';

    /** @var string */
    protected string $entity = Setting::class;

    /**
     * @param int $page
     * @param int $resultPerPage
     *
     * @return PaginatedCollection
     * @throws DataObjectManagerException
     */
    public function getPaginatedSettingList(int $page, int $resultPerPage): PaginatedCollection
    {
        return $this->getPaginatedList(
            $this->getDataObjectManager(),
            $page,
            $resultPerPage
        );
    }
}
