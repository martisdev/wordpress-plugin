<?php
global $MyRadio;
if (!isset($MyRadio)) {
    $key = get_option('msc_client_key');
    $MyRadio = new my_radio($key, get_locale(), get_option('msc_debug'));
    if ($MyRadio->RESPOSTA_STATUS !== SUCCES) {
        if ($MyRadio->IS_DEGUG == true) {
            $msg = 'STATUS: ' . $MyRadio->RESPOSTA_STATUS . ' CODE: ' . $MyRadio->RESPOSTA_CODE . ' MSG: ' . $MyRadio->RESPOSTA_MESSAGE;
            show_msc_message($msg, message_type::DANGER);
            return;
        }
    }
}