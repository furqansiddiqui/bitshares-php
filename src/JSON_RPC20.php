<?php
declare(strict_types=1);

namespace FurqanSiddiqui\BitShares;

use FurqanSiddiqui\BitShares\Exception\BadResponseException;
use FurqanSiddiqui\BitShares\Exception\ErrorResponseException;

/**
 * Class JSON_RPC20
 * @package FurqanSiddiqui\BitShares
 */
class JSON_RPC20
{
    /**
     * @param string $method
     * @param array|null $params
     * @param bool $id
     * @return string
     */
    public static function Payload(string $method, ?array $params = null, $id = false): string
    {
        $payload = [
            "jsonrpc" => "2.0",
            "method" => $method,
        ];

        if (is_array($params)) {
            $payload["params"] = $params;
        }

        if ($id !== false) {
            if (is_string($id) || is_int($id) || is_null($id)) {
                $payload["id"] = $id;
            } else {
                throw new \InvalidArgumentException('Invalid value for request ID (JSON RPC spec 2.0)');
            }
        }

        return json_encode($payload);
    }

    /**
     * @param string $result
     * @param bool $reqId
     * @return mixed
     * @throws BadResponseException
     * @throws ErrorResponseException
     */
    public static function Response(string $result, $reqId = false)
    {
        $res = json_decode($result, true);
        if (!is_array($res)) {
            throw new BadResponseException('Failed to JSON decode the response string');
        }

        if ($res["jsonrpc"] !== "2.0") {
            throw new BadResponseException('Not a valid JSON RPC spec 2.0 response');
        }

        if ($reqId !== false) {
            if ($res["id"] !== $reqId) {
                throw new BadResponseException('Response ID does not match request ID');
            }
        }

        if (isset($res["error"])) {
            $errorCode = $res["error"]["code"];
            $errorMsg = $res["error"]["message"];
            throw new ErrorResponseException(strval($errorMsg), intval($errorCode));
        }

        return $res["result"] ?? null;
    }
}
