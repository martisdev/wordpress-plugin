<?php
function mscra_reset_system()
{
    wp_delete_nav_menu(__('MSC Main', 'mscra-automation'));
    wp_delete_nav_menu(__('MSC Footer', 'mscra-automation'));

    //List and delete all pages with the meta _msc_hook_id
    $args = array(
        'post_type' => 'page',
        'posts_per_page' => '100',
        'post_status' => 'publish',
        'meta_query' => array(
            array(
                'key' => '_msc_hook_id',
                'compare' => 'EXISTS',
            ),
        ),
    );
    $query = new WP_Query($args);
    while ($query->have_posts()) {
        $query->the_post();
        $id = get_the_ID();
        wp_delete_post($id, true);
    }
    /* Restore original Post Data */
    update_option('mscra_initialize', 'false');
    wp_reset_postdata();
}

function mscra_unistall()
{

    if (get_option('mscra_initialize') == 'true') {
        mscra_reset_system();
    }    
    
    $option_name = 'mscra_client_key';
    delete_option($option_name);
    delete_site_option($option_name);
    
    $option_name = 'mscra_debug';
    delete_option($option_name);
    delete_site_option($option_name);
    
    $option_name = 'mscra_initialize';
    delete_option($option_name);
    delete_site_option($option_name);
    
    $option_name = 'mscra_player';
    delete_option($option_name);
    delete_site_option($option_name);
    
    $option_name = 'mscra_color';
    delete_option($option_name);
    delete_site_option($option_name);
    
    $option_name = 'mscra_enable_aws';
    delete_option($option_name);
    delete_site_option($option_name);
    
    $option_name = 'mscra_no-ajax-ids';
    delete_option($option_name);
    delete_site_option($option_name);
    
    $option_name = 'mscra_container-id';
    delete_option($option_name);
    delete_site_option($option_name);
    
    $option_name = 'mscra_mcdc';
    delete_option($option_name);
    delete_site_option($option_name);
    
    $option_name = 'mscra_transition';
    delete_option($option_name);
    delete_site_option($option_name);
    
    $option_name = 'mscra_transition';
    delete_option($option_name);
    delete_site_option($option_name);
    
    $option_name = 'mscra_scrollTop';
    delete_option($option_name);
    delete_site_option($option_name);
    delete_site_option($option_name);
    
    $option_name = 'mscra_loader';
    delete_option($option_name);
    delete_site_option($option_name);
    // drop a custom database table
    //global $wpdb;
    //$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}mytable");
}

register_uninstall_hook(__FILE__, 'mscra_unistall');