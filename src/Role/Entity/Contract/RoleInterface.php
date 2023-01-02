<?php
namespace Orion\Role\Entity\Contract;

/**
 * Interface RoleInterface
 *
 * @package Orion\Role\Entity\Contract
 */
interface RoleInterface
{
    public const COLUMN_ID = 'id';
    public const COLUMN_NAME = 'name';
    public const COLUMN_DESCRIPTION = 'description';
    public const COLUMN_STATUS = 'status';
    public const COLUMN_CREATED_AT = 'created_at';
    public const COLUMN_UPDATED_AT = 'updated_at';
}
