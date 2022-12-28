<?php
namespace Orion\Rcon\Service;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Framework\Interfaces\HttpResponseCodeInterface;
use Orion\Rcon\Entity\Rcon;
use Orion\Rcon\Exception\RconException;
use Orion\Rcon\Interfaces\Response\RconResponseCodeInterface;
use Orion\Rcon\Repository\RconRepository;

/**
 * Class DeleteRconCommandService
 *
 * @package Orion\Rcon\Service
 */
class DeleteRconCommandService
{
    /**
     * DeleteRconCommandService constructor.
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
     * @throws NoSuchEntityException
     * @throws RconException
     */
    public function execute(array $data): CustomResponseInterface
    {
        /** @var Rcon $command */
        $command = $this->rconRepository->get($data['command'], 'command');
        $deleted = $this->rconRepository->delete($command->getId());

        if (!$deleted) {
            throw new RconException(
                __('Command could not be deleted'),
                RconResponseCodeInterface::RESPONSE_RCON_COMMAND_NOT_DELETED,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        return response()
            ->setData(true);
    }
}
