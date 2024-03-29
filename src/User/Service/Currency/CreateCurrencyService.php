<?php
namespace Orion\User\Service\Currency;

use Orion\Core\Exception\DataObjectManagerException;
use Orion\Core\Interfaces\CustomResponseInterface;
use Orion\User\Entity\UserCurrency;
use Orion\User\Repository\UserCurrencyRepository;

/**
 * Class CreateCurrencyService
 *
 * @package Ares\User\Service\Currency
 */
class CreateCurrencyService
{
    /**
     * CreateCurrencyService constructor.
     *
     * @param   UserCurrencyRepository  $userCurrencyRepository
     */
    public function __construct(
        private UserCurrencyRepository $userCurrencyRepository
    ) {}

    /**
     * Creates new user currency by given data.
     *
     * @param int $userId
     * @param int $type
     * @param int $amount
     *
     * @return CustomResponseInterface
     * @throws DataObjectManagerException
     */
    public function execute(int $userId, int $type, int $amount): CustomResponseInterface
    {
        /** @var UserCurrency $userCurrency */
        $userCurrency = $this->userCurrencyRepository
            ->save(
                $this->getNewUserCurrency(
                    $userId,
                    $type,
                    $amount
                )
            );

        return response()
            ->setData($userCurrency);
    }

    /**
     * Returns new user currency object.
     *
     * @param int $userId
     * @param int $type
     * @param int $amount
     *
     * @return UserCurrency
     */
    private function getNewUserCurrency(int $userId, int $type, int $amount): UserCurrency
    {
        $userCurrency = new UserCurrency();

        return $userCurrency
            ->setUserId($userId)
            ->setType($type)
            ->setAmount($amount);
    }
}
