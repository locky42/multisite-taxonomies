<?php
/**
 * Plugin Name: Multisite Taxonomies
 * Plugin URI: https://github.com/locky42/multisite-taxonomies
 * Description: Create taxonomies for multisite
 * Version: 0.1.0
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

include MST_PLUGIN_DIR . '/admin/init.php';
include MST_PLUGIN_DIR . '/taxonomies.php';
