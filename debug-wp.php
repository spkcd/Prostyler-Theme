<?php
// WordPress Debug Mode - place this file in your theme root and access it via browser
// URL: yourdomain.com/wp-content/themes/prostyler-theme/debug-wp.php

// Enable full error reporting (override theme settings)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

// Load WordPress with full debugging
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', true);

echo '<h2>WordPress Theme Debug Test</h2>';
echo '<p>PHP Version: ' . phpversion() . '</p>';
echo '<p>Attempting to load WordPress...</p>';

try {
    // Try to load WordPress
    require_once('../../../../../../wp-load.php');

    if (defined('ABSPATH')) {
        echo '<p style="color: green;">✅ WordPress loaded successfully!</p>';
        echo '<p>WordPress Version: ' . get_bloginfo('version') . '</p>';
        echo '<p>Active Theme: ' . get_template() . '</p>';
        
        // Test theme functions
        echo '<h3>Theme Functions Test:</h3>';
        if (function_exists('post_password_required')) {
            echo '<p style="color: green;">✅ post_password_required() function exists</p>';
        } else {
            echo '<p style="color: red;">❌ post_password_required() function missing</p>';
        }
        
        if (function_exists('get_header')) {
            echo '<p style="color: green;">✅ get_header() function exists</p>';
        } else {
            echo '<p style="color: red;">❌ get_header() function missing</p>';
        }
        
        // Check for common issues
        echo '<h3>Plugin Status:</h3>';
        if (function_exists('get_plugins')) {
            $plugins = get_plugins();
            $active_plugins = get_option('active_plugins');
            echo '<p>Total Plugins: ' . count($plugins) . '</p>';
            echo '<p>Active Plugins: ' . count($active_plugins) . '</p>';
            
            // List active plugins
            if (!empty($active_plugins)) {
                echo '<h4>Active Plugins:</h4><ul>';
                foreach ($active_plugins as $plugin) {
                    echo '<li>' . $plugin . '</li>';
                }
                echo '</ul>';
            }
        }
        
        // Check memory and PHP settings
        echo '<h3>System Info:</h3>';
        echo '<p>Memory Limit: ' . ini_get('memory_limit') . '</p>';
        echo '<p>Max Execution Time: ' . ini_get('max_execution_time') . '</p>';
        echo '<p>WP Memory Limit: ' . WP_MEMORY_LIMIT . '</p>';
        
        // Check error logs
        echo '<h3>Error Log Check:</h3>';
        $error_log_paths = array(
            ABSPATH . 'error_log',
            ABSPATH . 'wp-content/debug.log',
            get_template_directory() . '/error_log'
        );
        
        foreach ($error_log_paths as $path) {
            if (file_exists($path)) {
                echo '<p style="color: orange;">Found error log: ' . $path . '</p>';
                $recent_errors = file_get_contents($path);
                if ($recent_errors) {
                    echo '<h4>Recent Errors from ' . basename($path) . ':</h4>';
                    echo '<pre style="background: #f0f0f0; padding: 10px; overflow: auto; max-height: 300px;">';
                    echo htmlspecialchars(substr($recent_errors, -2000)); // Last 2000 characters
                    echo '</pre>';
                }
            }
        }
        
    } else {
        echo '<p style="color: red;">❌ Failed to load WordPress - ABSPATH not defined</p>';
    }
    
} catch (Exception $e) {
    echo '<p style="color: red;">❌ Exception occurred: ' . $e->getMessage() . '</p>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
} catch (ParseError $e) {
    echo '<p style="color: red;">❌ Parse Error: ' . $e->getMessage() . '</p>';
    echo '<p>File: ' . $e->getFile() . ' Line: ' . $e->getLine() . '</p>';
} catch (Error $e) {
    echo '<p style="color: red;">❌ Fatal Error: ' . $e->getMessage() . '</p>';
    echo '<p>File: ' . $e->getFile() . ' Line: ' . $e->getLine() . '</p>';
}

echo '<p>Debug test completed at: ' . date('Y-m-d H:i:s') . '</p>';
?> 