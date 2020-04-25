<?php
declare(strict_types=1);

namespace FurqanSiddiqui\BitShares\WalletAPI;

use FurqanSiddiqui\BitShares\WalletAPI;

/**
 * Class Assets
 * @package FurqanSiddiqui\BitShares\WalletAPI
 */
class Assets
{
    /** @var WalletAPI */
    private $walletAPI;
    /** @var array */
    private $assets;

    /**
     * Assets constructor.
     * @param WalletAPI $walletAPI
     */
    public function __construct(WalletAPI $walletAPI)
    {
        $this->walletAPI = $walletAPI;
        $this->assets = [];
    }

    /**
     * @param string $assetId
     * @param bool $cachedObj
     * @return Objects\Asset
     * @throws \FurqanSiddiqui\BitShares\Exception\BadResponseException
     * @throws \FurqanSiddiqui\BitShares\Exception\ConnectionException
     * @throws \FurqanSiddiqui\BitShares\Exception\ErrorResponseException
     */
    public function get(string $assetId, bool $cachedObj = true): WalletAPI\Objects\Asset
    {
        if (isset($this->assets[$assetId]) && $cachedObj) {
            return $this->assets[$assetId];
        }

        $assetRaw = $this->walletAPI->call("get_asset", [$assetId]);
        $assetObj = new WalletAPI\Objects\Asset($assetRaw);
        $this->assets[$assetObj->id()] = $assetObj;
        return $assetObj;
    }
}
