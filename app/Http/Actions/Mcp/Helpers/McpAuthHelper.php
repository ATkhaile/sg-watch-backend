<?php

namespace App\Http\Actions\Mcp\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class McpAuthHelper
{
    /**
     * Cached admin user instance
     */
    private static ?User $mcpUser = null;


    private static function getMcpUser(): User
    {
        if (self::$mcpUser !== null) {
            return self::$mcpUser;
        }

        $user = User::where('email', 'admin@gmail.com')->first();

        if (!$user) {
            throw new \Exception('MCP admin user (admin@gmail.com) not found in database. Please create this user first.');
        }

        self::$mcpUser = $user;
        return $user;
    }


    public static function executeAsMcpUser(callable $callback): mixed
    {
        // Get the admin user from database
        $adminUser = self::getMcpUser();

        $previousUser = Auth::guard('api')->user();

        Auth::guard('api')->setUser($adminUser);

        try {
            $result = $callback();

            return $result;
        } finally {
            if ($previousUser) Auth::guard('api')->setUser($previousUser);
        }
    }

    public static function executeAsCurrentUser(callable $callback): mixed
    {

        $previousUser = Auth::guard('api')->user();
        Auth::guard('api')->setUser($previousUser);

        try {
            $result = $callback();

            return $result;
        } finally {
            if ($previousUser) Auth::guard('api')->setUser($previousUser);
        }
    }



    /**
     * Get the current MCP admin user if it exists
     * 
     * @return User|null
     */
    public static function getMcpAdminUser(): ?User
    {
        try {
            return self::getMcpUser();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Check if we're currently in MCP admin context
     * 
     * @return bool
     */
    public static function isMcpContext(): bool
    {
        $currentUser = Auth::guard('api')->user();

        if (!$currentUser) {
            return false;
        }

        try {
            $adminUser = self::getMcpUser();
            return $currentUser->id === $adminUser->id;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Clear the cached admin user (useful for testing)
     * 
     * @return void
     */
    public static function clearCache(): void
    {
        self::$mcpUser = null;
    }
}
