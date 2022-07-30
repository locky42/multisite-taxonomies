<?php

add_action("admin_menu", "multisite_taxonomies_admin_menus");
add_action('network_admin_menu', "multisite_taxonomies_admin_menus");

function multisite_taxonomies_admin_menus() {
    add_menu_page(
        'Multisite Taxonomies Settings',
        'MTax',
        'multisite_taxonomies',
        'multisite_taxonomies',
        'multisite_taxonomies_options_page',
        MST_PLUGIN_URL . '/assets/logo.png'
    );
    add_submenu_page(
        'multisite_taxonomies',
        'Multisite Taxonomies Fields',
        'MTax Fields',
        'multisite_taxonomies_fields',
        'multisite_taxonomies_fields',
        'multisite_taxonomies_fields_page',
        MST_PLUGIN_URL . '/assets/logo.png'
    );
}
