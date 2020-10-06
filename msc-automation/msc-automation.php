<?php
/*
  Plugin Name:    MSC Radio Automation
  Plugin URI:     https://msc-soft.com/plugin-wordpress/
  Description:    Radio Automation Software | The radio on cloud. This plugin syncronize your radio station with your web page.
  Version:        2.2
  Author:         MSC-Soft team <info@msc-soft.com>
  Author URI:     https://msc-soft.com/
  License:        GPL2
  License URI:    http://www.gnu.org/licenses/gpl-2.0.txt
  Text Domain:    msc-automation
  Domain Path:    /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

include_once plugin_dir_path(__FILE__) . 'wp-defines.php';

function msc_load_textdomain() {
    $path_lang = basename(dirname(__FILE__)) . '/languages';
    //get_locale()
    load_plugin_textdomain('msc-automation', false, $path_lang);
}

add_action('plugins_loaded', 'msc_load_textdomain');

if (is_admin()) {

    // Establim els menús de configuració 

    function mscPluginMenu() {
        add_menu_page(__('MSC Radio Automation', 'msc-automation'), __('MSC Radio Automation', 'msc-automation'), 'administrator', MSC_DIR . '/admin/msc-index.php', '', plugins_url(MSC_DIR . '/images/logo-msc.png'));
        add_submenu_page(MSC_DIR . '/admin/msc-index.php', __('Settings', 'msc-automation'), __('Settings', 'msc-automation'), 'administrator', MSC_DIR . '/admin/msc-setting.php', '');
        $test_ini_option = get_option('msc_initialize', 'sth');
        if ($test_ini_option <> 'sth') {
            add_submenu_page(MSC_DIR . '/admin/msc-index.php', __('Initialize', 'msc-automation'), __('Initialize', 'msc-automation'), 'administrator', MSC_DIR . '/admin/msc-ini.php', '');
        }
        add_submenu_page(MSC_DIR . '/admin/msc-index.php', __('Help', 'msc-automation'), __('Help', 'msc-automation'), 'administrator', MSC_DIR . '/admin/msc-help.php', '');
    }

    add_action('admin_menu', 'mscPluginMenu');

    function msc_register_settings() {
        register_setting('msc_settings', 'msc_client_key', '');
        register_setting('msc_settings', 'msc_debug', 0);
        register_setting('msc_settings', 'msc_initialize', 'false');
        register_setting('msc_settings', 'msc_player', 'bottom');
        register_setting('msc_settings', 'msc_color', sanitize_text_field('#003399'));
        register_setting('msc_settings', 'msc_enable_aws', 1);
        register_setting('msc_settings', 'msc_no-ajax-ids', '');
        register_setting('msc_settings', 'msc_container-id', 'main');
        register_setting('msc_settings', 'msc_mcdc', 'menu');
        register_setting('msc_settings', 'msc_search-form', 'search-form');
        register_setting('msc_settings', 'msc_transition', 0);
        register_setting('msc_settings', 'msc_scrollTop', 0);
        register_setting('msc_settings', 'msc_loader', '');
    }

    add_action('admin_init', 'msc_register_settings');
} else {    
    if (!isset($_COOKIE['msc_usr'])) {
        setcookie("msc_usr", hash('md5', time() . get_bloginfo('name'), FALSE), time() + 60 * 60 * 24 * 360, COOKIEPATH, COOKIE_DOMAIN);  //360 days                
    }    
    $show_player = get_option('msc_player', 'nothing');
    if ($show_player !== 'nothing') {
        function show_player() {
            get_player();
        }
        add_action('wp_footer', 'show_player');
    }
}

//register CSS
function msc_register_init() {
    wp_enqueue_style('msc-automat', plugins_url('/css/style.css', __FILE__));
    wp_enqueue_script('font-awesome', 'https://kit.fontawesome.com/833b9cc7e9.js');
}

add_action('init', 'msc_register_init');

//Register javascript
function msc_scrip_refresh() {
    if (is_single() || is_page()) {
        //if ( ! wp_script_is( 'jquery', 'enqueued' )) {
        if (!jQuery) {
            wp_register_script('msc-jquery', 'http://code.jquery.com/jquery-latest.min.js');
            wp_enqueue_script('msc-jquery');
            //Enqueue
            //wp_enqueue_script( 'jquery' );                
        }
        wp_enqueue_script('script_treeview', MSC_JQUERY_URL . 'msc_js.js');
    }
}

add_action('wp_enqueue_scripts', 'msc_scrip_refresh');

//Control de sessió
add_action('init', 'msc_start_session', 1);
add_action('wp_logout', 'msc_end_session');
add_action('wp_login', 'msc_end_session');

function msc_start_session() {
    if (!session_id()) {
        session_start();
    }
}

function msc_end_session() {
    session_destroy();
}


include_once 'widjets.php';

/* Add template for iframe */
include_once 'templates.php';

/* Ajaxify WordPress Site */
include_once 'ajaxify.php';