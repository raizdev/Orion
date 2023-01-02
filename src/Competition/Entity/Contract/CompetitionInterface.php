<?php
namespace Orion\Competition\Entity\Contract;

/**
 * Interface CompetitionInterface
 *
 * @package Orion\Competition\Entity\Contract
 */
interface CompetitionInterface
{
    public const COLUMN_ID = 'id';
    public const COLUMN_TITLE = 'title';
    public const COLUMN_SHORT_TITLE = 'short_title';
    public const COLUMN_IFRAME = 'iframe';
    public const COLUMN_HEADER = 'header';
    public const COLUMN_TO_TIMESTAMP = 'to_timestamp';
    public const COLUMN_CREATED_AT = 'created_at';
}
