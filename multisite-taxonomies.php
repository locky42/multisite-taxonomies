<?php
/**
 * Plugin Name: Multisite Taxonomies
 * Plugin URI: https://github.com/locky42/multisite-taxonomies
 * Description: Create taxonomies for multisite
 * Version: 1.1.3
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

include_once MST_PLUGIN_DIR . '/loader.php';

if (is_admin()) { // note the use of is_admin() to double-check that this is happening in the admin
    $parts = explode('/', $_SERVER['SCRIPT_NAME']);
    // check only if is plugin page in multisite admin panel
    if (in_array(array_pop($parts), ['plugins.php', 'plugin-install.php', 'update.php']) && array_pop($parts) == 'network') {
        new WPGitHubUpdater(__FILE__, 'locky42', 'multisite-taxonomies');
    }
}
