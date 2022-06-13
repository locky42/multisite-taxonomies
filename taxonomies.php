<?php

add_action('init', 'add_multisite_taxonomies', 100000);

function add_multisite_taxonomies()
{
    $multisite_taxonomies = get_site_option('multisite_taxonomies');
    if ($multisite_taxonomies && !empty($multisite_taxonomies)) {
        foreach ($multisite_taxonomies as $multisite_taxonomy) {
            $labels = array(
                'name' => __($multisite_taxonomy['plural_label']),
                'singular_name' => __($multisite_taxonomy['singular_label']),
                'search_items' =>  __( 'Search Subjects' ),
                'all_items' => __( 'All ' . $multisite_taxonomy['plural_label']),
                'menu_name' => __($multisite_taxonomy['singular_label']),
            );
            register_taxonomy($multisite_taxonomy['slug'], ['post'], array(
                'hierarchical' => false,
                'labels' => $labels,
                'show_ui' => true,
                'show_in_rest' => true,
                'show_admin_column' => true,
                'query_var' => true,
                'rewrite' => array('slug' => $multisite_taxonomy['slug']),
            ));
        }
    }
}
