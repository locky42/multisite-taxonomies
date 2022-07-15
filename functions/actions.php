<?php

add_action('pre_post_update', 'mtax_save_post_action');
add_action('pre_get_terms', 'mtax_pre_get_terms');
add_action('init', 'multisite_change_tax_terms_table', 0);
add_action('switch_blog', 'multisite_change_tax_terms_table', 0);

function mtax_save_post_action($postId)
{
    if (!empty(array_intersect(mtax_get_taxonomies_slugs(), get_post_taxonomies($postId)))) {
        global $wpdb;
        $wpdb->terms = $wpdb->base_prefix . 'terms';
        $wpdb->term_taxonomy = $wpdb->base_prefix . 'term_taxonomy';
    }
}

function mtax_pre_get_terms($query)
{
    if (!empty(array_intersect(mtax_get_taxonomies_slugs(), $query->query_vars['taxonomy']))) {
        global $wpdb;
        $wpdb->terms = $wpdb->base_prefix . 'terms';
        $wpdb->term_taxonomy = $wpdb->base_prefix . 'term_taxonomy';
    }
}

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
