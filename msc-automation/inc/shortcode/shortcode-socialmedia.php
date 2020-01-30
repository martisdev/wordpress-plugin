<?php

    function get_timeline_FaceBook(){
                global $MyRadio;                
        if(!isset($MyRadio)){ 
            $MyRadio = new my_radio(get_option('msc_client_key'),get_locale(),get_option('msc_debug'));
        }        
        if ($MyRadio->RESPOSTA_STATUS !== SUCCES ){
            if ($MyRadio->IS_DEGUG == true){                                                
                    $msg = 'STATUS: '.$MyRadio->RESPOSTA_STATUS.' CODE: '.$MyRadio->RESPOSTA_CODE.' MSG: '.$MyRadio->RESPOSTA_MESSAGE ;
                    show_msc_message($msg ,message_type::DANGER);
                    return;
            }
        }
        $strReturn = '';
        if (strlen($MyRadio->URL_FaceBook)  ){            
            $strReturn.='<div align=center >                    
                            <section id="facebook">
                                <div class="row">';                
                                    $FB = new Facebook($MyRadio->URL_FaceBook );                                                
                                    $strReturn.='<h3>Al facebook ...</h3>';
                                    $strReturn.= '<br>';                                        
                                    $strReturn.= $FB->show_LikeBox(ColorScheme::COLOR_SCHEME_LIGHT, 600, 400, true, true, true, true);
                                    $strReturn.='<BR>';
                                    $strReturn.= $FB->show_Comments(15,600,ColorScheme::COLOR_SCHEME_LIGHT);
                                    unset($FB);
                                $strReturn.='</div></section><!-- FACEBOOK -->';
            $strReturn.= '</div>';
        }
        return $strReturn;
    }
    
    add_shortcode('timeline_FaceBook', 'get_timeline_FaceBook');