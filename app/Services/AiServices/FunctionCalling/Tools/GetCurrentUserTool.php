<?php

namespace App\Services\AiServices\FunctionCalling\Tools;

use App\Services\AiServices\FunctionCalling\ToolExecutionContext;
use App\Services\AiServices\FunctionCalling\ToolResult;

class GetCurrentUserTool extends AbstractTool
{
    public function getName(): string
    {
        return 'get_current_user';
    }

    public function getDescription(): string
    {
        return 'Get information about the currently logged-in user. Use this tool when the user asks about ' .
               'their personal information such as name, email, roles, or any other account details. ' .
               '現在ログイン中のユーザー情報を取得します。ユーザーが自分の名前、メール、役割などの個人情報について質問した場合に使用します。';
    }

    public function getParametersSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'fields' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'string',
                        'enum' => [
                            'all',
                            'id',
                            'name',
                            'email',
                            'roles',
                            'permissions',
                            'created_at',
                        ],
                    ],
                    'description' => 'Specific fields to retrieve. Use "all" to get all available information. ' .
                                   '(取得するフィールド。"all"を指定すると全情報を取得)',
                ],
            ],
            'required' => [],
        ];
    }

    public function execute(array $arguments, ToolExecutionContext $context): ToolResult
    {
        $user = $context->user;

        if (!$user) {
            return $this->error(
                'No user is currently logged in. Unable to retrieve user information. ' .
                'ユーザーがログインしていません。ユーザー情報を取得できません。'
            );
        }

        $requestedFields = $arguments['fields'] ?? ['all'];
        $getAllFields = in_array('all', $requestedFields) || empty($requestedFields);

        try {
            $userInfo = [];

            // Basic information
            if ($getAllFields || in_array('id', $requestedFields)) {
                $userInfo['id'] = $user->id;
            }
            if ($getAllFields || in_array('name', $requestedFields)) {
                $userInfo['first_name'] = $user->first_name;
                $userInfo['last_name'] = $user->last_name;
                $userInfo['full_name'] = $user->full_name;
            }
            if ($getAllFields || in_array('email', $requestedFields)) {
                $userInfo['email'] = $user->email;
            }

            // Roles
            if ($getAllFields || in_array('roles', $requestedFields)) {
                $roles = $user->roles()->pluck('name')->toArray();
                $userInfo['roles'] = !empty($roles) ? $roles : ['No roles assigned'];
            }

            // Permissions
            if ($getAllFields || in_array('permissions', $requestedFields)) {
                $permissions = $user->getAllPermissions()->pluck('name')->toArray();
                $userInfo['permissions'] = !empty($permissions) ? $permissions : ['No permissions assigned'];
            }

            // Timestamps
            if ($getAllFields || in_array('created_at', $requestedFields)) {
                $userInfo['account_created_at'] = $user->created_at?->format('Y/m/d H:i:s');
            }

            // Additional info
            if ($getAllFields) {
                $userInfo['is_system_admin'] = $user->isSystemAdmin();
            }

            // Filter out null values for cleaner output
            $userInfo = array_filter($userInfo, function ($value) {
                if (is_array($value)) {
                    return !empty(array_filter($value, fn($v) => $v !== null));
                }
                return $value !== null;
            });

            return $this->success(
                data: $userInfo,
                message: 'Successfully retrieved user information. ユーザー情報を取得しました。'
            );
        } catch (\Exception $e) {
            return $this->error(
                'Failed to retrieve user information: ' . $e->getMessage() .
                ' ユーザー情報の取得に失敗しました。'
            );
        }
    }

    public function isAvailable(ToolExecutionContext $context): bool
    {
        // Available for agent-type applications
        // Tool will return appropriate error message if user is not logged in
        return $context->application->type === 'agent';
    }
}
