<?php
declare(strict_types=1);

namespace FurqanSiddiqui\BitShares;

/**
 * Class Validator
 * @package FurqanSiddiqui\BitShares
 */
class Validator
{
    /**
     * @param $accountId
     * @return bool
     */
    public static function AccountName($accountId): bool
    {
        if (is_string($accountId)) {
            if (preg_match('/^[a-z0-9\-]{2,64}$/', $accountId)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $id
     * @return bool
     */
    public static function AccountOrAssetId($id): bool
    {
        if (is_string($id)) {
            if (preg_match('/[0-9]+(\.[0-9]+)*/', $id)) {
                return true;
            }
        }

        return false;
    }
}
