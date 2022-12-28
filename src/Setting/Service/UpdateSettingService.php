<?php
namespace Ares\Setting\Service;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Orion\Setting\Entity\Setting;
use Orion\Setting\Repository\SettingRepository;

/**
 * Class UpdateSettingsService
 *
 * @package Orion\Setting\Service
 */
class UpdateSettingService
{
    /**
     * UpdateSettingService constructor.
     *
     * @param SettingRepository $settingsRepository
     */
    public function __construct(
        private SettingRepository $settingsRepository
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
        $configData = $this->settingsRepository->get($key, 'key');
        $configData->setValue($value);
        $configData = $this->settingsRepository->save($configData);

        return response()
            ->setData(
                $configData
            );
    }
}
