<?php

function mscra_get_Home($attributes)
{
    if (is_admin()) {return;}

    mscra_get_now_onair();
}

add_shortcode('mscra_home', 'mscra_get_Home');

function mscra_get_detail_track($attributes)
{
    if (is_admin()) {return;}

    $ref = (isset($_GET['ref'])) ? sanitize_text_field($_GET['ref']) : '';
    if ($ref != '') {
        $values = explode(',', hex2bin($ref));
        $id_track = $values[0];
        $type_track = $values[1];
    } else {
        if(!isset($attributes['id'])){
            return;
        }
        $id_track = $attributes['id'];
        $type_track = $attributes['type'];
    }
    if (!isset($attributes['img_width'])) {
        $img_width = 200;
    } else {
        $img_width = $attributes['img_width'];
    }
    $url_player = '';
    switch ($type_track) {
        case TIP_AUTOMATIC_PROGRAMA:
            include MSCRA_PLUGIN_DIR . 'connect_api.php';
            $Vars[0] = 'id=' . $id_track;
            $list = $MyRadio->QueryGetTable(seccions::PODCAST, sub_seccions::SHOWINFO_PCAST, $Vars);
            if ($MyRadio->RESPOSTA_ROWS > 0) {
                $upload_dir = wp_upload_dir();
                $PathToSaveImg = $upload_dir['basedir'] . '/' . WP_MSCRA_TMP_IMG_DIR . '/prg-' . $prg_id . '.jpg';
                $PathToShowImg = $upload_dir['baseurl'] . '/' . WP_MSCRA_TMP_IMG_DIR . '/prg-' . $prg_id . '.jpg';
                $def_image = get_site_icon_url('120');
                if (mscra_getImage(base64_decode($list['item']['IMAGE']), $PathToSaveImg, $img_width) == false) {
                    //canvia a imatge per defecte
                    $PathToShowImg = $def_image;
                }
                $url_player = $upload_dir['baseurl'] . '/' . WP_MSCRA_PODCAST_DIR . '/' . $list['item']['LINK'];
                $title = sanitize_text_field($list['item']['NAME']);
                $subtitle = htmlentities(sanitize_text_field($list['item']['DESCRIP']));
            }
            break;
        case TIP_DIRECTE_:
            include MSCRA_PLUGIN_DIR . 'connect_api.php';
            $Vars[0] = 'id=' . $id_track;
            $list = $MyRadio->QueryGetTable(seccions::PROGRAMS, sub_seccions::SHOWINFO_PRG, $Vars);
            if ($MyRadio->RESPOSTA_ROWS > 0) {
                $upload_dir = wp_upload_dir();
                $PathToSaveImg = $upload_dir['basedir'] . '/' . WP_MSCRA_TMP_IMG_DIR . '/prg-' . $prg_id . '.jpg';
                $PathToShowImg = $upload_dir['baseurl'] . '/' . WP_MSCRA_TMP_IMG_DIR . '/prg-' . $prg_id . '.jpg';
                $def_image = get_site_icon_url('120');
                if (mscra_getImage(base64_decode($list['item']['IMAGE']), $PathToSaveImg, $img_width) == false) {
                    //canvia a imatge per defecte
                    $PathToShowImg = $def_image;
                }
                $title = sanitize_text_field($list['item']['NAME']);
                $subtitle = htmlentities(sanitize_text_field($list['item']['DESCRIP']));
            }
            break;
        case TIP_AUTOMATIC_RADIOFORMULA:
        case TIP_AUTOMATIC_LLISTA:
            do_shortcode('[mscra_detail_song id="' . $id_track . '" img_width="' . $img_width . '"]');
            return;
    }
    $post_title = $title . ' | ' . $subtitle;
    echo "<script> document.title =" . $post_title . " ; </script>";

    /*wp_register_script('script_opengraph', MSCRA_JQUERY_URL . 'refresh_og.js', '1.0.0');
    $params_og = array('i' => $PathToShowImg, 't' => $title . ' | ' . $subtitle);
    wp_localize_script('script_opengraph', 'object_params', $params_og);
    wp_enqueue_script('script_opengraph');*/
    ?>
    <div class="detail_track_top">
        <img id="jp-image" src="<?php echo $PathToShowImg; ?>">
        <div id="jp_title"><span><?php echo $title; ?></span></div>
        <div id="jp_subtitle-name"><?php echo $subtitle; ?></div>
        <?PHP
if (strlen($url_player) > 3) {
        ?>
            <figure class="wp-block-audio"><?php echo $title; ?><br><audio controls="" src="<?php echo $url_player; ?>"></audio>
        <?php }?>
        <div><?php _e('On', 'mscra-automation');?> <b><a href="<?php echo get_home_url(); ?>" title="<?php echo get_bloginfo('description'); ?>" target="_blank"><?php echo get_bloginfo('name'); ?></a></b></div>
    </div>
    <?php
}

add_shortcode('mscra_detail_track', 'mscra_get_detail_track');