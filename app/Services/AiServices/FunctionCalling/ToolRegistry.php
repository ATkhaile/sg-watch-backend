<?php

namespace App\Services\AiServices\FunctionCalling;

use App\Models\AiTool;
use App\Services\AiServices\FunctionCalling\Tools\AbstractTool;
use Illuminate\Support\Collection;

class ToolRegistry
{
    /** @var Collection<string, AbstractTool> */
    private Collection $tools;

    /** @var array<string, bool>|null Cache of active tool codes */
    private ?array $activeToolsCache = null;

    public function __construct()
    {
        $this->tools = collect();
    }

    /**
     * Get list of active tool codes from database
     *
     * @return array<string, bool> Map of tool code => is_active
     */
    private function getActiveToolCodes(): array
    {
        if ($this->activeToolsCache === null) {
            $this->activeToolsCache = AiTool::where('is_active', true)
                ->pluck('code')
                ->flip()
                ->map(fn () => true)
                ->toArray();
        }

        return $this->activeToolsCache;
    }

    /**
     * Check if a tool is active in database
     *
     * @param string $toolCode The tool code to check
     * @return bool
     */
    private function isToolActive(string $toolCode): bool
    {
        $activeTools = $this->getActiveToolCodes();
        return isset($activeTools[$toolCode]);
    }

    /**
     * Register a tool
     */
    public function register(AbstractTool $tool): self
    {
        $this->tools->put($tool->getName(), $tool);
        return $this;
    }

    /**
     * Get a tool by name
     */
    public function get(string $name): ?AbstractTool
    {
        return $this->tools->get($name);
    }

    /**
     * Get all registered tools
     */
    public function all(): Collection
    {
        return $this->tools;
    }

    /**
     * Get tools available for a specific context
     */
    public function getAvailable(ToolExecutionContext $context): Collection
    {
        // Get allowed tools from application configuration
        $allowedTools = $context->application->tools ?? [];

        return $this->tools->filter(function (AbstractTool $tool) use ($context, $allowedTools) {
            // First check if the tool is available based on its own logic
            if (!$tool->isAvailable($context)) {
                return false;
            }

            // If application has no tools configured, no tools are allowed
            if (empty($allowedTools)) {
                return false;
            }

            // Only allow tools that are in the application's configured tools list
            if (!in_array($tool->getName(), $allowedTools)) {
                return false;
            }

            // Check if tool is active in database
            if (!$this->isToolActive($tool->getName())) {
                return false;
            }

            return true;
        });
    }

    /**
     * Check if a tool exists
     */
    public function has(string $name): bool
    {
        return $this->tools->has($name);
    }

    /**
     * Get count of registered tools
     */
    public function count(): int
    {
        return $this->tools->count();
    }
}
