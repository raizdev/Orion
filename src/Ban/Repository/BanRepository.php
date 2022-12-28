<?php
namespace Orion\Ban\Repository;

use Orion\Ban\Entity\Ban;
use Ares\Framework\Repository\BaseRepository;

/**
 * Class BanRepository
 *
 * @package Orion\Ban\Repository
 */
class BanRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_BAN_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_BAN_COLLECTION_';

    /** @var string */
    protected string $entity = Ban::class;
}
