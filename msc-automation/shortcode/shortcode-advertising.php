<?php

function get_manager_adv() {
    if (is_admin()){return;}
    if (isset($_POST['falca']) || isset($_POST['cliID'])) 
    {
        $falcaID = $_POST['select'];
        $ClientID = $_POST['cliID'];

        //$falques = $Publicitat->get_llista_falques_client($ClientID,$total_rows);
        if ($total_rows > 0) {
            // S'han d'escollir totes o una de les falques.
            $strReturn = "<CENTER>" . __('Escolliu la falca que voleu llistar ...', 'msc-automation') . "<br>";
            $strReturn .= "<FORM METHOD = \"POST\" ACTION=" . get_permalink() . ">\n";
            $strReturn .= "<div align=center>";
            $strReturn .= "<select name=\"select\" >";
            $strReturn .= "<option value = 0>Totes</option>";
            $counter = 0;
            while ($counter < $total_rows):
                $strReturn .= "<option value = " . $falques ['FALCA_ID'][$counter] . ">";
                $strReturn .= $falques ['FALCA_NOM'][$counter] . " </option>";
                $counter = $counter + 1;
            endwhile;
            $strReturn .= "</select>\n";
            $strReturn .= "<input name=\"cliID\" type=\"hidden\" value=" . $ClientID . ">";
            $strReturn .= "<input name=\"falca\" type=\"submit\" value=\"falca\">";
            $strReturn .= "</div>";
            $strReturn .= "</form><br>";
        }
        //Llistar falques

        $total_rows = 0;

        $report = $Publicitat->ReportClient($falcaID, $ClientID, $total_rows, true);

        if ($total_rows > 0) {
            include_once PATH_WP_SNIPPETS . 'snippets/snpGeneral.php';
            $ColsNames[0] = 'Nom Falca';
            $ColsNames[1] = 'Nom Client';
            $ColsNames[2] = 'Data de Radiaci�';
            $strReturn .= ShowList($report, $ColsNames, 'datatable', 'altrow');
            $strReturn .= "<br>";
            if ($falcaID == 0) {// Zero equival a totes les d'aquest client.
                $strReturn .= "<CENTER>S&acute;han radiat " . $total_rows . " vegades.</CENTER><P>";
            } else {
                $strReturn .= "<CENTER>S&acute;ha radiat " . $total_rows . " vegades.</CENTER><P>";
            }
        } else {
            $strReturn .= "<CENTER>No hi ha cap falca.</CENTER><P>";
        }
    } 
    else 
    {
        if (isset($_POST['CliUser']) || isset($_POST['CliPsw'])) 
        {
            $CliUsusari = $_POST['CliUser'];
            $CliPassword = $_POST['CliPsw'];
            if (!$CliUsusari || !$CliPassword) {
                $strReturn .= "<FORM METHOD=POST ACTION=" . get_permalink() . " >\n";
                $strReturn .= "<table border=0 align=center cellpadding=0 cellspacing=0>";
                $strReturn .= "<tr><td><p><H1>Zona reservada als clients</H></p><p>&nbsp;</p></td></tr>";
                $strReturn .= "<tr><td><span>Client:</span></td><td ><input name=CliUser type=password></td></tr>";
                $strReturn .= "<tr><td><span></span></td><td><span></span></td></tr>";
                $strReturn .= "<tr><td><span>Contrasenya:</span></td><td><input name=CliPsw type=password></td></tr>";
                $strReturn .= "<tr><td><span></span></td><td><input name=submit type=submit value=Entrar></td></tr>";
                $strReturn .= "</table></form>";
            } else {                
                global $MyRadio;
                if (!isset($MyRadio)) {
                    $MyRadio = new my_radio(get_option('msc_client_key'), get_locale(), get_option('msc_debug'));
                }
                if ($MyRadio->RESPOSTA_STATUS !== SUCCES) {
                    if ($MyRadio->IS_DEGUG == true) {
                        $msg = 'STATUS: ' . $MyRadio->RESPOSTA_STATUS . ' CODE: ' . $MyRadio->RESPOSTA_CODE . ' MSG: ' . $MyRadio->RESPOSTA_MESSAGE;
                        show_msc_message($msg, message_type::DANGER);
                        return;
                    }
                }
                
                $Vars[0] = "u=" . $CliUsusari;
                $Vars[1] = "p=" . $CliPassword;
                $info_client = $MyRadio->QueryGetTable(seccions::ADVERTISING, sub_seccions::LOGIN, $Vars);                
                if ($MyRadio->RESPOSTA_ROWS>0) {
                    //finalitza registrant la acci�                                       
                    $CliId = $info_client['item']['ID'];
                    $Empresa = $info_client['item']['NAME'];
                    $Titular = $info_client['item']['CONTACT'];

                    //echo "<CENTER>". date('l, d M Y h:i') ."</CENTER><P>";
                    //$strReturn .= "<p>&nbsp;</p>";
                    $strReturn .= "<CENTER><h2>Benvingut sr/a $Titular de $Empresa</h2>";
                    $strReturn .= "</BR>";
                    //Busca totes les seves falques				
                    $falques = $info_client['item']['ADVS']['ADV'];                    
                    $total_rows= count($falques);                    
                    if ($total_rows > 0) {
                        // S'han d'escollir totes o una de les falques.                        
                        $strReturn .= "<CENTER>Escolliu la falca que voleu llistar ...<br>";
                        $strReturn .= "<FORM METHOD = \"POST\" ACTION=" . get_permalink() . ">\n";
                        $strReturn .= "<div align=center>";
                        $strReturn .= "<select name=\"select\" >";
                        $strReturn .= "<option value = 0>Totes</option>";
                        $counter = 0;
                        while ($counter < $total_rows):
                            $strReturn .= "<option value = ".$falques [$counter]['ID'].">".$falques [$counter]['NAME']."</option>";                            
                            $counter = $counter + 1;
                        endwhile;
                        $strReturn .= "</select>\n";
                        $strReturn .= "</BR>";
                        $strReturn .= "<input name=\"cliID\" type=\"hidden\" value=" . $CliId . ">";                        
                        $strReturn .= "<input name=\"falca\" type=\"submit\" value=\"llistar\">";
                        $strReturn .= "</div>";
                        $strReturn .= "</form>";
                    } else {
                        $strReturn .= '<p>No hi ha cap falca d&acute;aquest client.</p>';
                    }
                } else {
                    $strReturn .= "<br>";
                    $strReturn .= "<CENTER><span>No s&acute;ha trobat cap client.</span></CENTER>";
                }
            }
        } 
        else 
        {

            $strReturn .= "<FORM METHOD=POST ACTION=" . get_permalink() . " >\n";
            $strReturn .= "<table>";
            $strReturn .= "<tr><td>Client:</td><td ><input name=CliUser type=password></td></tr>";
            $strReturn .= "<tr><td><span>Contrasenya:</span></td><td><input name=CliPsw type=password></td></tr>";
            $strReturn .= "<tr><td><span></span></td><td><input name=submit type=submit value=Entrar></td></tr>";
            $strReturn .= "</table></form>";
        }
    }
    return $strReturn;
}

add_shortcode('manager_adv', 'get_manager_adv');
