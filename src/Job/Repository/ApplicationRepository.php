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
    protected string $entity = Application::class;

    /**
     * @param int $page
     * @param int $resultPerPage
     *
     * @return PaginatedCollection
     * @throws DataObjectManagerException
     */

    public function getApplicationByUser(int $user_id, int $page = 1, int $resultPerPage = 5): PaginatedCollection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where('user_id', $user_id)
            ->orderBy('id', 'DESC')
            ->addRelation('job');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }

    /**
     * @param int $page
     * @param int $resultPerPage
     *
     * @return PaginatedCollection
     * @throws DataObjectManagerException
     */

    public function getAllApplications(int $page = 1, int $resultPerPage = 5): PaginatedCollection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->orderBy('id', 'DESC')
            ->addRelation('user')
            ->addRelation('job');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }
}
