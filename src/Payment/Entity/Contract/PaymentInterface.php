<?php
namespace Orion\Payment\Entity\Contract;

/**
 * Interface PaymentInterface
 *
 * @package Orion\Payment\Entity\Contract
 */
interface PaymentInterface
{
    public const COLUMN_ID = 'id';
    public const COLUMN_USER_ID = 'user_id';
    public const COLUMN_CODE = 'code';
    public const COLUMN_PROCESSED = 'processed';
    public const COLUMN_TYPE = 'type';
}
