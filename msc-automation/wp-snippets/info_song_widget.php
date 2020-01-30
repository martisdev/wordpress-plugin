<?php
    //Used in functions: get_now_playing_widget, 
    session_start();
    global $MyRadio;        
    if(!isset($MyRadio)){ 
        include_once '../inc/defines.php';
        include_once '../inc/my_radio.php';
        include_once '../inc/utils.php';
        $MyRadio = new my_radio($key,get_locale(),get_option('msc_debug')); 
        if ($MyRadio->RESPOSTA_MESSAGE <> 'OK' ){
            if ($MyRadio->IS_DEGUG == true){                                                
                    $msg = 'STATUS:'.$MyRadio->RESPOSTA_STATUS.' CODE:'.$MyRadio->RESPOSTA_CODE.' MSG'.$MyRadio->RESPOSTA_MESSAGE ;
                    show_msc_message($msg ,message_type::DANGER);
                    return;
            }           
        }
        if(!isset($upload_dir)){$upload_dir = $_SESSION['upload_dir'];}        
        if(!isset($image)){$image = $_SESSION['image_w'];}        
        if(!isset($img_width)){$img_width = $_SESSION['img_width_w'];}
    }     
    $Vars[0] = 'rows=1';
    $Vars[1] = 'image=1';    
    $list = $MyRadio->QueryGetTable(seccions::MUSIC,sub_seccions::LISTRADIA,$Vars);
    if ($MyRadio->RESPOSTA_ROWS>0){ 
        $counter = 0;  
        $strReturn = '<div id ="song" style="width:'.$img_width.'px;"">';
        while($counter < $MyRadio->RESPOSTA_ROWS):                  
            $StrEcho = '';
            if($image==TRUE){                     
                $PathToSaveImg = $upload_dir['basedir'].'/'.TMP_IMG_DIR.'/disc_img-'.$list['track']['ID'].'.jpg'; 
                $PathToShowImg = $upload_dir['baseurl'].'/'.TMP_IMG_DIR.'/disc_img-'.$list['track']['ID'].'.jpg';                     
                if (getImage(base64_decode($list['track']['IMAGE']),$PathToSaveImg,$img_width)==TRUE){
                    $StrEcho .= '<img src='.$PathToShowImg.'>';                    
                }
            }                                                  
            //$StrEcho .= '<span ><h3><b>'.$list['track']['VALUE'].'</b></h3>';            
            //$StrEcho .= '<h4>'.$list['track']['INTERP'].'</h4>';
            //$StrEcho .= '<h5>('.$list['track']['STYLE'].')</h5></span>';  
            $StrEcho .= '<div class="artist">'.$list['track']['INTERP'].'</div>';
            $StrEcho .= '<div class="song">'.$list['track']['TITLE'].'</div>';  

            $counter = $counter + 1;
            $strReturn .= $StrEcho ;
        endwhile;        
        $strReturn .= '</div>';
        echo $strReturn;
    }
