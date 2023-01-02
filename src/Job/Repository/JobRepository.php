<?php
namespace Orion\Job\Repository;

use Orion\Core\Exception\DataObjectManagerException;
use Orion\Core\Model\Query\PaginatedCollection;
use Orion\Core\Repository\BaseRepository;
use Orion\Job\Entity\Job;

/**
 * Class CompetitionRepository
 *
 * @package Orion\Job\Repository
 */
class JobRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_JOBS_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_JOBS_COLLECTION_';

    /** @var string */
    protected string $entity = Job::class;
}
