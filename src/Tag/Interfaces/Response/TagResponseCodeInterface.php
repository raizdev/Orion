<?php

namespace Orion\Tag\Interfaces\Response;

use Ares\Framework\Interfaces\CustomResponseCodeInterface;

/**
 * Interface TagResponseCodeInterface
 *
 * @package Orion\Tag\Interfaces\Response
 */
interface TagResponseCodeInterface extends CustomResponseCodeInterface
{
    /** @var int */
    public const RESPONSE_PHOTO_NOT_DELETED = 10776;
    public const RESPONSE_GUESTBOOK_ENTRY_NOT_DELETED = 19854;
    public const RESPONSE_TAG_ENTITY_ALREADY_EXISTS = 19855;
    public const RESPONSE_TAG_NOT_DELETED = 19856;
}
