<?php
namespace Orion\Role\Entity\Contract;

/**
 * Interface RoleHierarchyInterface
 *
 * @package Orion\Role\Entity\Contract
 */
interface RoleHierarchyInterface
{
    public const COLUMN_ID = 'id';
    public const COLUMN_PARENT_ROLE_ID = 'parent_role_id';
    public const COLUMN_CHILD_ROLE_ID = 'child_role_id';
    public const COLUMN_CREATED_AT = 'created_at';
}
