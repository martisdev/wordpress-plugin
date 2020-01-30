<?php

function get_last_podcast(){
    global $MyRadio;
    if(!isset($MyRadio)){
        $MyRadio = new my_radio(get_option('msc_client_key'), get_locale(), get_option('msc_debug'));
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
            $urldownload = MSC_PLUGIN_URL.'inc/download.php?fileurl='.$urlmp3.'&filename='.urlencode($nom_programa);

            $URL_Share = $base_URL_Share.$list['item'][$counter]['ID'].'&type='.TIP_AUTOMATIC_PROGRAMA;
            $URL_Facebook = 'https://www.facebook.com/sharer/sharer.php?u='.$URL_Share.'&t='.$nom_programa;
            $URL_Twitter = 'https://twitter.com/share?url='.$URL_Share.'&via=TWITTER_HANDLE&text='.$nom_programa;
            $URL_Iframe = '<iframe src="'.$URL_Share.'" allowfullscreen scrolling="no" frameborder="0" width="270px" height="370px"></iframe>';

            $StrReturn .= '<h3 id="pod-title-'.$counter.'" ><b>'.htmlentities($nom_programa).'</b></h3>';
            if (strlen($descrip)>2){$StrReturn .= '<i>'.$descrip.'</i><br>';
            }
            $marks = $list_podcast['item'][$counter]['MARKS']['MARK'];
            $count_marks = count($marks);
            if($count_marks>0){
                $StrReturn  .= '<li class="fas fa-plus-square" onclick="displayList(this, list_parts_'.$counter.')" style="padding:5px"></li>'
                            . '<a class="track" data-pos="0" data-pod="'.$id.'" href="'.$urlmp3.'">'.$titol.'</a>'
                            . '<i class="fas fa-clock"></i><i> ['.$duration.'] </i> '
                            . '<a class="no-ajaxy fas fa-download" href="'.$urldownload.'"></a>';                                
                /*$StrReturn .=  '<a id="BtnShare" class="fas fa-share-alt" aria-hidden="true" onclick="ShowModalshare()" href="javascript:void"></a>
                                    <div id="myModalShare" class="modalShare">
                                    <!-- Modal content -->
                                    <div class="modal-content_share">
                                      <span class="closeShare"><i class="fas fa-times fa-2x"></i></span>
                                      <a id="fb" class="fab fa-facebook-square fa-2x" href="'.$URL_Facebook.'" onclick="javascript:window.open(this.href, "", "menubar = no, toolbar = no, resizable = yes, scrollbars = yes, height = 300, width = 600");return false;" target="_blank" title="'._e('Share on Facebook','msc-automation').'">
                                      </a>                                                
                                      <a id="tw" class="fab fa-twitter-square fa-2x" href="<?php echo $URL_Twitter;?>"
                                          onclick="javascript:window.open(this.href, "", "menubar = no, toolbar = no, resizable = yes, scrollbars = yes, height = 300, width = 600");return false;"
                                          target="_blank" title="'._e('Share on Twitter','msc-automation').'">
                                      </a>
                                      <a class="fas fa-code fa-2x" onclick="ShowIframeCode()" title="'._e('Share on your web','msc-automation').'" href="javascript:void"></a>
                                      <div id="iframe" style="display:none">
                                          <textarea type="text"  id="ifr"><?php echo $URL_Iframe;?></textarea>
                                          <i>'._e('Copy this code for add in your web','msc-automation').'</i>
                                      </div>
                                    </div>
                                  </div><!--END  Modal Share -->';*/
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
                $StrReturn .= '<ul><li>'
                        . '<a class = "track no-ajaxy" data-pos = "0" data-pod = "'.$id.'" href = "'.$urlmp3.'">'.$titol.'</a>'
                        . '<i class = "fas fa-clock"></i><i> ['.$duration.'] </i>'
                        . '<a class = "no-ajaxy fas fa-download" href = "'.$urldownload.'"></a>';                                                            
                /*$StrReturn .=  '<a id="BtnShare" class="fas fa-share-alt" aria-hidden="true" onclick="ShowModalshare()" href="javascript:void"></a>
                                    <div id="myModalShare" class="modalShare">
                                    <!-- Modal content -->
                                    <div class="modal-content_share">
                                      <span class="closeShare"><i class="fas fa-times fa-2x"></i></span>
                                      <a id="fb" class="fab fa-facebook-square fa-2x" href="'.$URL_Facebook.'" onclick="javascript:window.open(this.href, "", "menubar = no, toolbar = no, resizable = yes, scrollbars = yes, height = 300, width = 600");return false;" target="_blank" title="'._e('Share on Facebook','msc-automation').'">
                                      </a>                                                
                                      <a id="tw" class="fab fa-twitter-square fa-2x" href="<?php echo $URL_Twitter;?>"
                                          onclick="javascript:window.open(this.href, "", "menubar = no, toolbar = no, resizable = yes, scrollbars = yes, height = 300, width = 600");return false;"
                                          target="_blank" title="'._e('Share on Twitter','msc-automation').'">
                                      </a>
                                      <a class="fas fa-code fa-2x" onclick="ShowIframeCode()" title="'._e('Share on your web','msc-automation').'" href="javascript:void"></a>
                                      <div id="iframe" style="display:none">
                                          <textarea type="text"  id="ifr"><?php echo $URL_Iframe;?></textarea>
                                          <i>'._e('Copy this code for add in your web','msc-automation').'</i>
                                      </div>
                                    </div>
                                  </div><!--END  Modal Share -->';^*/
                $StrReturn .= '</li></ul>';
            }                 
            $counter ++; 
        endwhile;                            
        $StrReturn .= '</div>';
        return $StrReturn ;  
        }
    }
    
    add_shortcode('last_podcast', 'get_last_podcast');
    
