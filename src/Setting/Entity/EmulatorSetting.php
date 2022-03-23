<?php declare(strict_types=1);
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Setting\Entity;

use Ares\Framework\Model\DataObject;
use Ares\Setting\Entity\Contract\EmulatorSettingInterface;

/**
 * Class EmulatorSetting
 *
 * @package Ares\Setting\Entity
 */
class EmulatorSetting extends DataObject implements EmulatorSettingInterface
{
    /** @var string */
    public const TABLE = 'emulator_settings';

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->getData(EmulatorSettingInterface::COLUMN_KEY);
    }

    /**
     * @param string $key
     *
     * @return EmulatorSetting
     */
    public function setKey(string $key): EmulatorSetting
    {
        return $this->setData(EmulatorSettingInterface::COLUMN_KEY, $key);
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->getData(EmulatorSettingInterface::COLUMN_VALUE);
    }

    /**
     * @param string $value
     *
     * @return EmulatorSetting
     */
    public function setValue(string $value): EmulatorSetting
    {
        return $this->setData(EmulatorSettingInterface::COLUMN_VALUE, $value);
    }
}
