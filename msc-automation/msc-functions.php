<?php
    
include_once MSC_PLUGIN_DIR.'/inc/shortcode/shortcode-player.php';               
include_once MSC_PLUGIN_DIR.'/inc/shortcode/shortcode-programs.php';
include_once MSC_PLUGIN_DIR.'/inc/shortcode/shortcode-podcast.php';
include_once MSC_PLUGIN_DIR.'/inc/shortcode/shortcode-music.php';
include_once MSC_PLUGIN_DIR.'/inc/shortcode/shortcode-calendar.php';
include_once MSC_PLUGIN_DIR.'/inc/shortcode/shortcode-general.php';   
include_once MSC_PLUGIN_DIR.'/inc/shortcode/shortcode-advertising.php';   
include_once MSC_PLUGIN_DIR.'/inc/shortcode/shortcode-socialmedia.php';   

        
function my_add_custom_fields($post_id){
    if ( $_POST['post_type'] == 'post' ) {
        $prg_id =  $_POST['_msc_hook_id'];
        add_post_meta($post_id, '_msc_hook_id', $prg_id, true);
    }
    return true;
}   

add_action('wp_insert_post', 'my_add_custom_fields');
 /**
 * This function adds a meta box with a callback function of my_metabox_callback()
 */
function add_wpdocs_meta_box() {
    add_meta_box(
        '_msc_hook',
        __( 'Hook relation', 'msc-automation' ),
        'wpdocs_metabox_callback',
        'page',
        'normal',
        'low'         
    );
}

add_action( 'add_meta_boxes', 'add_wpdocs_meta_box');
/**
 * Get post meta in a callback
 *
 * @param WP_Post $post    The current post.
 * @param array   $metabox With metabox id, title, callback, and args elements.
 */
 
function wpdocs_metabox_callback( $post ) {
    // Output last time the post was modified.
    //echo 'Last Modified: ' . $post->post_modified; 
    $meta = get_post_meta( $post->ID, '_msc_hook_id', true );
 ?>
    <label><?php _e('Value','msc-automation'); ?></label>
        <input type="text" name="_msc_hook_id" 
            value="<?php echo esc_attr($meta); ?>"/>
<?php
    // Output value of custom field.
    //echo get_post_meta( $post->ID, '_msc_hook_id', true );
}

/**
 * On post save, save plugin's data
 */
function blc_save_postdata($post_id){
    
      // Verify this came from the our screen and with proper authorization,
      // because save_post can be triggered at other times
      /*if ( !wp_verify_nonce( $_POST['blc_noncename'], plugin_basename(__FILE__) )) {
        return $post_id;
      }*/

      // Verify if this is an auto save routine. If it is our form has not been submitted, so we dont want
      // to do anything
      if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
          return $post_id;
      }
        
      // Check permissions to edit pages and/or posts
      if ( 'page' == $_POST['post_type'] ||  'post' == $_POST['post_type']) {
        if ( !current_user_can( 'edit_page', $post_id ) || !current_user_can( 'edit_post', $post_id )){
            return $post_id;
        }          
      } 

      // OK, we're authenticated: we need to find and save the data
      $blc =  $_POST['_msc_hook_id'];
      
      // save data in INVISIBLE custom field (note the "_" prefixing the custom fields' name
      update_post_meta($post_id, '_msc_hook_id', $blc); 

    }

add_action('save_post',  'blc_save_postdata');     
  

function my_theme_hide_admin_bar($bool) {
    if ( is_page_template( NAME_TEMPLATE_IFRAME ) ) :
      return false;
    else :
      return $bool;
    endif;
}
add_filter('show_admin_bar', 'my_theme_hide_admin_bar');

add_action('plugins_loaded', array('PageTemplater', 'get_instance'));

//TODO: for page TRACK, SONG, ALBUM or STREAMING
//https://francescocarlucci.com/seo/wordpress-open-graph-meta-without-plugin/
//add_action('wp_head', 'fc_opengraph');


add_filter( 'language_attributes', 'html_prefix' );
function html_prefix( $atts ) {
    $prefix = 'prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#"';
    $atts  .= ' ' . $prefix;
    return $atts;
}


function fc_opengraph() {

    if (is_single() || is_page()) {

        $post_id = get_queried_object_id();

        $url = get_permalink($post_id);
        $title = get_the_title($post_id);
        $site_name = get_bloginfo('name');

        $description = wp_trim_words(get_post_field('post_content', $post_id), 25);

        $image = get_the_post_thumbnail_url($post_id);
        if (!empty(get_post_meta($post_id, 'og_image', true)))
            $image = get_post_meta($post_id, 'og_image', true);

        $locale = get_locale();

        echo '<meta property="og:locale" content="' . esc_attr($locale) . '" />';
        echo '<meta property="og:type" content="article" />';
        echo '<meta property="og:title" content="' . esc_attr($title) . ' | ' . esc_attr($site_name) . '" />';
        echo '<meta property="og:description" content="' . esc_attr($description) . '" />';
        echo '<meta property="og:url" content="' . esc_url($url) . '" />';
        echo '<meta property="og:site_name" content="' . esc_attr($site_name) . '" />';

        if ($image)
            echo '<meta property="og:image" content="' . esc_url($image) . '" />';

        // Twitter Card
        /* echo '<meta name="twitter:card" content="summary_large_image" />';
          echo '<meta name="twitter:site" content="@francecarlucci" />';
          echo '<meta name="twitter:creator" content="@francecarlucci" />'; */
    }
}