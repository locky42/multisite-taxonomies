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
 * Network:     true
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

if (is_admin()) { // note the use of is_admin() to double check that this is happening in the admin
    $config = array(
        'slug' => plugin_basename(__FILE__), // this is the slug of your plugin
        'proper_folder_name' => 'plugin-name', // this is the name of the folder your plugin lives in
        'api_url' => 'https://api.github.com/repos/locky42/multisite-taxonomies', // the github API url of your github repo
        'raw_url' => 'https://api.github.com/repos/locky42/multisite-taxonomies/main', // the github raw url of your github repo
        'github_url' => 'https://github.com/locky42/multisite-taxonomies', // the github url of your github repo
        'zip_url' => 'https://github.com/locky42/multisite-taxonomies/zipball/main', // the zip url of the github repo
        'sslverify' => true, // wether WP should check the validity of the SSL cert when getting an update, see https://github.com/jkudish/WordPress-GitHub-Plugin-Updater/issues/2 and https://github.com/jkudish/WordPress-GitHub-Plugin-Updater/issues/4 for details
        'requires' => '5.0', // which version of WordPress does your plugin require?
        'tested' => '6.0', // which version of WordPress is your plugin tested up to?
        'readme' => 'README.md' // which file to use as the readme for the version number
    );
    new WPGitHubUpdater($config);
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

