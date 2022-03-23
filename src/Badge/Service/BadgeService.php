<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Badge\Service;

use Ares\Badge\Helper\BadgeHelper;
use Ares\Framework\Exception\NoSuchEntityException;

/**
 * Class BadgeAlbumService
 *
 * @package Ares\Badge\Service
 */
class BadgeService
{
    /**
     * BadgeAlbumService constructor.
     *
     * @param BadgeHelper $badgeHelper
     */
    public function __construct(
        private BadgeHelper $badgeHelper
    ) {}

    /**
     * @throws NoSuchEntityException
     */
    public function execute(): bool|array
    {
        if(!is_dir($this->badgeHelper->getPath())) {
            return false;
        }

        chdir($this->badgeHelper->getPath());

        foreach ($this->badgeHelper->getFiles() as $file) {
            $badges[$file] = filemtime($file);
        }

        arsort($badges);

        $sortedBadges = array_slice($badges, 0, 11);

        foreach ($sortedBadges as $badge => $key) {
            $sortedBadges[$badge] = $this->badgeHelper->getUrl() . $badge;
        }

        return $sortedBadges;
    }
}
