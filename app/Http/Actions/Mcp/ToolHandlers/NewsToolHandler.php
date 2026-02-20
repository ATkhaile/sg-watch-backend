<?php

namespace App\Http\Actions\Mcp\ToolHandlers;

use App\Domain\News\Entity\CreateNewsRequestEntity;
use App\Domain\News\UseCase\CreateNewsUseCase;
use App\Http\Actions\Mcp\Helpers\McpAuthHelper;

class NewsToolHandler
{
    public function handle(string $action, array $arguments): mixed
    {
        switch ($action) {
            case 'create':
                return $this->create($arguments);
            
            // case 'update':
            //     return $this->update($arguments);
            // case 'delete':
            //     return $this->delete($arguments);
            
            default:
                throw new \Exception("Unknown action: {$action} for news domain");
        }
    }

    private function create(array $arguments): array
    {
        if (!isset($arguments['title']) || !isset($arguments['content'])) {
            throw new \Exception("Missing required fields: title and content");
        }

        $categoryId = $arguments['category_id'] ?? null;
        if ($categoryId === null) {
            $categoryId = $this->selectBestCategory($arguments['title'], $arguments['content']);
        }

        $entity = new CreateNewsRequestEntity(
            title: $arguments['title'],
            content: $arguments['content'],
            short_description: $arguments['short_description'] ?? null,
            category_id: $categoryId,
            tag_ids: $arguments['tag_ids'] ?? null,
            is_important: $arguments['is_important'] ?? false,
            is_new: $arguments['is_new'] ?? false,
            status: $arguments['status'] ?? 0,
            created_at: $arguments['created_at'] ?? null,
            published_at: $arguments['published_at'] ?? null,
            send_notification: $arguments['send_notification'] ?? false,
            thumbnail: $arguments['thumbnail'] ?? null
        );

        $result = McpAuthHelper::executeAsMcpUser(function () use ($entity) {
            $useCase = app(CreateNewsUseCase::class);
            return $useCase->__invoke($entity);
        });

        return [
            'message' => $result->getMessage(),
            'status_code' => $result->getStatus(),
        ];
    }

    /**
     * Automatically select the best category based on title and content
     * Uses simple text matching algorithm
     */
    private function selectBestCategory(string $title, string $content): ?int
    {
        try {
            // Get all categories using CategoryToolHandler
            $categoryHandler = new \App\Http\Actions\Mcp\ToolHandlers\CategoryToolHandler();
            $categoryResult = $categoryHandler->handle('list', []);
            
            if (empty($categoryResult['categories'])) {
                return null;
            }

            $categories = $categoryResult['categories'];
            $bestMatch = null;
            $highestScore = 0;

            // Combine title and content for matching (title weighted more)
            $titleLower = mb_strtolower($title);
            $contentLower = mb_strtolower(substr($content, 0, 500)); // Only use first 500 chars for performance
            
            foreach ($categories as $category) {
                $categoryNameLower = mb_strtolower($category['name']);
                $categoryDescLower = mb_strtolower($category['description'] ?? '');
                
                $score = 0;
                
                if (str_contains($titleLower, $categoryNameLower)) {
                    $score += 10;
                }
                
                if (str_contains($contentLower, $categoryNameLower)) {
                    $score += 5;
                }
                
                if (!empty($categoryDescLower)) {
                    $descWords = explode(' ', $categoryDescLower);
                    foreach ($descWords as $word) {
                        if (strlen($word) > 4) { // Only check meaningful words
                            if (str_contains($titleLower, $word)) {
                                $score += 2;
                            }
                            if (str_contains($contentLower, $word)) {
                                $score += 1;
                            }
                        }
                    }
                }
                
                if ($score > $highestScore) {
                    $highestScore = $score;
                    $bestMatch = $category['id'];
                }
            }
            
            return $bestMatch;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Failed to auto-select category', [
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }
}

