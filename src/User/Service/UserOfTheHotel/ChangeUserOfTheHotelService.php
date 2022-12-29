<?php
namespace Orion\User\Service\UserOfTheHotel;

use Orion\Core\Exception\DataObjectManagerException;
use Orion\Core\Exception\NoSuchEntityException;
use Orion\Core\Interfaces\CustomResponseInterface;
use Orion\User\Entity\User;
use Orion\User\Entity\UserOfTheHotel;
use Orion\User\Entity\UserSetting;
use Orion\User\Repository\UserOfTheHotelRepository;
use Orion\User\Repository\UserRepository;
use Orion\User\Repository\UserSettingRepository;

/**
 * Class ChangeUserOfTheHotelService
 *
 * @package Orion\User\Service\UserOfTheHotel
 */
class ChangeUserOfTheHotelService
{
    /**
     * ChangeUserOfTheHotelService constructor.
     *
     * @param UserSettingRepository    $userSettingRepository
     * @param UserOfTheHotelRepository $userOfTheHotelRepository
     * @param UserRepository           $userRepository
     */
    public function __construct(
        private UserSettingRepository $userSettingRepository,
        private UserOfTheHotelRepository $userOfTheHotelRepository,
        private UserRepository $userRepository
    ) {}

    /**
     * @return CustomResponseInterface
     *
     * @throws DataObjectManagerException|NoSuchEntityException
     */
    public function execute(): CustomResponseInterface
    {
        /** @var UserOfTheHotel $currentUserOfTheHotel */
        $currentUserOfTheHotel = $this->userOfTheHotelRepository->getCurrentUser();

        if (!$currentUserOfTheHotel || $currentUserOfTheHotel->getToTimestamp() <= time()) {
            $nextUserOfTheHotel = $this->getNewUserOfTheHotel();

            /** @var UserOfTheHotel $nextUserOfTheHotel */
            $nextUserOfTheHotel = $this->userOfTheHotelRepository->save($nextUserOfTheHotel);
            $nextUserOfTheHotel->getUser();

            return response()
                ->setData($nextUserOfTheHotel);
        }

        return response()
            ->setData($currentUserOfTheHotel);
    }

    /**
     * @return UserOfTheHotel
     *
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
     */
    private function getNewUserOfTheHotel(): UserOfTheHotel
    {
        /** @var UserSetting $eligibleUser */
        $eligibleUser = $this->userSettingRepository->getUserWithMostRespects();

        /** @var User $userData */
        $userData = $this->userRepository->get($eligibleUser->getUserId());

        $newUser = new UserOfTheHotel();

        return $newUser
            ->setUserId($userData->getId())
            ->setToTimestamp(strtotime('+1 week'))
            ->setCreatedAt(new \DateTime());
    }
}
