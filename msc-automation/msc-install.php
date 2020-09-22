<?php

$up_dir = wp_upload_dir();
// Create a folder in the Uploads Directory of WordPress to store your files
if (!file_exists($up_dir['basedir'] . '/' . TMP_IMG_DIR)) {
    mkdir($up_dir['basedir'] . '/' . TMP_IMG_DIR, 0775, true);
}

// Create a folder in the Uploads Directory of WordPress to store your files
if (!file_exists($up_dir['basedir'] . '/' . PODCAST_DIR)) {
    mkdir($up_dir['basedir'] . '/' . PODCAST_DIR, 0775, true);
}

/* Ini and reset pluging */

function ini_all_action() {
    $post_author = get_current_user_id();
    create_basic_mnu($post_author);
    create_footer_mnu();

    // create page song for iframe               
    $song_page = array(
        'post_type' => 'page',
        'post_title' => __('song', 'msc-automation'),
        'post_status' => 'publish',
        'post_content' => '[detail_song]',
        'post_author' => $post_author,
        //'post_slug' => 'site-map'
        // Assign page template
        'page_template' => NAME_TEMPLATE_IFRAME
    );
    $song_page_check = get_page_by_title(__('song', 'msc-automation'));
    if (!isset($song_page_check->ID)) {
        $song_page_id = wp_insert_post($song_page);
    }

    // create page album for iframe           
    $album_page = array(
        'post_type' => 'page',
        'post_title' => __('album', 'msc-automation'),
        'post_status' => 'publish',
        'post_content' => '[detail_album]',
        'post_author' => $post_author,
        //'post_slug' => 'site-map'
        // Assign page template
        'page_template' => NAME_TEMPLATE_IFRAME
    );
    $album_page_check = get_page_by_title(__('album', 'msc-automation'));
    if (!isset($album_page_check->ID)) {
        $album_page_id = wp_insert_post($album_page);
    }

    // create page track for iframe             
    $track_page = array(
        'post_type' => 'page',
        'post_title' => __('track', 'msc-automation'),
        'post_status' => 'publish',
        'post_content' => '[detail_track]',
        'post_author' => $post_author,
        //'post_slug' => 'site-map'
        // Assign page template
        'page_template' => NAME_TEMPLATE_IFRAME
    );
    $track_page_check = get_page_by_title(__('track', 'msc-automation'));
    if (!isset($track_page_check->ID)) {
        $track_page_id = wp_insert_post($track_page);
    }
    
    // create page player streaming for iframe             
    $player_page = array(
        'post_type' => 'page',
        'post_title' => __('Player Stream', 'msc-automation'),
        'post_status' => 'publish',
        'post_content' => '[iframe_player_streaming]',
        'post_author' => $post_author,
        //'post_slug' => 'site-map'
        // Assign page template
        'page_template' => NAME_TEMPLATE_IFRAME
    );
    $player_page_check = get_page_by_title(__('Player Stream', 'msc-automation'));
    if (!isset($player_page_check->ID)) {
        $player_page_id = wp_insert_post($player_page);
    }
    
    update_option('msc_initialize', 'true');

    echo '<div id="message" class="updated fade"><p>'
    . __('<b>Successfully created the selected options</b><br> Once the initialization is done, go to the menus section. Select the menu "MSC Main" and check the box "Primary Menu" to view it.', 'msc-automation') . '</p></div>';
}

function create_basic_mnu($post_author) {
    $nav_item = wp_create_nav_menu(__('MSC Main', 'msc-automation'));
    // mnu HOME
    if ($_POST['create_home'] == 'true') {
        wp_update_nav_menu_item($nav_item, 0, array(
            'menu-item-title' => __('Home', 'msc-automation'),
            'menu-item-classes' => 'home',
            'menu-item-url' => home_url('/'),
            'menu-item-status' => 'publish'));

        $home_page_title = __('Now on air ...', 'msc-automation');
        $home_page_check = get_page_by_title($home_page_title);
        $home_page = array(
            'post_type' => 'page',
            'post_title' => $home_page_title,
            'post_status' => 'publish',
            'post_content' => '[home]',
            'post_author' => $post_author
                //'post_slug' => 'site-map'
        );
        if (!isset($home_page_check->ID)) {
            $home_page_id = wp_insert_post($home_page);
            update_option('page_on_front', $home_page_id);
            update_option('show_on_front', 'page');
        }

        // end mnu HOME    
    }

    // mnu Programació
    if ($_POST['create_calendar'] == 'true') {
        $schedu_page_title = __('Schedule', 'msc-automation');
        $schedu_page_check = get_page_by_title($schedu_page_title);
        $schedu_page = array(
            'post_type' => 'page',
            'post_title' => $schedu_page_title,
            'post_status' => 'publish',
            'post_content' => '[calendar_day]',
            'post_author' => $post_author,
            'post_parent' => 0
                //'post_slug' => 'site-map'
        );
        if (!isset($schedu_page_check->ID)) {
            $schedu_page_id = wp_insert_post($schedu_page);
        }
        wp_update_nav_menu_item($nav_item, 0, array(
            'menu-item-title' => $schedu_page_title,
            'menu-item-object-id' => $schedu_page_id,
            'menu-item-object' => 'page',
            'menu-item-type' => 'post_type',
            'menu-item-status' => 'publish'));
        //END mnu Programació 
    }

    //mnu Programes  
    $prg_page_title = __('Programs', 'msc-automation');
    $prg_page_check = get_page_by_title($prg_page_title);
    $prg_page = array(
        'post_type' => 'page',
        'post_title' => $prg_page_title,
        'post_status' => 'publish',
        'post_content' => '[list_programs]',
        'post_author' => $post_author
            //'post_slug' => 'site-map'
    );
    if (!isset($prg_page_check->ID)) {
        $prg_id_pare = wp_insert_post($prg_page);
    }

    $prg_nav_id = wp_update_nav_menu_item($nav_item, 0, array(
        'menu-item-title' => __('Programs', 'msc-automation'),
        'menu-item-object-id' => $prg_id_pare,
        'menu-item-object' => 'page',
        'menu-item-type' => 'post_type',
        'menu-item-status' => 'publish'));

    //End mnu Programes
    // Ini child list Programs
    // connectar amb API i llistar programes actius
    global $MyRadio;
    if (!isset($MyRadio)) {
        $MyRadio = new my_radio(get_option('msc_client_key'), get_locale(), get_option('msc_debug'));
    }

    if ($MyRadio->RESPOSTA_MESSAGE <> 'OK') {
        if ($MyRadio->IS_DEGUG == true) {
            show_msc_message($MyRadio->RESPOSTA_API, message_type::DANGER);
        }
    }

    $list = $MyRadio->QueryGetTable(seccions::PROGRAMS, sub_seccions::LIST_PRGS);
    if ($MyRadio->RESPOSTA_ROWS > 0) {
        $counter = 0;
        while ($counter < $MyRadio->RESPOSTA_ROWS):
            $prg_id = $list['item'][$counter]['ID'];
            $prg_page_title = wp_strip_all_tags($list['item'][$counter]['NAME']);
            $prg_page_check = get_page_by_title($prg_page_title);
            $prg_page = array(
                'post_type' => 'page',
                'post_title' => $prg_page_title,
                'post_status' => 'publish',
                'post_content' => '[show_program id="' . $prg_id . '" download="TRUE"]',
                'post_author' => $post_author,
                'post_parent' => $prg_id_pare
                    //'post_slug' => 'site-map'
                    // Assign page template
                    //'page_template'  => NAME_TEMPLATE_IFRAME
            );
            //if(!isset($prg_page_check->ID) && !the_slug_exists('site-map')){
            if (!isset($prg_page_check->ID)) {
                $_POST['_msc_hook_id'] = $prg_id;
                $prg_page_id = wp_insert_post($prg_page);
                unset($_POST['_msc_hook_id']);
                //add_post_meta($prg_page_id, '_msc_hook_id', $prg_id);
                // TODO: add tags                

                wp_update_nav_menu_item($nav_item, 0, array(
                    'menu-item-title' => $prg_page_title,
                    'menu-item-object-id' => $prg_page_id,
                    'menu-item-object' => 'page',
                    'menu-item-type' => 'post_type',
                    'menu-item-parent-id' => $prg_nav_id,
                    'menu-item-status' => 'publish'));
            }
            $counter ++;
        endwhile;
    }
    // End child list programs
    // mnu Podcast
    if ($_POST['create_podcast'] == 'true') {

        $pod_page_title = __('Radio on demand', 'msc-automation');
        $pod_page_check = get_page_by_title($pod_page_title);
        $pod_page = array(
            'post_type' => 'page',
            'post_title' => $pod_page_title,
            'post_status' => 'publish',
            'post_content' => '[last_podcast]',
            'post_author' => $post_author,
            'post_parent' => 0
                //'post_slug' => 'site-map'
        );
        if (!isset($pod_page_check->ID)) {
            $pod_page_id = wp_insert_post($pod_page);
        }
        wp_update_nav_menu_item($nav_item, 0, array(
            'menu-item-title' => $pod_page_title,
            'menu-item-object-id' => $pod_page_id,
            'menu-item-object' => 'page',
            'menu-item-type' => 'post_type',
            'menu-item-status' => 'publish'));
        //END mnu Podcast
    }
    //INI mnu Ràdio activitat
    if ($_POST['create_search'] == 'true' || $_POST['create_new_album'] == 'true' || $_POST['create_history_play'] == 'true' || $_POST['create_vote_payer'] == 'true') {
        $act_page_title = __('Radio Activity', 'msc-automation');
        $act_page_check = get_page_by_title($act_page_title);
        $act_page = array(
            'post_type' => 'page',
            'post_title' => $act_page_title,
            'post_status' => 'publish',
            'post_author' => $post_author,
            'post_parent' => 0
                //'post_slug' => 'site-map'
        );
        if (!isset($act_page_check->ID)) {
            $act_page_id = wp_insert_post($act_page);
        }
        $parent_mnu = wp_update_nav_menu_item($nav_item, 0, array(
            'menu-item-title' => $act_page_title,
            'menu-item-object-id' => $act_page_id,
            'menu-item-object' => 'page',
            'menu-item-type' => 'post_type',
            'menu-item-status' => 'publish'));
    }//END mnu Ràdio Activitat        
    // mnu search music
    if ($_POST['create_search'] == 'true') {
        $search_page_title = __('Search music', 'msc-automation');
        $search_page_check = get_page_by_title($search_page_title);
        $search_page = array(
            'post_type' => 'page',
            'post_title' => $search_page_title,
            'post_status' => 'publish',
            'post_content' => '[search_music]',
            'post_author' => $post_author,
            'post_parent' => 0
                //'post_slug' => 'site-map'
        );
        if (!isset($search_page_check->ID)) {
            $_POST['_msc_hook_id'] = 'search';
            $search_page_id = wp_insert_post($search_page);
            unset($_POST['_msc_hook_id']);
        }
        wp_update_nav_menu_item($nav_item, 0, array(
            'menu-item-title' => $search_page_title,
            'menu-item-object-id' => $search_page_id,
            'menu-item-object' => 'page',
            'menu-item-type' => 'post_type',
            'menu-item-parent-id' => $parent_mnu,
            'menu-item-status' => 'publish'));
    }
    // mnu Albums release
    if ($_POST['create_new_album'] == 'true') {
        $release_page_title = __('Albums release', 'msc-automation');
        $release_page_check = get_page_by_title($release_page_title);
        $release_page = array(
            'post_type' => 'page',
            'post_title' => $release_page_title,
            'post_status' => 'publish',
            'post_content' => '[last_albums rows="5"]',
            'post_author' => $post_author,
            'post_parent' => 0
                //'post_slug' => 'site-map'
        );
        if (!isset($release_page_check->ID)) {
            $release_page_id = wp_insert_post($release_page);
        }
        wp_update_nav_menu_item($nav_item, 0, array(
            'menu-item-title' => $release_page_title,
            'menu-item-object-id' => $release_page_id,
            'menu-item-object' => 'page',
            'menu-item-type' => 'post_type',
            'menu-item-parent-id' => $parent_mnu,
            'menu-item-status' => 'publish'));
    }
    // mnu History played
    if ($_POST['create_history_play'] == 'true') {
        $history_page_title = __('History played', 'msc-automation');
        $history_page_check = get_page_by_title($history_page_title);
        $history_page = array(
            'post_type' => 'page',
            'post_title' => $history_page_title,
            'post_status' => 'publish',
            'post_content' => '[last_played rows="20"]',
            'post_author' => $post_author,
            'post_parent' => 0
                //'post_slug' => 'site-map'
        );
        if (!isset($history_page_check->ID)) {
            $history_page_id = wp_insert_post($history_page);
        }
        wp_update_nav_menu_item($nav_item, 0, array(
            'menu-item-title' => $history_page_title,
            'menu-item-object-id' => $history_page_id,
            'menu-item-object' => 'page',
            'menu-item-type' => 'post_type',
            'menu-item-parent-id' => $parent_mnu,
            'menu-item-status' => 'publish'));
    }
    // mnu Vote music to player
    if ($_POST['create_vote_payer'] == 'true') {
        $vote_page_title = __('Vote music to play', 'msc-automation');
        $vote_page_check = get_page_by_title($vote_page_title);
        $vote_page = array(
            'post_type' => 'page',
            'post_title' => $vote_page_title,
            'post_status' => 'publish',
            'post_content' => '[public_vote_player]',
            'post_author' => $post_author,
            'post_parent' => 0
                //'post_slug' => 'site-map'
        );
        if (!isset($vote_page_check->ID)) {
            $vote_page_id = wp_insert_post($vote_page);
        }
        wp_update_nav_menu_item($nav_item, 0, array(
            'menu-item-title' => $vote_page_title,
            'menu-item-object-id' => $vote_page_id,
            'menu-item-object' => 'page',
            'menu-item-type' => 'post_type',
            'menu-item-parent-id' => $parent_mnu,
            'menu-item-status' => 'publish'));
    }

    //mnu NEWS
    if ($_POST['create_news'] == 'true') {
        $news_page_title = __('News', 'msc-automation');
        $$news_page_check = get_page_by_title($news_page_title);
        $news_page = array(
            'post_type' => 'page',
            'post_title' => $news_page_title,
            'post_status' => 'publish',
            'post_content' => '',
            'post_author' => $post_author,
            'post_parent' => 0
        );
        if (!isset($$news_page_check->ID)) {
            $news_page_id = wp_insert_post($news_page);
        }
        wp_update_nav_menu_item($nav_item, 0, array(
            'menu-item-title' => $news_page_title,
            'menu-item-object-id' => $news_page_id,
            'menu-item-object' => 'page',
            'menu-item-type' => 'post_type',
            'menu-item-status' => 'publish'));
        update_option('page_for_posts', $news_page_id);
    }

    //register_nav_menu( 'header-menu', __( 'MSC Main','msc-automation') );
    register_nav_menu('primary', __('MSC Main', 'msc-automation'));
}

function create_footer_mnu() {
    // mnu footer
    $foot_item = wp_create_nav_menu(__('MSC Footer', 'msc-automation'));
    wp_update_nav_menu_item($foot_item, 0, array(
        'menu-item-title' => __('Powered by: MSC radio Automation'),
        'menu-item-description' => __('Radio Automation Software | The radio on cloud', 'msc-automation'),
        'menu-item-attr-title' => __('Radio Automation Software | The radio on cloud', 'msc-automation'),
        'menu-item-target' => '_blank',
        //'menu-item-classes' => 'home',
        'menu-item-url' => 'https://msc-soft.com/',
        'menu-item-status' => 'publish'));
}

function reset_system() {

    wp_delete_nav_menu(__('MSC Main', 'msc-automation'));
    wp_delete_nav_menu(__('MSC Footer', 'msc-automation'));

    $page = get_page_by_title(__('Schedule', 'msc-automation'));
    if ($page) {
        wp_delete_post($page->ID, true);
    }

    $page = get_page_by_title(__('Programs', 'msc-automation'));
    if ($page) {
        wp_delete_post($page->ID, true);
    }

    $page = get_page_by_title(__('Radio on demand', 'msc-automation'));
    if ($page) {
        wp_delete_post($page->ID, true);
    }

    $page = get_page_by_title(__('Listen', 'msc-automation'));
    if ($page) {
        wp_delete_post($page->ID, true);
    }

    $page = get_page_by_title(__('Search music', 'msc-automation'));
    if ($page) {
        wp_delete_post($page->ID, true);
    }

    $page = get_page_by_title(__('Albums release', 'msc-automation'));
    if ($page) {
        wp_delete_post($page->ID, true);
    }

    $page = get_page_by_title(__('History played', 'msc-automation'));
    if ($page) {
        wp_delete_post($page->ID, true);
    }

    $page = get_page_by_title(__('Vote music to play', 'msc-automation'));
    if ($page) {
        wp_delete_post($page->ID, true);
    }


//Llistar tots les pàgines de programes i borrar el post i el meta
    $args = array(
        'post_type' => 'page',
        'posts_per_page' => '100',
        'meta_query' => array(
            'key' => '_msc_hook_id',
            'value' => '0',
            'compare' => '>',
        )
    );
    $query = new WP_Query($args);
    while ($query->have_posts()) {
        $query->the_post();
        $id = get_the_ID();
        wp_delete_post($id, TRUE);
    }
    /* Restore original Post Data */
    update_option('msc_initialize', 'false');
    wp_reset_postdata();
}

function unistall_msc() {
    if (get_option('msc_initialize') == 'true') {
        reset_system();
    }
    $option_name = 'msc_client_key';
    delete_option($option_name);
    delete_site_option($option_name);
    $option_name = 'msc_debug';
    delete_option($option_name);
    delete_site_option($option_name);
    $option_name = 'msc_initialize';
    delete_option($option_name);
    delete_site_option($option_name);
    $option_name = 'msc_player';
    delete_option($option_name);
    delete_site_option($option_name);
    $option_name = 'msc_color';
    delete_option($option_name);
    delete_site_option($option_name);
    $option_name = 'msc_enable_aws';
    delete_option($option_name);
    delete_site_option($option_name);
    $option_name = 'msc_no-ajax-ids';
    delete_option($option_name);
    delete_site_option($option_name);
    $option_name = 'msc_container-id';
    delete_option($option_name);
    delete_site_option($option_name);
    $option_name = 'msc_mcdc';
    delete_option($option_name);
    delete_site_option($option_name);
    $option_name = 'msc_search_form';
    delete_option($option_name);
    delete_site_option($option_name);
    $option_name = 'msc_transition';
    delete_option($option_name);
    delete_site_option($option_name);
    $option_name = 'msc_scrollTop';
    delete_option($option_name);
    delete_site_option($option_name);
    $option_name = 'msc_transition';
    delete_option($option_name);
    delete_site_option($option_name);
    $option_name = 'msc_loader';
    delete_option($option_name);
    delete_site_option($option_name);
    // drop a custom database table
    //global $wpdb;
    //$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}mytable");
}

register_uninstall_hook(__FILE__, 'unistall_msc');