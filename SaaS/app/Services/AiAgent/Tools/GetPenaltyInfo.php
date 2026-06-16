<?php

namespace App\Services\AiAgent\Tools;

class GetPenaltyInfo extends BaseTool
{
    public function name(): string { return 'get_penalty_info'; }

    public function description(): string { return 'Get detailed penalty information: current penalty rate, total penalties applied, unpaid months, and penalty breakdown per month.'; }

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
        $p = $this->dashboardData['penaltyInfo'] ?? [];

        return [
            'success' => true,
            'data' => [
                'is_overdue' => $p['isOverdue'] ?? false,
                'unpaid_count' => $p['unpaidCount'] ?? 0,
                'current_rate_label' => $p['currentLabel'] ?? '0%',
                'total_penalties' => $p['totalPenalties'] ?? 0,
                'total_due' => $p['totalDue'] ?? 0,
                'penalty_scale' => [
                    ['from_day' => 1, 'to_day' => 10, 'rate' => '0% (dans les délais)'],
                    ['from_day' => 11, 'to_day' => 15, 'rate' => '5%'],
                    ['from_day' => 16, 'to_day' => 20, 'rate' => '10%'],
                    ['from_day' => 21, 'rate' => '15%'],
                ],
                'details' => $p['details'] ?? [],
            ],
        ];
    }
}
