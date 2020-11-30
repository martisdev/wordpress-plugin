<?php
    global $MyRadio;                
    if(!isset($MyRadio)){  
        include_once '../inc/defines.php';        
        include_once '../inc/my_radio.php';
        include_once '../inc/utils.php';
        $my_key = $_GET['key'];        
        $my_id = $_GET['id'];        
        $my_type = $_GET['type'];        
        $my_val = $_GET['val'];        
        $MyRadio = new my_radio($my_key,WP_MSCRA_LANG_DEF,0);                            
        if ($MyRadio->RESPOSTA_MESSAGE <> 'OK' ){
            if ($MyRadio->IS_DEGUG == true){               
                $title = 'MSC API Error';
                $subtitle = $MyRadio->RESPOSTA_MESSAGE;
            }           
        }
    }        
    $Vars[0]= 'id='.$my_id ;            
    if ($my_type == TIP_AUTOMATIC_PROGRAMA){
        if($my_val == 1){            
            $MyRadio->ExecuteNonQuery(seccions::PODCAST,sub_seccions::LIKE,$Vars,TRUE);                
        }else{            
            $MyRadio->ExecuteNonQuery(seccions::PODCAST,sub_seccions::UNLIKE,$Vars,TRUE);                
        }        
    }else{
        if($my_val == 1){            
            $MyRadio->ExecuteNonQuery(seccions::MUSIC,sub_seccions::LIKE,$Vars,TRUE);                
        }else{
            $MyRadio->ExecuteNonQuery(seccions::MUSIC,sub_seccions::UNLIKE,$Vars,TRUE);                
        }
    }
    
    