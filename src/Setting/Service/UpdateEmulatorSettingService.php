<?php
namespace Orion\Setting\Service;

use Orion\Core\Exception\DataObjectManagerException;
use Orion\Core\Exception\NoSuchEntityException;
use Orion\Core\Interfaces\CustomResponseInterface;
use Orion\Setting\Entity\Setting;
use Orion\Setting\Repository\EmulatorSettingRepository;

/**
 * Class UpdateEmulatorSettingService
 *
 * @package Orion\Setting\Service
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
