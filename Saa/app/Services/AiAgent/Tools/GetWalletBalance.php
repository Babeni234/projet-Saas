<?php

namespace App\Services\AiAgent\Tools;

class GetWalletBalance extends BaseTool
{
    public function name(): string { return 'get_wallet_balance'; }

    public function description(): string { return 'Get the current wallet/portefeuille balance and recent transactions.'; }

    public function parameters(): array
    {
        return [
            'type' => 'object',
            'properties' => (object)[],
            'required' => [],
        ];
    }

    public function execute(array $params = []): array
    {
        return [
            'success' => true,
            'data' => [
                'balance' => $this->dashboardData['walletBalance'] ?? 0,
                'currency' => 'XAF',
            ],
        ];
    }
}
