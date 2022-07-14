<?php
/**
 * Plugin Name: Multisite Taxonomies
 * Plugin URI: https://github.com/locky42/multisite-taxonomies
 * Description: Create taxonomies for multisite
 * Version: 1.0.0
 * Author: Zinchenko Maxym
 * Author URI: https://github.com/locky42
 * License: WTFPL
 * License URI: http://www.wtfpl.net/
 *
 * Network: true
 */

if(!defined('ABSPATH')) exit;

$plugin_url = untrailingslashit(plugin_dir_url(__FILE__));
define('MST_PLUGIN_URL', $plugin_url);
define('MST_PLUGIN', __FILE__);
define('MST_PLUGIN_DIR', untrailingslashit(dirname(MST_PLUGIN)));

include_once MST_PLUGIN_DIR . '/admin/init.php';
include_once MST_PLUGIN_DIR . '/taxonomies.php';
include_once MST_PLUGIN_DIR . '/functions/functions.php';
include_once MST_PLUGIN_DIR . '/helpers/DBHelper.php';
include_once MST_PLUGIN_DIR . '/classes/updater.php';

if (is_admin()) { // note the use of is_admin() to double-check that this is happening in the admin
    $parts = explode('/', $_SERVER['SCRIPT_NAME']);
    // check only if is plugin page in multisite admin panel
    if (in_array(array_pop($parts), ['plugins.php', 'plugin-install.php', 'update.php']) && array_pop($parts) == 'network') {
        new WPGitHubUpdater(__FILE__, 'locky42', 'multisite-taxonomies');
    }
}

add_action( 'init', 'multisite_change_tax_terms_table', 0 );
add_action( 'switch_blog', 'multisite_change_tax_terms_table', 0 );
function multisite_change_tax_terms_table()
{
    mtax_check_tables();
    $multisite_taxonomies_slugs = mtax_get_taxonomies_slugs();
    if (isset($_GET['taxonomy']) && $_GET['taxonomy'] && in_array($_GET['taxonomy'], $multisite_taxonomies_slugs)) {
        global $wpdb;
        $wpdb->terms = $wpdb->base_prefix . 'terms';
        $wpdb->term_taxonomy = $wpdb->base_prefix . 'term_taxonomy';
    }
}

