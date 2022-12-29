<?php
namespace Orion\Competition\Repository;

use Orion\Core\Exception\DataObjectManagerException;
use Orion\Core\Model\Query\PaginatedCollection;
use Orion\Core\Repository\BaseRepository;
use Orion\Competition\Entity\Competition;

/**
 * Class CompetitionRepository
 *
 * @package Orion\Competition\Repository
 */
class CompetitionRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_COMPETITIONS_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_COMPETITIONS_COLLECTION_';

    /** @var string */
    protected string $entity = Competition::class;

    /**
     * @param int $page
     * @param int $resultPerPage
     *
     * @return PaginatedCollection
     * @throws DataObjectManagerException
     */

    public function getPaginatedCompetitionList(int $page = 0, int $resultPerPage = 10): PaginatedCollection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->orderBy('id', 'DESC');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }
}
