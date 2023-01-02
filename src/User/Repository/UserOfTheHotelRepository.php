<?php declare(strict_types=1);
namespace Orion\User\Repository;

use Orion\Core\Exception\DataObjectManagerException;
use Orion\Core\Exception\NoSuchEntityException;
use Orion\Core\Repository\BaseRepository;
use Orion\User\Entity\UserOfTheHotel;

/**
 * Class UserOfTheHotelRepository
 *
 * @package Orion\User\Repository
 */
class UserOfTheHotelRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_UOTH_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_UOTH_COLLECTION_';

    /** @var string */
    protected string $entity = UserOfTheHotel::class;

    /**
     * @return UserOfTheHotel|null
     *
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
     */
    public function getCurrentUser(): ?UserOfTheHotel
    {
        $searchCriteria = $this->getDataObjectManager()
            ->latest()
            ->addRelation('user');

        return $this->getOneBy($searchCriteria, true);
    }
}
