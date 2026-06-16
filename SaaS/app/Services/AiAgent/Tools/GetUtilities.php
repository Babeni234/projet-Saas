<?php

namespace App\Services\AiAgent\Tools;

class GetUtilities extends BaseTool
{
    public function name(): string { return 'get_utilities'; }

    public function description(): string { return 'Get water and electricity consumption data, latest readings, costs, and payment status.'; }

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
        $data = [];
        if (isset($this->dashboardData['latestWaterConso'])) {
            $data['water'] = [
                'consumption' => $this->dashboardData['latestWaterConso'],
                'unit' => $this->dashboardData['latestWaterUnit'] ?? 'm³',
                'cost' => $this->dashboardData['latestWaterCost'] ?? 0,
                'period' => $this->dashboardData['latestWaterPeriod'] ?? '',
                'status' => $this->dashboardData['waterStatus'] ?? 'unknown',
            ];
        }
        if (isset($this->dashboardData['latestElecConso'])) {
            $data['electricity'] = [
                'consumption' => $this->dashboardData['latestElecConso'],
                'unit' => $this->dashboardData['latestElecUnit'] ?? 'kWh',
                'cost' => $this->dashboardData['latestElecCost'] ?? 0,
                'period' => $this->dashboardData['latestElecPeriod'] ?? '',
                'status' => $this->dashboardData['elecStatus'] ?? 'unknown',
            ];
        }

        return [
            'success' => true,
            'data' => $data,
        ];
    }
}
