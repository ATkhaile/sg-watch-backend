<?php

namespace App\Http\Actions\Mcp\ToolHandlers;

use App\Domain\Category\Entity\CategoryEntity;
use App\Domain\Category\Entity\CreateCategoryRequestEntity;
use App\Domain\Category\UseCase\CreateCategoryUseCase;
use App\Domain\Category\UseCase\GetAllCategoryUseCase;
use App\Http\Actions\Mcp\Helpers\McpAuthHelper;

class CategoryToolHandler
{
    public function handle(string $action, array $arguments): mixed
    {
        switch ($action) {
            case 'create':
                return $this->create($arguments);
            case 'list':
                return $this->list($arguments);
            default:
                throw new \Exception("Unknown action: {$action} for category domain");
        }
    }

    private function create(array $arguments): array
    {
        if (!isset($arguments['name'])) {
            throw new \Exception("Missing required fields: title and content");
        }

        $entity = new CreateCategoryRequestEntity(
            name: $arguments['name'],
            description: $arguments['description'] ?? null
        );

        $result = McpAuthHelper::executeAsMcpUser(function () use ($entity) {
            $useCase = app(CreateCategoryUseCase::class);
            return $useCase->__invoke($entity);
        });

        return [
            'message' => $result->getMessage(),
            'status_code' => $result->getStatus(),
        ];
    }

    private function list(): array
    {
        $entity = new CategoryEntity(
            page: 1,
            limit: 1000
        );

        $result = McpAuthHelper::executeAsMcpUser(function () use ($entity) {
            $useCase = app(GetAllCategoryUseCase::class);
            return $useCase->__invoke($entity);
        });

        return [
            'categories' => $result->getCategories(),
            'status_code' => $result->getStatus(),
        ];
    }
}
