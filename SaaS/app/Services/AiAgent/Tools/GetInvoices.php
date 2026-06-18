<?php

namespace App\Services\AiAgent\Tools;

class GetInvoices extends BaseTool
{
    public function name(): string { return 'get_invoices'; }

    public function description(): string { return 'Get all invoices and bills (rent, water, electricity) with their status (pending, paid, overdue).'; }

    public function parameters(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'type' => [
                    'type' => 'string',
                    'enum' => ['all', 'rent', 'utility', 'water', 'electric'],
                    'description' => 'Filter by invoice type',
                ],
            ],
            'required' => [],
        ];
    }

    public function execute(array $params = []): array
    {
        $type = $params['type'] ?? 'all';
        $invoices = $this->dashboardData['invoices'] ?? [];

        if ($type === 'rent') {
            $invoices = array_values(array_filter($invoices, fn($i) => ($i['type'] ?? '') === 'RENT'));
        } elseif ($type === 'utility' || $type === 'water' || $type === 'electric') {
            $types = $type === 'utility' ? ['WATER', 'ELECTRIC'] : [strtoupper($type)];
            $invoices = array_values(array_filter($invoices, fn($i) => in_array($i['type'] ?? '', $types)));
        }

        return [
            'success' => true,
            'data' => array_map(fn($i) => [
                'type' => $i['type'] ?? '',
                'period' => $i['period'] ?? '',
                'amount' => $i['amount'] ?? 0,
                'status' => $i['status'] ?? 'unknown',
                'due_date' => $i['due_date'] ?? '',
            ], $invoices),
        ];
    }
}
