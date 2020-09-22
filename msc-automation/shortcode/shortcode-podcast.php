<?php

function get_last_podcast(){
    if (is_admin()){return;}
    global $MyRadio;
    $my_key = get_option('msc_client_key');
    if(!isset($MyRadio)){
        $MyRadio = new my_radio($my_key, get_locale(), get_option('msc_debug'));
    }

    if ($MyRadio->RESPOSTA_STATUS !== SUCCES ){
        if ($MyRadio->IS_DEGUG == true){
            $msg = 'STATUS: '.$MyRadio->RESPOSTA_STATUS.' CODE: '.$MyRadio->RESPOSTA_CODE.' MSG: '.$MyRadio->RESPOSTA_MESSAGE;
            show_msc_message($msg, message_type::DANGER);
            return;
        }
    }

    $list_podcast = $MyRadio->QueryGetTable(seccions::PROGRAMS, sub_seccions::LISTPODCAST_PRG, '');

    if ($MyRadio->RESPOSTA_ROWS>0){
        $counter = 0;
        $upload_dir = wp_upload_dir();
        $url_podcast = $upload_dir['baseurl'].'/'.PODCAST_DIR;
        $base_URL_Share = get_home_url(0, NAME_PAGE_TRACK . '/') . '?id=';
        
//$StrReturn .='<div id="jp_container"><div>';
        $StrReturn .= '<div id="jp_container">';
        while($counter < $MyRadio->RESPOSTA_ROWS):
            $id = $list_podcast['item'][$counter]["ID"];
            $nom_programa = ($list_podcast['item'][$counter]['NAME']);
            $descrip = ($list_podcast['item'][$counter]['DESCRIP']);
            $duration = $list_podcast['item'][$counter]['DURATION'];
            $data_crea = $list_podcast['item'][$counter]['DATE_PUBLICATION'];
            $titol = $nom_programa.' '.date('d-m-Y', strtotime($data_crea));
            $urlmp3 = $url_podcast.'/'.$list_podcast['item'][$counter]['FILE'];
            $urldownload = MSC_PLUGIN_URL.'inc/download.php?fileurl='.$urlmp3.'&filename='.urlencode($nom_programa).'&id='.$id.'&key='.$my_key;

            $URL_Share = $base_URL_Share.$id.'&type='.TIP_AUTOMATIC_PROGRAMA;
            $URL_Facebook = 'https://www.facebook.com/sharer/sharer.php?t='.urlencode($nom_programa).'&u='.$URL_Share;
            $URL_Twitter = 'https://twitter.com/share?via=TWITTER_HANDLE&text='.urlencode($nom_programa).'&url='.$URL_Share.'';            
            $URL_Pinterest = 'https://pinterest.com/pin/create/button/?description=' . urlencode($nom_programa).'&url=' . $URL_Share  ;
            $URL_Linked_in = 'https://www.linkedin.com/shareArticle?mini=true&title=' . urlencode($nom_programa).'&url=' . $URL_Share ;
            $URL_WhatsApp = 'https://wa.me/?text=' . $nom_programa . '+-+' . $URL_Share;
            $URL_Iframe = '<iframe src="'.$URL_Share.'" allowfullscreen scrolling="no" frameborder="0" width="270px" height="370px"></iframe>';
            
            $StrReturn .= '<h3 id="pod-title-'.$counter.'" ><b>'.htmlentities($nom_programa).'</b></h3>';
            if (strlen($descrip)>2){$StrReturn .= '<i>'.$descrip.'</i><br>';
            }
            $marks = $list_podcast['item'][$counter]['MARKS']['MARK'];
            if(is_array($marks)==true ){
                $count_marks = count($marks);
            }else{
                $count_marks = 0;
            }                        
            if($count_marks>0){
                $StrReturn  .= '<li class="fas fa-plus-square" onclick="displayList(this, list_parts_'.$counter.')" style="padding:5px"></li>'
                            . '<a class="track" data-pos="0" data-pod="'.$id.'" href="'.$urlmp3.'">'.$titol.'</a>'
                            . '<i class="fas fa-clock"></i><i> ['.$duration.'] </i>'
                            . '<br><a class="no-ajaxy fas fa-download" href="'.$urldownload.'"></a>';                  
                $StrReturn  .= social_share($id,$URL_Facebook,$URL_Twitter,$URL_Pinterest,$URL_Linked_in,$URL_WhatsApp,$URL_Iframe);
                $StrReturn .= '<ul style = "display:none" id = "list_parts_'.$counter.'">';
                $counter_mark = 0;                
                while($counter_mark < $count_marks):  
                    $seg = $marks[$counter_mark]['SECOND'];
                    $comment = $marks[$counter_mark]['COMMENT'];
                    $StrReturn .= '<li style = "margin-left:50px"><a class = "track no-ajaxy" data-pos = "'.$seg.'" data-pod = "'.$id.'" href = "'.$urlmp3.'">'.$comment.'</a></li>';                        
                    $counter_mark++;
                endwhile;
                $StrReturn .= '</ul>';                        
            }else{
                if (get_option('msc_player')=='nothing'){
                       $StrReturn .= '<figure class="wp-block-audio">'.$titol.'<br><audio controls="" src='.$urlmp3.' style="width:90%"></audio><i class="fas fa-clock"></i><i> [' . $duration . '] </i><br>'
                            . '<a class="no-ajaxy fas fa-arrow-alt-square-down fa-2x" href="' . $urldownload . '"></a>';
                       $StrReturn  .= social_share($id,$URL_Facebook,$URL_Twitter,$URL_Pinterest,$URL_Linked_in,$URL_WhatsApp,$URL_Iframe);            
                       $StrReturn  .= '</figure>';
                    }else{
                        $StrReturn .= '<ul><li>'
                                    . '<a class="track no-ajaxy" data-pos="0" data-pod="' . $id . '" href="' . $urlmp3 . '">' . $titol . '</a>'
                                    . '<i class="fas fa-clock"></i><i> [' . $duration . '] </i><br>'                                
                                    . '<a class="no-ajaxy fas fa-arrow-alt-square-down fa-2x" href="' . $urldownload . '"></a>';
                        $StrReturn  .= social_share($id,$URL_Facebook,$URL_Twitter,$URL_Pinterest,$URL_Linked_in,$URL_WhatsApp,$URL_Iframe);            
                        $StrReturn  .= '</li></ul>';
                    }
            }                 
            $counter ++; 
        endwhile;                            
        $StrReturn .= '</div>';
        return $StrReturn ;  
        }
    }
    
    function social_share($id,$URL_Facebook,$URL_Twitter,$URL_Pinterest,$URL_Linked_in,$URL_WhatsApp,$URL_Iframe){
        return '<a id="fb-'.$id.'" class="fab fa-facebook-square fa-2x" href="'.$URL_Facebook.'" onclick="javascript:window.open(this.href, "", "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600");return false;" 
                                            target="_blank" title="'. __('Share on Facebook', 'msc-automation').'">
                                        </a>                                                
                                        <a id="tw-'.$id.'" class="fab fa-twitter-square fa-2x" href="'.$URL_Twitter.'"
                                           onclick="javascript:window.open(this.href, "", "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600");return false;"
                                           target="_blank" title="'.__('Share on Twitter', 'msc-automation').'">
                                        </a>                                            
                                        <a id="pt-'.$id.'" class="fab fa-pinterest-square fa-2x" href="'.$URL_Pinterest.'"
                                           onclick="javascript:window.open(this.href, "", "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600");return false;"
                                           target="_blank" title="'.__('Share on Pinterest', 'msc-automation').'">
                                        </a>                                            
                                        <a id="li-'.$id.'" class="fab fa-linkedin fa-2x" href="'.$URL_Linked_in.'"
                                           onclick="javascript:window.open(this.href, "", "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600");return false;"
                                           target="_blank" title="'.__('Share on LinkedIn', 'msc-automation').'">
                                        </a>                                            
                                        <a id="wa-'.$id.'" class="fab fa-whatsapp-square fa-2x" href="'.$URL_WhatsApp.'"
                                           onclick="javascript:window.open(this.href, "", "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600");return false;"
                                           target="_blank" title="'.__('Share on WhatsApp', 'msc-automation').'">
                                        </a>';
                                        /*'<a class="fas fa-code fa-2x" data-pod="'. $id.'" onclick="ShowIframeCode(this)" title="'. __('Share on your web', 'msc-automation').'" href="javascript:void"></a>
                                        <div id="iframe-'.$id.'" style="display:none">
                                            <textarea type="text"  id="ifr">'.$URL_Iframe.'</textarea>
                                            <i>'.__('Copy this code for add in your web', 'msc-automation').'</i>
                                        </div>';*/
    }
    
    
    add_shortcode('last_podcast', 'get_last_podcast');    