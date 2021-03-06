<?php

if (!defined('WPINC')) {
    die;
}

global $MyRadio;
if (!isset($MyRadio)) {
    $key = get_option('mscra_client_key');
    
    if (!function_exists('get_plugin_data')) {
        require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }
    $plugin_data = get_plugin_data(MSCRA_PLUGIN_DIR.'msc-automation.php');        
    $plugin_version = $plugin_data['Version'];            
                
    $MyRadio = new my_radio($key, get_locale(),$plugin_version, get_option('mscra_debug'));
    if ($MyRadio->RESPOSTA_STATUS !== SUCCES) {
        if ($MyRadio->IS_DEGUG == true) {
            $msg = 'STATUS: ' . $MyRadio->RESPOSTA_STATUS . ' CODE: ' . $MyRadio->RESPOSTA_CODE . ' MSG: ' . sanitize_text_field($MyRadio->RESPOSTA_MESSAGE);
            mscra_show_message($msg, message_type::DANGER);            
        }
        //message
        exit;
    }
}