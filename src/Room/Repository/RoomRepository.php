<?php
namespace Orion\Room\Repository;

use Orion\Core\Exception\DataObjectManagerException;
use Orion\Core\Exception\NoSuchEntityException;
use Orion\Core\Model\Query\PaginatedCollection;
use Orion\Core\Repository\BaseRepository;
use Orion\Room\Entity\Room;

/**
 * Class RoomRepository
 *
 * @package Orion\Room\Repository
 */
class RoomRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_ROOM_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_ROOM_COLLECTION_';

    /** @var string */
    protected string $entity = Room::class;

    /**
     * Searches rooms by search term.
     *
     * @param string $term
     * @param int    $page
     * @param int    $resultPerPage
     *
     * @return PaginatedCollection
     * @throws DataObjectManagerException
     */
    public function searchRooms(string $term, int $page, int $resultPerPage): PaginatedCollection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where('name', 'LIKE', '%'.$term.'%')
            ->orderBy('users', 'DESC');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }

    /**
     * @param int $page
     * @param int $resultPerPage
     *
     * @return PaginatedCollection
     * @throws DataObjectManagerException
     */
    public function getPaginatedRoomList(int $page, int $resultPerPage): PaginatedCollection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->orderBy('id', 'DESC')
            ->addRelation('guild')
            ->addRelation('user');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }

    /**
     * @param int $ownerId
     * @param int $page
     * @param int $resultPerPage
     *
     * @return PaginatedCollection
     * @throws DataObjectManagerException
     */
    public function getUserRoomsPaginatedList(int $ownerId, int $page, int $resultPerPage): PaginatedCollection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where('owner_id', $ownerId)
            ->orderBy('id', 'DESC');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }

    /**
     * @param int $page
     * @param int $resultPerPage
     *
     * @return PaginatedCollection
     * @throws DataObjectManagerException
     */
    public function getMostVisitedRoom(int $page = 1, int $resultPerPage = 10): PaginatedCollection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->orderBy('users', 'DESC')
            ->addRelation('user');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }

    /**
     * @param int $page
     * @param int $resultPerPage
     *
     * @return PaginatedCollection
     * @throws DataObjectManagerException
     */
    public function getByScore(int $page = 1, int $resultPerPage = 10): PaginatedCollection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->orderBy('score', 'DESC')
            ->addRelation('user');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }
}
