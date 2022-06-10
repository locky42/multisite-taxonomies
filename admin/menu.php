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
}
