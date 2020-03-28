<?php
declare(strict_types=1);

namespace FurqanSiddiqui\BitShares\WalletAPI;

use FurqanSiddiqui\BitShares\Exception\ResponseMapException;

/**
 * Class Info
 * @package FurqanSiddiqui\BitShares\WalletAPI
 */
class Info
{
    /** @var int */
    public $headBlockNum;
    /** @var string */
    public $headBlockId;
    /** @var string */
    public $headBlockAge;
    /** @var string */
    public $chainId;

    /**
     * Info constructor.
     * @param $res
     * @throws ResponseMapException
     */
    public function __construct($res)
    {
        if (!is_array($res)) {
            throw new ResponseMapException(sprintf('Response object WalletAPI\Info expects an array result'));
        }

        $this->headBlockNum = $res["head_block_num"];
        $this->headBlockId = $res["head_block_id"];
        $this->headBlockAge = $res["head_block_age"];
        $this->chainId = $res["chain_id"];
    }
}
