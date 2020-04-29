<?php
declare(strict_types=1);

namespace FurqanSiddiqui\BitShares\WalletAPI;

use Comely\DataTypes\Buffer\Base64;
use FurqanSiddiqui\BitShares\Validator;
use FurqanSiddiqui\BitShares\WalletAPI;

/**
 * Class Account
 * @package FurqanSiddiqui\BitShares\WalletAPI
 */
class Account
{
    /** @var WalletAPI */
    private $walletAPI;
    /** @var string */
    private $accountId;
    /** @var string */
    private $accountName;
    /** @var null|Base64 */
    private $publicKey;
    /** @var null|Base64 */
    private $privateKey;
    /** @var null|WalletAPI\Objects\AccountInfo */
    private $accountInfoObj;

    /**
     * Account constructor.
     * @param WalletAPI $walletAPI
     * @param string $accountIdOrName
     */
    public function __construct(WalletAPI $walletAPI, string $accountIdOrName)
    {
        $isValidAccountName = Validator::AccountName($accountIdOrName);
        $isValidAccountId = Validator::AccountOrAssetId($accountIdOrName);
        if (!$isValidAccountName && !$isValidAccountId) {
            throw new \InvalidArgumentException('Invalid account ID/name given to constructor');
        }

        $this->walletAPI = $walletAPI;
        $this->accountId = $isValidAccountId ? $accountIdOrName : null;
        $this->accountName = $isValidAccountName ? $accountIdOrName : null;
    }

    /**
     * @return array
     */
    public function __debugInfo(): array
    {
        return [sprintf('BitShares account "%s"', $this->accountId)];
    }

    /**
     * @param $privateKey
     */
    public function setPrivateKey($privateKey): void
    {
        if (is_string($privateKey)) {
            $privateKey = new Base64($privateKey);
        }

        if (!$privateKey instanceof Base64) {
            throw new \InvalidArgumentException('Private key must be a String or Base64');
        }

        $this->privateKey = $privateKey->copy();
        $this->privateKey->readOnly(true);
    }

    /**
     * @return Base64|null
     */
    public function getPrivateKey(): ?Base64
    {
        return $this->privateKey;
    }

    /**
     * @param $publicKey
     */
    public function setPublicKey($publicKey): void
    {
        if (is_string($publicKey)) {
            $publicKey = new Base64($publicKey);
        }

        if (!$publicKey instanceof Base64) {
            throw new \InvalidArgumentException('Private key must be a String or Base64');
        }

        $this->publicKey = $publicKey->copy();
        $this->publicKey->readOnly(true);
    }

    /**
     * @return Base64|null
     */
    public function getPublicKey(): ?Base64
    {
        return $this->publicKey;
    }

    /**
     * @param bool $cached
     * @return Objects\AccountInfo
     * @throws \FurqanSiddiqui\BitShares\Exception\BadResponseException
     * @throws \FurqanSiddiqui\BitShares\Exception\ConnectionException
     * @throws \FurqanSiddiqui\BitShares\Exception\ErrorResponseException
     */
    public function info(bool $cached = true): WalletAPI\Objects\AccountInfo
    {
        if ($this->accountInfoObj && $cached) {
            return $this->accountInfoObj;
        }

        $accountInfoRaw = $this->walletAPI->call("get_account", [$this->accountId ?? $this->accountName]);
        if (!is_array($accountInfoRaw)) {
            throw new \UnexpectedValueException(
                sprintf('Expected getAccount call result type Array; got "%s"', gettype($accountInfoRaw))
            );
        }

        $accountInfoObj = new WalletAPI\Objects\AccountInfo($accountInfoRaw);
        $this->accountInfoObj = $accountInfoObj;
        $this->accountName = $this->accountInfoObj->name();
        $this->accountId = $this->accountInfoObj->id();

        return $this->accountInfoObj;
    }

    /**
     * @return string
     */
    public function accountId(): string
    {
        return $this->accountId ?? $this->accountName;
    }

    /**
     * @return string
     */
    public function accountName(): string
    {
        return $this->accountName ?? $this->accountId;
    }

    /**
     * @return array
     * @throws \FurqanSiddiqui\BitShares\Exception\BadResponseException
     * @throws \FurqanSiddiqui\BitShares\Exception\ConnectionException
     * @throws \FurqanSiddiqui\BitShares\Exception\ErrorResponseException
     */
    public function listBalances(): array
    {
        $balances = $this->walletAPI->call("list_account_balances", [$this->accountName]);
        if (!is_array($balances)) {
            throw new \UnexpectedValueException('listAccountBalances expected an Array');
        }

        $accountAssetBalances = [];
        if ($balances) {
            foreach ($balances as $balance) {
                if (!is_array($balance)) {
                    throw new \UnexpectedValueException('Each item in listAccountBalances array must be of type object');
                }

                $accountAssetBalances[] = new WalletAPI\Objects\AccountAssetBalance($this->walletAPI, $balance);
            }
        }

        return $accountAssetBalances;
    }

    /**
     * @param int $limit
     * @param bool $skipOnFail
     * @return array
     * @throws \FurqanSiddiqui\BitShares\Exception\BadResponseException
     * @throws \FurqanSiddiqui\BitShares\Exception\ConnectionException
     * @throws \FurqanSiddiqui\BitShares\Exception\ErrorResponseException
     * @throws \Throwable
     */
    public function history(int $limit = 100, bool $skipOnFail = false): array
    {
        $accountTxs = $this->walletAPI->call("get_account_history", [$this->accountId(), $limit]);
        if (!is_array($accountTxs)) {
            throw new \UnexpectedValueException('getAccountHistory expected an Array result');
        }

        $history = [];
        $index = -1;
        foreach ($accountTxs as $accountTxRaw) {
            $index++;
            try {
                $txEntry = new WalletAPI\Objects\AccountTx($accountTxRaw);
            } catch (\Throwable $t) {
                if (!$skipOnFail) {
                    throw $t;
                }

                trigger_error(sprintf('[Tx-index:%d][%s][%s] %s', $index, get_class($t), $t->getCode(), $t->getMessage()), E_USER_WARNING);
            }

            if (isset($txEntry)) {
                $history[] = $txEntry;
            }
        }

        return $history;
    }

    /**
     * @param Account $to
     * @param string $amount
     * @param Objects\Asset $asset
     * @param string $memo
     * @return Objects\SignedTransaction
     * @throws \FurqanSiddiqui\BitShares\Exception\BadResponseException
     * @throws \FurqanSiddiqui\BitShares\Exception\ConnectionException
     * @throws \FurqanSiddiqui\BitShares\Exception\ErrorResponseException
     */
    public function transfer(Account $to, string $amount, WalletAPI\Objects\Asset $asset, string $memo): WalletAPI\Objects\SignedTransaction
    {
        $scale = $asset->precision();
        if (bccomp($amount, "0", $scale) !== 1) {
            throw new \UnexpectedValueException('Amount must be a positive numeric value');
        }

        $amount = bcmul($amount, "1", $scale);
        $memoLen = strlen($memo);
        if ($memoLen > 32) {
            throw new \UnexpectedValueException('Memo exceeds limit of 32 bytes');
        }

        $transfer = $this->walletAPI->call(
            "transfer2",
            [
                $this->accountId(),
                $to->accountId(),
                $amount,
                $asset->id(),
                $memo,
                true,
            ]
        );
        if (!is_array($transfer)) {
            throw new \UnexpectedValueException(
                sprintf('Transfer method expected response of type Array; got "%s"', gettype($transfer))
            );
        }

        $txId = $transfer[0];
        if (!is_string($txId) || !preg_match('/^[a-f0-9]{40}$/i', $txId)) {
            throw new \UnexpectedValueException('Invalid transaction ID from transfer2');
        }

        $txRaw = $transfer[1];
        if (!is_array($txRaw)) {
            throw new \UnexpectedValueException('Invalid signed transaction obj from transfer2');
        }

        $signedTx = new WalletAPI\Objects\SignedTransaction($txRaw);
        $signedTx->txId = $txId;
        return $signedTx;
    }
}
