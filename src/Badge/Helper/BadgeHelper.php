<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Badge\Helper;


use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Setting\Repository\EmulatorSettingRepository;
use PHLAK\Config\Config;

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
    private string $url;

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
        private EmulatorSettingRepository $emulatorSettingRepository,
        private Config $config
    ) {
        $this->url = $this->getUrl();
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
     * @return string
     */
    public function getUrl(): string {
        return $this->config->get('hotel_settings.nitro_url') . '/assets/c_images/album1584/';
    }

    /**
     * @return bool|array
     */
    public function getFiles(): bool|array {
        return glob("*.{png,gif,PNG,GIF}",GLOB_BRACE);
    }

}