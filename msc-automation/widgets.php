<?php

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