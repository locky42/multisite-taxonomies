<?php

include_once MST_PLUGIN_DIR . '/admin/init.php';
include_once MST_PLUGIN_DIR . '/taxonomies.php';

$patterns_include = [
    MST_PLUGIN_DIR . '/classes/*.php',
    MST_PLUGIN_DIR . '/functions/*.php',
    MST_PLUGIN_DIR . '/helpers/*.php',
];

foreach ($patterns_include as $include) {
    foreach (glob($include) as $class) {
        require_once $class;
    }
}
