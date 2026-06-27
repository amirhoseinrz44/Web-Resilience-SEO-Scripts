<?php
/**
 * Script: Crisis Mode 503 Handler
 * Description: Prevents Google from de-indexing pages by throwing a strict 503 Retry-After 
 * status instead of a soft 404 or connection timeout during database connection issues.
 */

function web_resilience_force_503_on_db_error() {
    if ( is_admin() ) return;
    
    global $wpdb;
    // Check if DB is responsive
    if ( ! $wpdb->check_connection() ) {
        header( 'HTTP/1.1 503 Service Temporarily Unavailable' );
        header( 'Status: 503 Service Temporarily Unavailable' );
        header( 'Retry-After: 86400' ); // Tell Googlebot to come back in 24 hours
        
        echo '<h1>Service Unavailable</h1><p>We are experiencing severe network/database disruptions. Indexing is paused.</p>';
        exit();
    }
}
add_action( 'init', 'web_resilience_force_503_on_db_error' );
