<?php
declare(strict_types=1);

namespace FurqanSiddiqui\BitShares\WalletAPI\Objects;

use FurqanSiddiqui\BitShares\Validator;

/**
 * Class AccountInfo
 * @package FurqanSiddiqui\BitShares\WalletAPI\Objects
 */
class AccountInfo
{
    /** @var string */
    private $id;
    /** @var string */
    private $name;
    /** @var string */
    private $registrar;
    /** @var string */
    private $referrer;
    /** @var string */
    private $lifetimeReferrer;
    /** @var array */
    private $raw;

    /**
     * AccountInfo constructor.
     * @param array $acc
     */
    public function __construct(array $acc)
    {
        if (!Validator::AccountOrAssetId($acc["id"])) {
            throw new \UnexpectedValueException('Invalid account info ID');
        }

        $this->id = $acc["id"];

        if (!Validator::AccountName($acc["name"])) {
            throw new \UnexpectedValueException('Invalid account info name');
        }

        $this->name = $acc["name"];

        if (!Validator::AccountOrAssetId($acc["registrar"])) {
            throw new \UnexpectedValueException('Invalid account info registrar');
        }

        $this->registrar = $acc["registrar"];

        if (!Validator::AccountOrAssetId($acc["referrer"])) {
            throw new \UnexpectedValueException('Invalid account info referrer');
        }

        $this->referrer = $acc["referrer"];

        if (!Validator::AccountOrAssetId($acc["lifetime_referrer"])) {
            throw new \UnexpectedValueException('Invalid account info lifetime_referrer');
        }

        $this->lifetimeReferrer = $acc["lifetime_referrer"];
        $this->raw = $acc;
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function registrar(): string
    {
        return $this->registrar;
    }

    /**
     * @return string
     */
    public function referrer(): string
    {
        return $this->referrer;
    }

    /**
     * @return string
     */
    public function lifetimeReferrer(): string
    {
        return $this->lifetimeReferrer;
    }

    /**
     * @return array
     */
    public function raw(): array
    {
        return $this->raw;
    }
}
