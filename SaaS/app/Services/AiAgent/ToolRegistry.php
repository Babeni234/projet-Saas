<?php

namespace App\Services\AiAgent;

use App\Models\User;
use App\Services\AiAgent\Tools\BaseTool;
use App\Services\AiAgent\Tools\GetOverview;
use App\Services\AiAgent\Tools\GetRentStatus;
use App\Services\AiAgent\Tools\GetContractInfo;
use App\Services\AiAgent\Tools\GetWalletBalance;
use App\Services\AiAgent\Tools\GetInvoices;
use App\Services\AiAgent\Tools\GetPenaltyInfo;
use App\Services\AiAgent\Tools\GetUtilities;
use App\Services\AiAgent\Tools\GetProfile;
use App\Services\AiAgent\Tools\ExecuteAction;

class ToolRegistry
{
    private array $tools = [];

    public function __construct(User $user, array $dashboardData = [])
    {
        $this->register($user, $dashboardData);
    }

    private function register(User $user, array $dashboardData): void
    {
        $classes = [
            GetOverview::class,
            GetRentStatus::class,
            GetContractInfo::class,
            GetWalletBalance::class,
            GetInvoices::class,
            GetPenaltyInfo::class,
            GetUtilities::class,
            GetProfile::class,
            ExecuteAction::class,
        ];

        foreach ($classes as $class) {
            $tool = new $class($user, $dashboardData);
            $this->tools[$tool->name()] = $tool;
        }
    }

    public function all(): array
    {
        return $this->tools;
    }

    public function get(string $name): ?BaseTool
    {
        return $this->tools[$name] ?? null;
    }

    public function execute(string $name, array $params = []): array
    {
        $tool = $this->get($name);
        if (!$tool) {
            return ['success' => false, 'error' => "Tool '{$name}' not found."];
        }
        try {
            return $tool->execute($params);
        } catch (\Throwable $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function toOpenAiFunctions(): array
    {
        return array_map(fn(BaseTool $t) => $t->toOpenAiFunction(), $this->tools);
    }
}
