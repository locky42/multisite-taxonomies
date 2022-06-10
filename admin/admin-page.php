<?php

add_action('admin_init', 'multisite_taxonomies_settings_init');

/**
 * @return void
 */
function multisite_taxonomies_settings_init()
{
    register_setting( 'multisite_taxonomies', 'multisite_taxonomies_settings' );
    add_settings_section(
        'multisite_taxonomies_section',
        __('Multisite taxonomies', 'multisite_taxonomies'),
        null,
        'multisite_taxonomies'
    );
    add_settings_field(
        'multisite_taxonomies_1',
        __( 'Taxonomies', 'multisite_taxonomies' ),
        'multisite_taxonomies_repeatable_meta_box_callback',
        'multisite_taxonomies',
        'multisite_taxonomies_section'
    );
}

/**
 * @return void
 */
function multisite_taxonomies_options_page()
{ ?>
    <form method="post" action="/wp-admin/network/edit.php?action=multisite_taxonomies">
    <?php
        settings_fields( 'multisite_taxonomies' );
        do_settings_sections( 'multisite_taxonomies' );
        submit_button();
        ?>
    </form>
    <?php
}


add_action('admin_init', 'taxonomies_repeater_meta_boxes', 2);

/**
 * @return void
 */
function taxonomies_repeater_meta_boxes() {
    add_meta_box( 'taxonomy-repeater-data', 'Taxonomy Repeater', 'multisite_taxonomies_repeatable_meta_box_callback', 'post', 'normal');
}

/**
 * @return void
 */
function multisite_taxonomies_repeatable_meta_box_callback()
{
    $multisite_taxonomies = get_option('multisite_taxonomies');
    wp_nonce_field( 'repeaterBox', 'formType' );
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function( $ ){
            $( '#add-row' ).on('click', function() {
                var row = $( '.empty-row.custom-repeater-text' ).clone(true);
                row.removeClass( 'empty-row custom-repeater-text' ).css('display','table-row');
                row.insertBefore( '#repeatable-fieldset-one tbody>tr:last' );
                return false;
            });

            $( '.remove-row' ).on('click', function() {
                $(this).closest('tr').remove();
                return false;
            });
        });

    </script>

    <table id="repeatable-fieldset-one" width="100%" class="form-table">
        <tbody>
        <?php
        if ($multisite_taxonomies):
            foreach ($multisite_taxonomies as $field): ?>
                <tr>
                    <td>
                        <label><?= __('Taxonomy slug'); ?></label>
                        <input
                                type="text"
                                style="width:98%; margin: 0.5em 0;"
                                name="tax_slug[]"
                                value="<?= $field['slug'] ? : ''; ?>"
                                placeholder="<?= __('Taxonomy slug'); ?>"
                        />

                        <label><?= __('Plural Label'); ?></label>
                        <input
                                type="text"
                                style="width:98%; margin: 0.5em 0;"
                                name="tax_plural_label[]"
                                value="<?= $field['plural_label'] ? : ''; ?>"
                                placeholder="<?= __('Plural Label'); ?>"
                        />

                        <label><?= __('Singular Label'); ?></label>
                        <input
                                type="text"
                                style="width:98%; margin: 0.5em 0;"
                                name="tax_singular_label[]"
                                value="<?= $field['singular_label'] ? : ''; ?>"
                                placeholder="<?= __('Singular Label'); ?>"
                        />
                    </td>
                    <td>
                        <a class="button remove-row" href="#1">
                            <?= __('Remove'); ?>
                        </a>
                    </td>
                </tr>
                <?php
            endforeach;
        else: ?>
            <tr>
                <td>
                    <label><?= __('Taxonomy slug'); ?></label>
                    <input type="text" style="width:98%; margin: 0.5em 0;" name="tax_slug[]" placeholder="<?= __('Taxonomy slug'); ?>"/>

                    <label><?= __('Plural Label'); ?></label>
                    <input type="text" style="width:98%; margin: 0.5em 0;" name="tax_plural_label[]" value="" placeholder="<?= __('Plural Label'); ?>" />

                    <label><?= __('Singular Label'); ?></label>
                    <input type="text" style="width:98%; margin: 0.5em 0;" name="tax_singular_label[]" value="" placeholder="<?= __('Singular Label'); ?>" />
                </td>
                <td>
                    <a class="button  cmb-remove-row-button button-disabled" href="#">
                        <?= __('Remove'); ?>
                    </a>
                </td>
            </tr>
        <?php endif; ?>
        <tr class="empty-row custom-repeater-text" style="display: none">
            <td>
                <label><?= __('Taxonomy slug'); ?></label>
                <input type="text" style="width:98%; margin: 0.5em 0;" name="tax_slug[]" placeholder="<?= __('Taxonomy slug'); ?>"/>

                <label><?= __('Plural Label'); ?></label>
                <input type="text" style="width:98%; margin: 0.5em 0;" name="tax_plural_label[]" value="" placeholder="<?= __('Plural Label'); ?>" />

                <label><?= __('Singular Label'); ?></label>
                <input type="text" style="width:98%; margin: 0.5em 0;" name="tax_singular_label[]" value="" placeholder="<?= __('Singular Label'); ?>" />
            </td>
            <td>
                <a class="button remove-row" href="#">
                    <?= __('Remove'); ?>
                </a>
            </td>
        </tr>
        </tbody>
    </table>
    <p><a id="add-row" class="button" href="#"><?= __('Add taxonomy'); ?></a></p>
    <?php
}

add_action('network_admin_edit_multisite_taxonomies', 'taxonomies_save');

/**
 * @return void
 */
function taxonomies_save()
{
    $taxonomies = [];
    $wp_http_referer = $_POST['_wp_http_referer'];
    foreach ($_POST['tax_slug'] as $key => $tax_slug) {
        if ($tax_slug) {
            $taxonomies[] = [
                'slug' => $tax_slug,
                'plural_label' => $_POST['tax_plural_label'][$key],
                'singular_label' => $_POST['tax_singular_label'][$key],
            ];
        }
    }

    wp_cache_set('notoptions', $taxonomies, 'options');

    $multisite_taxonomies = get_option('multisite_taxonomies');

    if ($multisite_taxonomies) {
        update_option('multisite_taxonomies', $taxonomies, true);
    } else {
        add_option('multisite_taxonomies', $taxonomies, '', true);
    }

    wp_redirect($wp_http_referer);
    exit;
}
