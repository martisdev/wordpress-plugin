<?php

// Creating the widget on air
class mscra_widget_onair extends WP_Widget
{

    public function __construct()
    {
        parent::__construct(
            // Base ID of your widget
            'widget_onair',
            // Widget name will appear in UI
            __('Now Playing', 'mscra-automation'),
            // Widget description
            array('description' => __('Shows information about the current song', 'mscra-automation'))
        );
    }

    // Creating widget front-end
    // This is where the action happens
    public function widget($args, $instance)
    {
        $title = apply_filters('widget_title', $instance['title']);
        // before and after widget arguments are defined by themes
        echo $args['before_widget'];
        if (!empty($title)) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        // This is where you run the code and display the output
        $args['image_w'] = true;
        $args['img_width_w'] = 100;
        echo mscra_get_now_playing_widget($args);
        echo $args['after_widget'];
    }

    // Widget Backend
    public function form($instance)
    {
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = __('Now playing...', 'mscra-automation');
        }
        // Widget admin form
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'mscra-automation');?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <?php
}

    // Updating widget replacing old instances with new
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        return $instance;
    }

}

function mscra_get_now_playing_widget($attributes)
{
    if (is_admin()) {
        return;
    }
    $image = (isset($attributes['image_w'])) ? $attributes['image_w'] : false;
    $img_width = (isset($attributes['img_width_w'])) ? $attributes['img_width_w'] : 200;

    $file_js = MSCRA_JQUERY_URL . 'refresh_now_playing_widget.js';
    wp_enqueue_script('msc-refresh-widget', $file_js, array('jquery'), '1.0.0', true);
    
    include MSCRA_PLUGIN_DIR . 'connect_api.php';

    $Vars[0] = 'rows=1';
    $Vars[1] = 'image=1';
    $list = $MyRadio->QueryGetTable(seccions::MUSIC, sub_seccions::LISTRADIA, $Vars);
    
    if ($MyRadio->RESPOSTA_ROWS > 0) {
        $counter = 0;
        //$strReturn = '<div id ="refresh-widget" style="width:' . $img_width . 'px;"">';
        $strReturn = '<div id ="refresh-widget">';
        while ($counter < $MyRadio->RESPOSTA_ROWS):
            $StrEcho = '';
            if ($image == true) {
                $upload_dir = wp_upload_dir(); 
                $PathToSaveImg = $upload_dir['basedir'] . '/' . WP_MSCRA_TMP_IMG_DIR . '/disc_img-' . $list['track']['ID'] . '.jpg';
                $PathToShowImg = $upload_dir['baseurl'] . '/' . WP_MSCRA_TMP_IMG_DIR . '/disc_img-' . $list['track']['ID'] . '.jpg';
                if (mscra_getImage(base64_decode($list['track']['IMAGE']), $PathToSaveImg, $img_width) == true) {
                    $StrEcho .= '<img id="img-refresh" src=' . $PathToShowImg . '>';
                }
            }
            $StrEcho .= '<div id="artist-refresh">' . $list['track']['INTERP'] . '</div>';
            $StrEcho .= '<div id="song-refresh">' . $list['track']['TITLE'] . '</div>';

            $counter = $counter + 1;
            $strReturn .= $StrEcho;
        endwhile;
        $strReturn .= '</div>';
    }    
    return $strReturn;
}

class mscra_widget_powerby extends WP_Widget
{

    public function __construct()
    {
        parent::__construct(
            // Base ID of your widget
            'widget_powerby',
            // Widget name will appear in UI
            __('Powered by MSC Radio Automation', 'mscra-automation'),
            // Widget description
            array('description' => __('Show a link to developer web (thanks for put in your footer zone)', 'mscra-automation'))
        );
    }

    // Creating widget front-end
    // This is where the action happens
    public function widget($args, $instance)
    {
        $title = apply_filters('widget_title', $instance['title']);
        // before and after widget arguments are defined by themes
        echo $args['before_widget'];
        if (!empty($title)) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        // This is where you run the code and display the output
        ?>
        <div class="textwidget">
            <p><a data-mce-href="<?php _e('https://msc-soft.com/', 'mscra-automation');?>"
                  href="<?php _e('https://msc-soft.com/', 'mscra-automation');?>"
                  target="_blank"
                  rel="noopener noreferrer"><?php _e('MSC Radio Automation', 'mscra-automation');?></a>
                <br data-mce-bogus="1">
            </p>
        </div>
        <?php
}

    // Widget Backend
    public function form($instance)
    {
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
function mscra_load_widget()
{
    register_widget('mscra_widget_onair');
    register_widget('mscra_widget_powerby');
}

add_action('widgets_init', 'mscra_load_widget');
