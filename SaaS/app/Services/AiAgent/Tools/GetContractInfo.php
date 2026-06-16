<?php

namespace App\Services\AiAgent\Tools;

class GetContractInfo extends BaseTool
{
    public function name(): string { return 'get_contract_info'; }

    public function description(): string { return 'Get the current lease/contract details: property info, rent amount, deposit, charges, dates, and owner/manager contact.'; }

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
        $c = $this->dashboardData['contract'] ?? [];

        return [
            'success' => true,
            'data' => [
                'property_name' => $c['property']['name'] ?? 'N/A',
                'property_type' => $c['property']['type'] ?? 'N/A',
                'address' => $c['property']['address'] ?? $this->dashboardData['address'] ?? 'N/A',
                'city' => $c['property']['city'] ?? 'N/A',
                'rent' => $c['rent'] ?? 0,
                'deposit' => $c['deposit'] ?? 0,
                'charges' => $c['charges'] ?? 0,
                'start_date' => $c['start_date'] ?? 'N/A',
                'end_date' => $c['end_date'] ?? 'N/A',
                'status' => $c['status'] ?? 'active',
                'type' => $c['type'] ?? 'standard',
                'owner_name' => $c['property']['owner_name'] ?? $c['owner'] ?? 'N/A',
                'documents_count' => count($c['documents'] ?? []),
            ],
        ];
    }
}
