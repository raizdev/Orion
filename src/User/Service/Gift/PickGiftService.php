<?php
namespace Orion\User\Service\Gift;

use Orion\Core\Exception\DataObjectManagerException;
use Orion\Core\Exception\NoSuchEntityException;
use Orion\Core\Interfaces\CustomResponseInterface;
use Orion\Core\Interfaces\HttpResponseCodeInterface;
use Orion\Rcon\Exception\RconException;
use Orion\Rcon\Service\ExecuteRconCommandService;
use Orion\Role\Exception\RoleException;
use Orion\User\Entity\Gift\DailyGift;
use Orion\User\Entity\User;
use Orion\User\Exception\Gift\DailyGiftException;
use Orion\User\Interfaces\Response\UserResponseCodeInterface;
use Orion\User\Repository\Gift\DailyGiftRepository;
use Orion\Core\Config;

/**
 * Class PickGiftService
 *
 * @package Orion\User\Service\Gift
 */
class PickGiftService
{
    /**
     * PickGiftService constructor.
     *
     * @param DailyGiftRepository       $dailyGiftRepository
     * @param ExecuteRconCommandService $executeRconCommandService
     * @param Config                    $config
     */
    public function __construct(
        private DailyGiftRepository $dailyGiftRepository,
        private ExecuteRconCommandService $executeRconCommandService,
        private Config $config
    ) {}

    /**
     * Checks and fetches user daily gift.
     *
     * @param User $user
     *
     * @return CustomResponseInterface
     * @throws DailyGiftException
     * @throws NoSuchEntityException
     */
    public function execute(User $user): CustomResponseInterface
    {
        /** @var DailyGift $dailyGift */
        $dailyGift = $this->dailyGiftRepository->get($user->getId(), 'user_id', true);

        try {
            if (!$dailyGift) {
                $dailyGift = $this->getNewDailyGift($user);

                return response()
                    ->setData($dailyGift);
            }

            $pickTime = $dailyGift->getPickTime();

            if (time() <= $pickTime) {
                throw new DailyGiftException(
                    __('User already picked the daily gift.'),
                    UserResponseCodeInterface::RESPONSE_GIFT_ALREADY_PICKED,
                    HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
                );
            }
            $this->applyGift($dailyGift, $user, $dailyGift->getAmount());
        } catch (Exception $exception) {
            throw new DailyGiftException(
                $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }

        return response()
            ->setData($dailyGift);
    }

    /**
     * Returns random gift amount.
     *
     * @return int
     * @throws \Exception
     */
    private function getRandomGiftAmount(): int
    {
        return random_int(
            $this->config->get('hotel_settings.gift.min_amount'),
            $this->config->get('hotel_settings.gift.max_amount')
        );
    }

    /**
     * Applies gift to user.
     *
     * @param DailyGift $dailyGift
     * @param User      $user
     * @param int       $amount
     *
     * @throws DailyGiftException
     * @throws DataObjectManagerException
     */
    private function applyGift(DailyGift $dailyGift, User $user, int $amount): void
    {
        $dailyGift->setPickTime(strtotime('+1 day'));

        try {
            $this->executeRconCommandService->execute(
                $user->getId(),
                [
                    'command' => 'givecredits',
                    'params' => [
                        'user_id' => $user->getId(),
                        'credits' => $amount
                    ]
                ],
                true
            );
        } catch (Exception $exception) {
            throw new DailyGiftException(
                $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }

        $this->dailyGiftRepository->save($dailyGift);
    }

    /**
     * Saves and returns new daily gift.
     *
     * @param User $user
     *
     * @return DailyGift|null
     * @throws DataObjectManagerException
     * @throws \JsonException
     * @throws RconException
     * @throws RoleException
     * @throws \Exception
     */
    private function getNewDailyGift(User $user): ?DailyGift
    {
        $dailyGift = new DailyGift();

        $dailyGift
            ->setUserId($user->getId())
            ->setPickTime(strtotime('+1 day'))
            ->setAmount($this->getRandomGiftAmount());

        $this->executeRconCommandService->execute(
            $user->getId(),
            [
                'command' => 'givecredits',
                'params' => [
                    'user_id' => $user->getId(),
                    'credits' => $this->getRandomGiftAmount()
                ]
            ],
            true
        );

        $this->dailyGiftRepository->save($dailyGift);

        return $dailyGift;
    }
}
