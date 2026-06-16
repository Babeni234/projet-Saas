<?php

namespace App\Services\AiAgent\Tools;

class GetProfile extends BaseTool
{
    public function name(): string { return 'get_profile'; }

    public function description(): string { return 'Get the connected tenant profile information: name, email, phone, and account details.'; }

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
        $u = $this->user;

        return [
            'success' => true,
            'data' => [
                'name' => $u->name,
                'first_name' => $u->first_name ?? '',
                'last_name' => $u->last_name ?? '',
                'email' => $u->email,
                'phone' => $u->phone ?? '',
                'role' => $u->role ?? 'tenant',
                'email_verified' => !is_null($u->email_verified_at),
            ],
        ];
    }
}
