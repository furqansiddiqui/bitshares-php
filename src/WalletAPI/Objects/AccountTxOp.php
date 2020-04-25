<?php
declare(strict_types=1);

namespace FurqanSiddiqui\BitShares\WalletAPI\Objects;

/**
 * Class AccountTxOp
 * @package FurqanSiddiqui\BitShares\WalletAPI\Objects
 */
class AccountTxOp
{
    /** @var array */
    private $raw;

    /**
     * AccountTxOp constructor.
     * @param array $op
     */
    public function __construct(array $op)
    {
        $this->raw = $op;
    }

    /**
     * @return array
     */
    public function raw(): array
    {
        return $this->raw;
    }
}
