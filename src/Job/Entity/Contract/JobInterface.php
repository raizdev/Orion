<?php
namespace Orion\Job\Entity\Contract;

/**
 * Interface PermissionInterface
 *
 * @package Orion\Job\Entity\Contract
 */
interface JobInterface
{
    public const COLUMN_ID = 'id';
    public const COLUMN_JOB = 'job';
    public const COLUMN_FULL_DESCRIPTION = 'full_description';
    public const COLUMN_SHORT_DESCRIPTION = 'short_description';
    public const COLUMN_CREATED_AT = 'created_at';
    public const COLUMN_UPDATED_AT = 'updated_at';
}
