<?php

/**
 * Automatically load all tool schemas from the ToolSchema directory
 * Each file in ToolSchema should return an array of tool definitions
 */

$tools = [];
$schemaPath = __DIR__ . '/ToolSchema';

// Check if directory exists
if (is_dir($schemaPath)) {
    // Get all PHP files in the ToolSchema directory
    $files = glob($schemaPath . '/*.php');
    
    foreach ($files as $file) {
        // Include the file and get the returned array
        $toolSchema = require $file;
        
        // Merge if it's an array
        if (is_array($toolSchema)) {
            $tools = array_merge($tools, $toolSchema);
        }
    }
}

return $tools;