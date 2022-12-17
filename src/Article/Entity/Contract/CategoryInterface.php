<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Article\Entity\Contract;

/**
 * Interface CategoryInterface
 *
 * @package Ares\Article\Entity\CategoryInterface
 */
interface CategoryInterface
{
    public const COLUMN_ID = 'id';
    public const COLUMN_TITLE = 'title';
    public const COLUMN_DESCRIPTION = 'description';
    public const COLUMN_SLUG = 'slug';
    public const COLUMN_HIDDEN = 'hidden';
}
