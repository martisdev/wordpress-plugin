<?php
/*
  Plugin Name:    MSC Radio Automation
  Plugin URI:     https://msc-soft.com/plugin-wordpress/
  Description:    Radio Automation Software | The radio on cloud. This plugin syncronize your radio station with your web page.
  Version:        2.1
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




// Creating the widget on air
class widget_onair extends WP_Widget {

    function __construct() {
        parent::__construct(
                // Base ID of your widget
                'widget_onair',
                // Widget name will appear in UI
                __('Now Playing', 'msc-automation'),
                // Widget description
                array('description' => __('Shows information about the current song', 'msc-automation'),)
        );
    }

    // Creating widget front-end
    // This is where the action happens
    public function widget($args, $instance) {
        $title = apply_filters('widget_title', $instance['title']);
        // before and after widget arguments are defined by themes
        echo $args['before_widget'];
        if (!empty($title))
            echo $args['before_title'] . $title . $args['after_title'];
        // This is where you run the code and display the output        
        $args['image_w'] = TRUE;
        $args['img_width_w'] = 100;
        echo get_now_playing_widget($args);
        echo $args['after_widget'];
    }

    // Widget Backend 
    public function form($instance) {
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = __('Now playing...', 'msc-automation');
        }
        // Widget admin form
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'msc-automation'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <?php
    }

    // Updating widget replacing old instances with new
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title']) ) ? strip_tags($new_instance['title']) : '';
        return $instance;
    }

}

// Class widget_onair ends here
// Creating the widget on air
class widget_powerby extends WP_Widget {

    function __construct() {
        parent::__construct(
                // Base ID of your widget
                'widget_powerby',
                // Widget name will appear in UI
                __('Powered by MSC Radio Automation', 'msc-automation'),
                // Widget description
                array('description' => __('Show a link to developer web (thanks for put in your footer zone)', 'msc-automation'),)
        );
    }

    // Creating widget front-end
    // This is where the action happens
    public function widget($args, $instance) {
        $title = apply_filters('widget_title', $instance['title']);
        // before and after widget arguments are defined by themes
        echo $args['before_widget'];
        if (!empty($title))
            echo $args['before_title'] . $title . $args['after_title'];
        // This is where you run the code and display the output        
        ?> 
        <div class="textwidget">
            <p><a data-mce-href="<?php _e('https://msc-soft.com/', 'msc-automation'); ?>" 
                  href="<?php _e('https://msc-soft.com/', 'msc-automation'); ?>" 
                  target="_blank" 
                  rel="noopener noreferrer"><?php _e('MSC Radio Automation', 'msc-automation'); ?></a>
                <br data-mce-bogus="1">
            </p>
        </div>       
        <?php
    }

    // Widget Backend 
    public function form($instance) {
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = __('Powered by', 'msc-automation');
        }
        // Widget admin form
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __('Title', 'msc-automation') . ':'; ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <?php
    }

}

// Class widget_powerby ends here
// Register and load the widget
function wpb_load_widget() {
    register_widget('widget_onair');
    register_widget('widget_powerby');
}

add_action('widgets_init', 'wpb_load_widget');


/* Ajaxify WordPress Site */

/**
 * 	Function name: aws_load_scripts
 * 	Description: Loading the required js files and assing required php variable to js variable.
 */
function aws_load_scripts() {
    if (get_option('msc_enable_aws') == 'true') {
        //Check whether the core jqury library enqued or not. If not enqued the enque this
        if (!wp_script_is('jquery')) {
            wp_enqueue_script('jquery');
        }
        wp_enqueue_script('history-js', MSC_PLUGIN_URL . 'jquery/ajaxify/history.js', array('jquery'));
        wp_enqueue_script('ajaxify-js', MSC_PLUGIN_URL . 'jquery/ajaxify/ajaxify.js', array('jquery'));

        $ids_arr = explode(',', get_option('msc_no-ajax-ids'));
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
            'container_id' => get_option('msc_container-id'),
            'mcdc' => get_option('msc_mcdc'),
            'searchID' => get_option('msc_search-form'),
            'transition' => get_option('msc_transition'),
            'scrollTop' => get_option('msc_scrollTop'),
            'loader' => get_option('msc_loader'),
            'bp_status' => $bp_status
        );

        wp_localize_script('ajaxify-js', 'aws_data', $aws_data);
    }
}

// End of aws_load_scripts function
//calling aws_load_scripts function to load js files
add_action('wp_enqueue_scripts', 'aws_load_scripts');


/* Add template for iframe */

class PageTemplater {

    /**
     * A reference to an instance of this class.
     */
    private static $instance;

    /**
     * The array of templates that this plugin tracks.
     */
    protected $templates;

    /**
     * Returns an instance of this class. 
     */
    public static function get_instance() {

        if (null == self::$instance) {
            self::$instance = new PageTemplater();
        }

        return self::$instance;
    }

    /**
     * Initializes the plugin by setting filters and administration functions.
     */
    private function __construct() {

        $this->templates = array();


        // Add a filter to the attributes metabox to inject template into the cache.
        if (version_compare(floatval(get_bloginfo('version')), '4.7', '<')) {

            // 4.6 and older
            add_filter(
                    'page_attributes_dropdown_pages_args', array($this, 'register_project_templates')
            );
        } else {

            // Add a filter to the wp 4.7 version attributes metabox
            add_filter(
                    'theme_page_templates', array($this, 'add_new_template')
            );
        }

        // Add a filter to the save post to inject out template into the page cache
        add_filter(
                'wp_insert_post_data', array($this, 'register_project_templates')
        );


        // Add a filter to the template include to determine if the page has our 
        // template assigned and return it's path
        add_filter(
                'template_include', array($this, 'view_project_template')
        );


        // Add your templates to this array.
        $this->templates = array(
            NAME_TEMPLATE_IFRAME => 'Template for bare content',
        );
    }

    /**
     * Adds our template to the page dropdown for v4.7+
     *
     */
    public function add_new_template($posts_templates) {
        $posts_templates = array_merge($posts_templates, $this->templates);
        return $posts_templates;
    }

    /**
     * Adds our template to the pages cache in order to trick WordPress
     * into thinking the template file exists where it doens't really exist.
     */
    public function register_project_templates($atts) {

        // Create the key used for the themes cache
        $cache_key = 'page_templates-' . md5(get_theme_root() . '/' . get_stylesheet());

        // Retrieve the cache list. 
        // If it doesn't exist, or it's empty prepare an array
        $templates = wp_get_theme()->get_page_templates();
        if (empty($templates)) {
            $templates = array();
        }

        // New cache, therefore remove the old one
        wp_cache_delete($cache_key, 'themes');

        // Now add our template to the list of templates by merging our templates
        // with the existing templates array from the cache.
        $templates = array_merge($templates, $this->templates);

        // Add the modified cache to allow WordPress to pick it up for listing
        // available templates
        wp_cache_add($cache_key, $templates, 'themes', 1800);

        return $atts;
    }

    /**
     * Checks if the template is assigned to the page
     */
    public function view_project_template($template) {

        // Get global post
        global $post;

        // Return template if post is empty
        if (!$post) {
            return $template;
        }

        // Return default template if we don't have a custom one defined
        if (!isset($this->templates[get_post_meta(
                                $post->ID, '_wp_page_template', true
                )])) {
            return $template;
        }

        $file = plugin_dir_path(__FILE__) . get_post_meta(
                        $post->ID, '_wp_page_template', true
        );

        // Just to be safe, we check if the file exist first
        if (file_exists($file)) {
            return $file;
        } else {
            echo $file;
        }

        // Return template
        return $template;
    }

}
