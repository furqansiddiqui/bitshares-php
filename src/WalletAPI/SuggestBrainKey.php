<?php
declare(strict_types=1);

namespace FurqanSiddiqui\BitShares\WalletAPI;

use Comely\DataTypes\Buffer\Base64;
use FurqanSiddiqui\BitShares\Exception\ResponseMapException;

/**
 * Class SuggestBrainKey
 * @package FurqanSiddiqui\BitShares\WalletAPI
 */
class SuggestBrainKey
{
    /** @var string */
    public $brainPrivateKey;
    /** @var string */
    public $wifPrivateKey;
    /** @var string */
    public $publicKey;

    /**
     * SuggestBrainKey constructor.
     * @param $res
     * @throws ResponseMapException
     */
    public function __construct($res)
    {
        if (!is_array($res)) {
            throw new ResponseMapException(sprintf('Response object WalletAPI\SuggestBrainKey expects an array result'));
        }

        $this->brainPrivateKey = $res["brain_priv_key"];
        $this->wifPrivateKey = (new Base64($res["wif_priv_key"]))->readOnly(true);
        $this->publicKey = (new Base64($res["pub_key"]))->readOnly(true);
    }
}
