<?php

$up_dir = wp_upload_dir();
// Create a folder in the Uploads Directory of WordPress to store your files
if (!file_exists($up_dir['basedir'] . '/' . WP_MSCRA_TMP_IMG_DIR)) {
    mkdir($up_dir['basedir'] . '/' . WP_MSCRA_TMP_IMG_DIR, 0775, true);
}

// Create a folder in the Uploads Directory of WordPress to store your files
if (!file_exists($up_dir['basedir'] . '/' . WP_MSCRA_PODCAST_DIR)) {
    mkdir($up_dir['basedir'] . '/' . WP_MSCRA_PODCAST_DIR, 0775, true);
}

/* Ini and reset pluging */

function mscra_ini_all_action()
{
    $post_author = get_current_user_id();
    mscra_create_basic_mnu($post_author);
    mscra_create_footer_mnu();

    // create page song for iframe
    $song_page = array(
        'post_type' => 'page',
        'post_title' => sanitize_text_field(__('Song', 'mscra-automation')),
        'post_status' => 'publish',
        'post_content' => '[mscra_detail_song]',
        'post_author' => $post_author,
        //'post_slug' => 'site-map'
        // Assign page template
        'page_template' => MSCRA_NAME_TEMPLATE_IFRAME,
    );
    $song_page_check = get_page_by_title(__('Song', 'mscra-automation'));
    if (!isset($song_page_check->ID)) {
        $song_page_id = wp_insert_post($song_page);
        add_post_meta($song_page_id, '_msc_hook_id', MSCRA_HOOK_SONG, true);
    }

    // create page album for iframe
    $album_page = array(
        'post_type' => 'page',
        'post_title' => sanitize_text_field(__('album', 'mscra-automation')),
        'post_status' => 'publish',
        'post_content' => '[mscra_detail_album]',
        'post_author' => $post_author,
        //'post_slug' => 'site-map'
        // Assign page template
        'page_template' => MSCRA_NAME_TEMPLATE_IFRAME,
    );
    $album_page_check = get_page_by_title(__('album', 'mscra-automation'));
    if (!isset($album_page_check->ID)) {
        $album_page_id = wp_insert_post($album_page);
        add_post_meta($album_page_id, '_msc_hook_id', MSCRA_HOOK_ALBUM, true);
    }

    // create page track for iframe
    $track_page_name = sanitize_text_field(__('track', 'mscra-automation'));
    $track_page = array(
        'post_type' => 'page',
        'post_title' => $track_page_name,
        'post_status' => 'publish',
        'post_content' => '[mscra_detail_track]',
        'post_author' => $post_author,
        //'post_slug' => 'site-map'
        // Assign page template
        'page_template' => MSCRA_NAME_TEMPLATE_IFRAME,
    );
    $track_page_check = get_page_by_title($track_page_name);
    if (!isset($track_page_check->ID)) {
        $track_page_id = wp_insert_post($track_page);
        add_post_meta($track_page_id, '_msc_hook_id', MSCRA_HOOK_TRACK, true);
    }

    // create page player streaming for iframe

    $player_page = array(
        'post_type' => 'page',
        'post_title' => sanitize_text_field(__('Player Stream', 'mscra-automation')),
        'post_status' => 'publish',
        'post_content' => '[mscra_iframe_player_streaming]',
        'post_author' => $post_author,
        //'post_slug' => 'site-map'
        // Assign page template
        'page_template' => MSCRA_NAME_TEMPLATE_IFRAME,
    );
    $player_page_check = get_page_by_title(__('Player Stream', 'mscra-automation'));
    if (!isset($player_page_check->ID)) {
        $player_page_id = wp_insert_post($player_page);
        add_post_meta($player_page_id, '_msc_hook_id', MSCRA_HOOK_PLAYER_STREAM, true);
    }

    update_option('mscra_initialize', 'true');

    echo '<div id="message" class="updated fade"><p>'
    . __('<b>Successfully created the selected options</b><br> Once the initialization is done, go to the menus section. Select the menu "MSC Main" and check the box "Primary Menu" to view it.', 'mscra-automation') . '</p></div>';
}

function mscra_create_basic_mnu($post_author)
{
    $nav_item = wp_create_nav_menu(__('MSC Main', 'mscra-automation'));
    // mnu HOME
    if ($_POST['create_home'] == 'true') {
        wp_update_nav_menu_item($nav_item, 0, array(
            'menu-item-title' => __('Home', 'mscra-automation'),
            'menu-item-classes' => 'home',
            'menu-item-url' => home_url('/'),
            'menu-item-status' => 'publish'));

        $home_page_title = sanitize_text_field(__('Now on air ...', 'mscra-automation'));
        $home_page_check = get_page_by_title($home_page_title);
        $home_page = array(
            'post_type' => 'page',
            'post_title' => $home_page_title,
            'post_status' => 'publish',
            'post_content' => '[mscra_home]',
            'post_author' => $post_author,
            //'post_slug' => 'site-map'
        );
        if (!isset($home_page_check->ID)) {
            $home_page_id = wp_insert_post($home_page);
            add_post_meta($home_page_id, '_msc_hook_id', MSCRA_HOOK_HOME, true);
            update_option('page_on_front', $home_page_id);
            update_option('show_on_front', 'page');
        }

        // end mnu HOME
    }

    // mnu Programació
    if ($_POST['create_calendar'] == 'true') {
        $schedu_page_title = sanitize_text_field(__('Schedule', 'mscra-automation'));
        $schedu_page_check = get_page_by_title($schedu_page_title);
        $schedu_page = array(
            'post_type' => 'page',
            'post_title' => $schedu_page_title,
            'post_status' => 'publish',
            'post_content' => '[mscra_calendar_day]',
            'post_author' => $post_author,
            'post_parent' => 0,
            //'post_slug' => 'site-map'
        );
        if (!isset($schedu_page_check->ID)) {
            $schedu_page_id = wp_insert_post($schedu_page);
            add_post_meta($schedu_page_id, '_msc_hook_id', MSCRA_HOOK_CALENDAR, true);
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
    $prg_page_title = sanitize_text_field(__('Programs', 'mscra-automation'));
    $prg_page_check = get_page_by_title($prg_page_title);
    $prg_page = array(
        'post_type' => 'page',
        'post_title' => $prg_page_title,
        'post_status' => 'publish',
        'post_content' => '[mscra_list_programs]',
        'post_author' => $post_author,
        //'post_slug' => 'site-map'
    );
    if (!isset($prg_page_check->ID)) {
        $prg_id_pare = wp_insert_post($prg_page);
        add_post_meta($prg_id_pare, '_msc_hook_id', MSCRA_HOOK_PROGRAMS, true);
    }

    $prg_nav_id = wp_update_nav_menu_item($nav_item, 0, array(
        'menu-item-title' => __('Programs', 'mscra-automation'),
        'menu-item-object-id' => $prg_id_pare,
        'menu-item-object' => 'page',
        'menu-item-type' => 'post_type',
        'menu-item-status' => 'publish'));

    //End mnu Programes
    // Ini child list Programs
    // connectar amb API i llistar programes actius
    include MSCRA_PLUGIN_DIR . 'connect_api.php';
    $list = $MyRadio->QueryGetTable(seccions::PROGRAMS, sub_seccions::LIST_PRGS);
    if ($MyRadio->RESPOSTA_ROWS > 0) {
        $counter = 0;
        while ($counter < $MyRadio->RESPOSTA_ROWS):
            $prg_id = $list['item'][$counter]['ID'];
            $prg_page_title = wp_strip_all_tags($list['item'][$counter]['NAME']);
            $prg_page_check = get_page_by_title($prg_page_title);
            $prg_page = array(
                'post_type' => 'page',
                'post_title' => sanitize_text_field($prg_page_title),
                'post_status' => 'publish',
                'post_content' => '[mscra_show_program id="' . $prg_id . '" download="TRUE"]',
                'post_author' => $post_author,
                'post_parent' => $prg_id_pare,
            );
            if (!isset($prg_page_check->ID)) {
                $prg_page_id = wp_insert_post($prg_page);
                add_post_meta($prg_page_id, '_msc_hook_id', $prg_id, true);
                // TODO: add tags

                wp_update_nav_menu_item($nav_item, 0, array(
                    'menu-item-title' => $prg_page_title,
                    'menu-item-object-id' => $prg_page_id,
                    'menu-item-object' => 'page',
                    'menu-item-type' => 'post_type',
                    'menu-item-parent-id' => $prg_nav_id,
                    'menu-item-status' => 'publish'));
            }
            $counter++;
        endwhile;
    }
    // End child list programs
    // mnu Podcast
    if ($_POST['create_podcast'] == 'true') {

        $pod_page_title = sanitize_text_field(__('Radio on demand', 'mscra-automation'));
        $pod_page_check = get_page_by_title($pod_page_title);
        $pod_page = array(
            'post_type' => 'page',
            'post_title' => $pod_page_title,
            'post_status' => 'publish',
            'post_content' => '[mscra_last_podcast]',
            'post_author' => $post_author,
            'post_parent' => 0,
            //'post_slug' => 'site-map'
        );
        if (!isset($pod_page_check->ID)) {
            $pod_page_id = wp_insert_post($pod_page);
            add_post_meta($pod_page_id, '_msc_hook_id', MSCRA_HOOK_ON_DEMAND, true);
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
        $act_page_title = sanitize_text_field(__('Radio Activity', 'mscra-automation'));
        $act_page_check = get_page_by_title($act_page_title);
        $act_page = array(
            'post_type' => 'page',
            'post_title' => $act_page_title,
            'post_status' => 'publish',
            'post_author' => $post_author,
            'post_parent' => 0,
            //'post_slug' => 'site-map'
        );
        if (!isset($act_page_check->ID)) {
            $act_page_id = wp_insert_post($act_page);
            add_post_meta($act_page_id, '_msc_hook_id', MSCRA_HOOK_ON_ACTIVITY, true);
        }
        $parent_mnu = wp_update_nav_menu_item($nav_item, 0, array(
            'menu-item-title' => $act_page_title,
            'menu-item-object-id' => $act_page_id,
            'menu-item-object' => 'page',
            'menu-item-type' => 'post_type',
            'menu-item-status' => 'publish'));
    } //END mnu Ràdio Activitat
    // page search music
    if ($_POST['create_search'] == 'true') {
        $search_page_title = sanitize_text_field(__('Search music', 'mscra-automation'));
        $search_page_check = get_page_by_title($search_page_title);
        $search_page = array(
            'post_type' => 'page',
            'post_title' => $search_page_title,
            'post_status' => 'publish',
            'post_content' => '[mscra_search_music]',
            'post_author' => $post_author,
            'post_parent' => 0,
            //'post_slug' => 'site-map'
        );
        if (!isset($search_page_check->ID)) {
            //$_POST['_msc_hook_id'] = 'search';
            $search_page_id = wp_insert_post($search_page);
            add_post_meta($search_page_id, '_msc_hook_id', MSCRA_HOOK_SEARCH, true);

            //unset($_POST['_msc_hook_id']);
        }
        wp_update_nav_menu_item($nav_item, 0, array(
            'menu-item-title' => $search_page_title,
            'menu-item-object-id' => $search_page_id,
            'menu-item-object' => 'page',
            'menu-item-type' => 'post_type',
            'menu-item-parent-id' => $parent_mnu,
            'menu-item-status' => 'publish'));
    }
    // page Albums release
    if ($_POST['create_new_album'] == 'true') {
        $release_page_title = sanitize_text_field(__('Albums release', 'mscra-automation'));
        $release_page_check = get_page_by_title($release_page_title);
        $release_page = array(
            'post_type' => 'page',
            'post_title' => $release_page_title,
            'post_status' => 'publish',
            'post_content' => '[mscra_last_albums rows="5"]',
            'post_author' => $post_author,
            'post_parent' => 0,
            //'post_slug' => 'site-map'
        );
        if (!isset($release_page_check->ID)) {
            $release_page_id = wp_insert_post($release_page);
            add_post_meta($release_page_id, '_msc_hook_id', MSCRA_HOOK_ALBUM, true);
        }
        wp_update_nav_menu_item($nav_item, 0, array(
            'menu-item-title' => $release_page_title,
            'menu-item-object-id' => $release_page_id,
            'menu-item-object' => 'page',
            'menu-item-type' => 'post_type',
            'menu-item-parent-id' => $parent_mnu,
            'menu-item-status' => 'publish'));
    }
    // page History played
    if ($_POST['create_history_play'] == 'true') {
        $history_page_title = sanitize_text_field(__('History played', 'mscra-automation'));
        $history_page_check = get_page_by_title($history_page_title);
        $history_page = array(
            'post_type' => 'page',
            'post_title' => $history_page_title,
            'post_status' => 'publish',
            'post_content' => '[mscra_last_played rows="20"]',
            'post_author' => $post_author,
            'post_parent' => 0,
            //'post_slug' => 'site-map'
        );
        if (!isset($history_page_check->ID)) {
            $history_page_id = wp_insert_post($history_page);
            add_post_meta($history_page_id, '_msc_hook_id', MSCRA_HOOK_ON_HISTORY, true);
        }
        wp_update_nav_menu_item($nav_item, 0, array(
            'menu-item-title' => $history_page_title,
            'menu-item-object-id' => $history_page_id,
            'menu-item-object' => 'page',
            'menu-item-type' => 'post_type',
            'menu-item-parent-id' => $parent_mnu,
            'menu-item-status' => 'publish'));
    }
    // page Vote music to player
    if ($_POST['create_vote_payer'] == 'true') {
        $vote_page_title = sanitize_text_field(__('Vote music to play', 'mscra-automation'));
        $vote_page_check = get_page_by_title($vote_page_title);
        $vote_page = array(
            'post_type' => 'page',
            'post_title' => $vote_page_title,
            'post_status' => 'publish',
            'post_content' => '[mscra_public_vote_player]',
            'post_author' => $post_author,
            'post_parent' => 0,
            //'post_slug' => 'site-map'
        );
        if (!isset($vote_page_check->ID)) {
            $vote_page_id = wp_insert_post($vote_page);
            add_post_meta($vote_page_id, '_msc_hook_id', MSCRA_HOOK_ON_VOTE, true);
        }
        wp_update_nav_menu_item($nav_item, 0, array(
            'menu-item-title' => $vote_page_title,
            'menu-item-object-id' => $vote_page_id,
            'menu-item-object' => 'page',
            'menu-item-type' => 'post_type',
            'menu-item-parent-id' => $parent_mnu,
            'menu-item-status' => 'publish'));
    }

    //page NEWS
    if ($_POST['create_news'] == 'true') {
        $news_page_title = sanitize_text_field(__('News', 'mscra-automation'));
        $news_page_check = get_page_by_title($news_page_title);
        $news_page = array(
            'post_type' => 'page',
            'post_title' => $news_page_title,
            'post_status' => 'publish',
            'post_content' => '',
            'post_author' => $post_author,
            'post_parent' => 0,
        );
        if (!isset($news_page_check->ID)) {
            $news_page_id = wp_insert_post($news_page);
            add_post_meta($news_page_id, '_msc_hook_id', MSCRA_HOOK_ON_NEWS, true);
        }
        wp_update_nav_menu_item($nav_item, 0, array(
            'menu-item-title' => $news_page_title,
            'menu-item-object-id' => $news_page_id,
            'menu-item-object' => 'page',
            'menu-item-type' => 'post_type',
            'menu-item-status' => 'publish'));
        update_option('page_for_posts', $news_page_id);
    }

    // page Advertising
    if ($_POST['create_Advertising'] == 'true') {
        $adv_page_title = __('Advertising', 'mscra-automation');
        $adv_page_check = get_page_by_title($adv_page_title);
        $adv_page = array(
            'post_type' => 'page',
            'post_title' => $adv_page_title,
            'post_status' => 'publish',
            'post_content' => '[mscra_manager_adv]',
            'post_author' => $post_author,
            'post_parent' => 0,
            //'post_slug' => 'site-map'
        );
        if (!isset($adv_page_check->ID)) {
            $adv_page_id = wp_insert_post($adv_page);
            add_post_meta($adv_page_id, '_msc_hook_id', MSCRA_HOOK_ON_ADS, true);
        }
    }

    //register_nav_menu( 'header-menu', __( 'MSC Main','mscra-automation') );
    register_nav_menu('primary', __('MSC Main', 'mscra-automation'));
}

function mscra_create_footer_mnu()
{
    // mnu footer
    $foot_item = wp_create_nav_menu(__('MSC Footer', 'mscra-automation'));
    wp_update_nav_menu_item($foot_item, 0, array(
        'menu-item-title' => __('Powered by MSC Radio Automation'),
        'menu-item-description' => __('Radio Automation Software | The radio on cloud', 'mscra-automation'),
        'menu-item-attr-title' => __('Radio Automation Software | The radio on cloud', 'mscra-automation'),
        'menu-item-target' => '_blank',
        //'menu-item-classes' => 'home',
        'menu-item-url' => 'https://msc-soft.com/',
        'menu-item-status' => 'publish'));
}