<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Badge\Helper;


use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Setting\Repository\EmulatorSettingRepository;

/**
 * Class BadgeHelper
 *
 * @package Ares\Badge\Service
 */
class BadgeHelper
{
    /**
     * @var string
     */
    private string $path;

    /**
     * BadgeHelper constructor.
     *
     * @param EmulatorSettingRepository $emulatorSettingRepository
     * @throws NoSuchEntityException
     */
    public function __construct(
        private EmulatorSettingRepository $emulatorSettingRepository
    ) {
        $this->path = $this->getPath();
    }

    /**
     * @throws NoSuchEntityException
     * @return string
     */
    public function getPath(): string {
        $badgeParts = $this->emulatorSettingRepository->get('imager.location.badgeparts', 'key');

        /** @var string $badgeParts */
        return str_replace('Badgeparts', '', $badgeParts->value) . 'album1584';
    }

    /**
     * @return bool|array
     */
    public function getFiles(): bool|array {
        return glob("*.{png,gif,PNG,GIF}",GLOB_BRACE);
    }

}