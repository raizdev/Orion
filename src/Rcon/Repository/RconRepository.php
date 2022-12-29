<?php
namespace Orion\Rcon\Repository;

use Orion\Core\Repository\BaseRepository;
use Orion\Rcon\Entity\Rcon;

/**
 * Class RconRepository
 *
 * @package Orion\Rcon\Repository
 */
class RconRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_RCON_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_RCON_COLLECTION_';

    /** @var string */
    protected string $entity = Rcon::class;
}
