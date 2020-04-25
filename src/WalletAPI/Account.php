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
    /** @var null|Base64 */
    private $publicKey;
    /** @var null|Base64 */
    private $privateKey;

    /**
     * Account constructor.
     * @param WalletAPI $walletAPI
     * @param string $accountId
     */
    public function __construct(WalletAPI $walletAPI, string $accountId)
    {
        if (!Validator::AccountId($accountId)) {
            throw new \InvalidArgumentException('Invalid account ID');
        }

        $this->walletAPI = $walletAPI;
        $this->accountId = $accountId;
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
     * @return string
     */
    public function accountId(): string
    {
        return $this->accountId;
    }

    /**
     * @return array
     * @throws \FurqanSiddiqui\BitShares\Exception\BadResponseException
     * @throws \FurqanSiddiqui\BitShares\Exception\ConnectionException
     * @throws \FurqanSiddiqui\BitShares\Exception\ErrorResponseException
     */
    public function listBalances(): array
    {
        $balances = $this->walletAPI->call("list_account_balances", [$this->accountId]);
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
}
