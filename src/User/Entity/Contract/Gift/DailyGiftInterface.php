<?php
namespace Orion\User\Entity\Contract\Gift;

/**
 * Interface DailyGiftInterface
 *
 * @package Orion\User\Entity\Contract\Gift
 */
interface DailyGiftInterface
{
    public const COLUMN_ID = 'id';
    public const COLUMN_USER_ID = 'user_id';
    public const COLUMN_AMOUNT = 'amount';
    public const COLUMN_PICK_TIME = 'pick_time';
}
