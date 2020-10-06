<?php

function get_Home($attributes) {
    if (is_admin()) {
        return;
    }
    get_now_onair();
}

add_shortcode('home', 'get_Home');

function get_detail_track($attribtes) {
    if (is_admin()) {
        return;
    }
       
    if (isset($_GET['ref'])) {        
        $values= explode (',',hex2bin($_GET['ref']));
        $id_track= $values[0];
        $type_track =$values[1];
    }else{
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
            global $MyRadio;
            if (!isset($MyRadio)) {
                $MyRadio = new my_radio(get_option('msc_client_key'), get_locale(), get_option('msc_debug'));
                if ($MyRadio->RESPOSTA_STATUS !== SUCCES) {
                    if ($MyRadio->IS_DEGUG == true) {
                        $msg = 'STATUS: ' . $MyRadio->RESPOSTA_STATUS . ' CODE: ' . $MyRadio->RESPOSTA_CODE . ' MSG: ' . $MyRadio->RESPOSTA_MESSAGE;
                        show_msc_message($msg, message_type::DANGER);
                        return;
                    }
                }
            }
            $Vars[0] = 'id=' . $id_track;
            $list = $MyRadio->QueryGetTable(seccions::PODCAST, sub_seccions::SHOWINFO_PCAST, $Vars);
            if ($MyRadio->RESPOSTA_ROWS > 0) {
                $upload_dir = wp_upload_dir();
                $PathToSaveImg = $upload_dir['basedir'] . '/' . TMP_IMG_DIR . '/prg-' . $prg_id . '.jpg';
                $PathToShowImg = $upload_dir['baseurl'] . '/' . TMP_IMG_DIR . '/prg-' . $prg_id . '.jpg';
                $def_image = get_site_icon_url('120');
                if (getImage(base64_decode($list['item']['IMAGE']), $PathToSaveImg, $img_width) == false) {
                    //canvia a imatge per defecte                              
                    $PathToShowImg = $def_image;
                }
                $url_player = $upload_dir['baseurl'].'/'.PODCAST_DIR.'/'.$list['item']['LINK'];
                $title = htmlentities($list['item']['NAME']);
                $subtitle = htmlentities($list['item']['DESCRIP']);                
            }                        
            break;
        case TIP_DIRECTE_:
            global $MyRadio;
            if (!isset($MyRadio)) {
                $MyRadio = new my_radio(get_option('msc_client_key'), get_locale(), get_option('msc_debug'));
                if ($MyRadio->RESPOSTA_STATUS !== SUCCES) {
                    if ($MyRadio->IS_DEGUG == true) {
                        $msg = 'STATUS: ' . $MyRadio->RESPOSTA_STATUS . ' CODE: ' . $MyRadio->RESPOSTA_CODE . ' MSG: ' . $MyRadio->RESPOSTA_MESSAGE;
                        show_msc_message($msg, message_type::DANGER);
                        return;
                    }
                }
            }
            $Vars[0] = 'id=' . $id_track;
            $list = $MyRadio->QueryGetTable(seccions::PROGRAMS, sub_seccions::SHOWINFO_PRG, $Vars);
            if ($MyRadio->RESPOSTA_ROWS > 0) {
                $upload_dir = wp_upload_dir();
                $PathToSaveImg = $upload_dir['basedir'] . '/' . TMP_IMG_DIR . '/prg-' . $prg_id . '.jpg';
                $PathToShowImg = $upload_dir['baseurl'] . '/' . TMP_IMG_DIR . '/prg-' . $prg_id . '.jpg';
                $def_image = get_site_icon_url('120');
                if (getImage(base64_decode($list['item']['IMAGE']), $PathToSaveImg, $img_width) == false) {
                    //canvia a imatge per defecte                              
                    $PathToShowImg = $def_image;
                }
                $title = htmlentities($list['item']['NAME']);
                $subtitle = htmlentities($list['item']['DESCRIP']);
            }
            break;
        case TIP_AUTOMATIC_RADIOFORMULA:
        case TIP_AUTOMATIC_LLISTA:
            $attributes['id'] = $id_track;
            $attributes['img_width'] = $img_width;
            get_detail_song($attributes);
            return;
    }

    $post_title = $title . ' | ' . $subtitle;
    echo "<script> document.title =".$post_title." ; </script>"; 
    

    wp_register_script('script_opengraph', MSC_JQUERY_URL . 'refresh_og.js', '1.0.0');
    $params_og = array('i' => $PathToShowImg, 't' => $title . ' | ' . $subtitle);
    wp_localize_script('script_opengraph', 'object_params', $params_og);
    wp_enqueue_script('script_opengraph');
    ?>
    <div class="detail_track_top">        
        <img id="jp-image" src="<?php echo $PathToShowImg; ?>">
        <div id="jp_title"><span><?php echo $title; ?></span></div>
        <div id="jp_subtitle-name"><?php echo $subtitle; ?></div>
        <?PHP 
        if(strlen($url_player)>3){
            ?>
            <figure class="wp-block-audio"><?php echo $title; ?><br><audio controls="" src="<?php echo $url_player; ?>"></audio>
        <?php } ?>
        <div><?php _e('On', 'msc-automation'); ?> <b><a href="<?php echo get_home_url(); ?>" title="<?php echo get_bloginfo('description'); ?>" target="_blank"><?php echo get_bloginfo('name'); ?></a></b></div>        
    </div>
    <?php
}

add_shortcode('detail_track', 'get_detail_track');
