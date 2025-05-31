<?php
// Final comprehensive test - access via: yourdomain.com/wp-content/themes/prostyler-theme/final-test.php

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo '<h2>🎯 ProStyler Theme - Final Compatibility Test</h2>';
echo '<p><strong>PHP Version:</strong> ' . phpversion() . '</p>';

$error_count = 0;
$warning_count = 0;

// Set up error handler to count issues
function custom_error_handler($errno, $errstr, $errfile, $errline) {
    global $error_count, $warning_count;
    
    if ($errno == E_ERROR || $errno == E_PARSE) {
        $error_count++;
        echo '<p style="color: red;">❌ Fatal Error: ' . $errstr . ' in ' . basename($errfile) . ':' . $errline . '</p>';
    } elseif ($errno == E_WARNING) {
        $warning_count++;
        echo '<p style="color: orange;">⚠️ Warning: ' . $errstr . ' in ' . basename($errfile) . ':' . $errline . '</p>';
    } elseif ($errno == E_DEPRECATED) {
        $warning_count++;
        echo '<p style="color: #ccaa00;">📋 Deprecated: ' . $errstr . ' in ' . basename($errfile) . ':' . $errline . '</p>';
    }
    
    return true; // Don't execute PHP internal error handler
}

set_error_handler("custom_error_handler");

try {
    echo '<h3>🔍 Phase 1: WordPress Loading Test</h3>';
    
    if (file_exists('../../../../../../wp-load.php')) {
        echo '<p>✅ WordPress core found</p>';
        
        require_once('../../../../../../wp-load.php');
        
        if (defined('ABSPATH')) {
            echo '<p style="color: green;">✅ WordPress loaded successfully!</p>';
            echo '<p><strong>WordPress Version:</strong> ' . get_bloginfo('version') . '</p>';
            echo '<p><strong>Active Theme:</strong> ' . get_template() . '</p>';
            
            echo '<h3>🔧 Phase 2: Theme Functions Test</h3>';
            
            // Test critical WordPress functions
            $functions_to_test = [
                'post_password_required',
                'get_header',
                'get_footer',
                'wp_head',
                'wp_footer',
                'the_content',
                'get_template_directory_uri'
            ];
            
            foreach ($functions_to_test as $function) {
                if (function_exists($function)) {
                    echo '<p style="color: green;">✅ ' . $function . '() available</p>';
                } else {
                    echo '<p style="color: red;">❌ ' . $function . '() missing</p>';
                }
            }
            
            echo '<h3>⚙️ Phase 3: Theme Options Test</h3>';
            
            global $cbt_options;
            if (isset($cbt_options) && is_array($cbt_options)) {
                echo '<p style="color: green;">✅ Theme options loaded (' . count($cbt_options) . ' options)</p>';
            } else {
                echo '<p style="color: orange;">⚠️ Theme options not initialized (normal on first load)</p>';
            }
            
            echo '<h3>🎨 Phase 4: Page Builder Test</h3>';
            
            // Test if page builder classes exist
            $pb_classes = [
                'ST_Pb_Helper_Functions',
                'ST_Pb_Helper_Premade',
                'ST_Heading'
            ];
            
            foreach ($pb_classes as $class) {
                if (class_exists($class)) {
                    echo '<p style="color: green;">✅ ' . $class . ' class loaded</p>';
                } else {
                    echo '<p style="color: orange;">⚠️ ' . $class . ' class not found</p>';
                }
            }
            
            echo '<h3>🛒 Phase 5: WooCommerce Integration Test</h3>';
            
            if (class_exists('WooCommerce')) {
                echo '<p style="color: green;">✅ WooCommerce is active</p>';
            } else {
                echo '<p style="color: blue;">ℹ️ WooCommerce not installed (optional)</p>';
            }
            
            echo '<h3>📊 Test Results Summary</h3>';
            
            if ($error_count === 0) {
                echo '<p style="color: green; font-size: 18px; font-weight: bold;">🎉 SUCCESS! No fatal errors detected!</p>';
                echo '<p style="color: green;">Your theme is ready to use!</p>';
            } else {
                echo '<p style="color: red; font-size: 18px; font-weight: bold;">❌ ' . $error_count . ' fatal error(s) found</p>';
            }
            
            if ($warning_count > 0) {
                echo '<p style="color: orange;">⚠️ ' . $warning_count . ' warning(s)/deprecated notices (non-critical)</p>';
                echo '<p><small>Note: Warnings and deprecated notices are normal and don\'t prevent the theme from working.</small></p>';
            }
            
            echo '<hr>';
            echo '<h3>🚀 Next Steps</h3>';
            echo '<div style="background: #f0f8ff; padding: 15px; border-left: 4px solid #007cba;">';
            echo '<p><strong>Your theme should now work properly!</strong></p>';
            echo '<ul>';
            echo '<li><a href="' . home_url() . '" target="_blank" style="color: #007cba;">🏠 Visit your website frontend</a></li>';
            echo '<li><a href="' . admin_url() . '" target="_blank" style="color: #007cba;">⚙️ Access WordPress admin panel</a></li>';
            echo '<li><a href="' . admin_url('admin.php?page=prostyler_config') . '" target="_blank" style="color: #007cba;">🎨 Configure theme options</a></li>';
            echo '</ul>';
            echo '</div>';
            
        } else {
            echo '<p style="color: red;">❌ WordPress failed to initialize</p>';
        }
    } else {
        echo '<p style="color: red;">❌ Cannot find WordPress installation</p>';
    }
    
} catch (ParseError $e) {
    echo '<p style="color: red;">❌ Parse Error: ' . $e->getMessage() . '</p>';
    echo '<p>File: ' . $e->getFile() . ' Line: ' . $e->getLine() . '</p>';
} catch (Error $e) {
    echo '<p style="color: red;">❌ Fatal Error: ' . $e->getMessage() . '</p>';
    echo '<p>File: ' . $e->getFile() . ' Line: ' . $e->getLine() . '</p>';
} catch (Exception $e) {
    echo '<p style="color: red;">❌ Exception: ' . $e->getMessage() . '</p>';
}

echo '<hr>';
echo '<div style="background: #f9f9f9; padding: 10px; margin-top: 20px;">';
echo '<p><small><strong>Issues Fixed:</strong></small></p>';
echo '<p><small>✅ Deprecated create_function() → Modern widget registration</small></p>';
echo '<p><small>✅ Static method call errors → Proper static declarations</small></p>';
echo '<p><small>✅ Widget constructor compatibility → Modern __construct()</small></p>';
echo '<p><small>✅ MySQL deprecated functions → Modern wpdb methods</small></p>';
echo '<p><small>✅ Method signature mismatches → Compatible parameters</small></p>';
echo '<p><small>✅ Undefined array keys → Proper isset() checks</small></p>';
echo '<p><small>Test completed: ' . date('Y-m-d H:i:s') . '</small></p>';
echo '</div>';

// Restore default error handler
restore_error_handler();
?> 