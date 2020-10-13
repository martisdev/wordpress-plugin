<?php    
    define('MSCRA_DIR', dirname(plugin_basename( __FILE__ )));    
    define('MSCRA_PLUGIN_URL', plugin_dir_url(__FILE__) );    
    define('MSCRA_PLUGIN_DIR', plugin_dir_path( __FILE__ ));
    
    define('MSCRA_WP_SNIPPETS_DIR' , MSCRA_PLUGIN_DIR.'wp-snippets/');       
    define('MSCRA_WP_SNIPPETS_URL' , MSCRA_PLUGIN_URL.'wp-snippets/');       
    define('MSCRA_CSS_URL',MSCRA_PLUGIN_URL.'css/' );
    define('MSCRA_JQUERY_URL',MSCRA_PLUGIN_URL.'jquery/' );
    define('MSCRA_NAME_TEMPLATE_IFRAME','template-iframe.php' );    
    
    include_once MSCRA_PLUGIN_DIR.'/inc/defines.php';
    $upload_dir = wp_upload_dir();
    define('MSCRA_DIR_TEMP_IMAGE', $upload_dir['basedir'].'/'.WP_MSCRA_TMP_IMG_DIR );
    define('MSC_URL_TEMP_IMAGE', $upload_dir['baseurl'].'/'.WP_MSCRA_TMP_IMG_DIR );
    
    include_once MSCRA_PLUGIN_DIR.'/inc/my_radio.php';        
    include_once(MSCRA_PLUGIN_DIR.'functions.php' ); 
    include_once(MSCRA_PLUGIN_DIR.'msc-install.php' );    
    include_once MSCRA_PLUGIN_DIR.'/inc/utils.php'; 
    include_once MSCRA_PLUGIN_DIR.'/inc/facebook.php'; 
    include_once MSCRA_PLUGIN_DIR.'/inc/twitter.php';     
    //include_once 'widgets.php';    
    include_once 'templates.php';    
    include_once 'ajaxify.php';