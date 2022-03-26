<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Badge\Service;

use Ares\Badge\Helper\BadgeHelper;
use Ares\Framework\Service\CacheService;
use Ares\Framework\Exception\NoSuchEntityException;

/**
 * Class BadgeAlbumService
 *
 * @package Ares\Badge\Service
 */
class BadgeAlbumService
{
    public string $cacheBadgeAlbum = 'badgeAlbum';
    /**
     * BadgeAlbumService constructor.
     *
     * @param BadgeHelper   $badgeHelper
     * @param CacheService  $cacheService
     */
    public function __construct(
        private BadgeHelper $badgeHelper,
        private CacheService $cacheService
    ) {}

    /**
     * @throws NoSuchEntityException
     */
    public function execute(): bool|array
    {
        if($this->cacheService->get($this->cacheBadgeAlbum)) {
            return $this->cacheService->get($this->cacheBadgeAlbum);
        }

        if(!is_dir($this->badgeHelper->getPath())) {
            return false;
        }

        chdir($this->badgeHelper->getPath());

        foreach ($this->badgeHelper->getFiles() as $file) {
            $badges[$file] = filemtime($file);
        }

        arsort($badges);

        $sortedBadges = array_slice($badges, 0, 11);

        $this->cacheService->set($this->cacheBadgeAlbum, $sortedBadges, 7200);

        return $sortedBadges;
    }
}
