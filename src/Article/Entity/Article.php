<?php declare(strict_types=1);
namespace Orion\Article\Entity;

use Orion\Article\Entity\Contract\ArticleInterface;
use Orion\Article\Repository\ArticleRepository;
use Orion\Core\Exception\DataObjectManagerException;
use Orion\Core\Model\DataObject;
use Orion\User\Entity\User;
use Orion\Article\Entity\Category;
use Orion\Article\Repository\CategoryRepository;
use Orion\User\Repository\UserRepository;

/**
 * Class Article
 *
 * @package Ares\Article\Entity
 */
class Article extends DataObject implements ArticleInterface
{
    /** @var string */
    public const TABLE = 'ares_articles';

    /** @var array **/
    public const RELATIONS = [
      'user'                => 'getUser',
      'articleCategory'     => 'getCategory'
    ];

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->getData(ArticleInterface::COLUMN_ID);
    }

    /**
     * @param int $id
     *
     * @return Article
     */
    public function setId(int $id): Article
    {
        return $this->setData(ArticleInterface::COLUMN_ID, $id);
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->getData(ArticleInterface::COLUMN_TITLE);
    }

    /**
     * @param string $title
     *
     * @return Article
     */
    public function setTitle(string $title): Article
    {
        return $this->setData(ArticleInterface::COLUMN_TITLE, $title);
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->getData(ArticleInterface::COLUMN_SLUG);
    }

    /**
     * @param string $slug
     *
     * @return Article
     */
    public function setSlug(string $slug): Article
    {
        return $this->setData(ArticleInterface::COLUMN_SLUG, $slug);
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->getData(ArticleInterface::COLUMN_DESCRIPTION);
    }

    /**
     * @param string $description
     *
     * @return Article
     */
    public function setDescription(string $description): Article
    {
        return $this->setData(ArticleInterface::COLUMN_DESCRIPTION, $description);
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->getData(ArticleInterface::COLUMN_CONTENT);
    }

    /**
     * @param string $content
     *
     * @return Article
     */
    public function setContent(string $content)
    {
        return $this->setData(ArticleInterface::COLUMN_CONTENT, $content);
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->getData(ArticleInterface::COLUMN_IMAGE);
    }

    /**
     * @param string $image
     *
     * @return Article
     */
    public function setImage(string $image): Article
    {
        return $this->setData(ArticleInterface::COLUMN_IMAGE, $image);
    }

    /**
     * @return string
     */
    public function getThumbnail(): string
    {
        return $this->getData(ArticleInterface::COLUMN_THUMBNAIL);
    }

    /**
     * @param string $thumbnail
     *
     * @return Article
     */
    public function setThumbnail(string $thumbnail): Article
    {
        return $this->setData(ArticleInterface::COLUMN_THUMBNAIL, $thumbnail);
    }

    /**
     * @return int|null
     */
    public function getAuthorId(): ?int
    {
        return $this->getData(ArticleInterface::COLUMN_AUTHOR_ID);
    }

    /**
     * @param int $authorId
     *
     * @return Article
     */
    public function setAuthorId(int $authorId): Article
    {
        return $this->setData(ArticleInterface::COLUMN_AUTHOR_ID, $authorId);
    }

    /**
     * @return int
     */
    public function getHidden(): int
    {
        return $this->getData(ArticleInterface::COLUMN_HIDDEN);
    }

    /**
     * @param int $hidden
     *
     * @return Article
     */
    public function setHidden(int $hidden): Article
    {
        return $this->setData(ArticleInterface::COLUMN_HIDDEN, $hidden);
    }

    /**
     * @return int
     */
    public function getPinned(): int
    {
        return $this->getData(ArticleInterface::COLUMN_PINNED);
    }

    /**
     * @param int $pinned
     *
     * @return Article
     */
    public function setPinned(int $pinned): Article
    {
        return $this->setData(ArticleInterface::COLUMN_PINNED, $pinned);
    }

    /**
     * @return int
     */
    public function getLikes(): int
    {
        return $this->getData(ArticleInterface::COLUMN_LIKES);
    }

    /**
     * @param int $likes
     *
     * @return Article
     */
    public function setLikes(int $likes): Article
    {
        return $this->setData(ArticleInterface::COLUMN_LIKES, $likes);
    }

    /**
     * @return int
     */
    public function getDislikes(): int
    {
        return $this->getData(ArticleInterface::COLUMN_DISLIKES);
    }

    /**
     * @param int $dislikes
     *
     * @return Article
     */
    public function setDislikes(int $dislikes): Article
    {
        return $this->setData(ArticleInterface::COLUMN_DISLIKES, $dislikes);
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->getData(ArticleInterface::COLUMN_CREATED_AT);
    }

    /**
     * @param \DateTime $createdAt
     *
     * @return Article
     */
    public function setCreatedAt(\DateTime $createdAt): Article
    {
        return $this->setData(ArticleInterface::COLUMN_CREATED_AT, $createdAt);
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->getData(ArticleInterface::COLUMN_UPDATED_AT);
    }

    /**
     * @param \DateTime $updatedAt
     *
     * @return Article
     */
    public function setUpdatedAt(\DateTime $updatedAt): Article
    {
        return $this->setData(ArticleInterface::COLUMN_UPDATED_AT, $updatedAt);
    }

     /**
     * @return int
     */
    public function getCatId(): int
    {
        return $this->getData(ArticleInterface::COLUMN_CAT_ID);
    }

    /**
     * @param int $catId
     *
     * @return Article
     */
    public function setCatId(int $catId): Article
    {
        return $this->setData(ArticleInterface::COLUMN_CAT_ID, $catId);
    }

    /**
     * @return Category|null
     *
     * @throws DataObjectManagerException
     */
    public function getCategory(): ?Category
    {
        /** @var User $user */
        $category = $this->getData('category');
        if ($category) {
            return $category;
        }

        if (!isset($this)) {
            return null;
        }

        /** @var ArticleRepository $articleRepository */
        $articleRepository = repository(ArticleRepository::class);

        /** @var CategoryRepository $userRepository */
        $categoryRepository = repository(CategoryRepository::class);

        /** @var Category $user */
        $category = $articleRepository->getOneToOne(
            $categoryRepository,
            $this->getCatId(),
            'id'
        );

        if (!$category) {
            return null;
        }

        $this->setCategory($category);

        return $category;
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

        /** @var ArticleRepository $articleRepository */
        $articleRepository = repository(ArticleRepository::class);

        /** @var UserRepository $userRepository */
        $userRepository = repository(UserRepository::class);

        /** @var User $user */
        $user = $articleRepository->getOneToOne(
            $userRepository,
            $this->getAuthorId(),
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
     * @return Article
     */
    public function setUser(User $user): Article
    {
        return $this->setData('user', $user);
    }

    /**
     * @param Category $category
     *
     * @return Article
     */
    public function setCategory(Category $category): Article
    {
        return $this->setData('category', $category);
    }
}
