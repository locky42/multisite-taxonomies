<?php

/**
 * @return false|mixed
 */
function mtax_get_taxonomies()
{
    return get_site_option('multisite_taxonomies');
}

/**
 * @return array
 */
function mtax_get_taxonomies_slugs()
{
    $taxonomies = mtax_get_taxonomies();
    $slugs = [];
    if (!empty($taxonomies)) {
        foreach ($taxonomies as $taxonomy) {
            $slugs[] = $taxonomy['slug'];
        }
    }

    return $slugs;
}

/**
 * @return void
 */
function mtax_check_tables()
{
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    if (!DBHelper::isTableExist($wpdb->terms)) {
        DBHelper::cloneOriginalTable($wpdb->terms, $charset_collate);
    }

    if (!DBHelper::isTableExist($wpdb->term_taxonomy)) {
        DBHelper::cloneOriginalTable($wpdb->term_taxonomy, $charset_collate);
    }
}
