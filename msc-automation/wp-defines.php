<?php

    define('MSC_PLUGIN_VERSION', '2.0.0' );
    define('MSC_DIR', 'msc-automation');
    define('MSC_PLUGIN_URL', plugins_url().'/'.MSC_DIR.'/' );
    define('MSC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
    define('WP_SNIPPETS_DIR' , MSC_PLUGIN_DIR.'wp-snippets/');       
    define('WP_SNIPPETS_URL' , MSC_PLUGIN_URL.'wp-snippets/');   
    define('MSC_FUNCTIONS_URL',MSC_PLUGIN_URL.'shortcode/' );
    define('MSC_CSS_URL',MSC_PLUGIN_URL.'css/' );
    define('MSC_JQUERY_URL',MSC_PLUGIN_URL.'jquery/' );
    define('NAME_TEMPLATE_IFRAME','template-iframe.php' );
    define('NAME_PAGE_TRACK','track' );
    define('NAME_PAGE_SONG','song' );
    define('NAME_PAGE_ALBUM','album' );

    
    include_once MSC_PLUGIN_DIR.'/inc/defines.php';
    $upload_dir = wp_upload_dir();
    define('DIR_TEMP_IMAGE', $upload_dir['basedir'].'/'.TMP_IMG_DIR );
    define('URL_TEMP_IMAGE', $upload_dir['baseurl'].'/'.TMP_IMG_DIR );
    
    include_once MSC_PLUGIN_DIR.'/inc/my_radio.php';        
    require_once(MSC_PLUGIN_DIR.'msc-functions.php' ); 
    require_once(MSC_PLUGIN_DIR.'msc-install.php' ); 
    
    include_once MSC_PLUGIN_DIR.'/inc/utils.php'; 
    include_once MSC_PLUGIN_DIR.'/inc/facebook.php'; 
    include_once MSC_PLUGIN_DIR.'/inc/twitter.php';     
    