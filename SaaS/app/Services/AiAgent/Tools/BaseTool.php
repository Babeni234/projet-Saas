<?php

namespace App\Services\AiAgent\Tools;

use App\Models\User;

abstract class BaseTool
{
    protected User $user;
    protected array $dashboardData;

    public function __construct(User $user, array $dashboardData = [])
    {
        $this->user = $user;
        $this->dashboardData = $dashboardData;
    }

    abstract public function name(): string;

    abstract public function description(): string;

    abstract public function parameters(): array;

    abstract public function execute(array $params = []): array;

    public function toOpenAiFunction(): array
    {
        return [
            'type' => 'function',
            'function' => [
                'name' => $this->name(),
                'description' => $this->description(),
                'parameters' => $this->parameters(),
            ],
        ];
    }
}
