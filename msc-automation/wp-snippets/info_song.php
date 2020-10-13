<?php
    //Used in functions: get_now_playing, 
    session_start();
    global $MyRadio;        
    if(!isset($MyRadio)){ 
        include_once '../inc/defines.php';
        include_once '../inc/my_radio.php';
        include_once '../inc/utils.php';
        $MyRadio = new my_radio($key,get_locale(),get_option('mscra_debug')); 
        if ($MyRadio->RESPOSTA_MESSAGE <> 'OK' ){
            if ($MyRadio->IS_DEGUG == true){                                                
                    $msg = 'STATUS:'.$MyRadio->RESPOSTA_STATUS.' CODE:'.$MyRadio->RESPOSTA_CODE.' MSG'.$MyRadio->RESPOSTA_MESSAGE ;
                    mscra_show_message($msg ,message_type::DANGER);
                    return;
            }           
        }
        if(!isset($upload_dir)){$upload_dir = $_SESSION['upload_dir'];}        
        if(!isset($image)){$image = $_SESSION['image'];}        
        if(!isset($img_width)){$img_width = $_SESSION['img_width'];}
        
    }     
    $Vars[0] = 'rows=1';
    $Vars[1] = 'image=1';    
    $list = $MyRadio->QueryGetTable(seccions::MUSIC,sub_seccions::LISTRADIA,$Vars);        
    if ($MyRadio->RESPOSTA_ROWS>0){     
        $counter = 0;
        
        if($image==TRUE){                     
            $PathToSaveImg = $upload_dir['basedir'].'/'.WP_MSCRA_TMP_IMG_DIR.'/disc_img-'.$list['track']['ID'].'.jpg'; 
            $PathToShowImg = $upload_dir['baseurl'].'/'.WP_MSCRA_TMP_IMG_DIR.'/disc_img-'.$list['track']['ID'].'.jpg';                     
            if (mscra_getImage(base64_decode($list['track']['IMAGE']),$PathToSaveImg,$img_width)==TRUE){                
                ?>
                <div id="jp-image" style="width:<?php echo $img_width;?>px;">
                    <img src="<?php echo $PathToShowImg;?>">
                </div>    
                <?php
            }
        }                
        ?>
        <div id="jp_container" class="jp-audio">                 
            <div id="jp-info">
                <div id="jp_title">
                    <span><?php echo $list['track']['INTERP']; ?></span>
                </div>
                <i id="jp_subtitle-name"><?php echo $list['track']['TITLE'] ?></i>
                <div id="jp-socialbuttons">
                    <a class="fas fa-heart" aria-hidden="true" href="#"></a>
                    <i class="fas fa-download" aria-hidden="true"></i>
                    <i class="fas fa-share-alt" aria-hidden="true"></i>
                </div>
            </div>
    </div>
        <?php                
    }