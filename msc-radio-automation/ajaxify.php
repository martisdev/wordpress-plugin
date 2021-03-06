<?php

/**
 * 	Function name: aws_load_scripts
 * 	Description: Loading the required js files and assing required php variable to js variable.
 */
function mscra_load_ajaxify() {    
    if (get_option('mscra_enable_aws') == 'true') {                
        //Check whether the core jqury library enqued or not. If not enqued the enque this
        if (!wp_script_is('jquery')) {
            wp_enqueue_script('jquery');
        }
        wp_enqueue_script('history-js', MSCRA_JQUERY_URL . 'ajaxify/history.js', array('jquery'));
        wp_enqueue_script('ajaxify-js', MSCRA_JQUERY_URL . 'ajaxify/ajaxify.js', array('jquery'));

        $ids_arr = explode(',', get_option('mscra_no-ajax-ids'));
        foreach ($ids_arr as $key => $id) {
            if (trim($id) == '')
                unset($ids_arr[$key]);
            else
                $ids_arr[$key] = '#' . trim($id) . ' a';
        }
        $ids = implode(',', $ids_arr);

        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        $bp_status = is_plugin_active('buddypress/bp-loader.php');

        $aws_data = array(
            'rootUrl' => site_url() . '/',
            'ids' => $ids,
            'container_id' => get_option('mscra_container-id'),
            'mcdc' => get_option('mscra_mcdc'),
            'searchID' => get_option('mscra_search-form'),
            'transition' => get_option('mscra_transition'),
            'scrollTop' => get_option('mscra_scrollTop'),
            'loader' => MSCRA_PLUGIN_URL . '/images/ajaxify/'. get_option('mscra_loader'),
            'bp_status' => $bp_status
        );
        wp_localize_script('ajaxify-js', 'aws_data', $aws_data);
        
    }
}

// End of aws_load_scripts function
//calling aws_load_scripts function to load js files
add_action('wp_enqueue_scripts', 'mscra_load_ajaxify');
