<?php
namespace Orion\Article\Entity\Contract;

/**
 * Interface CategoryInterface
 *
 * @package Orion\Article\Entity\CategoryInterface
 */
interface CategoryInterface
{
    public const COLUMN_ID = 'id';
    public const COLUMN_TITLE = 'title';
    public const COLUMN_DESCRIPTION = 'description';
    public const COLUMN_SLUG = 'slug';
    public const COLUMN_HIDDEN = 'hidden';
}
