<?php
namespace Orion\User\Service\Settings;

use Orion\Core\Exception\DataObjectManagerException;
use Orion\Core\Exception\NoSuchEntityException;
use Orion\Core\Interfaces\CustomResponseInterface;
use Orion\Core\Interfaces\HttpResponseCodeInterface;
use Orion\User\Entity\User;
use Orion\User\Entity\UserSetting;
use Orion\User\Exception\UserSettingsException;
use Orion\User\Interfaces\Response\UserResponseCodeInterface;
use Orion\User\Repository\UserRepository;
use Orion\User\Repository\UserSettingRepository;

/**
 * Class ChangeUsernameService
 *
 * @package Orion\User\Service\Settings
 */
class ChangeUsernameService
{
    /**
     * ChangeUsernameService constructor.
     *
     * @param UserRepository $userRepository
     * @param UserSettingRepository $userSettingRepository
     */
    public function __construct(
        private UserRepository $userRepository,
        private UserSettingRepository $userSettingRepository
    ) {}

    /**
     * Changes user name by given data.
     *
     * @param User   $user
     * @param string $username
     * @param string $password
     *
     * @return CustomResponseInterface
     * @throws DataObjectManagerException
     * @throws UserSettingsException
     * @throws NoSuchEntityException
     */
    public function execute(User $user, string $username, string $password): CustomResponseInterface
    {
        /** @var UserSetting $userSetting */
        $userSetting = $this->userSettingRepository->get($user->getId(), 'user_id');

        if (!password_verify($password, $user->getPassword())) {
            throw new UserSettingsException(
                __('Given old password does not match the current password'),
                UserResponseCodeInterface::RESPONSE_SETTINGS_DIFFERENT_PASSWORD,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        if (!$userSetting->getCanChangeName()) {
            throw new UserSettingsException(
                __('User is not allowed to change the Username'),
                UserResponseCodeInterface::RESPONSE_SETTINGS_NOT_ALLOWED_TO_CHANGE_USERNAME,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        /** @var User $usernameExists */
        $usernameExists = $this->userRepository->get($username, 'username', true);

        if ($usernameExists || $user->getUsername() === $usernameExists) {
            throw new UserSettingsException(
                __('You cannot use the same Username or a User with this username already exists'),
                UserResponseCodeInterface::RESPONSE_SETTINGS_USER_USERNAME_EXISTS,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        /** @var User $user */
        $user = $this->userRepository->save($user->setUsername($username));

        return response()
            ->setData($user);
    }
}
