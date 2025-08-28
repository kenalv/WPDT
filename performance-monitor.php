<?php
/**
 * WordPress Performance Monitor Plugin
 * Must-Use Plugin for performance monitoring and optimization
 */

// Only run if WordPress is loaded
if (!defined('ABSPATH')) {
    exit;
}

// Performance monitoring class
class WPDT_Performance_Monitor {
    
    public function __init() {
        add_action('init', [$this, 'optimize_autoload_options'], 1);
        add_action('rest_api_init', [$this, 'optimize_rest_api_performance']);
        add_action('init', [$this, 'cache_frequent_options']);
        add_action('shutdown', [$this, 'log_slow_queries']);
        
        // Clean up expired transients weekly
        if (!wp_next_scheduled('wpdt_cleanup_transients')) {
            wp_schedule_event(time(), 'weekly', 'wpdt_cleanup_transients');
        }
        add_action('wpdt_cleanup_transients', [$this, 'cleanup_expired_transients']);
    }
    
    /**
     * Optimize autoload options to improve database performance
     */
    public function optimize_autoload_options() {
        // Only run once per day to avoid repeated execution
        $last_optimized = get_option('_wpdt_autoload_optimized', 0);
        if ((time() - $last_optimized) < DAY_IN_SECONDS) {
            return;
        }
        
        global $wpdb;
        
        // Disable autoload for large options that don't need to be loaded on every request
        $wpdb->query("UPDATE {$wpdb->options} SET autoload = 'no' WHERE option_name = 'rewrite_rules' AND autoload != 'no'");
        $wpdb->query("UPDATE {$wpdb->options} SET autoload = 'no' WHERE option_name LIKE '_transient_%' AND LENGTH(option_value) > 1000 AND autoload != 'no'");
        
        // Clean up expired transients
        $this->cleanup_expired_transients();
        
        // Mark as optimized
        update_option('_wpdt_autoload_optimized', time(), 'no');
    }
    
    /**
     * Optimize REST API performance
     */
    public function optimize_rest_api_performance() {
        // Disable unnecessary features for REST API requests
        remove_action('wp_head', 'wp_generator');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wp_shortlink_wp_head');
        
        // Prevent loading of unnecessary scripts/styles for REST requests
        if (defined('REST_REQUEST') && REST_REQUEST) {
            remove_action('wp_print_styles', 'print_emoji_styles');
            remove_action('wp_head', 'print_emoji_detection_script', 7);
            remove_action('wp_print_scripts', 'print_emoji_detection_script');
        }
    }
    
    /**
     * Cache frequently accessed options
     */
    public function cache_frequent_options() {
        static $cached = false;
        if ($cached) return;
        
        // Pre-load and cache some frequently used options
        wp_cache_add('template', get_option('template'), 'options');
        wp_cache_add('stylesheet', get_option('stylesheet'), 'options');
        
        $cached = true;
    }
    
    /**
     * Log slow queries if debugging is enabled
     */
    public function log_slow_queries() {
        if (!defined('SAVEQUERIES') || !SAVEQUERIES || !defined('WP_DEBUG_LOG') || !WP_DEBUG_LOG) {
            return;
        }
        
        global $wpdb;
        
        if (empty($wpdb->queries)) {
            return;
        }
        
        $slow_queries = [];
        $total_time = 0;
        
        foreach ($wpdb->queries as $query) {
            $query_time = floatval($query[1]);
            $total_time += $query_time;
            
            if ($query_time > 0.1) { // Log queries slower than 100ms
                $slow_queries[] = [
                    'time' => $query_time,
                    'query' => $query[0]
                ];
            }
        }
        
        if (!empty($slow_queries)) {
            $log_message = "=== SLOW QUERIES DETECTED ===\n";
            $log_message .= "Total queries: " . count($wpdb->queries) . "\n";
            $log_message .= "Total time: " . $total_time . "s\n";
            $log_message .= "Slow queries (>0.1s): " . count($slow_queries) . "\n";
            
            foreach ($slow_queries as $i => $slow_query) {
                $log_message .= "Slow Query #" . ($i + 1) . " (" . $slow_query['time'] . "s): " . $slow_query['query'] . "\n";
            }
            
            $log_message .= "=== END SLOW QUERIES ===";
            
            error_log($log_message);
        }
    }
    
    /**
     * Clean up expired transients
     */
    public function cleanup_expired_transients() {
        global $wpdb;
        
        // Delete expired transient timeouts
        $expired_timeouts = $wpdb->query("
            DELETE FROM {$wpdb->options} 
            WHERE option_name LIKE '_transient_timeout_%' 
            AND option_value < UNIX_TIMESTAMP()
        ");
        
        // Delete transients without timeouts (orphaned)
        $orphaned_transients = $wpdb->query("
            DELETE FROM {$wpdb->options} 
            WHERE option_name LIKE '_transient_%' 
            AND option_name NOT LIKE '_transient_timeout_%' 
            AND option_name NOT IN (
                SELECT REPLACE(option_name, '_transient_timeout_', '_transient_') 
                FROM {$wpdb->options} 
                WHERE option_name LIKE '_transient_timeout_%'
            )
        ");
        
        if (defined('WP_DEBUG_LOG') && WP_DEBUG_LOG) {
            error_log("WPDT Cleanup: Removed {$expired_timeouts} expired timeouts and {$orphaned_transients} orphaned transients");
        }
    }
}

// Initialize the performance monitor
$wpdt_performance_monitor = new WPDT_Performance_Monitor();
$wpdt_performance_monitor->__init();

// Add some useful debugging functions
if (!function_exists('wpdt_debug_log')) {
    function wpdt_debug_log($message) {
        if (defined('WP_DEBUG_LOG') && WP_DEBUG_LOG) {
            error_log('[WPDT DEBUG] ' . print_r($message, true));
        }
    }
}

if (!function_exists('wpdt_get_memory_usage')) {
    function wpdt_get_memory_usage() {
        return [
            'current' => memory_get_usage(true),
            'peak' => memory_get_peak_usage(true),
            'current_mb' => round(memory_get_usage(true) / 1024 / 1024, 2),
            'peak_mb' => round(memory_get_peak_usage(true) / 1024 / 1024, 2)
        ];
    }
}
