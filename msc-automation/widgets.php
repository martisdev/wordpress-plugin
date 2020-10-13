<?php

// Creating the widget on air
class mscra_widget_onair extends WP_Widget {

    function __construct() {
        parent::__construct(
                // Base ID of your widget
                'widget_onair',
                // Widget name will appear in UI
                __('Now Playing', 'mscra-automation'),
                // Widget description
                array('description' => __('Shows information about the current song', 'mscra-automation'),)
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
        echo mscra_get_now_playing_widget($args);
        echo $args['after_widget'];
    }

    // Widget Backend 
    public function form($instance) {
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = __('Now playing...', 'mscra-automation');
        }
        // Widget admin form
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'mscra-automation'); ?></label> 
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

function mscra_get_now_playing_widget($attributes) 
{
    if (is_admin()) {
        return;
    }
    $image = (isset($attributes['image_w'])) ? $attributes['image_w'] : FALSE;
    $img_width = (isset($attributes['img_width_w'])) ? $attributes['img_width_w'] : 200;
    
    include MSCRA_PLUGIN_DIR.'connect_api.php';
    
    $_SESSION['image_w'] = $image;
    $upload_dir = wp_upload_dir();
    $_SESSION['upload_dir'] = $upload_dir;
    $_SESSION['img_width_w'] = $img_width;


    $doc_refresh = 'info_song_widget.php';
    ?>
    <div id="dom-source" style="display: none;"><?php echo MSCRA_WP_SNIPPETS_URL . $doc_refresh; ?></div>
    <div id="dom-div" style="display: none;"><?php echo '#refresh-widget'; ?></div>
    <?php
    $file_js = MSCRA_JQUERY_URL . 'refresh_now_playing_widget.js';
    wp_enqueue_script('handle-now_playing_widget', $file_js, array('jquery'), '1.0.0', true);
    $params = array(
        'nom_div' => '#refresh-widget',
        'time' => 15000,
        'source' => $file_js
    );
    wp_localize_script('handle-list_radia', 'Params_refresh', $params);
    ?>        
    <div id="refresh-widget">
        <?php include ( MSCRA_WP_SNIPPETS_DIR . $doc_refresh); ?>
    </div>      

    <?php
}


class mscra_widget_powerby extends WP_Widget {

    function __construct() {
        parent::__construct(
                // Base ID of your widget
                'widget_powerby',
                // Widget name will appear in UI
                __('Powered by MSC Radio Automation', 'mscra-automation'),
                // Widget description
                array('description' => __('Show a link to developer web (thanks for put in your footer zone)', 'mscra-automation'),)
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
            <p><a data-mce-href="<?php _e('https://msc-soft.com/', 'mscra-automation'); ?>" 
                  href="<?php _e('https://msc-soft.com/', 'mscra-automation'); ?>" 
                  target="_blank" 
                  rel="noopener noreferrer"><?php _e('MSC Radio Automation', 'mscra-automation'); ?></a>
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
            $title = __('Powered by', 'mscra-automation');
        }
        // Widget admin form
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __('Title', 'mscra-automation') . ':'; ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <?php
    }

}


// Class widget_powerby ends here
// Register and load the widget
function mscra_load_widget() {
    register_widget('mscra_widget_onair');
    register_widget('mscra_widget_powerby');
}

add_action('widgets_init', 'mscra_load_widget');