<?php         
    //Used in functions: get_last_played, 
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
        if(!isset($NumRows)){$NumRows = $_SESSION['NumRows'];}
        if(!isset($dradi)){$dradi = $_SESSION['dradi'];}    
        if(!isset($image)){$image = $_SESSION['image'];}    
        if(!isset($col_name)){$col_name = $_SESSION['col_name'];} 
        if(!isset($upload_dir)){$upload_dir = $_SESSION['upload_dir'];}         
    }       
        
    $Vars[0] = 'rows='.$NumRows;    
    if ($dradi<>null){
        //if (test_date(urlencode($dradi))== TRUE){$Vars[1]= 'date='.urlencode($dradi);}
        $Vars[count($Vars)]= 'date='.urlencode($dradi);            
    }
    if($image==TRUE){
        $Vars[count($Vars)]= 'image=1';
    }    
    $list = $MyRadio->QueryGetTable(seccions::MUSIC,sub_seccions::LISTRADIA,$Vars);         
    if ($MyRadio->RESPOSTA_ROWS>0){ 
        $time_s = 15000;//TODO: falta que retorni els segons restans
        ?>
            <div id="dom-time" style="display: none;"><?php echo $time_s;?></div>
            <TABLE class="datatable"><TR>
                <?php if($image==TRUE){ echo '<TH scope="col"></TH>';}?>
                <TH scope="col"><?php echo $col_name[0]?></TH>
                <TH scope="col"><?php echo $col_name[1]?></TH>
                <TH scope="col"><?php echo $col_name[2]?></TH>
                <TH scope="col"><?php echo $col_name[3]?></TH>
                </TR>
        <?php
        $counter = 0;  
        $strReturn = '';        
        while($counter < $MyRadio->RESPOSTA_ROWS):                  
            if (fmod($counter,2) == 0 ){
                    $StrEcho= "<TR>";
            }else{
                    $StrEcho= "<TR class='altrow'>";
            }            
            if($image==TRUE){                                        
                $PathToSaveImg = $upload_dir['basedir'].'/'.TMP_IMG_DIR.'/disc_img-'.$list['track'][$counter]['ID'].'.jpg'; 
                $PathToShowImg = $upload_dir['baseurl'].'/'.TMP_IMG_DIR.'/disc_img-'.$list['track'][$counter]['ID'].'.jpg';                     
                if (getImage(base64_decode($list['track'][$counter]['IMAGE']),$PathToSaveImg,200)==TRUE){
                    $StrEcho .= '<TD><img src='.$PathToShowImg.'></TD>';
                }else{
                    $StrEcho .= '<TD></TD>';
                }                
            }                              
            $StrEcho .= '<TD>'.$list['track'][$counter]['TITLE'].'</TD>';
            $StrEcho .= '<TD>'.$list['track'][$counter]['INTERP'].'</TD>';
            $StrEcho .= '<TD>'.$list['track'][$counter]['STYLE'].'</TD>';                
            $StrEcho .= '<TD>'.$list['track'][$counter]['RADIATION'].'</TD>';
            $StrEcho .= '</TR>';

            $counter = $counter + 1;
            $strReturn .= $StrEcho ;
        endwhile;        
        $strReturn .= '</TABLE><br>';
        echo $strReturn;
    }