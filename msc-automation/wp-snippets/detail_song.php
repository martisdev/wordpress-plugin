<?php    
    include MSCRA_PLUGIN_DIR.'connect_api.php';
            
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
    $PathToSaveImg = $upload_dir['basedir'].'/'.WP_MSCRA_TMP_IMG_DIR.'/disc_img-'.$list['track']['ID'].'.jpg'; 
    $PathToShowImg = $upload_dir['baseurl'].'/'.WP_MSCRA_TMP_IMG_DIR.'/disc_img-'.$list['track']['ID'].'.jpg';                     
    if (mscra_getImage(base64_decode($list['track']['IMAGE']),$PathToSaveImg,$img_width)==TRUE){
        $strReturn .= '<img src='.$PathToShowImg.'>';    
    }                        
    $strReturn .= '<div class="artist"><span>'.$list['track']['INTERP'].'</span></div>';
    $strReturn .= '<div >'.$list['track']['TITLE'].'</div>';                                
    $strReturn .= '</div>';

