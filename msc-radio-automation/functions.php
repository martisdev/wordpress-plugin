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
include_once MSCRA_PLUGIN_DIR . '/shortcode/shortcode-cloud.php';

/**
 * This function adds a meta box with a callback function of my_metabox_callback()
 */
function mscra_add_wpdocs_meta_box()
{
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
function mscra_metabox_callback($post)
{
    // Output last time the post was modified.
    $meta = get_post_meta($post->ID, '_msc_hook_id', true);
    ?>
    <label><?php _e('Value', 'mscra-automation');?></label>
    <input type="text" name="_msc_hook_id" value="<?php echo esc_attr($meta); ?>"/>
    <?php
// Output value of custom field.
}

/**
 * Insert meta plugin in new post
 */
function mscra_add_custom_fields($post_id)
{
    if (isset($_POST['post_type'])) {
        $pt = sanitize_text_field($_POST['post_type']);
        if ($pt == 'post') {
            $prg_id = sanitize_text_field($_POST['_msc_hook_id']);
            add_post_meta($post_id, '_msc_hook_id', $prg_id, true);
        }
        return true;
    } else {
        return false;
    }

}

add_action('wp_insert_post', 'mscra_add_custom_fields');

/**
 * On post save, save plugin's data
 */
function mscra_save_postdata($post_id)
{

    /* Check if our nonce is set.*/
    if (!isset($_POST["_msc_hook_id"])) {
        return;
    }

    /* Verify that the nonce is valid.*/
    // Verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if (!wp_verify_nonce($_POST['_msc_hook_id'], plugin_basename(__FILE__))) {
        return;
    }
    /* If this is an autosave, our form has not been submitted, so we don't want to do anything.*/
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    // Check permissions to edit pages and/or posts
    if ('page' == $_POST['post_type'] || 'post' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id) || !current_user_can('edit_post', $post_id)) {
            return;
        }
    }

    // OK, we're authenticated: we need to find and save the data
    $meta = isset($_POST["_msc_hook_id"]) ? sanitize_text_field($_POST["_msc_hook_id"]) : '-1';

    // save data in INVISIBLE custom field (note the "_" prefixing the custom fields' name
    update_post_meta($post_id, '_msc_hook_id', $meta);
}

add_action('save_post', 'mscra_save_postdata');

function mscra_theme_hide_admin_bar($bool)
{
    if (is_page_template(MSCRA_NAME_TEMPLATE_IFRAME)):
        return false;
    else:
        return $bool;
    endif;
}

add_filter('show_admin_bar', 'mscra_theme_hide_admin_bar');

// Cookies alert
function mscra_cookie_script()
{
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

function mscra_get_page_by_meta($meta_value)
{
    $args = array(
        'post_type' => 'page',
        'posts_per_page' => '100',
        'post_status' => 'publish',
        'meta_query' => array(
            array(
                'key' => '_msc_hook_id',
                'value' => $meta_value,
                'compare' => '=',
            ),
        ),
    );

    $myposts = get_posts($args);
    foreach ($myposts as $post) {
        // return the first value
        return ($post);
    }
}

function mscra_add_query_vars_filter($vars)
{
    //mscra_get_search_music
    $vars[] = 'hp';
    return $vars;
}

//Add custom query vars
add_filter('query_vars', 'mscra_add_query_vars_filter');

// Send the file to download
function mscra_send_download_file()
{
    //get filedata
    $theFile = $_GET['fileurl'];

    if (!$theFile) {
        return;
    }
    $id = $_GET['id'];
    if (!is_numeric($id)) {
        return;
    }

    include MSCRA_PLUGIN_DIR . 'connect_api.php';
    $Vars[0] = 'id=' . $id;
    $MyRadio->QueryGetTable(seccions::PODCAST, sub_seccions::DOWNLOAD, $Vars);

    //clean the fileurl
    $file_url = stripslashes(trim($theFile));
    //get filename
    $file_name = basename($theFile);
    //get fileextension
    $file_extension = pathinfo($file_name);
    //security check
    $fileName = strtolower($file_url);

    $whitelist = array('mp3');

    if (!in_array(end(explode('.', $fileName)), $whitelist)) {
        exit('Invalid file!');
    }
    if (strpos($file_url, '.php') == true) {
        die("Invalid file!");
    }

    $file_new_name = $file_name;
    $content_type = "";
    //check filetype
    switch ($file_extension['extension']) {
        case "png":
            $content_type = "image/png";
            break;
        case "gif":
            $content_type = "image/gif";
            break;
        case "tiff":
            $content_type = "image/tiff";
            break;
        case 'mp3':
            $content_type = 'audio/mpeg';
            break;
        case "jpeg":
        case "jpg":
            $content_type = "image/jpg";
            break;
        default:
            $content_type = "application/force-download";
    }

    $content_type = apply_filters("ibenic_content_type", $content_type, $file_extension['extension']);

    header("Expires: 0");
    header("Cache-Control: no-cache, no-store, must-revalidate");
    header('Cache-Control: pre-check=0, post-check=0, max-age=0', false);
    header("Pragma: no-cache");
    header("Content-type: {$content_type}");
    header("Content-Disposition:attachment; filename={$file_new_name}");
    header("Content-Type: application/force-download");

    readfile("{$file_url}");
    exit();
}

// Start the download if there is a request for that
function mscra_download_file()
{
    if (isset($_GET['download_file'])) {
        mscra_send_download_file();
    }
}
add_action('init', 'mscra_download_file');