<?php
if (function_exists('opcache_reset')) {
    opcache_reset();
    echo json_encode(['success' => true, 'message' => 'OPCache reset successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'OPCache not available']);
}
