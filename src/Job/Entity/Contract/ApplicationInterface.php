<?php
namespace Orion\Job\Entity\Contract;

/**
 * Interface ApplicationInterface
 *
 * @package Orion\Job\Entity\Contract
 */
interface ApplicationInterface
{
    public const COLUMN_ID = 'id';
    public const COLUMN_JOB_ID = 'job_id';
    public const COLUMN_USER_ID = 'user_id';
    public const COLUMN_FIRSTNAME = 'firstname';
    public const COLUMN_DISCORD = 'discord';
    public const COLUMN_AGE = 'age';
    public const COLUMN_WEEKLY_HOURS = 'weekly_hours';
    public const COLUMN_COUNTRY = 'country';
    public const COLUMN_WHY_US = 'why_us';
    public const COLUMN_IDEAS = 'ideas';
    public const COLUMN_STATUS = 'status';
    public const COLUMN_CREATED_AT = 'created_at';
    public const COLUMN_UPDATED_AT = 'updated_at';
}
