<?php

function get_show_program($attributes) {
    if (is_admin()) {
        return;
    }
    $prg_id = (isset($attributes['id'])) ? $attributes['id'] : 0;
    $download = (isset($attributes['download'])) ? $attributes['download'] : TRUE;
    if ($prg_id == 0) {
        return;
    }

    include MSC_PLUGIN_DIR.'connect_api.php';
    
    $Vars[0] = 'id=' . $prg_id;
    $list = $MyRadio->QueryGetTable(seccions::PROGRAMS, sub_seccions::SHOWINFO_PRG, $Vars);
    if ($MyRadio->RESPOSTA_ROWS > 0) {
        $nom_programa = $list['item']['NAME'];
        //$StrReturn .='<h1>'.  utf8_decode($nom_programa).'</h1>';                                
        $upload_dir = wp_upload_dir();
        $PathToSaveImg = $upload_dir['basedir'] . '/' . TMP_IMG_DIR . '/prg-' . $prg_id . '.jpg';
        $PathToShowImg = $upload_dir['baseurl'] . '/' . TMP_IMG_DIR . '/prg-' . $prg_id . '.jpg';
        if (getImage(base64_decode($list['item']['IMAGE']), $PathToSaveImg, 700) == TRUE) {
            $StrReturn .= '<img src="' . $PathToShowImg . '" class="aligncenter size-full"><br><br>';
        }
        $StrReturn .= '<p>' . html_entity_decode($list['item']['DESCRIP']) . '</p><br>';
        $StrReturn .= '<p>' . __('Thematic', 'msc-automation') . ': ' . $list['item']['TOPIC'] . '<br>';
        $StrReturn .= __('Duration', 'msc-automation') . ': ' . $list['item']['DURATION'] . '</p>';

        $listTags = $list['item']['TAGS']['TAG'];
        $rows = count($listTags); //(count($listTags,1)/count($listTags,0))-1;            
        if ($rows > 0) {
            $StrReturn .= '<div id="content"> ';
            $StrReturn .= '<h2>' . htmlentities(__('Program tags', 'msc-automation')) . ': ' . '</h2';
            $StrReturn .= '<p>' . tag_cloud($listTags, "#", 10) . '</p>';
            $StrReturn .= '</div>';
        }
        //Facebook del programa
        $url_facebook = $list['item']['FACEBOOK'];
        if (strlen($url_facebook) > 3) {
            $StrReturn .= '<div id="content"> ';
            $StrReturn .= '<h3>' . htmlentities($nom_programa) . ' ' . __('On', 'msc-automation') . ' Facebook</h3>';
            $fb_prg = new Facebook($url_facebook);
            //$fb_prg->show_Co mments(20, 450, ColorScheme::COLOR_SCHEME_LIGHT);
            $StrReturn .= $fb_prg->show_LikeBox(ColorScheme::COLOR_SCHEME_LIGHT, 550, 200, true);
            $StrReturn .= '</div>';
        }
        //Twitter del programa
        $LinkTwitter = $list['item']['TWITTER'];
        if (strlen($LinkTwitter) > 3) {
            $StrReturn .= '<div id="content"> ';
            $StrReturn .= '<h3>' . htmlentities($nom_programa) . ' ' . __('On', 'msc-automation') . ' Twitter</h3>';
            $twitter_prg = new twitter($LinkTwitter, $MyRadio->LANG);
            $StrReturn .= $twitter_prg->show_FollowButton();
            $StrReturn .= '</div>';
        }
        //Podcast del programa
        $list_podcast = $list['item']['PODS']['POD'];
        $rows = count($list_podcast);
        $counter = 0;
        $upload_dir = wp_upload_dir();        
        $url_podcast = $upload_dir['baseurl'].'/'.PODCAST_DIR;
        $base_URL_Share = get_home_url(0, NAME_PAGE_TRACK . '/');


        $StrReturn .= '<div>';
        
        while ($counter < $rows):
            if ($rows == 1) {
                $id = $list_podcast["ID"];
                //$nom_programa = $list_podcast['NAME'];
                $descrip = htmlentities($list_podcast['DESCRIP']);
                $duration = $list_podcast['DURATION'];
                $data_crea = $list_podcast['DATE_PUBLICATION'];
                $titol = $nom_programa . ' ' . date('d-m-Y', strtotime($data_crea));
                $urlmp3 = $url_podcast . '/' . $list_podcast['FILE'];

                $marks = $list_podcast['MARKS']['MARK'];
            } else {
                $id = $list_podcast[$counter]["ID"];
                //$nom_programa = htmlentities($list_podcast[$counter]['NAME']);
                $descrip = htmlentities($list_podcast[$counter]['DESCRIP']);
                $duration = $list_podcast[$counter]['DURATION'];
                $data_crea = $list_podcast[$counter]['DATE_PUBLICATION'];
                $titol = $nom_programa . ' ' . date('d-m-Y', strtotime($data_crea));
                $urlmp3 = $url_podcast . '/' . $list_podcast[$counter]['FILE'];
                //TODO: contemplar si el player és el pròpi.
                $marks = $list_podcast[$counter]['MARKS']['MARK'];
            }
            $urldownload = MSC_PLUGIN_URL . 'inc/download.php?fileurl=' . $urlmp3 . '&filename=' . urlencode($nom_programa) . '&id=' . $id;
            $ref= "?ref=".bin2hex($id.','.TIP_AUTOMATIC_PROGRAMA);
            $URL_Share = $base_URL_Share.$ref;
            $URL_Facebook = 'https://www.facebook.com/sharer/sharer.php?t='.urlencode($nom_programa).'&u='.$URL_Share;
            $URL_Twitter = 'https://twitter.com/share?via=TWITTER_HANDLE&text='.urlencode($nom_programa).'&url='.$URL_Share.'';            
            $URL_Pinterest = 'https://pinterest.com/pin/create/button/?description=' . urlencode($nom_programa).'&url=' . $URL_Share  ;
            $URL_Linked_in = 'https://www.linkedin.com/shareArticle?mini=true&title=' . urlencode($nom_programa).'&url=' . $URL_Share ;
            $URL_WhatsApp = 'https://wa.me/?text=' . $nom_programa . '+-+' . $URL_Share;
            $URL_Iframe = '<iframe src="'.$URL_Share.'" allowfullscreen scrolling="no" frameborder="0" width="270px" height="370px"></iframe>';
            
            $count_marks = count($marks);
            if ($count_marks > 0) {
                $StrReturn .= '<li class="fas fa-plus-square" onclick="displayList(this, list_parts_' . $counter . ')" style="padding:5px"></li>'
                        . '<a class="fpod" data-pos="0" data-pod="' . $id . '" data-href="' . $urlmp3 . '" href="javascript:void" onclick="playThisFile(this)" >' . $titol . '</a>';
                if (strlen($descrip) > 2) {
                    $StrReturn .= '<i>' . $descrip . '</i><br>';
                }
                $StrReturn .= '<i class="fas fa-clock"></i><i> [' . $duration . '] </i> '
                        . '<a class="no-ajaxy fas fa-download" href="' . $urldownload . '"></a>';
                $StrReturn .= '<ul style="display:none" id="list_parts_' . $counter . '">';
                $counter_mark = 0;
                while ($counter_mark < $count_marks):
                    $seg = $marks[$counter_mark]['SECOND'];
                    $comment = $marks[$counter_mark]['COMMENT'];
                    $StrReturn .= '<li style="margin-left:50px"><a class="fpod" data-pos="' . $seg . '" data-pod="' . $id . '" data-href="' . $urlmp3 . '" href="javascript:void" onclick="playThisFile(this)" >' . $comment . '</a></li>';
                    $counter_mark++;
                endwhile;
                $StrReturn .= '</ul>';
            } else {
                if (get_option('msc_player') == 'nothing') {
                    $StrReturn .= '<figure class="wp-block-audio">' . $titol . '<br><audio controls="" src=' . $urlmp3 . ' style="width:90%"></audio><i class="fas fa-clock"></i><i> [' . $duration . '] </i><br>'
                            . '<a class="no-ajaxy fas fa-arrow-alt-square-down fa-2x" href="' . $urldownload . '"></a>';
                    $StrReturn .= social_share($id, $URL_Facebook, $URL_Twitter, $URL_Pinterest, $URL_Linked_in, $URL_WhatsApp, $URL_Iframe);
                    $StrReturn .= '</figure>';
                } else {
                    $StrReturn .= '<ul><li>'
                            . '<a class="fpod" data-pos="0" data-pod="' . $id . '" data-href="' . $urlmp3 . '" href="javascript:void" onclick="playThisFile(this)" >' . $titol . '</a>'
                            . '<i class="fas fa-clock"></i><i> [' . $duration . '] </i><br>'
                            . '<a class="no-ajaxy fas fa-arrow-alt-square-down fa-2x" href="' . $urldownload . '"></a>';
                    $StrReturn .= social_share($id, $URL_Facebook, $URL_Twitter, $URL_Pinterest, $URL_Linked_in, $URL_WhatsApp, $URL_Iframe);
                    $StrReturn .= '</li></ul>';
                }
            }
            $counter++;
        endwhile;
        $StrReturn .= '</div>';

        return $StrReturn;
    }
}

add_shortcode('show_program', 'get_show_program');

function get_cloud_tags_programs() {
    //list of programs with own tags
    //tag_cloud($listTags,$urlDesti,$div_size = 400);
}

add_shortcode('cloud_tags_programs', 'get_cloud_tags_programs');

function get_list_programs() {
    if (is_admin()) {
        return;
    }
    include MSC_PLUGIN_DIR.'connect_api.php';

    $list = $MyRadio->QueryGetTable(seccions::PROGRAMS, sub_seccions::LIST_PRGS);

    $counter = 0;

    $strReturn = '';
    //$strReturn .'<div class="wp-block-columns">';
    while ($counter < $MyRadio->RESPOSTA_ROWS):
        $prg_id = $list['item'][$counter]['ID'];
        $titol = $list['item'][$counter]['NAME'];
        $tema = $list['item'][$counter]['TEMATICA'];

        $page = get_page_by_title(html_entity_decode($titol));
        if (isset($page->ID)) {
            $url_prg = $page->guid;          
        } 

        $upload_dir = wp_upload_dir();
        $PathToSaveImg = $upload_dir['basedir'] . '/' . TMP_IMG_DIR . '/prg-' . $prg_id . '.jpg';
        $PathToShowImg = $upload_dir['baseurl'] . '/' . TMP_IMG_DIR . '/prg-' . $prg_id . '.jpg';
        if (getImage(base64_decode($list['item'][$counter]['IMAGE']), $PathToSaveImg, 250) == TRUE) {
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
    //$strReturn .='</div>';
    echo $strReturn;
}

add_shortcode('list_programs', 'get_list_programs');
