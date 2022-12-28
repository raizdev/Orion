<?php declare(strict_types=1);
namespace Orion\Tag\Entity;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Model\DataObject;
use Orion\Tag\Entity\Contract\TagInterface;
use Orion\Tag\Repository\TagRepository;
use Orion\User\Entity\User;
use Orion\User\Repository\UserRepository;

/**
 * Class Tag
 *
 * @package Orion\Tag\Entity
 */
class Tag extends DataObject implements TagInterface
{
    /** @var string */
    public const TABLE = 'ares_tags';

    /** @var array **/
    public const RELATIONS = [
      'user' => 'getUser'
    ];

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->getData(TagInterface::COLUMN_ID);
    }

    /**
     * @param int $id
     *
     * @return Tag
     */
    public function setId(int $id): Tag
    {
        return $this->setData(TagInterface::COLUMN_ID, $id);
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->getData(TagInterface::COLUMN_USER_ID);
    }

    /**
     * @param int $userId
     *
     * @return Tag
     */
    public function setUserId(int $userId): Tag
    {
        return $this->setData(TagInterface::COLUMN_USER_ID, $userId);
    }

    /**
     * @return int
     */
    public function getTag(): string
    {
        return $this->getData(TagInterface::COLUMN_TAG);
    }

    /**
     * @param int $roomId
     *
     * @return Tag
     */
    public function setTag(string $tag): Tag
    {
        return $this->setData(TagInterface::COLUMN_TAG, $tag);
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->getData(CompetitionInterface::COLUMN_CREATED_AT);
    }

    /**
     * @param \DateTime $createdAt
     *
     * @return UserOfTheHotel
     */
    public function setCreatedAt(\DateTime $createdAt): UserOfTheHotel
    {
        return $this->setData(CompetitionInterface::COLUMN_CREATED_AT, $createdAt);
    }

    /**
     * @return User|null
     *
     * @throws DataObjectManagerException
     */
    public function getUser(): ?User
    {
        /** @var User $user */
        $user = $this->getData('user');

        if ($user) {
            return $user;
        }

        if (!isset($this)) {
            return null;
        }

        /** @var TagRepository $tagRepository */
        $tagRepository = repository(TagRepository::class);

        /** @var UserRepository $userRepository */
        $userRepository = repository(UserRepository::class);

        /** @var User $user */
        $user = $tagRepository->getOneToOne(
            $userRepository,
            $this->getUserId(),
            'id'
        );

        if (!$user) {
            return null;
        }

        $this->setUser($user);

        return $user;
    }

    /**
     * @param User $user
     *
     * @return Tag
     */
    public function setUser(User $user): Tag
    {
        return $this->setData('user', $user);
    }
}
