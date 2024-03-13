<?php

namespace Przelewy24;

use Przelewy24\Api\Requests\PaymentRequests;
use Przelewy24\Api\Requests\TestRequests;
use Przelewy24\Api\Requests\TransactionRequests;

class Przelewy24
{
    private Config $config;

    public function __construct(
        int $merchantId,
        string $reportsKey,
        string $crc,
        bool $isLive = false,
        ?string $posId = null,
    ) {
        $this->config = new Config($merchantId, $reportsKey, $crc, $isLive, $posId);
    }

    public static function createSignature(array $parameters): string
    {
        $json = json_encode($parameters, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return hash('sha384', $json);
    }

    public function tests(): TestRequests
    {
        return new TestRequests($this->config);
    }

    public function payments(): PaymentRequests
    {
        return new PaymentRequests($this->config);
    }

    public function transactions(): TransactionRequests
    {
        return new TransactionRequests($this->config);
    }

    public function handleWebhook(array $requestData): TransactionStatusNotification
    {
        return new TransactionStatusNotification($this->config, $requestData);
    }
}
