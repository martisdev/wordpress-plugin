<?php

function get_Home($attributes) {
    /*global $MyRadio;
    if (!isset($MyRadio)) {
        $MyRadio = new my_radio(get_option('msc_client_key'), get_locale(), get_option('msc_debug'));
    }
    if ($MyRadio->RESPOSTA_STATUS !== SUCCES) {
        if ($MyRadio->IS_DEGUG == true) {
            $msg = 'STATUS: ' . $MyRadio->RESPOSTA_STATUS . ' CODE: ' . $MyRadio->RESPOSTA_CODE . ' MSG: ' . $MyRadio->RESPOSTA_MESSAGE;
            show_msc_message($msg, message_type::DANGER);
            return;
        }
    }*/
    get_now_onair();    
}

add_shortcode('home', 'get_Home');

function get_detail_track($attribtes) {

    if (isset($_GET['id'])) {
        $id_track = $_GET['id'];
    } else {
        $id_track = $attributes['id'];
    }
    if (isset($_GET['type'])) {
        $type_track = $_GET['type'];
    } else {
        $type_track = $attributes['type'];
    }
    if (!isset($attributes['img_width'])) {
        $img_width = 200;
    } else {
        $img_width = $attributes['img_width'];
    }

    switch ($type_track) {
        case TIP_AUTOMATIC_PROGRAMA:
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
                if (getImage(base64_decode($list['item']['IMAGE']),$PathToSaveImg,$img_width)==false){                 
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
    
        wp_register_script( 'script_opengraph', MSC_JQUERY_URL.'refresh_og.js', '1.0.0'  );
        $params_og = array('i'=>$PathToShowImg,'t'=> $title.' | '.$subtitle);
        wp_localize_script( 'script_opengraph', 'object_params', $params_og );
        wp_enqueue_script( 'script_opengraph' );
    ?>
    <div  style="padding:20px 20px 20px 20px">            
        <img id="jp-image" src="<?php echo $PathToShowImg; ?>">
        <div id="jp_title"><span><?php echo $title; ?></span></div>
        <div id="jp_subtitle-name"><?php echo $subtitle; ?></div>
        <div><?php _e('On', 'msc-automation'); ?> <b><a href="<?php echo get_home_url(); ?>" title="<?php echo get_bloginfo('description'); ?>" target="_blank"><?php echo get_bloginfo('name'); ?></a></b></div>
    </div>
    <?php
}

add_shortcode('detail_track', 'get_detail_track');
