<?php
namespace Orion\Setting\Repository;

use Orion\Core\Exception\DataObjectManagerException;
use Orion\Core\Model\Query\PaginatedCollection;
use Orion\Core\Repository\BaseRepository;
use Orion\Setting\Entity\EmulatorSetting;

/**
 * Class SettingsRepository
 *
 * @package Orion\Setting\Repository
 */
class EmulatorSettingRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'EMULATOR_SETTING';

    /** @var string */
    protected string $cacheCollectionPrefix = 'EMULATOR_SETTING_COLLECTION_';

    /** @var string */
    protected string $entity = EmulatorSetting::class;

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
