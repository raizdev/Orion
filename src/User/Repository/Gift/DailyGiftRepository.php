<?php
namespace Orion\User\Repository\Gift;

use Orion\Core\Repository\BaseRepository;
use Orion\User\Entity\Gift\DailyGift;

/**
 * Class DailyGiftRepository
 *
 * @package Orion\User\Repository\Gift
 */
class DailyGiftRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_USER_DAILY_GIFT_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_USER_DAILY_GIFT_COLLECTION_';

    /** @var string */
    protected string $entity = DailyGift::class;
}
