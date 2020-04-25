<?php
declare(strict_types=1);

namespace FurqanSiddiqui\BitShares\WalletAPI\Objects;

use FurqanSiddiqui\BitShares\Validator;
use FurqanSiddiqui\BitShares\WalletAPI;

/**
 * Class AccountAssetBalance
 * @package FurqanSiddiqui\BitShares\WalletAPI\Objects
 */
class AccountAssetBalance
{
    /** @var string */
    public $assetId;
    /** @var int */
    public $rawIntAmount;
    /** @var string */
    public $balance;
    /** @var Asset */
    public $asset;

    /**
     * AccountAssetBalance constructor.
     * @param WalletAPI $walletAPI
     * @param array $assetBalance
     * @throws \FurqanSiddiqui\BitShares\Exception\BadResponseException
     * @throws \FurqanSiddiqui\BitShares\Exception\ConnectionException
     * @throws \FurqanSiddiqui\BitShares\Exception\ErrorResponseException
     */
    public function __construct(WalletAPI $walletAPI, array $assetBalance)
    {
        $this->assetId = $assetBalance["asset_id"];
        if (!Validator::AccountOrAssetId($this->assetId)) {
            throw new \UnexpectedValueException('Invalid asset ID');
        }

        $this->rawIntAmount = $assetBalance["amount"];
        if (!is_int($this->rawIntAmount)) {
            throw new \UnexpectedValueException('Invalid asset balance amount');
        }

        $this->asset = $walletAPI->assets()->get($this->assetId);
        $scale = $this->asset->precision();
        $this->balance = bcdiv(strval($this->rawIntAmount), bcpow("10", strval($scale), 0), $scale);
    }
}
