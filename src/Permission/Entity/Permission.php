<?php declare(strict_types=1);
namespace Orion\Permission\Entity;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Model\DataObject;
use Orion\Permission\Entity\Contract\PermissionInterface;
use Orion\Permission\Repository\PermissionRepository;
use Orion\User\Entity\User;
use Orion\User\Repository\UserRepository;
use Ares\Framework\Model\Query\Collection;

/**
 * Class Permission
 *
 * @package Orion\Permission\Entity
 */
class Permission extends DataObject implements PermissionInterface
{
    /** @var string */
    public const TABLE = 'permissions';

    /** @var array **/
    public const RELATIONS = [
      'users' => 'getUsers'
    ];

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->getData(PermissionInterface::COLUMN_ID);
    }

    /**
     * @param int $id
     *
     * @return Permission
     */
    public function setId(int $id): Permission
    {
        return $this->setData(PermissionInterface::COLUMN_ID, $id);
    }

    /**
     * @return string
     */
    public function getRankName(): string
    {
        return $this->getData(PermissionInterface::COLUMN_RANK_NAME);
    }

    /**
     * @param string $rankName
     *
     * @return Permission
     */
    public function setRankName(string $rankName): Permission
    {
        return $this->setData(PermissionInterface::COLUMN_RANK_NAME, $rankName);
    }

    /**
     * @return string
     */
    public function getBadge(): string
    {
        return $this->getData(PermissionInterface::COLUMN_BADGE);
    }

    /**
     * @param string $badge
     *
     * @return Permission
     */
    public function setBadge(string $badge): Permission
    {
        return $this->setData(PermissionInterface::COLUMN_BADGE, $badge);
    }

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->getData(PermissionInterface::COLUMN_LEVEL);
    }

    /**
     * @param int $level
     *
     * @return Permission
     */
    public function setLevel(int $level): Permission
    {
        return $this->setData(PermissionInterface::COLUMN_LEVEL, $level);
    }

    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->getData(PermissionInterface::COLUMN_PREFIX);
    }

    /**
     * @param string $prefix
     *
     * @return Permission
     */
    public function setPrefix(string $prefix): Permission
    {
        return $this->setData(PermissionInterface::COLUMN_PREFIX, $prefix);
    }

    /**
     * @return string
     */
    public function getPrefixColor(): string
    {
        return $this->getData(PermissionInterface::COLUMN_PREFIX_COLOR);
    }

    /**
     * @param string $prefixColor
     *
     * @return Permission
     */
    public function setPrefixColor(string $prefixColor): Permission
    {
        return $this->setData(PermissionInterface::COLUMN_PREFIX_COLOR, $prefixColor);
    }

    /**
     * @return Collection|null
     *
     * @throws DataObjectManagerException
     */
    public function getUsers(): ?Collection
    {
        $users = $this->getData('users');

        if ($users) {
            return $users;
        }

        if (!isset($this)) {
            return null;
        }

        /** @var PermissionRepository $permissionRepository */
        $permissionRepository = repository(PermissionRepository::class);

        /** @var UserRepository $userRepository */
        $userRepository = repository(UserRepository::class);

        $users = $permissionRepository->getOneToMany(
            $userRepository,
            $this->getId(),
            'rank'
        );

        if (!$users->toArray()) {
            return null;
        }

        /** @var User $user */
        foreach ($users as $user) {
            $user->getBadges();
        }

        $filteredUsers = $users->each(function ($user) {
           $user->badges = $user->badges->filter(function ($badge) {
              return $badge->slot_id > 0;
           });
        });

        $this->setUsers($filteredUsers);

        return $filteredUsers;
    }

    /**
     * @param Collection $users
     *
     * @return Permission
     */
    public function setUsers(Collection $users): Permission
    {
        return $this->setData('users', $users);
    }
}
