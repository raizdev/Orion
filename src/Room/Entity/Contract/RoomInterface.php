<?php
namespace Orion\Room\Entity\Contract;

/**
 * Interface RoomInterface
 *
 * @package Orion\Room\Entity\Contract
 */
interface RoomInterface
{
    public const COLUMN_ID = 'id';
    public const COLUMN_OWNER_ID = 'owner_id';
    public const COLUMN_NAME = 'name';
    public const COLUMN_DESCRIPTION = 'description';
    public const COLUMN_STATE = 'state';
    public const COLUMN_USERS = 'users';
    public const COLUMN_USERS_MAX = 'users_max';
    public const COLUMN_GUILD_ID = 'guild_id';
    public const COLUMN_SCORE = 'score';
}
