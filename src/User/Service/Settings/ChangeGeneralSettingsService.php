<?php
namespace Orion\User\Service\Settings;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Orion\Rcon\Service\ExecuteRconCommandService;
use Orion\User\Entity\User;
use Orion\User\Exception\userRepositoryException;
use Orion\User\Repository\userRepository;
use Exception;

/**
 * Class ChangeGeneralSettingsService
 *
 * @package Orion\User\Service\Settings
 */
class ChangeGeneralSettingsService
{
    /**
     * ChangeGeneralSettingsService constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(
        private UserRepository $userRepository
    ) {}

    /**
     * Changes user general settings by given user.
     *
     * @param User  $user
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws DataObjectManagerException
     * @throws UserSettingsException
     * @throws NoSuchEntityException
     */
    public function execute(User $user, array $data): CustomResponseInterface
    {
        /** @var User $user */
        $user = $this->userRepository->get($user->getId(), 'user_id');

        /** @var UserSetting $userSetting */
        $user = $this->userRepository->save($this->getUpdatedUser($user, $data));
        dd($user);
        return response()
            ->setData($user);
    }

    /**
     * Returns updated user model.
     *
     * @param User $user
     * @param array $data
     * @return User
     */
    private function getUpdatedUser(User $user, array $data): User
    {
        return $user
            ->setAvatarBg($data['avatar_bg'])
            ->setYoutubeSong($data['youtube_song']);
    }
}
