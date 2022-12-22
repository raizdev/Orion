<?php declare(strict_types=1);
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Article\Entity;

use Ares\Article\Entity\Contract\CategoryInterface;
use Ares\Article\Repository\CommentRepository;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Model\DataObject;
use Ares\User\Entity\User;
use Ares\User\Repository\UserRepository;

/**
 * Class Article
 *
 * @package Ares\Article\Entity
 */
class Category extends DataObject implements CategoryInterface
{
    /** @var string */
    public const TABLE = 'ares_articles_categories';

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->getData(CategoryInterface::COLUMN_ID);
    }

    /**
     * @param int $id
     *
     * @return Category
     */
    public function setId(int $id): Category
    {
        return $this->setData(CategoryInterface::COLUMN_ID, $id);
    }

    /**
     * @return int
     */
    public function getTitle(): int
    {
        return $this->getData(CategoryInterface::COLUMN_TITLE);
    }

    /**
     * @param int $title
     *
     * @return Category
     */
    public function setTitle(int $title): Category
    {
        return $this->setData(CategoryInterface::COLUMN_TITLE, $title);
    }

    /**
     * @return int
     */
    public function getDescription(): int
    {
        return $this->getData(CategoryInterface::COLUMN_DESCRIPTION);
    }

    /**
     * @param int $description
     *
     * @return Category
     */
    public function setDescription(int $description): Category
    {
        return $this->setData(CategoryInterface::COLUMN_DESCRIPTION, $description);
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->getData(CategoryInterface::COLUMN_SLUG);
    }

    /**
     * @param string $slug
     *
     * @return Category
     */
    public function setSlug(string $slug): Category
    {
        return $this->setData(CategoryInterface::COLUMN_SLUG, $slug);
    }

    /**
     * @return int
     */
    public function getHidden(): int
    {
        return $this->getData(CommentInCategoryInterfaceterface::COLUMN_HIDDEN);
    }

    /**
     * @param int $hidden
     *
     * @return Category
     */
    public function setHidden(int $hidden): Category
    {
        return $this->setData(CategoryInterface::COLUMN_HIDDEN, $hidden);
    }
}
