<?php

function mscra_get_show_program($attributes)
{

    if (is_admin()) {return;}

    $prg_id = (isset($attributes['id'])) ? $attributes['id'] : 0;
    $download = (isset($attributes['download'])) ? $attributes['download'] : true;
    if ($prg_id == 0) {
        return;
    }

    include MSCRA_PLUGIN_DIR . 'connect_api.php';

    $Vars[0] = 'id=' . $prg_id;
    $list = $MyRadio->QueryGetTable(seccions::PROGRAMS, sub_seccions::SHOWINFO_PRG, $Vars);
    $StrReturn = "";
    if ($MyRadio->RESPOSTA_ROWS > 0) {
        $nom_programa = sanitize_text_field($list['item']['NAME']);
        //$StrReturn .='<h1>'.  utf8_decode($nom_programa).'</h1>';
        $upload_dir = wp_upload_dir();
        $PathToSaveImg = $upload_dir['basedir'] . '/' . WP_MSCRA_TMP_IMG_DIR . '/prg-' . $prg_id . '.jpg';
        $PathToShowImg = $upload_dir['baseurl'] . '/' . WP_MSCRA_TMP_IMG_DIR . '/prg-' . $prg_id . '.jpg';
        if (mscra_getImage(base64_decode($list['item']['IMAGE']), $PathToSaveImg, 700) == true) {
            $StrReturn .= '<img src="' . $PathToShowImg . '" class="aligncenter size-full"><br><br>';
        }
        $StrReturn .= '<p>' . html_entity_decode(sanitize_text_field($list['item']['DESCRIP'])) . '</p><br>';
        $StrReturn .= '<p>' . __('Thematic', 'mscra-automation') . ': ' . sanitize_text_field($list['item']['TOPIC']) . '<br>';
        $StrReturn .= __('Duration', 'mscra-automation') . ': ' . sanitize_text_field($list['item']['DURATION']) . '</p>';
        if (isset($list['item']['TAGS']['TAG'])) {
            $listTags = $list['item']['TAGS']['TAG'];
            $rows = count($listTags);
        } else {
            $rows = 0;
        }

        if ($rows > 0) {
            $StrReturn .= '<div id="content"> ';
            $StrReturn .= '<h2>' . htmlentities(__('Program tags', 'mscra-automation')) . ': ' . '</h2';
            $StrReturn .= '<p>' . mscra_tag_cloud($listTags, "#", 10) . '</p>';
            $StrReturn .= '</div>';
        }
        //Facebook del programa
        $url_facebook = sanitize_text_field($list['item']['FACEBOOK']);
        if (strlen($url_facebook) > 3) {
            $StrReturn .= '<div id="content"> ';
            $StrReturn .= '<h3>' . htmlentities($nom_programa) . ' ' . __('On', 'mscra-automation') . ' Facebook</h3>';
            $fb_prg = new mscra_Facebook($url_facebook);
            $StrReturn .= $fb_prg->show_LikeBox(ColorScheme::COLOR_SCHEME_LIGHT, 550, 200, true);
            $StrReturn .= '</div>';
        }
        //Twitter del programa
        $LinkTwitter = sanitize_text_field($list['item']['TWITTER']);
        if (strlen($LinkTwitter) > 3) {
            $StrReturn .= '<div id="content"> ';
            $StrReturn .= '<h3>' . htmlentities($nom_programa) . ' ' . __('On', 'mscra-automation') . ' Twitter</h3>';
            $twitter_prg = new mscra_twitter($LinkTwitter, $MyRadio->LANG);
            $StrReturn .= $twitter_prg->show_FollowButton();
            $StrReturn .= '</div>';
        }
        //Podcast del programa
        if (isset($list['item']['PODS']['POD'])) {
            $list_podcast = $list['item']['PODS']['POD'];
        }

        $upload_dir = wp_upload_dir();
        $url_podcast = $upload_dir['baseurl'] . '/' . WP_MSCRA_PODCAST_DIR;

        $page = mscra_get_page_by_meta(MSCRA_HOOK_TRACK);
        $base_URL_Share = get_permalink($page->ID);

        $StrReturn .= '<div>';
        if (isset($list_podcast)) {            
            if ($nom_programa=='Kavouras'){
                //TODO: provisional per gelida                
                $rows = count($list_podcast["ID"]);
            }else{
                $rows = count($list_podcast);
            }            
            
        } else {
            $rows = 0;
        }
        $counter = 0;        
        while ($counter < $rows):
            if ($rows == 1) {                
                $id = $list_podcast["ID"];
                //$nom_programa = $list_podcast['NAME'];
                $descrip = htmlentities(sanitize_text_field($list_podcast['DESCRIP']));
                $duration = sanitize_text_field($list_podcast['DURATION']);
                $data_crea = sanitize_text_field($list_podcast['DATE_PUBLICATION']);
                $titol = $nom_programa . ' ' . date('d-m-Y', strtotime($data_crea));
                $urlmp3 = $url_podcast . '/' . sanitize_text_field($list_podcast['FILE']);
                if (isset($list_podcast['MARKS']['MARK'])) {
                    $marks = $list_podcast['MARKS']['MARK'];
                }
            } else {
                
                $id = $list_podcast[$counter]["ID"];
                //$nom_programa = htmlentities($list_podcast[$counter]['NAME']);
                $descrip = htmlentities(sanitize_text_field($list_podcast[$counter]['DESCRIP']));
                $duration = sanitize_text_field($list_podcast[$counter]['DURATION']);
                $data_crea = sanitize_text_field($list_podcast[$counter]['DATE_PUBLICATION']);
                $titol = $nom_programa . ' ' . date('d-m-Y', strtotime($data_crea));
                $urlmp3 = $url_podcast . '/' . sanitize_text_field($list_podcast[$counter]['FILE']);
                //TODO: contemplar si el player és el pròpi.
                if (isset($list_podcast[$counter]['MARKS']['MARK'])) {
                    $marks = $list_podcast[$counter]['MARKS']['MARK'];
                }
            }

            $params = array('fileurl' => $urlmp3,
                'download_file' => 1,
                'id' => $id,
            );
            $urldownload = add_query_arg($params, get_permalink());

            $params = array('ref' => bin2hex($id . ',' . TIP_AUTOMATIC_PROGRAMA));
            $URL_Share = add_query_arg($params, $base_URL_Share);

            $URL_Facebook = 'https://www.facebook.com/sharer/sharer.php?t=' . urlencode($nom_programa) . '&u=' . $URL_Share;
            $URL_Twitter = 'https://twitter.com/share?via=TWITTER_HANDLE&text=' . urlencode($nom_programa) . '&url=' . $URL_Share . '';
            $URL_Pinterest = 'https://pinterest.com/pin/create/button/?description=' . urlencode($nom_programa) . '&url=' . $URL_Share;
            $URL_Linked_in = 'https://www.linkedin.com/shareArticle?mini=true&title=' . urlencode($nom_programa) . '&url=' . $URL_Share;
            $URL_WhatsApp = 'https://wa.me/?text=' . $nom_programa . '+-+' . $URL_Share;
            $URL_Iframe = '<iframe src="' . $URL_Share . '" allowfullscreen scrolling="no" frameborder="0" width="270px" height="370px"></iframe>';
            if (isset($marks)) {
                $count_marks = count($marks);
            } else {
                $count_marks = 0;
            }

            if ($count_marks > 0) {
                $StrReturn .= '<li class="fas fa-plus-square" onclick="mscra_displayList(this, list_parts_' . $counter . ')" style="padding:5px"></li>'
                    . '<a class="fpod" data-pos="0" data-pod="' . $id . '" data-href="' . $urlmp3 . '" href="javascript:void" onclick="mscra_PlayThisFile(this)" >' . $titol . '</a>';
                if (strlen($descrip) > 2) {
                    $StrReturn .= '<i>' . $descrip . '</i><br>';
                }
                $StrReturn .= '<i class="fas fa-clock"></i><i> [' . $duration . '] </i> ';

                $StrReturn .= '<ul style="display:none" id="list_parts_' . $counter . '">';
                $counter_mark = 0;
                while ($counter_mark < $count_marks):
                    $seg = sanitize_text_field($marks[$counter_mark]['SECOND']);
                    $comment = sanitize_text_field($marks[$counter_mark]['COMMENT']);
                    $StrReturn .= '<li style="margin-left:50px"><a class="fpod" data-pos="' . $seg . '" data-pod="' . $id . '" data-href="' . $urlmp3 . '" href="javascript:void" onclick="mscra_PlayThisFile(this)" >' . $comment . '</a></li>';
                    $counter_mark++;
                endwhile;
                $StrReturn .= '</ul>';
                $StrReturn .= '<p>' . mscra_social_share($id, $URL_Facebook, $URL_Twitter, $URL_Pinterest, $URL_Linked_in, $URL_WhatsApp, $URL_Iframe, $urldownload) . '</p>';
            } else {
                if (get_option('mscra_player') == 'nothing') {
                    $StrReturn .= '<figure class="wp-block-audio">' . $titol . '<br><audio controls="" src=' . $urlmp3 . ' style="width:90%"></audio><i class="fas fa-clock"></i><i> [' . $duration . '] </i><br>';
                    $StrReturn .= mscra_social_share($id, $URL_Facebook, $URL_Twitter, $URL_Pinterest, $URL_Linked_in, $URL_WhatsApp, $URL_Iframe, $urldownload, $urldownload);
                    $StrReturn .= '</figure>';
                } else {
                    $StrReturn .= '<p>';
                    $StrReturn .= '<li class="fas fa-podcast" style="padding:5px"></li>'
                        . '<a class="fpod" data-pos="0" data-pod="' . $id . '" data-href="' . $urlmp3 . '" href="javascript:void" onclick="mscra_PlayThisFile(this)" >' . $titol . '</a>'
                        . '<i class="fas fa-clock"></i><i> [' . $duration . '] </i><br>';
                    $StrReturn .= mscra_social_share($id, $URL_Facebook, $URL_Twitter, $URL_Pinterest, $URL_Linked_in, $URL_WhatsApp, $URL_Iframe, $urldownload);
                    $StrReturn .= '</p>';
                }
            }
            $counter++;
        endwhile;
        $StrReturn .= '</div>';

        return $StrReturn;
    }
}

add_shortcode('mscra_show_program', 'mscra_get_show_program');

function mscra_get_cloud_tags_programs()
{
    //list of programs with own tags
    //mscra_tag_cloud($listTags,$urlDesti,$div_size = 400);
}

add_shortcode('msc_cloud_tags_programs', 'mscra_get_cloud_tags_programs');

function mscra_get_list_programs()
{
    if (is_admin()) {return;}

    include MSCRA_PLUGIN_DIR . 'connect_api.php';

    $list = $MyRadio->QueryGetTable(seccions::PROGRAMS, sub_seccions::LIST_PRGS);

    $counter = 0;

    $strReturn = '';
    while ($counter < $MyRadio->RESPOSTA_ROWS):
        $prg_id = sanitize_text_field($list['item'][$counter]['ID']);
        $titol = sanitize_text_field($list['item'][$counter]['NAME']);
        $tema = sanitize_text_field($list['item'][$counter]['TEMATICA']);

        $page = get_page_by_title(html_entity_decode($titol));
        if (isset($page->ID)) {
            $url_prg = $page->guid;
        }

        $upload_dir = wp_upload_dir();
        $PathToSaveImg = $upload_dir['basedir'] . '/' . WP_MSCRA_TMP_IMG_DIR . '/prg-' . $prg_id . '.jpg';
        $PathToShowImg = $upload_dir['baseurl'] . '/' . WP_MSCRA_TMP_IMG_DIR . '/prg-' . $prg_id . '.jpg';
        if (mscra_getImage(base64_decode($list['item'][$counter]['IMAGE']), $PathToSaveImg, 250) == true) {
            //$strReturn .= '<img src="' . $PathToShowImg . '" class="aligncenter size-full">';
            $strReturn .= '<a href="' . $url_prg . '"><div class="prg-list" style=background-image:url(' . $PathToShowImg . ')><p>';
        } else {
            $strReturn .= '<div class="prg-list"><p>';
        }
        //$strReturn .='<i>'.$tema.'</i>';
        $strReturn .= '<h3>' . $titol . '</h3>';

        $strReturn .= '</p></div></a>';

        $counter = $counter + 1;
    endwhile;
    echo $strReturn;
}

add_shortcode('mscra_list_programs', 'mscra_get_list_programs');
