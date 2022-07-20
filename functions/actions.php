<?php

add_action('pre_post_update', 'mtax_save_post_action');
add_action('pre_get_terms', 'mtax_pre_get_terms');
add_action('init', 'multisite_change_tax_terms_table', 0);
add_action('switch_blog', 'multisite_change_tax_terms_table', 0);
add_action('admin_init', 'mtax_update_post_action', 1000);

function mtax_save_post_action($postId)
{
    if (!empty(array_intersect(mtax_get_taxonomies_slugs(), get_post_taxonomies($postId)))) {
        mtax_change_current_table();
    }
}

function mtax_pre_get_terms($query)
{
    if (!empty(array_intersect(mtax_get_taxonomies_slugs(), $query->query_vars['taxonomy']))) {
        mtax_change_current_table();
    }
}

function multisite_change_tax_terms_table()
{
    mtax_check_tables();
    $multisite_taxonomies_slugs = mtax_get_taxonomies_slugs();
    if (isset($_GET['taxonomy']) && $_GET['taxonomy'] && in_array($_GET['taxonomy'], $multisite_taxonomies_slugs)) {
        mtax_change_current_table();
    }
}

function mtax_update_post_action()
{
    if (
        isset($_POST['action']) &&
        $_POST['action'] == 'editedtag' &&
        isset($_POST['taxonomy']) &&
        in_array($_POST['taxonomy'], mtax_get_taxonomies_slugs())
    ) {
        mtax_change_current_table();
    }
}
