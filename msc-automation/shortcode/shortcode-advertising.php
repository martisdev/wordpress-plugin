<?php

function get_manager_adv() {
    if (is_admin()) {
        return;
    }
    if (isset($_GET['logout'])) {
        return logOut();
    }
    if (isset($_GET['cpsw']) || isset($_POST['oldPsw'])) {        
        return changePassword();
    }
    if (isset($_POST['select']) || isset($_POST['cliID'])) {
        //list inform radiotion        
        $falcaID = $_POST['select'];
        $ClientID = $_POST['cliID'];
        $strReturn .= show_ListRadiation($ClientID, $falcaID);
    } else {
        if (isset($_POST['CliUser']) || isset($_POST['CliPsw'])) {
            $CliUsusari = $_POST['CliUser'];
            $CliPassword = $_POST['CliPsw'];
            if (!$CliUsusari || !$CliPassword) {
                //Error falten dades al formulari
                $strReturn .= show_formLogin(true);
            } else {
                //list advertising                              
                $strReturn .= show_listAdvertising($CliUsusari, $CliPassword);
            }
        } else {
            //Login Client
            $strReturn .= show_formLogin();
        }
    }
    return $strReturn;
}

add_shortcode('manager_adv', 'get_manager_adv');

function show_formLogin($errorlog = false) {
    $strform = "<FORM METHOD=POST ACTION=" . get_permalink() . " class='search-form' >\n";
    $strform .= "<table>";
    if ($errorlog == true) {
        $_SESSION["client_expire"] = time() - 10;
        $strform .= "<tr><td><p><H1>" . __('Wrong username or password', 'msc-automation') . "</H></p><p>&nbsp;</p></td></tr>";
    } else {
        $strform .= "<tr><td><p><H1>" . __('Area reserved for customers', 'msc-automation') . "</H></p><p>&nbsp;</p></td></tr>";
    }
    $strform .= "<tr><td>" . __('Customer', 'msc-automation') . ":</td><td ><input name=CliUser type=password></td></tr>";
    $strform .= "<tr><td><span>" . __('Password', 'msc-automation') . ":</span></td><td><input name=CliPsw type=password></td></tr>";
    $strform .= "<tr><td><span></span></td><td><input name=submit type=submit value=" . __('Send', 'msc-automation') . "></td></tr>";
    $strform .= "</table></form>";
    return $strform;
}

function show_listAdvertising($user, $psw) {
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

    $Vars[0] = "u=" . $user;
    $Vars[1] = "p=" . $psw;
    $info_client = $MyRadio->QueryGetTable(seccions::ADVERTISING, sub_seccions::LOGIN, $Vars);
    if ($MyRadio->RESPOSTA_CODE == ADV_LOGIN_KO) {
        //$strform .= "<CENTER><span>" . __('No s&acute;ha trobat cap client.', 'msc-automation') . "</span></CENTER>";        
        $strform .= show_formLogin(true);
    } else {
        if ($MyRadio->RESPOSTA_ROWS > 0) {
            $_SESSION["client_expire"] = time() + 300;
            //Commercial list                           
            $CliId = $info_client['item']['ID'];
            $Empresa = $info_client['item']['NAME'];
            $Titular = $info_client['item']['CONTACT'];
            
            $strform .= show_mnu_client($CliId);
            $strform .= "<h2>" . sprintf(__('Welcome Mr/Ms %s from %s', 'msc-automation'), $Titular, $Empresa) . "</h2>";
            //Busca totes les seves falques				
            $falques = $info_client['item']['ADVS']['ADV'];

            $total_rows = count($falques);
            if ($total_rows == 1) {
                $strform .= "<h3>" . __('Choose the ad you want to list', 'msc-automation') . "...</h3>";

                $strform .= "<FORM METHOD=POST ACTION=" . get_permalink() . " >\n";
                $strform .= "<table>";
                $strform .= "<tr><td><span>" . __('Ads', 'msc-automation') . ":</span></td><td>";

                $strform .= "<select name=select>";
                $strform .= "<option value = 0>" . __('All', 'msc-automation') . "</option>";
                $strform .= "<option value = " . $falques ['ID'] . ">" . $falques ['NAME'] . "</option>";
                $strform .= "</select>";

                $strform .= "</td></tr>";
                $strform .= "<tr><td><span></span></td><td><input name=cliID type=hidden value=" . $CliId . "><input name=falca type=submit value=" . __('Send', 'msc-automation') . "></td></tr>";
                $strform .= "</table></form>";
            } elseif ($total_rows > 1) {
                // S'han d'escollir totes o una de les falques.  

                $strform .= "<h3>" . __('Choose the ad you want to list', 'msc-automation') . "...</h3>";

                $strform .= "<FORM METHOD=POST ACTION=" . get_permalink() . " >\n";
                $strform .= "<table>";
                $strform .= "<tr><td><span>" . __('Ads', 'msc-automation') . ":</span></td><td>";

                $strform .= "<select name=\"select\" >";
                $strform .= "<option value = 0>" . __('All', 'msc-automation') . "</option>";
                $counter = 0;
                while ($counter < $total_rows):
                    $strform .= "<option value = " . $falques [$counter]['ID'] . ">" . $falques [$counter]['NAME'] . "</option>";
                    $counter = $counter + 1;
                endwhile;
                $strform .= "</select>";

                $strform .= "</td></tr>";
                $strform .= "<tr><td><span></span></td><td><input name=\"cliID\" type=\"hidden\" value=" . $CliId . "><input name=falca type=submit value=" . __('Send', 'msc-automation') . "></td></tr>";
                $strform .= "</table></form>";
            } else {
                $strform .= __('There are no ad for this client..', 'msc-automation');
            }
        }
    }

    return $strform;
}

function show_ListRadiation($client, $falca) {
    if (isset($_SESSION["client_expire"])) {
        $tExpire = $_SESSION["client_expire"];
        if ($tExpire < time()) {
            $_SESSION["client_expire"] = time() - 10;
            $strform = __('Client session expired.', 'msc-automation');
            $strform .= show_formLogin();
            return $strform;
        }
    } else {
        return show_formLogin();
    }
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
    $Vars[0] = "f=" . $falca;
    $Vars[1] = "c=" . $client;
    $list = $MyRadio->QueryGetTable(seccions::ADVERTISING, sub_seccions::RADIATION, $Vars);
    $strform .= show_mnu_client($client);
    if ($MyRadio->RESPOSTA_ROWS > 0) {
        $total_rows = count($list['ADV']);
        if ($total_rows == 1) {
            $strform .= "<h3>" . __('Choose the ad you want to list', 'msc-automation') . " ...</h3>";
            $strform .= "<FORM METHOD=POST ACTION=" . get_permalink() . " >\n";
            $strform .= "<table>";
            $strform .= "<tr><td><span>" . __('Ads', 'msc-automation') . ":</span></td><td>";

            $strform .= "<select name=select>";
            $strform .= "<option value = 0>" . __('All', 'msc-automation') . "</option>";
            $strform .= "<option value = " . $list['ADV']['ID'] . ">" . $list['ADV']['NAME'] . "</option>";
            $strform .= "</select>";

            $strform .= "</td></tr>";
            $strform .= "<tr><td><span></span></td><td><input name=cliID type=hidden value=" . $client . "><input name=falca type=submit value=" . __('Send', 'msc-automation') . "></td></tr>";
            $strform .= "</table></form>";
        } elseif ($total_rows > 1) {
            // S'han d'escollir totes o una de les falques.                        
            $strform .= "<h3>" . __('Choose the ad you want to list', 'msc-automation') . " ...</h3>";

            $strform .= "<FORM METHOD=POST ACTION=" . get_permalink() . " >\n";
            $strform .= "<table>";
            $strform .= "<tr><td><span>" . __('Ads', 'msc-automation') . ":</span></td><td>";

            $strform .= "<select name=\"select\" >";
            $strform .= "<option value = 0>" . __('All', 'msc-automation') . "</option>";
            $counter = 0;
            while ($counter < $total_rows):
                $strform .= "<option value = " . $list['ADV'][$counter]['ID'] . ">" . $list['ADV'][$counter]['NAME'] . "</option>";
                $counter = $counter + 1;
            endwhile;
            $strform .= "</select>";

            $strform .= "</td></tr>";
            $strform .= "<tr><td><span></span></td><td><input name=\"cliID\" type=\"hidden\" value=" . $client . "><input name=falca type=submit value=" . __('List', 'msc-automation') . "></td></tr>";
            $strform .= "</table></form>";
        }
        //construir la taula de radiació
        $strform .= "<figure class='wp-block-table is-style-stripes'>";
        $strform .= "<TABLE><thead><TR>"
                . "<TH scope='col'>" . __('Name', 'msc-automation') . "</TH>"
                . "<TH scope='col'>" . __('Client', 'msc-automation') . "</TH>"
                . "<TH scope='col'>" . __('Radiation Date', 'msc-automation') . "</TH>"
                . "</thead></TR><tbody>";

        if ($MyRadio->RESPOSTA_ROWS == 1) {

            $StrEcho = "<TR>";
            $StrEcho .= '<TD>' . $list['item']['NAME'] . '</TD>';
            $StrEcho .= '<TD>' . $list['item']['CLI_NAME'] . '</TD>';
            $StrEcho .= '<TD>' . $list['item']['DATE'] . '</TD>';
            $StrEcho .= '</TR>';
            $strform .= $StrEcho;
        } else {
            $counter = 0;
            while ($counter < $MyRadio->RESPOSTA_ROWS):
                if (fmod($counter, 2) == 0) {
                    $StrEcho = "<TR>";
                } else {
                    $StrEcho = "<TR class='altrow'>";
                }
                $StrEcho .= '<TD>' . $list['item'][$counter]['NAME'] . '</TD>';
                $StrEcho .= '<TD>' . $list['item'][$counter]['CLI_NAME'] . '</TD>';
                $StrEcho .= '<TD>' . $list['item'][$counter]['DATE'] . '</TD>';
                $StrEcho .= '</TR>';

                $counter = $counter + 1;
                $strform .= $StrEcho;
            endwhile;
        }

        $strform .= '</tbody></TABLE><figcaption>';
        if ($falca == 0) {// Zero equival a totes les d'aquest client.
            $strform .= __("Number of times they have been radiated", 'msc-automation') . ": " . $MyRadio->RESPOSTA_ROWS;
        } else {
            $strform .= __("Number of times it has been radiated", 'msc-automation') . ": " . $MyRadio->RESPOSTA_ROWS;
        }
        $strform .= '</figcaption></figure>';
    } else {
        $strform .= "<CENTER>" . __('There are no ads', 'msc-automation') . ".</CENTER><P>";
    }

    return $strform;
}

function logOut() {
    unset($_SESSION["client_expire"]);
    /* $strform .= "You have been logged out";
      $strform .= "<META HTTP-EQUIV='Refresh' CONTENT='3;URL=".get_home_url()."'>";
      return $strform; */

    $strform .= "<h3>" . __('You have been logged out', 'msc-automation') . "</h3>";
    $strform .= show_formLogin();
    return $strform;
}

function changePassword() {
    if (isset($_POST['oldPsw'])) {        
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
        $Vars[0] = "op=" . bin2hex($_POST['oldPsw']);
        $Vars[1] = "np=" . bin2hex($_POST['newPsw']);
        $Vars[2] = "c=" . bin2hex($_POST['cliID']);
        $info_client = $MyRadio->QueryGetTable(seccions::ADVERTISING, sub_seccions::CREDENTIALS, $Vars);
        if ($MyRadio->RESPOSTA_STATUS == SUCCES) {
            $strform .= "<h3>" . __('The changes have been successful', 'msc-automation') . "...</h3>";            
            unset($_SESSION["client_expire"]);
            $strform .= show_formLogin();
        }else{
            $strform .= "<h3>" . __('Error setting changes', 'msc-automation') . "...</h3>";
        }
    } else {        
        $client= $_GET['c'];
        $strform .= "<h3>" . __('Change your password', 'msc-automation') . "...</h3>";
        $strform .= "<FORM METHOD=POST ACTION=" . get_permalink() . " >\n";
        $strform .= "<table>";
        $strform .= "<tr><td><span>". __('Current password', 'msc-automation').":</span></td><td><input name=oldPsw type=password></td></tr>";
        $strform .= "<tr><td><span>". __('New Password', 'msc-automation') .":</span></td><td><input name=newPsw type=password></td></tr>";
        $strform .= "</td></tr>";
        $strform .= "<tr><td><span></span></td><td><input name=cliID type=hidden value=" . $client . "><input name=falca type=submit value=" . __('Send', 'msc-automation') . "></td></tr>";
        $strform .= "</table></form>";
    }

    return $strform;
}

function show_mnu_client($client) {

    $strform = "<div class='topnav'>
                    <a href='" . get_permalink() . "?logout=1&c=".$client."'><b>". __('Logout', 'msc-automation')."</b></a> | 
                    <a href='" . get_permalink() . "?cpsw=1&c=".$client."'><b>". __('Change Password', 'msc-automation')."</b></a>                    
                </div>";
    return $strform;
}
