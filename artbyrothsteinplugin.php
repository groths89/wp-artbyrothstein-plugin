<?php
/*
    Plugin Name: Art By Rothstein Custom Posts
    Plugin URI: https://thecommodoredesigns.com
    Description: A plugin to get custom columns for the paintings and events custom post type.
    Version: 1.0
    Author: Gregory Rothstein
    Author URI: https://gregorypaulrothstein.dev
    License: GPL2
    */

add_filter('manage_paintings_posts_columns', 'artbyrothstein_paintings_columns');

function artbyrothstein_paintings_columns($columns)
{
    $columns = array(
        'cb' => $columns['cb'],
        'image' => __('Image'),
        'name' => __('Name', 'artbyrothstein'),
        'categories' => __('Categories', 'artbyrothstein')
    );

    return $columns;
}


add_action('manage_paintings_posts_custom_column', 'artbyrothstein_paintings_custom_column', 10, 2);
function artbyrothstein_paintings_custom_column($column, $post_id)
{
    // Image column
    if ('image' === $column) {
        echo get_the_post_thumbnail($post_id, array(240, 240));
    }

    // Name column
    if ('name' === $column) {
        $name = get_field('artbyrothstein_paintings_name');

        if (!$name) {
            _e('n/a');
        } else {
            echo $name;
        }
    }

    // Categories column
    if ('categories' === $column) {
        $categories = get_categories();

        if (!$categories) {
            _e('n/a');
        } else {
            echo $categories;
        }
    }
}

add_filter('manage_edit-paintings_sortable_columns', 'artbyrothstein_paintings_sortable_columns');
function artbyrothstein_paintings_sortable_columns($columns)
{
    $columns['name'] = 'artbyrothstein_paintings_name';
    return $columns;
}

add_action('pre_get_posts', 'artbyrothstein_posts_orderby');
function artbyrothstein_posts_orderby($query)
{
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }

    if ('artbyrothstein_paintings_name' === $query->get('orderby')) {
        $query->set('orderby', 'meta_value');
        $query->set('meta_key', 'artbyrothstein_paintings_name');
        $query->set('meta_type', 'numeric');
    }
}
