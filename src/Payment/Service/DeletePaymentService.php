<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Payment\Service;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Framework\Interfaces\HttpResponseCodeInterface;
use Ares\Payment\Exception\PaymentException;
use Ares\Payment\Interfaces\Response\PaymentResponseCodeInterface;
use Ares\Payment\Repository\PaymentRepository;

/**
 * Class DeletePaymentService
 *
 * @package Ares\Payment\Service
 */
class DeletePaymentService
{
    /**
     * DeletePaymentService constructor.
     *
     * @param PaymentRepository $paymentRepository
     */
    public function __construct(
        private PaymentRepository $paymentRepository
    ) {}

    /**
     * @param int $id
     *
     * @return CustomResponseInterface
     * @throws PaymentException
     * @throws DataObjectManagerException
     */
    public function execute(int $id): CustomResponseInterface
    {
        $deleted = $this->paymentRepository->delete($id);

        if (!$deleted) {
            throw new PaymentException(
                __('Payment could not be deleted'),
                PaymentResponseCodeInterface::RESPONSE_PAYMENT_NOT_DELETED,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        return response()
            ->setData(true);
    }
}
