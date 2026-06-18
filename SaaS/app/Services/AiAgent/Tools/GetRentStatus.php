<?php

namespace App\Services\AiAgent\Tools;

class GetRentStatus extends BaseTool
{
    public function name(): string { return 'get_rent_status'; }

    public function description(): string { return 'Get detailed rent payment status including pending payments, paid history, overdue months, and penalty details.'; }

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
        $invoices = $this->dashboardData['invoices'] ?? [];
        $penalty = $this->dashboardData['penaltyInfo'] ?? [];
        $pending = array_values(array_filter($invoices, fn($i) => ($i['status'] ?? '') === 'pending' && ($i['type'] ?? '') === 'RENT'));
        $paid = array_values(array_filter($invoices, fn($i) => ($i['status'] ?? '') === 'paid' && ($i['type'] ?? '') === 'RENT'));

        return [
            'success' => true,
            'data' => [
                'pending_count' => count($pending),
                'pending_invoices' => array_map(fn($i) => [
                    'period' => $i['period'] ?? '',
                    'amount' => $i['amount'] ?? 0,
                    'due_date' => $i['due_date'] ?? '',
                ], $pending),
                'paid_count' => count($paid),
                'last_paid' => count($paid) > 0 ? [
                    'period' => $paid[count($paid)-1]['period'] ?? '',
                    'amount' => $paid[count($paid)-1]['amount'] ?? 0,
                ] : null,
                'is_overdue' => $penalty['isOverdue'] ?? false,
                'unpaid_months' => $penalty['unpaidCount'] ?? 0,
                'total_due' => $penalty['totalDue'] ?? 0,
                'total_penalties' => $penalty['totalPenalties'] ?? 0,
                'penalty_rate' => $penalty['currentLabel'] ?? '0%',
                'penalty_details' => $penalty['details'] ?? [],
            ],
        ];
    }
}
