<?php
namespace Orion\Permission\Entity\Contract;

/**
 * Interface PermissionInterface
 *
 * @package Orion\Permission\Entity\Contract
 */
interface PermissionInterface
{
    public const COLUMN_ID = 'id';
    public const COLUMN_RANK_NAME = 'rank_name';
    public const COLUMN_BADGE = 'badge';
    public const COLUMN_LEVEL = 'level';
    public const COLUMN_PREFIX = 'prefix';
    public const COLUMN_PREFIX_COLOR = 'prefix_color';
}
