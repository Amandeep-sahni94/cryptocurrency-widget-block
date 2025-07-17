<?php
/**
 * Plugin Name:       Cryptocurrency Elementor Block
 * Requires at least: 6.1
 * Requires PHP:      7.0
 * Version:           1.1.0
 * Author:            sahniaman94
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Elementor requires at least: 3.22
 * Elementor tested up to: 3.27.6
 * Text Domain:       cryptocurrency-widget-block
 * Description:	Showcase cryptocurrency data using a variety of customizable widget blocks, each designed to present real-time information in an engaging and user-friendly format.
 */

/*

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
define( 'ELEMENTOR_CCWFG_WIDGET', __FILE__ );
include_once  __DIR__ .'/includes/functions.php';


/**
 * Include the ELEMENTOR_CCWFG_WIDGET class.
 */
include_once plugin_dir_path( ELEMENTOR_CCWFG_WIDGET ) . 'elementor-widgets/widget-register-settings/activate-widget.php';



if (!function_exists('ccwfg_register_block')) { 
function ccwfg_register_block() {
  
	register_block_type( __DIR__ . '/build',array(
        'api_version'   => 3,       
    ) );
}
}
add_action( 'init', 'ccwfg_register_block' );



// Add custom link to the plugin action links
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'ccwfg_add_action_link');
if (!function_exists('ccwfg_add_action_link')) { 
function ccwfg_add_action_link($links) {
    $nonce = wp_create_nonce('ccwfg_create_page_nonce');
    $links[] = '<a href="' . esc_url(admin_url('admin-post.php?action=ccwfg_create_page&_wpnonce=' . $nonce)) . '">Create a Widget</a>';
    return $links;
}
}

// Handle the page creation
add_action('admin_post_ccwfg_create_page', 'ccwfg_create_page');
if (!function_exists('ccwfg_create_page')) { 
function ccwfg_create_page() {
    // Check user capabilities
    if (!current_user_can('manage_options')) {
        wp_die('You do not have sufficient permissions to access this page.');
    }
$nonce=sanitize_text_field($_GET['_wpnonce']);
    // Verify the nonce for security
    if (!isset($nonce) || !wp_verify_nonce($nonce, 'ccwfg_create_page_nonce')) {
        wp_die('Nonce verification failed.');
    }

    // Define new page content with your widget block
    $page_content = '<!-- wp:cryptocurrency-widget-block/cryptocurrency-widget {"widgettype":"ticker","limit":"10"} /-->';

    // Create a new page
    $new_page = array(
        'post_title'   => 'Cryptocurrency Widget Block',
        'post_content' => $page_content,
        'post_status'  => 'draft',
        'post_type'    => 'page',
    );

    $post_id = wp_insert_post($new_page);

    if (is_wp_error($post_id)) {
        wp_die('Error creating page. Details: ' . $post_id->get_error_message());
    }

    // Redirect to the newly created page edit screen
    wp_safe_redirect(admin_url('post.php?post='.$post_id.'&action=edit'));
    exit;
}
}
