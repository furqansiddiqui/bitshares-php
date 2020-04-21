<?php
declare(strict_types=1);

namespace FurqanSiddiqui\BitShares\WalletAPI\Objects;

/**
 * Class SignedTransaction
 * @package FurqanSiddiqui\BitShares\WalletAPI\Objects
 */
class SignedTransaction
{
    /** @var int|null */
    public $refBlockNum;
    /** @var int|null */
    public $refBlockPrefix;
    /** @var string|null */
    public $expiration;
    /** @var null|array */
    public $operations;
    /** @var null|array */
    public $extensions;
    /** @var null|array */
    public $signatures;

    /** @var array */
    private $rawData;

    /**
     * SignedTransaction constructor.
     * @param array $tx
     */
    public function __construct(array $tx)
    {
        $this->rawData = $tx;

        if (isset($tx["ref_block_num"]) && is_int($tx["ref_block_num"])) {
            $this->refBlockNum = $tx["ref_block_num"];
        }

        if (isset($tx["ref_block_prefix"]) && is_int($tx["ref_block_prefix"])) {
            $this->refBlockPrefix = $tx["ref_block_prefix"];
        }

        if (isset($tx["expiration"]) && is_string($tx["expiration"])) {
            $this->expiration = $tx["expiration"];
        }

        $this->operations = $tx["operations"] ?? null;
        $this->extensions = $tx["extensions"] ?? null;
        $this->signatures = $tx["signatures"] ?? null;
    }

    /**
     * @return array
     */
    public function raw(): array
    {
        return $this->rawData;
    }
}
