<?php
declare(strict_types=1);

namespace FurqanSiddiqui\BitShares;

use FurqanSiddiqui\BitShares\Exception\BadResponseException;
use FurqanSiddiqui\BitShares\Exception\ConnectionException;
use FurqanSiddiqui\BitShares\Exception\ErrorResponseException;
use FurqanSiddiqui\BitShares\WalletAPI\Info;
use WebSocket\Client;

/**
 * Class WalletAPI
 * @package FurqanSiddiqui\BitShares
 */
class WalletAPI
{
    /** @var string */
    private $host;
    /** @var int */
    private $port;
    /** @var bool */
    private $tls;
    /** @var Client */
    private $sock;

    /**
     * WalletAPI constructor.
     * @param string $host
     * @param int $port
     * @param bool $tls
     */
    public function __construct(string $host, int $port = 8090, bool $tls = false)
    {
        if ($port >= 0xffff) {
            throw new \OutOfRangeException('Invalid wallet API WS port');
        }

        $this->host = $host;
        $this->port = $port;
        $this->tls = $tls;
        $this->sock = new Client($this->apiURL());
    }

    /**
     * @return string
     */
    public function apiURL(): string
    {
        return $this->tls ? "wss" : "ws" . "://" . $this->host . ":" . $this->port;
    }

    /**
     * @return int
     */
    private function uniqueReqId(): int
    {
        return time();
    }

    /**
     * @return Info
     * @throws BadResponseException
     * @throws ConnectionException
     * @throws ErrorResponseException
     * @throws Exception\ResponseMapException
     */
    public function info(): Info
    {
        return new Info($this->call("info", null, $this->uniqueReqId()));
    }

    /**
     * @param string $method
     * @param array|null $params
     * @param int $id
     * @return mixed
     * @throws ConnectionException
     * @throws BadResponseException
     * @throws ErrorResponseException
     */
    public function call(string $method, ?array $params, int $id)
    {
        try {
            $this->sock->send(JSON_RPC20::Payload($method, $params, $id));
            return JSON_RPC20::Response($this->sock->receive(), $id);
        } catch (\WebSocket\Exception $e) {
            throw new ConnectionException($e->getMessage());
        }
    }
}
