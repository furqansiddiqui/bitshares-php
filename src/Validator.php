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
    public static function AccountId($accountId): bool
    {
        if (is_string($accountId)) {
            if (preg_match('/^[a-z0-9\-]{2,64}$/', $accountId)) {
                return true;
            }
        }

        return false;
    }
}
