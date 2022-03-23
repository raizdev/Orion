<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Setting\Repository;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Model\Query\PaginatedCollection;
use Ares\Framework\Repository\BaseRepository;
use Ares\Setting\Entity\EmulatorSetting;

/**
 * Class SettingsRepository
 *
 * @package Ares\Setting\Repository
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
