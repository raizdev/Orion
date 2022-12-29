<?php
namespace Orion\Payment\Interfaces\Response;

use Orion\Core\Interfaces\CustomResponseCodeInterface;

/**
 * Interface PaymentResponseCodeInterface
 *
 * @package Orion\Payment\Interfaces\Response
 */
interface PaymentResponseCodeInterface extends CustomResponseCodeInterface
{
    /** @var int */
    public const RESPONSE_PAYMENT_NOT_DELETED = 10823;

    /** @var int */
    public const RESPONSE_PAYMENT_ALREADY_ONGOING = 10850;
}
