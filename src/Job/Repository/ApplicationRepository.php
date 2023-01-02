<?php
namespace Orion\Job\Repository;

use Orion\Core\Exception\DataObjectManagerException;
use Orion\Core\Model\Query\PaginatedCollection;
use Orion\Core\Repository\BaseRepository;
use Orion\Job\Entity\Application;

/**
 * Class CompetitionRepository
 *
 * @package Orion\Job\Repository
 */
class ApplicationRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_JOBS_APPLICATIONS_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_JOBS_APPLICATIONS_COLLECTION_';

    /** @var string */
    protected string $entity = Job::class;

    /**
     * @param int $page
     * @param int $resultPerPage
     *
     * @return PaginatedCollection
     * @throws DataObjectManagerException
     */

}
