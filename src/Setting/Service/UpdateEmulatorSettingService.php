<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Setting\Service;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Setting\Entity\Setting;
use Ares\Setting\Repository\EmulatorSettingRepository;

/**
 * Class UpdateEmulatorSettingService
 *
 * @package Ares\Setting\Service
 */
class UpdateEmulatorSettingService
{
    /**
     * UpdateSettingService constructor.
     *
     * @param EmulatorSettingRepository $emulatorSettingRepository
     */
    public function __construct(
        private EmulatorSettingRepository $emulatorSettingRepository
    ) {}

    /**
     * @param $data
     *
     * @return CustomResponseInterface
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
     */
    public function update($data): CustomResponseInterface
    {
        /** @var string $key */
        $key = $data['key'];

        /** @var string $value */
        $value = $data['value'];

        /** @var Setting $configData */
        $configData = $this->emulatorSettingRepository->get($key, 'key');
        $configData->setValue($value);
        $configData = $this->emulatorSettingRepository->save($configData);

        return response()
            ->setData(
                $configData
            );
    }
}
