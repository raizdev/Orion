<?php
namespace Orion\Payment\Service;

use Orion\Core\Exception\DataObjectManagerException;
use Orion\Core\Exception\NoSuchEntityException;
use Orion\Core\Interfaces\CustomResponseInterface;
use Orion\Core\Interfaces\HttpResponseCodeInterface;
use Orion\Payment\Entity\Payment;
use Orion\Payment\Exception\PaymentException;
use Orion\Payment\Interfaces\Response\PaymentResponseCodeInterface;
use Orion\Payment\Repository\PaymentRepository;

/**
 * Class CreatePaymentService
 *
 * @package Orion\Payment\Service
 */
class CreatePaymentService
{
    /**
     * CreatePaymentService constructor.
     *
     * @param PaymentRepository  $paymentRepository
     */
    public function __construct(
        private PaymentRepository $paymentRepository
    ) {}

    /**
     * @param int   $userId
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws DataObjectManagerException
     * @throws PaymentException
     * @throws NoSuchEntityException
     */
    public function execute(int $userId, array $data): CustomResponseInterface
    {
        $payment = $this->getNewPayment($userId, $data);

        /** @var Payment $existingPayment */
        $existingPayment = $this->paymentRepository->getExistingPayment($payment->getUserId());

        if ($existingPayment) {
            throw new PaymentException(
                __('You already have an ongoing payment'),
                PaymentResponseCodeInterface::RESPONSE_PAYMENT_ALREADY_ONGOING,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        /** @var Payment $payment */
        $payment = $this->paymentRepository->save($payment);

        return response()
            ->setData($payment);
    }

    /**
     * @param int   $userId
     * @param array $data
     *
     * @return Payment
     */
    public function getNewPayment(int $userId, array $data): Payment
    {
        $payment = new Payment();

        return $payment
            ->setCode($data['code'])
            ->setUserId($userId)
            ->setProcessed(0)
            ->setType(0);
    }
}
