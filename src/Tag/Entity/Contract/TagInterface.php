<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Tag\Entity\Contract;

/**
 * Interface TagInterface
 *
 * @package Ares\Tag\Entity\Contract
 */
interface TagInterface
{
    public const COLUMN_ID = 'id';
    public const COLUMN_USER_ID = 'user_id';
    public const COLUMN_TAG = 'tag';
    public const COLUMN_CREATED_AT = 'created_at';
}
