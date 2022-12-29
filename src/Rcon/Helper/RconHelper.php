<?php
namespace Orion\Rcon\Helper;

use Orion\Rcon\Exception\RconException;
use Orion\Rcon\Interfaces\Response\RconResponseCodeInterface;
use Orion\Core\Config;

/**
 * Class RconHelper
 *
 * @package Orion\Rcon\Helper
 */
class RconHelper
{
    /**
     * RconHelper constructor.
     *
     * @param Config $config
     */
    public function __construct(
        private Config $config
    ) {}

    /**
     * @param   resource    $socket
     * @param   string      $command
     * @param   array|null  $parameter
     *
     * @return array|null
     * @throws RconException
     * @throws \JsonException
     */
    public function sendCommand($socket, string $command, array $parameter = null): ?array
    {
        /** @var string $encodedData */
        $encodedData = json_encode([
            'key'  => $command,
            'data' => $parameter
        ], JSON_THROW_ON_ERROR);

        $executor = @socket_write($socket, $encodedData, strlen($encodedData));

        if (!$executor) {
            throw new RconException(
                __('Could not send the provided Command'),
            RconResponseCodeInterface::RESPONSE_RCON_COULD_NOT_SEND_COMMAND
            );
        }

        return json_decode(
            @socket_read($socket, 2048), true, 512, JSON_THROW_ON_ERROR
        );
    }

    /**
     * Builds the Socket connection
     *
     * @param   resource  $socket
     *
     * @return RconHelper
     * @throws RconException
     */
    public function buildConnection($socket): self
    {
        /** @var string $host */
        $host = $this->config->get('hotel_settings.client.rcon_host');

        /** @var int $port */
        $port = $this->config->get('hotel_settings.client.rcon_port');

        $isConnectionEstablished = $this->connectToSocket($socket, $host, $port);

        if (!$isConnectionEstablished) {
            throw new RconException(
                __('Could not establish a connection to the rcon server'),
                RconResponseCodeInterface::RESPONSE_RCON_NO_CONNECTION
            );
        }

        return $this;
    }

    /**
     * Connects to our Socket
     *
     * @param $socket
     * @param $host
     * @param $port
     *
     * @return bool
     */
    public function connectToSocket($socket, $host, $port): bool
    {
        return @socket_connect($socket, $host, $port);
    }

    /**
     * Creates a Socket
     *
     * @return \Socket
     * @throws RconException
     */
    public function createSocket(): \Socket
    {
        $socket = @socket_create(
            AF_INET,
            SOCK_STREAM,
            SOL_TCP
        );

        if (!$socket) {
            throw new RconException(
                __('Could not create the socket'),
                RconResponseCodeInterface::RESPONSE_RCON_COULD_NOT_CREATE_SOCKET
            );
        }

        return $socket;
    }
}
