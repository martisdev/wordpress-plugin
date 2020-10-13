<?php

function mscra_get_calendar_day() {
    if (is_admin()) {
        return;
    }
    include MSCRA_PLUGIN_DIR.'connect_api.php';
    
    //setlocale (LC_TIME, get_locale());
    if (!isset($_GET['goto'])) {
        $dateCal = current_time('Y-m-d');
    } else {
        list($year, $month, $day ) = explode('-', $_GET['date']);
        if ($_GET['goto'] == 'ant') {
            //$dateCal = $iniData-86400;                   
            $dateCal = mktime(0, 0, 0, $month, $day - 1, $year);
            $dateCal = strftime("%Y-%m-%d", $dateCal);
        } elseif ($_GET['goto'] == 'post') {
            //$dateCal = $iniData+86400;                
            $dateCal = mktime(0, 0, 0, $month, $day + 1, $year);
            $dateCal = strftime("%Y-%m-%d", $dateCal);
        }
    }

    $Vars[0] = "date=" . urlencode($dateCal);
    $list = $MyRadio->QueryGetTable(seccions::CALENDAR, sub_seccions::GRIDDAY, $Vars);

    $self = site_url() . "?p=" . get_the_ID();
    if (get_locale() == 'ca') {
        setlocale(LC_TIME, get_locale(), 'ca_ES', 'ca-ES');
    } else {
        setlocale(LC_TIME, get_locale());
    }
    $StrData = utf8_encode(strftime("%A, %d %B %Y", strtotime($dateCal)));
    $LinkAnt = $self . '&goto=ant&date=' . $dateCal;
    $LinkPost = $self . '&goto=post&date=' . $dateCal;

    $StrReturn = '<TABLE  align=center><TR>
                        <TH scope="col"><a href="' . $LinkAnt . '" style="text-align:center;"><i class="fas fa-chevron-left" aria-hidden="true"></i></a></TH>
                        <TH scope="col"><h3><b>' . $StrData . '</b></h3></TH>
                        <TH scope="col"><a href="' . $LinkPost . '" style="text-align:center;"><i class="fas fa-chevron-right" aria-hidden="true"></i></a></TH></TR>';

    $counter = 0;

    //$strReturn = '<div >';
    $Strloop = '';
    if ($MyRadio->RESPOSTA_ROWS == 1) {
        $type_prg = $list['item']['TYPE_PROG'];
        $prg_id = $list['item']['ID'];
        $txtLabel = ($list['item']['NAME']);
        $txtDescrip = ($list['item']['DESCRIP']);

        $MinutIni = $list['item']['MINUTE_INI'];
        $MinutDurada = $list['item']['DURATION'];
        $txtHoraIni = date("G:i", mktime(0, $MinutIni, 0, 0, 0, 0));
        $txtDurada = date("G:i", mktime(0, $MinutDurada, 0, 0, 0, 0));
        $is_reemissio = $list['item']['REMISSION'];

        $Strloop .= '<TR><td><div id="cal-top"><i class="far fa-calendar"> ' . $txtHoraIni . '</i></div></td>';
        switch ($type_prg):
            case TIP_AUTOMATIC:
                $page = get_page_by_title(html_entity_decode($txtLabel));
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
                $page = get_page_by_title(html_entity_decode($txtLabel));
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
            $type_prg = $list['item'][$counter]['TYPE_PROG'];
            $txtLabel = ($list['item'][$counter]['NAME']);
            $txtDescrip = ($list['item'][$counter]['DESCRIP']);

            $MinutIni = $list['item'][$counter]['MINUTE_INI'];
            $MinutDurada = $list['item'][$counter]['DURATION'];
            $txtHoraIni = date("G:i", mktime(0, $MinutIni, 0, 0, 0, 0));
            $txtDurada = date("G:i", mktime(0, $MinutDurada, 0, 0, 0, 0));
            $is_reemissio = $list['item'][$counter]['REMISSION'];

            $Strloop .= '<TR><td><div id="cal-top"><i class="far fa-calendar"> ' . $txtHoraIni . '</i></div></td>';
            switch ($type_prg):
                case TIP_AUTOMATIC:
                    $page = get_page_by_title(html_entity_decode($txtLabel));
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
                    $page = get_page_by_title(html_entity_decode($txtLabel));
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
        $StrReturn .= '<TR><td></td><td>';
        $StrReturn .= '<p>';
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
        $StrReturn .= __('default programming', 'mscra-automation') . '</p></td></td><td></TR>';
    }
    $StrReturn .= '</TABLE>';
    return $StrReturn;
}

add_shortcode('mscra_calendar_day', 'mscra_get_calendar_day');

function mscra_get_now_onair() {
    if (is_admin()) {
        return;
    }
    include MSCRA_PLUGIN_DIR.'connect_api.php';
    
    $Vars[0] = "date=" . urlencode(current_time('Y-m-d H:i:s'));
    $list = $MyRadio->QueryGetTable(seccions::CALENDAR, sub_seccions::NOWONAIR, $Vars);
    if ($MyRadio->RESPOSTA_ROWS > 0) {
        if ($list['item'][0]['ID'] == 0) {
            //programació per defecte (res programat)                
        }
        $ara_nom = $list['item'][0]['NAME'];
        $ara_descrip = $list['item'][0]['DESCRIP'];
        $ara_date_in = strftime("%H:%M", strtotime($list['item'][0]['DATE_INI']));
        $ara_date_out = strftime("%H:%M", strtotime($list['item'][0]['DATE_END']));
        $ara_remission = $list['item'][0]['REMISSION'];

        $despres_nom = $list['item'][1]['NAME'];
        $despres_descrip = $list['item'][1]['DESCRIP'];
        $despres_date_in = strftime("%H:%M", strtotime($list['item'][1]['DATE_INI']));
        $despres_date_out = strftime("%H:%M", strtotime($list['item'][1]['DATE_END']));
        $despres_remission = $list['item'][1]['REMISSION'];

        switch ($list['item'][0]['TYPE_PROG']) {
            case TIP_AUTOMATIC_LLISTA:break;
            case TIP_AUTOMATIC_RADIOFORMULA:break;
            case TIP_AUTOMATIC_PROGRAMA:
                $page = get_page_by_title($ara_nom);
                if (isset($page->ID)) {
                    $ara_nom = '<a href="' . $page->guid . '">' . htmlentities($ara_nom) . '</a>';
                } else {
                    $ara_nom = htmlentities($ara_nom);
                }
                break;
                break;
            case TIP_DIRECTE_:
                $page = get_page_by_title($ara_nom);
                if (isset($page->ID)) {
                    $ara_nom = '<a href="' . $page->guid . '">' . htmlentities($ara_nom) . '</a>';
                } else {
                    $ara_nom = htmlentities($ara_nom);
                }
                break;
            case TIP_CONEX_CENTRAL_:;
                break;
        }

        switch ($list['item'][1]['TYPE_PROG']) {
            case TIP_AUTOMATIC_LLISTA:break;
            case TIP_AUTOMATIC_RADIOFORMULA:;
                break;
            case TIP_AUTOMATIC_PROGRAMA:
                $page = get_page_by_title($despres_nom);
                if (isset($page->ID)) {
                    $despres_nom = '<a href="' . $page->guid . '">' . htmlentities($despres_nom) . '</a>';
                } else {
                    $despres_nom = htmlentities($despres_nom);
                }
                //_e('Programa en directe','mscra-automation');
                break;
                break;
            case TIP_DIRECTE_:
                $page = get_page_by_title($despres_nom);
                if (isset($page->ID)) {
                    $despres_nom = '<a href="' . $page->guid . '">' . htmlentities($despres_nom) . '</a>';
                } else {
                    $despres_nom = htmlentities($despres_nom);
                }
                //__('Programa en directe','mscra-automation');
                break;
            case TIP_CONEX_CENTRAL_:/* __('Desconnexió','mscra-automation') */;
                break;
        }
        ?>
        <div class="msc-now-after">
            <i><?php _e('Your are listening ...', 'mscra-automation'); ?></i>

            <div style="padding:20px;"><h2><?php echo $ara_nom; ?></h2>
                <p><?php echo $ara_descrip; ?></p>
                <?php
                if ($ara_remission == 1) {
                    ?><i class="fas fa-sync-alt" title="<?php _e('Rebroadcast', 'mscra-automation'); ?>"></i>
                    <?php
                }
                ?>
                <i class="far fa-calendar-alt"> <?php echo htmlentities($ara_date_in . ' - ' . $ara_date_out); ?></i></div></div>                                
        <div class="msc-now-after" >
            <i><?php _e('And after...', 'mscra-automation'); ?></i>

            <div style="padding:20px;"><h2><?php echo $despres_nom; ?></h2>
                <p><?php echo $despres_descrip; ?></p>
                <?php
                if ($despres_remission == 1) {
                    ?><i class="fas fa-sync-alt" title="<?php _e('Rebroadcast', 'mscra-automation'); ?>"></i><?php
                }
                ?><i class="far fa-calendar-alt" > <?php echo htmlentities($despres_date_in . ' - ' . $despres_date_out); ?></i></div></div>

        <?php
    }
}

add_shortcode('mscra_now_onair', 'mscra_get_now_onair');
