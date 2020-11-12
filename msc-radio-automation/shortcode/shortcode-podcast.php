<?php

function mscra_get_last_podcast()
{
    if (is_admin()) {
        return;
    }
    include MSCRA_PLUGIN_DIR . 'connect_api.php';

    $list_podcast = $MyRadio->QueryGetTable(seccions::PROGRAMS, sub_seccions::LISTPODCAST_PRG, '');

    if ($MyRadio->RESPOSTA_ROWS > 0) {
        $counter = 0;
        $upload_dir = wp_upload_dir();
        $url_podcast = $upload_dir['baseurl'] . '/' . WP_MSCRA_PODCAST_DIR;

        $page = mscra_get_page_by_meta(MSCRA_HOOK_TRACK);
        $base_URL_Share = $page->guid;

        $StrReturn = '<div>';
        while ($counter < $MyRadio->RESPOSTA_ROWS):
            $id = sanitize_text_field($list_podcast['item'][$counter]["ID"]);
            $nom_programa = sanitize_text_field($list_podcast['item'][$counter]['NAME']);
            $descrip = sanitize_text_field($list_podcast['item'][$counter]['DESCRIP']);
            $duration = sanitize_text_field($list_podcast['item'][$counter]['DURATION']);
            $data_crea = sanitize_text_field($list_podcast['item'][$counter]['DATE_PUBLICATION']);
            $titol = $nom_programa . ' ' . date('d-m-Y', strtotime($data_crea));
            $urlmp3 = strtolower($url_podcast . '/' . sanitize_text_field($list_podcast['item'][$counter]['FILE']));
            $params = array('fileurl' => $urlmp3,
                'download_file' => 1,
                'id' => $id,
            );
            $urldownload = add_query_arg($params, get_permalink());

            $hexid = bin2hex($id . ',' . TIP_AUTOMATIC_PROGRAMA);
            $params = array('ref' => $hexid);
            $URL_Share = add_query_arg($base_URL_Share, $params);

            $URL_Facebook = 'https://www.facebook.com/sharer/sharer.php?t=' . urlencode($nom_programa) . '&u=' . $URL_Share;
            $URL_Twitter = 'https://twitter.com/share?via=TWITTER_HANDLE&text=' . urlencode($nom_programa) . '&url=' . $URL_Share . '';
            $URL_Pinterest = 'https://pinterest.com/pin/create/button/?description=' . urlencode($nom_programa) . '&url=' . $URL_Share;
            $URL_Linked_in = 'https://www.linkedin.com/shareArticle?mini=true&title=' . urlencode($nom_programa) . '&url=' . $URL_Share;
            $URL_WhatsApp = 'https://wa.me/?text=' . $nom_programa . '+-+' . $URL_Share;
            $URL_Iframe = '<iframe src="' . $URL_Share . '" allowfullscreen scrolling="no" frameborder="0" width="270px" height="370px"></iframe>';

            $StrReturn .= '<h3 id="pod-title-' . $counter . '" ><b>' . htmlentities($nom_programa) . '</b></h3>';
            if (strlen($descrip) > 2) {
                $StrReturn .= '<i>' . $descrip . '</i><br>';
            }
            if (isset($list_podcast['item'][$counter]['MARKS']['MARK'])) {
                $marks = $list_podcast['item'][$counter]['MARKS']['MARK'];
                $count_marks = count($marks);
            } else {
                $count_marks = 0;
            }

            if ($count_marks > 0) {
                $StrReturn .= '<li class="fas fa-plus-square" onclick="mscra_displayList(this, list_parts_' . $counter . ')" style="padding:5px"></li>'
                    . '<a class="fpod" data-pos="0" data-pod="' . $id . '" data-href="' . $urlmp3 . '" href="javascript:void" onclick="mscra_PlayThisFile(this)" >' . $titol . '</a>'
                    . '<i class="fas fa-clock"></i><i> [' . $duration . '] </i>';
                $StrReturn .= mscra_social_share($id, $URL_Facebook, $URL_Twitter, $URL_Pinterest, $URL_Linked_in, $URL_WhatsApp, $URL_Iframe, $urldownload);
                $StrReturn .= '<ul style = "display:none" id = "list_parts_' . $counter . '">';
                $counter_mark = 0;
                while ($counter_mark < $count_marks):
                    $seg = sanitize_text_field($marks[$counter_mark]['SECOND']);
                    $comment = sanitize_text_field($marks[$counter_mark]['COMMENT']);
                    $StrReturn .= '<li style="margin-left:50px"><a class="fpod" data-pos="' . $seg . '" data-pod="' . $id . '" data-href="' . $urlmp3 . '" href="javascript:void" onclick="mscra_PlayThisFile(this)" >' . $comment . '</a></li>';
                    $counter_mark++;
                endwhile;
                $StrReturn .= '</ul>';
            } else {
                if (get_option('mscra_player') == 'nothing') {
                    $StrReturn .= '<figure class="wp-block-audio">' . $titol . '<br><audio controls="" src=' . $urlmp3 . ' style="width:90%"></audio><i class="fas fa-clock"></i><i> [' . $duration . '] </i><br>';
                    $StrReturn .= mscra_social_share($id, $URL_Facebook, $URL_Twitter, $URL_Pinterest, $URL_Linked_in, $URL_WhatsApp, $URL_Iframe, $urldownload);
                    $StrReturn .= '</figure>';
                } else {
                    $StrReturn .= '<ul><li>'
                        . '<a class="fpod" data-pos="0" data-pod="' . $id . '" data-href="' . $urlmp3 . '" href="javascript:void" onclick="mscra_PlayThisFile(this)" >' . $titol . '</a>'
                        . '<i class="fas fa-clock"></i><i> [' . $duration . '] </i><br>';
                    $StrReturn .= mscra_social_share($id, $URL_Facebook, $URL_Twitter, $URL_Pinterest, $URL_Linked_in, $URL_WhatsApp, $URL_Iframe, $urldownload);
                    $StrReturn .= '</li></ul>';
                }
            }
            $counter++;
        endwhile;
        $StrReturn .= '</div>';
        return $StrReturn;
    }
}

add_shortcode('mscra_last_podcast', 'mscra_get_last_podcast');
