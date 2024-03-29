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
        'multisite_taxonomies',
        __( 'Taxonomies', 'multisite_taxonomies' ),
        'multisite_taxonomies_repeatable_meta_box_callback',
        'multisite_taxonomies',
        'multisite_taxonomies_section'
    );
    add_settings_section(
        'multisite_taxonomies_fields',
        __('Multisite taxonomies fields', 'multisite_taxonomies_fields'),
        null,
        'multisite_taxonomies_fields'
    );
    add_settings_field(
        'multisite_taxonomies',
        __( 'Taxonomies', 'multisite_taxonomies' ),
        'multisite_taxonomies_fields_repeatable_meta_box_callback',
        'multisite_taxonomies_fields',
        'multisite_taxonomies_fields'
    );
}

/**
 * @return void
 */
function multisite_taxonomies_options_page()
{ ?>
    <form method="post" action="/wp-admin/network/edit.php?action=multisite_taxonomies">
    <?php
        settings_fields('multisite_taxonomies');
        do_settings_sections('multisite_taxonomies');
        submit_button();
        ?>
    </form>
    <?php
}

/**
 * @return void
 */
function multisite_taxonomies_fields_page()
{ ?>
    <form method="post" action="/wp-admin/network/edit.php?action=multisite_taxonomies">
        <?php
        settings_fields('multisite_taxonomies_fields');
        do_settings_sections('multisite_taxonomies_fields');
        submit_button();
        ?>
    </form>
    <?php
}

function multisite_taxonomies_fields_repeatable_meta_box_callback()
{
    $multisite_taxonomies = get_site_option('multisite_taxonomies'); ?>
    <script type="text/javascript">
        jQuery(document).ready(function( $ ){
            $('#add-row').on('click', function() {
                var row = $('.empty-row.custom-repeater-text').clone(true);
                row.removeClass('empty-row custom-repeater-text').css('display','table-row');
                console.log($(event.target).closest('table').find('tbody:last'));
                console.log(row);
                $(event.target).closest('table').find('tbody:last').append(row);
                resetAttributes();
                return false;
            });

            $('.remove-row').on('click', function() {
                $(this).closest('tr').remove();
                resetAttributes();
                return false;
            });

            function resetAttributes()
            {
                let fieldsets = $('fieldset');
                $(fieldsets).each(function (index) {
                    if (index < fieldsets.length -1) {
                        $(this).find('input').each(function () {
                            $(this).attr('id', $(this).attr('value') + '-' + index);
                            $(this).attr('name', 'post_types[' + index + '][]');
                        });
                        $(this).find('label').each(function () {
                            $(this).attr('for', $(this).data('type') + '-' + index);
                        });
                    }
                });
            }
        });
    </script>
    <?php if ($multisite_taxonomies):
        foreach ($multisite_taxonomies as $iteration => $multisite_taxonomy): ?>
        <table id="repeatable-fieldset-one" width="100%" class="form-table">
            <tbody>
                <tr>
                    <td>
                        <h3><?= $multisite_taxonomy['singular_label'] ?></h3>
                    </td>
                    <td>
                        <table>
                            <tbody>
                                <tr>
                                    <td>
                                        <label><?= __('Field slug'); ?></label>
                                        <input
                                                type="text"
                                                style="width:98%; margin: 0.5em 0;"
                                                name="field_slug[]"
                                                value=""
                                                placeholder="<?= __('Field slug'); ?>"
                                        />
                                    </td>
                                    <td>
                                        <label><?= __('Field name'); ?></label>
                                        <input
                                                type="text"
                                                style="width:98%; margin: 0.5em 0;"
                                                name="field_name[]"
                                                value=""
                                                placeholder="<?= __('Field name'); ?>"
                                        />
                                    </td>
                                    <td>
                                        <a class="button remove-row" href="#" style="margin-top: 1.5em;">
                                            <?= __('Remove'); ?>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><a id="add-row" class="button" href="#"><?= __('Add field'); ?></a></p>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php endforeach; ?>
    <?php endif; ?>
    <td>
        <tr class="empty-row custom-repeater-text" style="display: none">
            <td>
                <label><?= __('Field slug'); ?></label>
                <input
                        type="text"
                        style="width:98%; margin: 0.5em 0;"
                        name="field_slug[]"
                        value=""
                        placeholder="<?= __('Field slug'); ?>"
                />
            </td>
            <td>
                <label><?= __('Field name'); ?></label>
                <input
                        type="text"
                        style="width:98%; margin: 0.5em 0;"
                        name="field_name[]"
                        value=""
                        placeholder="<?= __('Field name'); ?>"
                />
            </td>
            <td>
                <a class="button remove-row" href="#" style="margin-top: 1.5em;">
                    <?= __('Remove'); ?>
                </a>
            </td>
        </tr>
    </td>
<?php }

/**
 * @return void
 */
function multisite_taxonomies_repeatable_meta_box_callback()
{
    $multisite_taxonomies = get_site_option('multisite_taxonomies');
    $postTypes = get_post_types(['public' => true]);
    wp_nonce_field( 'repeaterBox', 'formType' );
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function( $ ){
            $('#add-row').on('click', function() {
                var row = $('.empty-row.custom-repeater-text').clone(true);
                row.removeClass('empty-row custom-repeater-text').css('display','table-row');
                row.insertBefore('#repeatable-fieldset-one tbody>tr:last');
                resetAttributes();
                return false;
            });

            $('.remove-row').on('click', function() {
                $(this).closest('tr').remove();
                resetAttributes();
                return false;
            });

            function resetAttributes()
            {
                let fieldsets = $('fieldset');
                $(fieldsets).each(function (index) {
                    if (index < fieldsets.length -1) {
                        $(this).find('input').each(function () {
                            $(this).attr('id', $(this).attr('value') + '-' + index);
                            $(this).attr('name', 'post_types[' + index + '][]');
                        });
                        $(this).find('label').each(function () {
                            $(this).attr('for', $(this).data('type') + '-' + index);
                        });
                    }
                });
            }
        });
    </script>

    <table id="repeatable-fieldset-one" width="100%" class="form-table">
        <tbody>
        <?php
        if ($multisite_taxonomies):
            foreach ($multisite_taxonomies as $iteration => $field): ?>
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

                        <label><?= __('Post types'); ?></label>
                        <fieldset>
                            <?php foreach ($postTypes as $postType): ?>
                                <div>
                                    <input
                                            type="checkbox"
                                            id="<?= $postType; ?>-<?= $iteration; ?>"
                                            name="post_types[<?= $iteration; ?>][]"
                                            value="<?= $postType; ?>" <?= in_array($postType, $field['postTypes']) ? 'checked' : ''; ?>
                                    >
                                    <label for="<?= $postType; ?>-<?= $iteration; ?>" data-type="<?= $postType; ?>"><?= $postType; ?></label>
                                </div>
                            <?php endforeach; ?>
                        </fieldset>
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

                    <label><?= __('Post types'); ?></label>
                    <fieldset>
                        <?php foreach ($postTypes as $postType): ?>
                            <div>
                                <input type="checkbox" id="<?= $postType; ?>-0" name="post_types[0][]" value="<?= $postType; ?>">
                                <label for="<?= $postType; ?>-0" data-type="<?= $postType; ?>"><?= $postType; ?></label>
                            </div>
                        <?php endforeach; ?>
                    </fieldset>
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

                <label><?= __('Post types'); ?></label>
                <fieldset>
                    <?php foreach ($postTypes as $postType): ?>
                        <div>
                            <input type="checkbox" id="<?= $postType; ?>" name="post_types[][]" value="<?= $postType; ?>">
                            <label for="<?= $postType; ?>" data-type="<?= $postType; ?>"><?= $postType; ?></label>
                        </div>
                    <?php endforeach; ?>
                </fieldset>
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
                'postTypes' => $_POST['post_types'][$key]
            ];
        }
    }

    wp_cache_set('notoptions', $taxonomies, 'options');

    $multisite_taxonomies = get_site_option('multisite_taxonomies');

    if ($multisite_taxonomies) {
        update_site_option('multisite_taxonomies', $taxonomies);
    } else {
        add_site_option('multisite_taxonomies', $taxonomies);
    }

    wp_redirect($wp_http_referer);
    exit;
}
