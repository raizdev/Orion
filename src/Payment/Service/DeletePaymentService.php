<?php
namespace Orion\Payment\Service;

use Orion\Core\Exception\DataObjectManagerException;
use Orion\Core\Interfaces\CustomResponseInterface;
use Orion\Core\Interfaces\HttpResponseCodeInterface;
use Orion\Payment\Exception\PaymentException;
use Orion\Payment\Interfaces\Response\PaymentResponseCodeInterface;
use Orion\Payment\Repository\PaymentRepository;

/**
 * Class DeletePaymentService
 *
 * @package Orion\Payment\Service
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
