<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Role\Entity\Contract;

/**
 * Interface RoleRankInterface
 *
 * @package Ares\Role\Entity\Contract
 */
interface RoleRankInterface
{
    public const COLUMN_ID = 'id';
    public const COLUMN_ROLE_ID = 'role_id';
    public const COLUMN_RANK_ID = 'rank_id';
    public const COLUMN_CREATED_AT = 'created_at';
}