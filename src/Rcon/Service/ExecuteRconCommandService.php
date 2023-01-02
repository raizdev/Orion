<?php
namespace Orion\Rcon\Service;

use Orion\Core\Exception\NoSuchEntityException;
use Orion\Core\Interfaces\CustomResponseInterface;
use Orion\Core\Interfaces\HttpResponseCodeInterface;
use Orion\Rcon\Exception\RconException;
use Orion\Rcon\Interfaces\Response\RconResponseCodeInterface;
use Orion\Rcon\Model\Rcon;
use Orion\Rcon\Repository\RconRepository;
use Orion\Role\Exception\RoleException;
use Orion\Role\Service\CheckAccessService;

/**
 * Class ExecuteRconCommandService
 *
 * @package Orion\Rcon\Service
 */
class ExecuteRconCommandService
{
    /**
     * ExecuteRconCommandService constructor.
     *
     * @param RconRepository     $rconRepository
     * @param Rcon               $rcon
     * @param CheckAccessService $checkAccessService
     */
    public function __construct(
        private RconRepository $rconRepository,
        private Rcon $rcon,
        private CheckAccessService $checkAccessService
    ) {}

    /**
     * @param int   $userId
     * @param array $data
     *
     * @param bool  $fromSystem
     *
     * @return CustomResponseInterface
     * @throws RconException
     * @throws NoSuchEntityException
     */
    public function execute(int $userId, array $data, bool $fromSystem = false): CustomResponseInterface
    {
        /** @var \Ares\Rcon\Entity\Rcon $existingCommand */
        $existingCommand = $this->rconRepository->get($data['command'], 'command');

        try {
            if (!$fromSystem && $existingCommand->getPermission() !== null) {
                $permissionName = $existingCommand
                    ->getPermission()
                    ->getName();
            }

            $hasAccess = $this->checkAccessService
                ->execute(
                    $userId,
                    $permissionName ?? null
                );

            if (!$hasAccess) {
                throw new RoleException(
                    __('You dont have the special rights to execute that action'),
                    RconResponseCodeInterface::RESPONSE_RCON_NO_RIGHTS_TO_EXECUTE,
                    HttpResponseCodeInterface::HTTP_RESPONSE_FORBIDDEN
                );
            }

            $executeCommand = $this->rcon
                ->buildConnection()
                ->sendCommand(
                    $this->rcon->getSocket(),
                    $data['command'],
                    $data['params'] ?? null
                );
        } catch(\Exception $e) {
            throw new RconException(
              $e->getMessage(),
              $e->getCode()
            );
        }

        return response()
            ->setData($executeCommand);
    }
}
