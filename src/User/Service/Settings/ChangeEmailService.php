<?php
namespace Orion\User\Service\Settings;

use Orion\Core\Exception\DataObjectManagerException;
use Orion\Core\Exception\NoSuchEntityException;
use Orion\Core\Interfaces\CustomResponseInterface;
use Orion\Core\Interfaces\HttpResponseCodeInterface;
use Orion\User\Entity\User;
use Orion\User\Exception\UserSettingsException;
use Orion\User\Interfaces\Response\UserResponseCodeInterface;
use Orion\User\Repository\UserRepository;

/**
 * Class ChangeEmailService
 *
 * @package Orion\User\Service\Settings
 */
class ChangeEmailService
{
    /**
     * ChangeEmailService constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(
        private UserRepository $userRepository
    ) {}

    /**
     * Changes email by given data.
     *
     * @param User   $user
     * @param string $email
     * @param string $password
     *
     * @return CustomResponseInterface
     * @throws DataObjectManagerException
     * @throws UserSettingsException
     * @throws NoSuchEntityException
     */
    public function execute(User $user, string $email, string $password): CustomResponseInterface
    {
        $currentEmail = $user->getMail();

        if (!password_verify($password, $user->getPassword())) {
            throw new UserSettingsException(
                __('Given old password does not match the current password'),
                UserResponseCodeInterface::RESPONSE_SETTINGS_OLD_NOT_EQUALS_NEW,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        if ($currentEmail === $email) {
            throw new UserSettingsException(
                __('Given E-Mail should be different to current E-Mail'),
                UserResponseCodeInterface::RESPONSE_SETTINGS_DIFFERENT_EMAIL,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        /** @var User $emailExists */
        $emailExists = $this->userRepository->get($email, 'mail', true);

        if ($emailExists) {
            throw new UserSettingsException(
                __('User with given E-Mail already exists'),
                UserResponseCodeInterface::RESPONSE_SETTINGS_USER_EMAIL_EXISTS,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        $this->userRepository->save($user->setMail($email));

        return response()
            ->setData($user);
    }
}
