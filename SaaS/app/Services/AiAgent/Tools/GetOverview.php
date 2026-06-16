<?php

namespace App\Services\AiAgent\Tools;

class GetOverview extends BaseTool
{
    public function name(): string { return 'get_overview'; }

    public function description(): string { return 'Get the tenant dashboard overview: wallet balance, contract rent, property name, address, penalty status, unpaid months, and next payment date.'; }

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
        $contract = $this->dashboardData['contract'] ?? [];
        $penalty = $this->dashboardData['penaltyInfo'] ?? [];
        $invoices = $this->dashboardData['invoices'] ?? [];
        $pending = array_values(array_filter($invoices, fn($i) => ($i['status'] ?? '') === 'pending'));

        return [
            'success' => true,
            'data' => [
                'wallet_balance' => $this->dashboardData['walletBalance'] ?? 0,
                'property_name' => $contract['property']['name'] ?? 'N/A',
                'address' => $contract['property']['address'] ?? $this->dashboardData['address'] ?? 'N/A',
                'monthly_rent' => $contract['rent'] ?? 0,
                'contract_status' => $contract['status'] ?? 'active',
                'unpaid_months' => $penalty['unpaidCount'] ?? 0,
                'is_overdue' => $penalty['isOverdue'] ?? false,
                'total_due' => $penalty['totalDue'] ?? 0,
                'penalty_label' => $penalty['currentLabel'] ?? 'Aucune',
                'pending_invoices' => count($pending),
                'next_payment_date' => $pending[0]['due_date'] ?? 'N/A',
            ],
        ];
    }
}
