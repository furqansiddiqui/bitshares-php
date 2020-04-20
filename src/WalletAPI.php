<?php
declare(strict_types=1);

namespace FurqanSiddiqui\BitShares;

use FurqanSiddiqui\BitShares\Exception\BadResponseException;
use FurqanSiddiqui\BitShares\Exception\ConnectionException;
use FurqanSiddiqui\BitShares\Exception\ErrorResponseException;
use FurqanSiddiqui\BitShares\WalletAPI\Account;
use FurqanSiddiqui\BitShares\WalletAPI\Info;
use FurqanSiddiqui\BitShares\WalletAPI\SuggestBrainKey;
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
     * @return SuggestBrainKey
     * @throws BadResponseException
     * @throws ConnectionException
     * @throws ErrorResponseException
     * @throws Exception\ResponseMapException
     */
    public function suggestBrainKey(): SuggestBrainKey
    {
        return new SuggestBrainKey($this->call("suggest_brain_key", null, $this->uniqueReqId()));
    }

    /**
     * @return bool
     * @throws BadResponseException
     * @throws ConnectionException
     * @throws ErrorResponseException
     */
    public function isLocked(): bool
    {
        return $this->call("is_locked", [], $this->uniqueReqId());
    }

    /**
     * @return bool
     * @throws BadResponseException
     * @throws ConnectionException
     * @throws ErrorResponseException
     */
    public function isNew(): bool
    {
        return $this->call("is_new", [], $this->uniqueReqId());
    }

    /**
     * @param string $accountId
     * @return Account
     */
    public function account(string $accountId): Account
    {
        return new Account($this, $accountId);
    }

    /**
     * @param Account $account
     * @return bool
     * @throws BadResponseException
     * @throws ConnectionException
     * @throws ErrorResponseException
     */
    public function importAccount(Account $account): bool
    {
        $privateKey = $account->getPrivateKey();
        if (!$privateKey) {
            throw new \UnexpectedValueException('BTS account private key is not set');
        }

        return $this->call("import_key", [$account->accountId(), $privateKey->value()], $this->uniqueReqId());
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
