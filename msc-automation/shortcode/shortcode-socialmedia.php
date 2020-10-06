<?php

function get_timeline_FaceBook() {
    if (is_admin()) {
        return;
    }
    global $MyRadio;
    if (!isset($MyRadio)) {
        $MyRadio = new my_radio(get_option('msc_client_key'), get_locale(), get_option('msc_debug'));
        if ($MyRadio->RESPOSTA_STATUS !== SUCCES) {
            if ($MyRadio->IS_DEGUG == true) {
                $msg = 'STATUS: ' . $MyRadio->RESPOSTA_STATUS . ' CODE: ' . $MyRadio->RESPOSTA_CODE . ' MSG: ' . $MyRadio->RESPOSTA_MESSAGE;
                show_msc_message($msg, message_type::DANGER);
                return;
            }
        }
    }
    $strReturn = '';
    if (strlen($MyRadio->URL_FaceBook)) {
        $strReturn .= '<div align=center >                    
                            <section id="facebook">
                                <div class="row">';
        $FB = new Facebook($MyRadio->URL_FaceBook);
        $strReturn .= '<h3>Al facebook ...</h3>';
        $strReturn .= '<br>';
        $strReturn .= $FB->show_LikeBox(ColorScheme::COLOR_SCHEME_LIGHT, 600, 400, true, true, true, true);
        $strReturn .= '<BR>';
        $strReturn .= $FB->show_Comments(15, 600, ColorScheme::COLOR_SCHEME_LIGHT);
        unset($FB);
        $strReturn .= '</div></section><!-- FACEBOOK -->';
        $strReturn .= '</div>';
    }
    return $strReturn;
}

add_shortcode('timeline_FaceBook', 'get_timeline_FaceBook');

function social_share($id, $URL_Facebook, $URL_Twitter, $URL_Pinterest, $URL_Linked_in, $URL_WhatsApp, $URL_Iframe) {
    return '<a id="fb-' . $id . '" class="fab fa-facebook-square fa-2x" href="' . $URL_Facebook . '" onclick="javascript:window.open(this.href, "", "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600");return false;" 
                                            target="_blank" title="' . __('Share on Facebook', 'msc-automation') . '">
                                        </a>                                                
                                        <a id="tw-' . $id . '" class="fab fa-twitter-square fa-2x" href="' . $URL_Twitter . '"
                                           onclick="javascript:window.open(this.href, "", "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600");return false;"
                                           target="_blank" title="' . __('Share on Twitter', 'msc-automation') . '">
                                        </a>                                            
                                        <a id="pt-' . $id . '" class="fab fa-pinterest-square fa-2x" href="' . $URL_Pinterest . '"
                                           onclick="javascript:window.open(this.href, "", "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600");return false;"
                                           target="_blank" title="' . __('Share on Pinterest', 'msc-automation') . '">
                                        </a>                                            
                                        <a id="li-' . $id . '" class="fab fa-linkedin fa-2x" href="' . $URL_Linked_in . '"
                                           onclick="javascript:window.open(this.href, "", "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600");return false;"
                                           target="_blank" title="' . __('Share on LinkedIn', 'msc-automation') . '">
                                        </a>                                            
                                        <a id="wa-' . $id . '" class="fab fa-whatsapp-square fa-2x" href="' . $URL_WhatsApp . '"
                                           onclick="javascript:window.open(this.href, "", "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600");return false;"
                                           target="_blank" title="' . __('Share on WhatsApp', 'msc-automation') . '">
                                        </a>';
    /* '<a class="fas fa-code fa-2x" data-pod="'. $id.'" onclick="ShowIframeCode(this)" title="'. __('Share on your web', 'msc-automation').'" href="javascript:void"></a>
      <div id="iframe-'.$id.'" style="display:none">
      <textarea type="text"  id="ifr">'.$URL_Iframe.'</textarea>
      <i>'.__('Copy this code for add in your web', 'msc-automation').'</i>
      </div>'; */
}
