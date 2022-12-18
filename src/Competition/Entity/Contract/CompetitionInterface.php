<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Competition\Entity\Contract;

/**
 * Interface CompetitionInterface
 *
 * @package Ares\Competition\Entity\Contract
 */
interface CompetitionInterface
{
    public const COLUMN_ID = 'id';
    public const COLUMN_TITLE = 'title';
    public const COLUMN_SHORT_TITLE = 'short_title';
    public const COLUMN_IFRAME = 'iframe';
    public const COLUMN_HEADER = 'header';
}
