<?php    
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
            
    if (isset($HoraPrg)){
        $Vars[0] = 'id='.$temid ;
        $Vars[1] = 'date='.$HoraPrg ;    
        $list = $MyRadio->QueryGetTable(seccions::MUSIC,sub_seccions::PROGRAMSONG,$Vars); 
    }else{
        $Vars[0] = 'id='.$temid ;
        $list = $MyRadio->QueryGetTable(seccions::MUSIC,sub_seccions::SHOWINFO ,$Vars); 
    }    

    $img_width = 200;    

    $strReturn .= '<div id="nowinfo" style="width:'.$img_width.'px;">';
    $upload_dir = wp_upload_dir();
    $PathToSaveImg = $upload_dir['basedir'].'/'.TMP_IMG_DIR.'/disc_img-'.$list['track']['ID'].'.jpg'; 
    $PathToShowImg = $upload_dir['baseurl'].'/'.TMP_IMG_DIR.'/disc_img-'.$list['track']['ID'].'.jpg';                     
    if (getImage(base64_decode($list['track']['IMAGE']),$PathToSaveImg,$img_width)==TRUE){
        $strReturn .= '<img src='.$PathToShowImg.'>';    
    }                        
    $strReturn .= '<div class="artist"><span>'.$list['track']['INTERP'].'</span></div>';
    $strReturn .= '<div >'.$list['track']['TITLE'].'</div>';                                
    $strReturn .= '</div>';

