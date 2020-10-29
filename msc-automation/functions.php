<?php
include_once MSCRA_PLUGIN_DIR . '/shortcode/shortcode-player.php';
include_once MSCRA_PLUGIN_DIR . '/shortcode/shortcode-programs.php';
include_once MSCRA_PLUGIN_DIR . '/shortcode/shortcode-podcast.php';
include_once MSCRA_PLUGIN_DIR . '/shortcode/shortcode-music.php';
include_once MSCRA_PLUGIN_DIR . '/shortcode/shortcode-calendar.php';
include_once MSCRA_PLUGIN_DIR . '/shortcode/shortcode-general.php';
include_once MSCRA_PLUGIN_DIR . '/shortcode/shortcode-advertising.php';
include_once MSCRA_PLUGIN_DIR . '/shortcode/shortcode-socialmedia.php';
include_once MSCRA_PLUGIN_DIR . '/shortcode/shortcode-utils.php';



/**
 * This function adds a meta box with a callback function of my_metabox_callback()
 */
function mscra_add_wpdocs_meta_box() {
    add_meta_box(
            'msc_hook',
            __('Hook relation', 'mscra-automation'),
            'mscra_metabox_callback',
            'page',
            'normal',
            'low'
    );
}

add_action('add_meta_boxes', 'mscra_add_wpdocs_meta_box');

/**
 * Get post meta in a callback
 *
 * @param WP_Post $post The current post.
 */
function mscra_metabox_callback($post) {
    // Output last time the post was modified.    
    $meta = get_post_meta($post->ID, '_msc_hook_id', true);
    ?>
    <label><?php _e('Value', 'mscra-automation'); ?></label>
    <input type="text" name="_msc_hook_id" value="<?php echo esc_attr($meta); ?>"/>
    <?php
    // Output value of custom field.    
}

/**
 * Insert meta plugin in new post
 */
function mscra_add_custom_fields($post_id) {
    if (isset($_POST['post_type'])){
        $pt = sanitize_text_field($_POST['post_type']);
        if ( $pt == 'post') {
            $prg_id = sanitize_text_field($_POST['_msc_hook_id']);
            add_post_meta($post_id, '_msc_hook_id', $prg_id, true);
        }
        return true;
    }else{
        return false;
    }
    
}

add_action('wp_insert_post', 'mscra_add_custom_fields');


/**
 * On post save, save plugin's data
 */
function mscra_save_postdata($post_id) {

    /* Check if our nonce is set.*/
    if(!isset($_POST["_msc_hook_id"]) ){
        return;
    }

    /* Verify that the nonce is valid.*/
    // Verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times             
    if ( !wp_verify_nonce( $_POST['_msc_hook_id'], plugin_basename(__FILE__) )) {         
        return ;        
    }
    /* If this is an autosave, our form has not been submitted, so we don't want to do anything.*/    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {        
        return ;
    }
    // Check permissions to edit pages and/or posts
    if ('page' == $_POST['post_type'] || 'post' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id) || !current_user_can('edit_post', $post_id)) {
            return ;
        }
    }
    
    // OK, we're authenticated: we need to find and save the data
    $meta = isset($_POST["_msc_hook_id"] ) ? sanitize_text_field($_POST["_msc_hook_id"])  : '-1';
    
    // save data in INVISIBLE custom field (note the "_" prefixing the custom fields' name
    update_post_meta($post_id, '_msc_hook_id', $meta);
}

add_action('save_post', 'mscra_save_postdata');

function mscra_theme_hide_admin_bar($bool) {
    if (is_page_template(MSCRA_NAME_TEMPLATE_IFRAME)) :
        return false;
    else :
        return $bool;
    endif;
}

add_filter('show_admin_bar', 'mscra_theme_hide_admin_bar');

// Cookies alert 
function mscra_cookie_script() {
    $str_OK = __('OK', 'mscra-automation');
    $str_message = __('If you continue browsing it means that you accept cookies', 'mscra-automation');
    $str_moreinfo = __('More info', 'mscra-automation');
    $str_link = get_privacy_policy_url();
    wp_enqueue_script('msc_cookieconsent', '//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/1.0.10/cookieconsent.min.js"');
    ?>
    <script type="text/javascript">
        window.cookieconsent_options = {"message": "<?php echo $str_message ?>", "dismiss": "<?php echo $str_OK ?>", "learnMore": "<?php echo $str_moreinfo ?>", "link": "<?php echo $str_link ?>", "theme": "dark-bottom"};
    </script>
    <?php
}

add_action('wp_head', 'mscra_cookie_script');

function mscra_get_page_by_meta($meta_value) {
    $args = array(
        'post_type' => 'page',
        'posts_per_page' => '100',
        'post_status' => 'publish',
        'meta_query' => array(
            array(
                'key' => '_msc_hook_id',
                    'value' => $meta_value,
                    'compare' => '='
            )            
        )
    );

   $myposts = get_posts( $args );
   foreach ( $myposts as $post ) {
       // return the first value
       return( $post );
   }    
}

function mscra_add_query_vars_filter( $vars ){
    //mscra_get_search_music
    $vars[] = 'hp';    
    return $vars;
}

//Add custom query vars
add_filter( 'query_vars', 'mscra_add_query_vars_filter' );