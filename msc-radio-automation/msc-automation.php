<?php
/*
  Plugin Name:    MSC Radio Automation
  Plugin URI:     https://msc-soft.com/plugin-wordpress/
  Description:    Radio Automation Software | The radio on cloud. This plugin syncronize your radio station with your web page.
  Version:        2.3
  Author:         MSC-Soft team <info@msc-soft.com>
  Author URI:     https://msc-soft.com/
  License:        GPL2
  License URI:    http://www.gnu.org/licenses/gpl-2.0.txt
  Text Domain:    mscra-automation
  Domain Path:    /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

include_once plugin_dir_path(__FILE__) . 'wp-defines.php';

function mscra_load_textdomain() {
    $path_lang = basename(dirname(__FILE__)) . '/languages';
    //get_locale()
    load_plugin_textdomain('mscra-automation', false, $path_lang);
}

add_action('plugins_loaded', 'mscra_load_textdomain');

if (is_admin()) {
    // Establim els menús de configuració 
    function mscra_plugin_menu() {
        add_menu_page(__('MSC Radio Automation', 'mscra-automation'), __('MSC Radio Automation', 'mscra-automation'), 'administrator', MSCRA_DIR . '/admin/msc-index.php', '', plugins_url(MSCRA_DIR . '/images/logo-msc.png'));
        add_submenu_page(MSCRA_DIR . '/admin/msc-index.php', __('Settings', 'mscra-automation'), __('Settings', 'mscra-automation'), 'administrator', MSCRA_DIR . '/admin/msc-setting.php', '');
        $test_ini_option = get_option('mscra_initialize', 'sth');
        if ($test_ini_option <> 'sth') {
            add_submenu_page(MSCRA_DIR . '/admin/msc-index.php', __('Initialize', 'mscra-automation'), __('Initialize', 'mscra-automation'), 'administrator', MSCRA_DIR . '/admin/msc-ini.php', '');
        }
        add_submenu_page(MSCRA_DIR . '/admin/msc-index.php', __('Help', 'mscra-automation'), __('Help', 'mscra-automation'), 'administrator', MSCRA_DIR . '/admin/msc-help.php', '');
    }

    add_action('admin_menu', 'mscra_plugin_menu');

    function mscra_register_settings() {
        register_setting('mscra_settings', 'mscra_client_key', '');
        register_setting('mscra_settings', 'mscra_debug', 0);
        register_setting('mscra_settings', 'mscra_initialize', 'false');
        register_setting('mscra_settings', 'mscra_player', 'bottom');
        register_setting('mscra_settings', 'mscra_color', sanitize_text_field('#003399'));
        register_setting('mscra_settings', 'mscra_enable_aws', 1);
        register_setting('mscra_settings', 'mscra_no-ajax-ids', '');
        register_setting('mscra_settings', 'mscra_container-id', 'main');
        register_setting('mscra_settings', 'mscra_mcdc', 'menu');
        register_setting('mscra_settings', 'mscra_search-form', 'search-form');
        register_setting('mscra_settings', 'mscra_transition', 0);
        register_setting('mscra_settings', 'mscra_scrollTop', 0);
        register_setting('mscra_settings', 'mscra_loader', '');
    }

    add_action('admin_init', 'mscra_register_settings');
} else {    
    if (!isset($_COOKIE['mscra_usr'])) {
        setcookie("mscra_usr", hash('md5', time() . get_bloginfo('name'), FALSE), time() + 60 * 60 * 24 * 360, COOKIEPATH, COOKIE_DOMAIN);  //360 days                
    }    
    $show_player = get_option('mscra_player', 'nothing');
    if ($show_player !== 'nothing') {
        function mscra_show_player() {
            mscra_get_player();
        }
        add_action('wp_footer', 'mscra_show_player');
    }
    // NO work
    $hp = (isset($_GET['hp']))? sanitize_text_field($_GET['hp']):0;//get_query_var('hp',0);
    if ($hp !=0) {         
        setcookie("mscra_date_vote", time(), time() + 3600, COOKIEPATH, COOKIE_DOMAIN); //espera una hora
    }
}

//register CSS
function mscra_register_init() {
    wp_enqueue_style('msc-automat', plugins_url('/css/style.css', __FILE__));
    wp_enqueue_style('msc-fontawesome', plugins_url('/css/font-awesome/css/all.css', __FILE__));   
}

add_action('init', 'mscra_register_init');

//Register javascript
function mscra_scrip_refresh() {
    if (is_single() || is_page()) {        
        wp_enqueue_script('msc_scrips', MSCRA_JQUERY_URL . 'msc_js.js');
    }
}

add_action('wp_enqueue_scripts', 'mscra_scrip_refresh');

//Control de sessió
add_action('init', 'mscra_start_session', 1);
add_action('wp_logout', 'mscra_end_session');
add_action('wp_login', 'mscra_end_session');

function mscra_start_session() {
    if (!session_id()) {
        session_start();
    }
}

function mscra_end_session() {
    session_destroy();
}
