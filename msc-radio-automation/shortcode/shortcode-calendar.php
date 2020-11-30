<?php

function mscra_get_calendar_day()
{
    if (is_admin()) {
        return;
    }
    include MSCRA_PLUGIN_DIR . 'connect_api.php';
    
    $go_to = (isset($_GET['goto'])) ? sanitize_text_field($_GET['goto']) : '';
    if ($go_to == '') {
        $dateCal = current_time('Y-m-d');
    } else {
        $date_go = (isset($_GET['date'])) ? sanitize_text_field($_GET['date']) : current_time("Y-m-d");
        list($year, $month, $day) = explode('-', $date_go);
        if ($go_to == 'ant') {            
            $dateCal = mktime(0, 0, 0, $month, $day - 1, $year);
            $dateCal = strftime("%Y-%m-%d", $dateCal);
        } elseif ($go_to == 'post') {            
            $dateCal = mktime(0, 0, 0, $month, $day + 1, $year);
            $dateCal = strftime("%Y-%m-%d", $dateCal);
        }
    }

    $Vars[0] = "date=" . urlencode($dateCal);
    $list = $MyRadio->QueryGetTable(seccions::CALENDAR, sub_seccions::GRIDDAY, $Vars);

    if (get_locale() == 'ca') {
        setlocale(LC_TIME, get_locale(), 'ca_ES', 'ca-ES');
    } else {
        setlocale(LC_TIME, get_locale());
    }
    $StrData = utf8_encode(strftime("%A, %d %B %Y", strtotime($dateCal)));

    $params = array('goto' => 'ant', 'date' => $dateCal);
    $LinkAnt = add_query_arg($params);
    $params = array('goto' => 'post', 'date' => $dateCal);
    $LinkPost = add_query_arg($params);

    $StrReturn = '<TABLE  align=center><TR>
                        <TH scope="col"><a href="' . $LinkAnt . '" style="text-align:center;"><i class="fas fa-chevron-left" aria-hidden="true"></i></a></TH>
                        <TH scope="col"><h3><b>' . $StrData . '</b></h3></TH>
                        <TH scope="col"><a href="' . $LinkPost . '" style="text-align:center;"><i class="fas fa-chevron-right" aria-hidden="true"></i></a></TH></TR>';

    $counter = 0;

    $Strloop = '';
    if ($MyRadio->RESPOSTA_ROWS == 1) {
        $type_prg = sanitize_text_field($list['item']['TYPE_PROG']);
        $prg_id = sanitize_text_field($list['item']['ID_RELATED']);
        $txtLabel = sanitize_text_field(($list['item']['NAME']));
        $txtDescrip = sanitize_text_field($list['item']['DESCRIP']);

        $MinutIni = sanitize_text_field($list['item']['MINUTE_INI']);
        $MinutDurada = sanitize_text_field($list['item']['DURATION']);
        $txtHoraIni = date("G:i", mktime(0, $MinutIni, 0, 0, 0, 0));
        $txtDurada = date("G:i", mktime(0, $MinutDurada, 0, 0, 0, 0));
        $is_reemissio = sanitize_text_field($list['item']['REMISSION']);

        $Strloop .= '<TR><td><div id="cal-top"><i class="far fa-calendar"> ' . $txtHoraIni . '</i></div></td>';
        switch ($type_prg):
    case TIP_AUTOMATIC:
        $page = mscra_get_page_by_meta($prg_id);
        if (isset($page->ID)) {
                $url_prg = $page->guid;
                $Strloop .= '<td><h2><a href="' . $url_prg . '">' . $txtLabel . '</a></h2>';
        } else {
            $Strloop .= '<td><h2>' . $txtLabel . '</h2>';
        }
        break;
    case TIP_CONEX_CENTRAL:$Strloop .= '<td><h2>' . __('Desconnection', 'mscra-automation') . '</h2>';
        break;
    case TIP_DIRECTE:
        $page = mscra_get_page_by_meta($prg_id);
        if (isset($page->ID)) {
            $url_prg = $page->guid;
            $Strloop .= '<td><h2><a href="' . $url_prg . '">' . $txtLabel . '</a></h2>';
        } else {
            $Strloop .= '<td><h2>' . $txtLabel . '</h2>';
        }
        break;
        endswitch;
        $Strloop .= '<p>' . $txtDescrip . '<p></td>';
        $Strloop .= '<td>';
        if ($is_reemissio == 1) {
            $Strloop .= '<div id="cal-top"><i class="fas fa-sync-alt" title="' . __('Rebroadcast', 'mscra-automation') . '"></i></div>';
        }
        $Strloop .= '<div id="cal-bottom"><i class="fas fa-clock">' . $txtDurada . '</i></div></td></TR>';

        $StrReturn .= $Strloop;
        $StrReturn .= '<TR><td></td><td><p>' . __('The rest of the hours', 'mscra-automation') . ' ...</BR>';
        switch ($MyRadio->ProgramacioDefecte) {
            case TIP_AUTOMATIC:
                $StrReturn .= __('Radio music scheduling', 'mscra-automation');
                break;
            case TIP_CONEX_CENTRAL:
                $StrReturn .= __('Central connection', 'mscra-automation');
                break;
            case TIP_DIRECTE:
                $StrReturn .= __('On live', 'mscra-automation');
        }
        $StrReturn .= '</p></td><td></td><TR>';
    } elseif ($MyRadio->RESPOSTA_ROWS > 1) {
        $counter = 0;
        while ($counter < $MyRadio->RESPOSTA_ROWS):
            $prg_id = sanitize_text_field($list['item'][$counter]['ID_RELATED']);
            $type_prg = sanitize_text_field($list['item'][$counter]['TYPE_PROG']);
            $txtLabel = sanitize_text_field(($list['item'][$counter]['NAME']));
            $txtDescrip = sanitize_text_field($list['item'][$counter]['DESCRIP']);

            $MinutIni = sanitize_text_field($list['item'][$counter]['MINUTE_INI']);
            $MinutDurada = sanitize_text_field($list['item'][$counter]['DURATION']);
            $txtHoraIni = date("G:i", mktime(0, $MinutIni, 0, 0, 0, 0));
            $txtDurada = date("G:i", mktime(0, $MinutDurada, 0, 0, 0, 0));
            $is_reemissio = sanitize_text_field($list['item'][$counter]['REMISSION']);

            $Strloop .= '<TR><td><div id="cal-top"><i class="far fa-calendar"> ' . $txtHoraIni . '</i></div></td>';
            switch ($type_prg):
        case TIP_AUTOMATIC:
            $page = mscra_get_page_by_meta($prg_id);
            if (isset($page->ID)) {
                    $url_prg = $page->guid;
                    $Strloop .= '<td><h2><a href="' . $url_prg . '">' . $txtLabel . '</a></h2>';
            } else {
                $Strloop .= '<td><h2>' . $txtLabel . '</h2>';
            }
            break;
        case TIP_CONEX_CENTRAL:$Strloop .= '<td><h2>' . __('Desconnection', 'mscra-automation') . '</h2>';
            break;
        case TIP_DIRECTE:
            $page = mscra_get_page_by_meta($prg_id);
            if (isset($page->ID)) {
                $url_prg = $page->guid;
                $Strloop .= '<td><h2><a href="' . $url_prg . '">' . $txtLabel . '</a></h2>';
            } else {
                $Strloop .= '<td><h2>' . ($txtLabel) . '</h2>';
            }
            break;
            endswitch;
            $Strloop .= '<p>' . $txtDescrip . '<p></td>';
            $Strloop .= '<td>';
            if ($is_reemissio == 1) {
                $Strloop .= '<div id="cal-top"><i class="fas fa-sync-alt" title="' . __('Rebroadcast', 'mscra-automation') . '"></i></div>';
            }
            $Strloop .= '<div id="cal-bottom"><i class="fas fa-clock">' . $txtDurada . '</i></div></td></TR>';
            $counter += 1;
        endwhile;
        $StrReturn .= $Strloop;
        $StrReturn .= '<TR><td></td><td><p>' . __('The rest of the hours', 'mscra-automation') . ' ...</BR>';
        switch ($MyRadio->ProgramacioDefecte) {
            case TIP_AUTOMATIC:
                $StrReturn .= __('Radio music scheduling', 'mscra-automation');
                break;
            case TIP_CONEX_CENTRAL:
                $StrReturn .= __('Central connection', 'mscra-automation');
                break;
            case TIP_DIRECTE:
                $StrReturn .= __('On live', 'mscra-automation');
        }
        $StrReturn .= '</p></td><td></td><TR>';
    } else {
        $StrReturn .= '<TR><td></td><td><p>';        
        switch ($MyRadio->ProgramacioDefecte) {
            case TIP_AUTOMATIC:
                $StrReturn .= '<h2>' . __('Radio music scheduling', 'mscra-automation') . '</h2>';
                break;
            case TIP_CONEX_CENTRAL:
                $StrReturn .= '<h2>' . __('Central connection', 'mscra-automation') . '</h2>';
                break;
            case TIP_DIRECTE:
                $StrReturn .= '<h2>' . __('On live', 'mscra-automation') . '</h2>';
        }
        $StrReturn .= __('default programming', 'mscra-automation') . '</p></td><td></td></TR>';
    }
    $StrReturn .= '</TABLE>';
    return $StrReturn;
}

add_shortcode('mscra_calendar_day', 'mscra_get_calendar_day');

function mscra_get_now_onair()
{
    if (is_admin()) {
        return;
    }
    include MSCRA_PLUGIN_DIR . 'connect_api.php';

    $Vars[0] = "date=" . urlencode(current_time('Y-m-d H:i:s'));
    $list = $MyRadio->QueryGetTable(seccions::CALENDAR, sub_seccions::NOWONAIR, $Vars);
    if ($MyRadio->RESPOSTA_ROWS > 0) {

        if ($list['item'][0]['ID'] == 0) {
            //programació per defecte (res programat)
        }
        $ara_prg_id = sanitize_text_field($list['item'][0]['ID_RELATED']);
        $ara_nom = sanitize_text_field($list['item'][0]['NAME']);
        $ara_descrip = sanitize_text_field($list['item'][0]['DESCRIP']);
        $ara_date_in = strftime("%H:%M", strtotime($list['item'][0]['DATE_INI']));
        $ara_date_out = strftime("%H:%M", strtotime($list['item'][0]['DATE_END']));
        $ara_remission = sanitize_text_field($list['item'][0]['REMISSION']);

        $despres_prg_id = sanitize_text_field($list['item'][0]['ID_RELATED']);
        $despres_nom = sanitize_text_field($list['item'][1]['NAME']);
        $despres_descrip = sanitize_text_field($list['item'][1]['DESCRIP']);
        $despres_date_in = strftime("%H:%M", strtotime($list['item'][1]['DATE_INI']));
        $despres_date_out = strftime("%H:%M", strtotime($list['item'][1]['DATE_END']));
        $despres_remission = sanitize_text_field($list['item'][1]['REMISSION']);

        switch (sanitize_text_field($list['item'][0]['TYPE_PROG'])) {
            case TIP_AUTOMATIC_LLISTA:break;
            case TIP_AUTOMATIC_RADIOFORMULA:break;
            case TIP_AUTOMATIC_PROGRAMA:
                $page = mscra_get_page_by_meta($ara_prg_id);
                if (isset($page->ID)) {
                    $ara_nom = '<a href="' . $page->guid . '">' . htmlentities($ara_nom) . '</a>';
                } else {
                    $ara_nom = htmlentities($ara_nom);
                }
                break;
            case TIP_DIRECTE_:
                $page = mscra_get_page_by_meta($ara_prg_id);
                if (isset($page->ID)) {
                    $ara_nom = '<a href="' . $page->guid . '">' . htmlentities($ara_nom) . '</a>';
                } else {
                    $ara_nom = htmlentities($ara_nom);
                }
                break;
            case TIP_CONEX_CENTRAL_:;
                break;
        }

        switch (sanitize_text_field($list['item'][1]['TYPE_PROG'])) {
            case TIP_AUTOMATIC_LLISTA:break;
            case TIP_AUTOMATIC_RADIOFORMULA:;
                break;
            case TIP_AUTOMATIC_PROGRAMA:
                $page = mscra_get_page_by_meta($despres_prg_id);
                if (isset($page->ID)) {
                    $despres_nom = '<a href="' . $page->guid . '">' . htmlentities($despres_nom) . '</a>';
                } else {
                    $despres_nom = htmlentities($despres_nom);
                }
                //_e('Programa en directe','mscra-automation');
                break;
                break;
            case TIP_DIRECTE_:
                $page = mscra_get_page_by_meta($despres_prg_id);
                if (isset($page->ID)) {
                    $despres_nom = '<a href="' . $page->guid . '">' . htmlentities($despres_nom) . '</a>';
                } else {
                    $despres_nom = htmlentities($despres_nom);
                }
                //__('Programa en directe','mscra-automation');
                break;
            case TIP_CONEX_CENTRAL_: /* __('Desconnexió','mscra-automation') */;
                break;
        }
        ?>
<div class="msc-now-after">
    <i><?php _e('Your are listening ...', 'mscra-automation');?></i>

    <div style="padding:20px;">
        <h2><?php echo $ara_nom; ?></h2>
        <p><?php echo $ara_descrip; ?></p>
        <?php
if ($ara_remission == 1) {
            ?><i class="fas fa-sync-alt" title="<?php _e('Rebroadcast', 'mscra-automation');?>"></i>
        <?php
}
        ?>
        <i class="far fa-calendar-alt"> <?php echo htmlentities($ara_date_in . ' - ' . $ara_date_out); ?></i>
    </div>
</div>
<div class="msc-now-after">
    <i><?php _e('And after...', 'mscra-automation');?></i>

    <div style="padding:20px;">
        <h2><?php echo $despres_nom; ?></h2>
        <p><?php echo $despres_descrip; ?></p>
        <?php
if ($despres_remission == 1) {
            ?><i class="fas fa-sync-alt" title="<?php _e('Rebroadcast', 'mscra-automation');?>"></i><?php
}
        ?><i class="far fa-calendar-alt"> <?php echo htmlentities($despres_date_in . ' - ' . $despres_date_out); ?></i>
    </div>
</div>

<?php
}
}

add_shortcode('mscra_now_onair', 'mscra_get_now_onair');