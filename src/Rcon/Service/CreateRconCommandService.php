<?php
namespace Orion\Rcon\Service;

use Orion\Core\Exception\DataObjectManagerException;
use Orion\Core\Exception\NoSuchEntityException;
use Orion\Core\Interfaces\CustomResponseInterface;
use Orion\Core\Interfaces\HttpResponseCodeInterface;
use Orion\Rcon\Entity\Rcon;
use Orion\Rcon\Exception\RconException;
use Orion\Rcon\Interfaces\Response\RconResponseCodeInterface;
use Orion\Rcon\Repository\RconRepository;

/**
 * Class CreateRconCommandService
 *
 * @package Ares\Rcon\Service
 */
class CreateRconCommandService
{
    /**
     * CreateRconCommandService constructor.
     *
     * @param RconRepository $rconRepository
     */
    public function __construct(
        private RconRepository $rconRepository
    ) {}

    /**
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws DataObjectManagerException
     * @throws RconException
     * @throws NoSuchEntityException
     */
    public function execute(array $data): CustomResponseInterface
    {
        /** @var Rcon $existingCommand */
        $existingCommand = $this->rconRepository->get($data['command'], 'command', true);

        if ($existingCommand) {
            throw new RconException(
                __('The command %s already exists',
                    [$existingCommand->getCommand()]),
                RconResponseCodeInterface::RESPONSE_RCON_COMMAND_ALREADY_EXIST,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        $command = $this->getNewCommand($data);

        /** @var Rcon $command */
        $command = $this->rconRepository->save($command);

        return response()
            ->setData($command);
    }

    /**
     * @param array $data
     *
     * @return Rcon
     */
    private function getNewCommand(array $data): Rcon
    {
        $rconCommand = new Rcon();

        return $rconCommand
            ->setCommand($data['command'])
            ->setTitle($data['title'])
            ->setDescription($data['description']);
    }
}
