<?php declare(strict_types=1);
namespace Orion\User\Repository;

use Orion\Core\Exception\NoSuchEntityException;
use Orion\Core\Model\Query\Collection;
use Orion\User\Entity\User;
use Orion\Core\Repository\BaseRepository;

/**
 * Class UserRepository
 *
 * @package Orion\User\Repository
 */
class UserRepository extends BaseRepository
{
    /** @var string */
    protected string $entity = User::class;

    /** @var string */
    protected string $cachePrefix = 'ARES_USER_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_USER_COLLECTION_';

    /**
     * @return int
     */
    public function getUserOnlineCount(): int
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where('online', '1');

        return $this->getList($searchCriteria, false)->count();
    }

    /**
     * @return Collection
     */
    public function getTopCredits(): Collection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->orderBy('credits', 'DESC')
            ->limit(3);

        return $this->getList($searchCriteria);
    }

    /**
     * @param string|null $username
     * @param string|null $mail
     *
     * @return User|null
     * @throws NoSuchEntityException
     */
    public function getRegisteredUser(?string $username, ?string $mail): ?User
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where('username', $username)
            ->orWhere('mail', $mail);

        return $this->getOneBy($searchCriteria, true, false);
    }

    /**
     * @param string $username
     *
     * @return string|null
     * @throws NoSuchEntityException
     */
    public function getUserLook(string $username): ?string
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where('username', $username);

        return $this->getOneBy($searchCriteria)?->getLook();
    }

    /**
     * @param string $ipRegister
     *
     * @return int
     */
    public function getAccountCountByIp(string $ipRegister): int
    {
        return $this->getDataObjectManager()
            ->where('ip_register', $ipRegister)
            ->count();
    }
}
