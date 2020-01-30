<?php

    function get_show_program($attributes){            
        
        $prg_id  = (isset($attributes['id'])) ? $attributes['id'] : 0; 
        $download  = (isset($attributes['download'])) ? $attributes['download'] : TRUE;         
        if ($prg_id==0){return;}
        
        global $MyRadio;        
        if(!isset($MyRadio)){
            $MyRadio = new my_radio(get_option('msc_client_key'),get_locale(),get_option('msc_debug'));
            if ($MyRadio->RESPOSTA_STATUS !== SUCCES ){
                if ($MyRadio->IS_DEGUG == true){                                                
                    $msg = 'STATUS: '.$MyRadio->RESPOSTA_STATUS.' CODE: '.$MyRadio->RESPOSTA_CODE.' MSG: '.$MyRadio->RESPOSTA_MESSAGE ;
                    show_msc_message($msg ,message_type::DANGER);
                    return;
                }
            }
        }        
        $Vars[0]= 'id='.$prg_id;
        $list = $MyRadio->QueryGetTable(seccions::PROGRAMS,sub_seccions::SHOWINFO_PRG,$Vars);                                                      
        if ($MyRadio->RESPOSTA_ROWS>0){                    
                $nom_programa = $list['item']['NAME'];
                 //$StrReturn .='<h1>'.  utf8_decode($nom_programa).'</h1>';                                
                $upload_dir = wp_upload_dir();
                $PathToSaveImg = $upload_dir['basedir'].'/'.TMP_IMG_DIR.'/prg-'.$prg_id.'.jpg'; 
                $PathToShowImg = $upload_dir['baseurl'].'/'.TMP_IMG_DIR.'/prg-'.$prg_id.'.jpg'; 
                if(getImage(base64_decode($list['item']['IMAGE']), $PathToSaveImg, 700)==TRUE){                     
                    $StrReturn .= '<img src="'.$PathToShowImg.'" class="aligncenter size-full"><br><br>';
                }                
                $StrReturn .= '<p>'. htmlentities($list['item']['DESCRIP']).'</p><br>';
                $StrReturn .= '<p>'.htmlentities(__('Thematic','msc-automation')).': '.$list['item']['TOPIC'].'<br>';
                $StrReturn .= htmlentities(__('Duration','msc-automation')).': '.$list['item']['DURATION'].'</p>';
            
                $listTags = $list['item']['TAGS']['TAG'];            
                $rows= count($listTags);//(count($listTags,1)/count($listTags,0))-1;            
                if ($rows>0){                
                    $StrReturn .= '<div id="content"> ';                      
                        $StrReturn .= '<h2>'.htmlentities(__('Program tags','msc-automation')).': '.'</h2';
                        $StrReturn .= '<p>'.tag_cloud($listTags,"#",10).'</p>';
                    $StrReturn .= '</div>';
                }
                //Facebook del programa
                $url_facebook = $list['item']['FACEBOOK'];                
                if (strlen($url_facebook)>3){                    
                    $StrReturn .= '<div id="content"> '; 
                         $StrReturn .= '<h3>'.htmlentities($nom_programa).' '.__('on','msc-automation').' Facebook</h3>';
                         $fb_prg = new Facebook($url_facebook);                       
                         //$fb_prg->show_Comments(20, 450, ColorScheme::COLOR_SCHEME_LIGHT);
                         $StrReturn .= $fb_prg->show_LikeBox( ColorScheme::COLOR_SCHEME_LIGHT, 550, 200,true);
                    $StrReturn .= '</div>';
                }
                //Twitter del programa
                $LinkTwitter = $list['item']['TWITTER'];
                if (strlen($LinkTwitter)>3){                           
                    $StrReturn .= '<div id="content"> '; 
                         $StrReturn .= '<h3>'.htmlentities($nom_programa).' '.__('on','msc-automation').' Twitter</h3>';
                         $twitter_prg = new twitter($LinkTwitter, 'ca') ;
                         $StrReturn .= $twitter_prg->show_FollowButton();
                    $StrReturn .= '</div>';
                }            
                //Podcast del programa
                $list_podcast = $list['item']['PODS']['POD'];                                      
                $rows= count($list_podcast); 
                $counter = 0;                                
                $upload_dir = wp_upload_dir();
                $url_podcast = $upload_dir['baseurl'].'/'.PODCAST_DIR;
                    
                if ($rows==1){                                
                    $StrReturn .='<div id="jp_container" class="jp-audio">';
                    //$StrReturn .= '<h3 id="pod-title-'.$counter.'" >'.htmlentities($nom_programa).'</h3>';                                    
                    while($counter < $rows):                                                                
                        $id = $list_podcast["ID"];                    
                        $nom_programa = $list_podcast['NAME'];                                
                        $descrip = htmlentities($list_podcast['DESCRIP']);                                
                        $duration = $list_podcast['DURATION'];                                
                        $data_crea = $list_podcast['DATE_PUBLICATION'];
                        $titol = $nom_programa.' '.date('d-m-Y',strtotime($data_crea));               
                        $urlmp3 = $url_podcast.'/'.$list_podcast['FILE'];                                    
                        $urldownload= MSC_PLUGIN_URL.'inc/download.php?fileurl='.$urlmp3.'&filename='.urlencode($nom_programa);

                        //$StrReturn .= '<h3 id="pod-title-'.$counter.'" ><b>'.htmlentities($nom_programa).'</b></h3>';
                        if (strlen($descrip)>2){$StrReturn .= '<i>'.$descrip.'</i><br>';}                               
                        $marks = $list_podcast['MARKS']['MARK'];                                                           
                        $count_marks = count($marks);
                        if($count_marks>0){
                            $StrReturn .= '<li class="fas fa-plus-square" onclick="displayList(this, list_parts_'.$counter.')" style="padding:5px"></li>'
                                    . '<a class="no-ajaxy track" data-pos="0" data-pod="'.$id.'" href="'.$urlmp3.'">'.$titol.'</a>'
                                    . '<i class="fas fa-clock"></i><i> ['.$duration.'] </i> '
                                    . '<a class="no-ajaxy fas fa-download" href="'.$urldownload.'"></a>';

                            $StrReturn .= '<ul style="display:none" id="list_parts_'.$counter.'">';                                            
                            $counter_mark = 0;                
                            while($counter_mark < $count_marks):  
                                $seg = $marks[$counter_mark]['SECOND'];
                                $comment = $marks[$counter_mark]['COMMENT'];
                                $StrReturn .= '<li style="margin-left:50px"><a class="track no-ajaxy" data-pos="'.$seg.'"  data-pod="'.$id.'" href="'.$urlmp3.'">'.$comment.'</a></li>';                        
                                $counter_mark++;
                            endwhile;
                            $StrReturn .= '</ul>';                        
                        }else{
                            $StrReturn .= '<ul><li>'
                                    . '<a class="track no-ajaxy" data-pos="0" data-pod="'.$id.'" href="'.$urlmp3.'">'.$titol.'</a>'
                                    . '<i class="fas fa-clock"></i><i> ['.$duration.'] </i>'
                                    . '<a class="no-ajaxy fas fa-download" href="'.$urldownload.'"></a>'                            
                                    . '</li></ul>';                                                            

                        }                 
                        $counter ++; 
                    endwhile;                
                    $StrReturn .='</div>';   
                }elseif($rows>1){                                                    
                    $StrReturn .='<div id="jp_container" class="jp-audio">';
                    //$StrReturn .= '<h3 id="pod-title-'.$counter.'" >'.htmlentities($nom_programa).'</h3>';                                    
                    while($counter < $rows):                                                                
                        $id = $list_podcast[$counter]["ID"];                    
                        //$nom_programa = htmlentities($list_podcast[$counter]['NAME']);                                
                        $descrip = htmlentities($list_podcast[$counter]['DESCRIP']);                              
                        $duration = $list_podcast[$counter]['DURATION'];                                
                        $data_crea = $list_podcast[$counter]['DATE_PUBLICATION'];
                        $titol = $nom_programa.' '.date('d-m-Y',strtotime($data_crea));               
                        $urlmp3 = $url_podcast.'/'.$list_podcast[$counter]['FILE'];                                    
                        $urldownload= MSC_PLUGIN_URL.'inc/download.php?fileurl='.$urlmp3.'&filename='.urlencode($nom_programa);
                        $StrReturn .= '<div>';
                        //$StrReturn .= '<h3 id="pod-title-'.$counter.'" ><b>'.htmlentities($nom_programa).'</b></h3>';
                        if (strlen($descrip)>2){$StrReturn .= '<i>'.$descrip.'</i><br>';}                               
                        $marks = $list_podcast[$counter]['MARKS']['MARK'];                        
                        $count_marks = count($marks);
                        if($count_marks>0){
                            $StrReturn .= '<li class="fas fa-plus-square" onclick="displayList(this, list_parts_'.$counter.')" style="padding:5px"></li>'
                                    . '<a class="no-ajaxy track" data-pos="0" data-pod="'.$id.'" href="'.$urlmp3.'">'.$titol.'</a>'
                                    . '<i class="fas fa-clock"></i><i> ['.$duration.'] </i> '
                                    . '<a class="no-ajaxy fas fa-download" href="'.$urldownload.'"></a>';

                            $StrReturn .= '<ul style="display:none" id="list_parts_'.$counter.'">';                                            
                            $counter_mark = 0;                
                            while($counter_mark < $count_marks):  
                                $seg = $marks[$counter_mark]['SECOND'];
                                $comment = $marks[$counter_mark]['COMMENT'];
                                $StrReturn .= '<li style="margin-left:50px"><a class="track no-ajaxy" data-pos="'.$seg.'"  data-pod="'.$id.'" href="'.$urlmp3.'">'.utf8_decode($comment).'</a></li>';                        
                                $counter_mark++;
                            endwhile;
                            $StrReturn .= '</ul>';                        
                        }else{
                            $StrReturn .= '<ul><li>'
                                    . '<a class="track no-ajaxy" data-pos="0" data-pod="'.$id.'" href="'.$urlmp3.'">'.$titol.'</a>'
                                    . '<i class="fas fa-clock"></i><i> ['.$duration.'] </i>'
                                    . '<a class="no-ajaxy fas fa-download" href="'.$urldownload.'"></a>'                            
                                    . '</li></ul>';                                                            

                        }                 
                        $StrReturn .= '</div>';
                        $counter ++;                         
                    endwhile;                
                    $StrReturn .='</div><!-- <div id="jp_container" -->';   
                
            }                
            return $StrReturn ;
        }
    }
    
    add_shortcode('show_program', 'get_show_program');
    
    
    function get_cloud_tags_programs(){
        //list of programs with own tags
        
        //tag_cloud($listTags,$urlDesti,$div_size = 400);
    }
    
    add_shortcode('cloud_tags_programs', 'get_cloud_tags_programs');