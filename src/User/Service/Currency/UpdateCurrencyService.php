<?php
namespace Orion\User\Service\Currency;

use Orion\Core\Interfaces\HttpResponseCodeInterface;
use Orion\User\Exception\UserCurrencyException;
use Orion\User\Interfaces\Response\UserResponseCodeInterface;
use Orion\User\Repository\UserCurrencyRepository;
use Exception;

/**
 * Class UpdateCurrencyService
 *
 * @package Orion\User\Service\Currency
 */
class UpdateCurrencyService
{
    /**
     * UpdateCurrencyService constructor.
     *
     * @param UserCurrencyRepository $userCurrencyRepository
     */
    public function __construct(
        private UserCurrencyRepository $userCurrencyRepository
    ) {}

    /**
     * Updates currency by given data.
     *
     * @param int $userId
     * @param int $type
     * @param int $amount
     *
     * @return void
     * @throws UserCurrencyException
     */
    public function execute(int $userId, int $type, int $amount): void
    {
        $currencies = $this->userCurrencyRepository->getUserCurrency($userId, $type);

        if (!$currencies) {
            throw new UserCurrencyException(
                __('No Currencies were found'),
                UserResponseCodeInterface::RESPONSE_CURRENCY_NOT_FOUND,
                HttpResponseCodeInterface::HTTP_RESPONSE_NOT_FOUND
            );
        }

        foreach ($currencies as $currency) {
            $currency->setAmount($amount);
            try {
                $this->userCurrencyRepository->save($currency);
            } catch (Exception) {
                throw new UserCurrencyException(
                    __('Currency could not be updated.'),
                    UserResponseCodeInterface::RESPONSE_CURRENCY_NOT_UPDATED,
                    HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
                );
            }
        }
    }
}
