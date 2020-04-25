<?php
declare(strict_types=1);

namespace FurqanSiddiqui\BitShares\WalletAPI\Objects;

use FurqanSiddiqui\BitShares\Validator;

/**
 * Class Asset
 * @package FurqanSiddiqui\BitShares\WalletAPI\Objects
 */
class Asset
{
    /** @var string */
    private $id;
    /** @var string */
    private $symbol;
    /** @var int */
    private $precision;
    /** @var string */
    private $issuer;
    /** @var array */
    private $options;
    /** @var array */
    private $raw;

    /**
     * Asset constructor.
     * @param array $asset
     */
    public function __construct(array $asset)
    {
        $this->id = $asset["id"];
        if (!Validator::AccountOrAssetId($this->id)) {
            throw new \UnexpectedValueException('Invalid asset identifier');
        }

        $this->symbol = $asset["symbol"];
        if (!is_string($this->symbol) || !preg_match('/\w{2,8}/', $this->symbol)) {
            throw new \UnexpectedValueException('Invalid asset symbol');
        }

        $this->precision = $asset["precision"];
        if (!is_int($this->precision)) {
            throw new \UnexpectedValueException('Invalid asset precision value');
        } elseif ($this->precision < 0 || $this->precision > 18) {
            throw new \OutOfRangeException(sprintf('Asset precision value "%d" is out of range', $this->precision));
        }

        $this->issuer = $asset["issuer"];
        if (!Validator::AccountOrAssetId($this->issuer)) {
            throw new \OutOfRangeException('Invalid asset issuer identifier');
        }

        $this->options = $asset["options"];
        if (!is_array($this->options)) {
            throw new \UnexpectedValueException(sprintf('Asset options must be an object, got "%s"', gettype($this->options)));
        }

        $this->raw = $asset;
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
    public function symbol(): string
    {
        return $this->symbol;
    }

    /**
     * @return int
     */
    public function precision(): int
    {
        return $this->precision;
    }

    /**
     * @return string
     */
    public function issuerId(): string
    {
        return $this->issuer;
    }

    /**
     * @return array
     */
    public function options(): array
    {
        return $this->options;
    }

    /**
     * @return array
     */
    public function raw(): array
    {
        return $this->raw;
    }
}
