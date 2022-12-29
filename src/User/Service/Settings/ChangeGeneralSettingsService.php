<?php
namespace Orion\User\Service\Settings;

use Orion\Core\Exception\DataObjectManagerException;
use Orion\Core\Exception\NoSuchEntityException;
use Orion\Core\Interfaces\CustomResponseInterface;
use Orion\Rcon\Service\ExecuteRconCommandService;
use Orion\User\Entity\User;
use Orion\User\Exception\userRepositoryException;
use Orion\User\Repository\userRepository;
use Slim\Routing\RouteParser;
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
        private UserRepository $userRepository,
        private RouteParser $routeParser
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

        return response()->setData([
            'replacepage'  => $this->routeParser->urlFor('settings-personalisation'),
            'status'    => 'success',
            'message'   => __('Personalisation saved!!'),
        ]);
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
            ->setYoutubeSong($data['youtube_song'] ?? null);
    }
}
