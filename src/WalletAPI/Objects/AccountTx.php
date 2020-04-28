<?php
declare(strict_types=1);

namespace FurqanSiddiqui\BitShares\WalletAPI\Objects;

/**
 * Class AccountTx
 * @package FurqanSiddiqui\BitShares\WalletAPI\Objects
 */
class AccountTx
{
    /** @var string */
    private $memo;
    /** @var string */
    private $description;
    /** @var array */
    private $op;
    /** @var int */
    private $blockNum;
    /** @var array */
    private $raw;

    /**
     * AccountTx constructor.
     * @param array $tx
     */
    public function __construct(array $tx)
    {
        $this->memo = $tx["memo"];
        if (!is_string($this->memo)) {
            throw new \UnexpectedValueException('Prop "memo" is required for AccountTx object');
        }

        $this->description = $tx["description"];
        if (!is_string($this->description)) {
            throw new \UnexpectedValueException('Prop "description" is required for AccountTx object');
        }

        $op = $tx["op"];
        if (!is_array($op)) {
            throw new \UnexpectedValueException('Prop "op" of type Array is required for AccountTx object');
        }

        $this->op = new AccountTxOp($op);

        $this->blockNum = $tx["op"]["block_num"];
        if (!is_int($this->blockNum)) {
            throw new \UnexpectedValueException('Prop "blockNum" of type Int is required for AccountTx object');
        }

        $this->raw = $tx;
    }

    /**
     * @return string
     */
    public function memo(): string
    {
        return $this->memo;
    }

    /**
     * @return string
     */
    public function description(): string
    {
        return $this->description;
    }

    /**
     * @return AccountTxOp
     */
    public function op(): AccountTxOp
    {
        return $this->op;
    }

    /**
     * @return int
     */
    public function blockNum(): int
    {
        return $this->blockNum;
    }

    /**
     * @return array
     */
    public function raw(): array
    {
        return $this->raw;
    }
}
