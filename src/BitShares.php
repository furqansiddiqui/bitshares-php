<?php
declare(strict_types=1);

namespace FurqanSiddiqui\BitShares;

/**
 * Class BitShares
 * @package FurqanSiddiqui\BitShares
 */
class BitShares
{
    /**
     * @param string $host
     * @param int $port
     * @return WalletAPI
     */
    public static function WalletAPI(string $host, int $port): WalletAPI
    {
        return new WalletAPI($host, $port);
    }
}
