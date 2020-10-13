<?php

function mscra_get_manager_adv() {
    if (is_admin()) {
        return;
    }
    if (isset($_GET['logout'])) {
        return mscra_logOut();
    }
    if (isset($_GET['cpsw']) || isset($_POST['oldPsw'])) {
        return mscra_changePassword();
    }
    if (isset($_POST['select']) || isset($_POST['cliID'])) {
        //list inform radiotion        
        $falcaID = $_POST['select'];
        $ClientID = $_POST['cliID'];
        $strReturn = mscra_show_ListRadiation($ClientID, $falcaID);
    } else {
        if (isset($_GET['CliUser']) || isset($_GET['CliPsw'])) {
            $CliUsusari = $_GET['CliUser'];
            $CliPassword = $_GET['CliPsw'];
            if (!$CliUsusari || !$CliPassword) {
                //Error falten dades al formulari
                $strReturn = mscra_show_formLogin(true);
            } else {
                //list advertising                              
                $strReturn = mscra_show_listAdvertising($CliUsusari, $CliPassword);
            }
        } else {
            //Login Client
            $strReturn = mscra_show_formLogin();
        }
    }
    return $strReturn;
}

add_shortcode('mscra_manager_adv', 'mscra_get_manager_adv');

function mscra_show_formLogin($errorlog = false) {
    $strform = "<FORM METHOD=GET ACTION=" . get_permalink() . " class='search-form' >\n";
    $strform .= "<table>";
    if ($errorlog == true) {
        $_SESSION["client_expire"] = time() - 10;
        $strform .= "<tr><td><p><H1>" . __('Wrong username or password', 'mscra-automation') . "</H></p><p>&nbsp;</p></td></tr>";
    } else {
        $strform .= "<tr><td><p><H1>" . __('Area reserved for customers', 'mscra-automation') . "</H></p><p>&nbsp;</p></td></tr>";
    }
    $strform .= "<tr><td>" . __('Customer', 'mscra-automation') . ":</td><td ><input name=CliUser type=password></td></tr>";
    $strform .= "<tr><td><span>" . __('Password', 'mscra-automation') . ":</span></td><td><input name=CliPsw type=password></td></tr>";
    $strform .= "<tr><td><span></span></td><td><input name=submit type=submit value=" . __('Send', 'mscra-automation') . "></td></tr>";
    //$strform .= '<span id="ajax-search" style="display:none;"><a href=""></a></span>';
    $strform .= "</table></form>";
    return $strform;
}

//<button type="submit" class="search-submit">" . __('Send', 'mscra-automation') . "</button>
function mscra_show_listAdvertising($user, $psw) {
    
    include MSCRA_PLUGIN_DIR.'connect_api.php';
    
    $Vars[0] = "u=" . $user;
    $Vars[1] = "p=" . $psw;
    $info_client = $MyRadio->QueryGetTable(seccions::ADVERTISING, sub_seccions::LOGIN, $Vars);
    if ($MyRadio->RESPOSTA_CODE == ADV_LOGIN_KO) {
        //$strform .= "<CENTER><span>" . __('No s&acute;ha trobat cap client.', 'mscra-automation') . "</span></CENTER>";        
        $strform .= show_formLogin(true);
    } else {
        if ($MyRadio->RESPOSTA_ROWS > 0) {
            $_SESSION["client_expire"] = time() + 300;
            //Commercial list                           
            $CliId = $info_client['item']['ID'];
            $Empresa = $info_client['item']['NAME'];
            $Titular = $info_client['item']['CONTACT'];

            $strform .= mscra_show_mnu_client($CliId);
            $strform .= "<h2>" . sprintf(__('Welcome Mr/Ms %s from %s', 'mscra-automation'), $Titular, $Empresa) . "</h2>";
            //Busca totes les seves falques				
            $falques = $info_client['item']['ADVS']['ADV'];
            if(is_array($falques)){
                $total_rows = count($falques);
            }else{
                $total_rows = 0;
            }            
            
            if ($total_rows == 1) {
                $strform .= "<h3>" . __('Choose the ad you want to list', 'mscra-automation') . "...</h3>";

                $strform .= "<FORM METHOD=POST ACTION=" . get_permalink() . " >\n";
                $strform .= "<table>";
                $strform .= "<tr><td><span>" . __('Ads', 'mscra-automation') . ":</span></td><td>";

                $strform .= "<select name=select>";
                $strform .= "<option value = 0>" . __('All', 'mscra-automation') . "</option>";
                $strform .= "<option value = " . $falques ['ID'] . ">" . $falques ['NAME'] . "</option>";
                $strform .= "</select>";

                $strform .= "</td></tr>";
                $strform .= "<tr><td><span></span></td><td><input name=cliID type=hidden value=" . $CliId . "><input name=falca type=submit value=" . __('Send', 'mscra-automation') . "></td></tr>";
                $strform .= "</table></form>";
            } elseif ($total_rows > 1) {
                // S'han d'escollir totes o una de les falques.  

                $strform .= "<h3>" . __('Choose the ad you want to list', 'mscra-automation') . "...</h3>";

                $strform .= "<FORM METHOD=POST ACTION=" . get_permalink() . " >\n";
                $strform .= "<table>";
                $strform .= "<tr><td><span>" . __('Ads', 'mscra-automation') . ":</span></td><td>";

                $strform .= "<select name=\"select\" >";
                $strform .= "<option value = 0>" . __('All', 'mscra-automation') . "</option>";
                $counter = 0;
                while ($counter < $total_rows):
                    $strform .= "<option value = " . $falques [$counter]['ID'] . ">" . $falques [$counter]['NAME'] . "</option>";
                    $counter = $counter + 1;
                endwhile;
                $strform .= "</select>";

                $strform .= "</td></tr>";
                $strform .= "<tr><td><span></span></td><td><input name=\"cliID\" type=\"hidden\" value=" . $CliId . "><input name=falca type=submit value=" . __('Send', 'mscra-automation') . "></td></tr>";
                $strform .= "</table></form>";
            } else {
                $strform .= __('There are no ad for this client..', 'mscra-automation');
            }
        }
    }

    return $strform;
}

function mscra_show_ListRadiation($client, $falca) {
    if (isset($_SESSION["client_expire"])) {
        $tExpire = $_SESSION["client_expire"];
        if ($tExpire < time()) {
            $_SESSION["client_expire"] = time() - 10;
            $strform = __('Client session expired.', 'mscra-automation');
            $strform .= show_formLogin();
            return $strform;
        }
    } else {
        return show_formLogin();
    }
    include MSCRA_PLUGIN_DIR.'connect_api.php';

    $Vars[0] = "f=" . $falca;
    $Vars[1] = "c=" . $client;
    $list = $MyRadio->QueryGetTable(seccions::ADVERTISING, sub_seccions::RADIATION, $Vars);
    $strform .= show_mnu_client($client);
    if ($MyRadio->RESPOSTA_ROWS > 0) {
        if ($MyRadio->RESPOSTA_ROWS == 1) {
            $strform .= "<h3>" . __('Choose the ad you want to list', 'mscra-automation') . " ...</h3>";
            $strform .= "<FORM METHOD=POST ACTION=" . get_permalink() . " >\n";
            $strform .= "<table>";
            $strform .= "<tr><td><span>" . __('Ads', 'mscra-automation') . ":</span></td><td>";

            $strform .= "<select name=select>";
            $strform .= "<option value = 0>" . __('All', 'mscra-automation') . "</option>";
            $strform .= "<option value = " . $list['ADV']['ID'] . ">" . $list['ADV']['NAME'] . "</option>";
            $strform .= "</select>";

            $strform .= "</td></tr>";
            $strform .= "<tr><td><span></span></td><td><input name=cliID type=hidden value=" . $client . "><input name=falca type=submit value=" . __('Send', 'mscra-automation') . "></td></tr>";
            $strform .= "</table></form>";
        } elseif ($MyRadio->RESPOSTA_ROWS > 1) {
            // S'han d'escollir totes o una de les falques.                        
            $strform .= "<h3>" . __('Choose the ad you want to list', 'mscra-automation') . " ...</h3>";

            $strform .= "<FORM METHOD=POST ACTION=" . get_permalink() . " >\n";
            $strform .= "<table>";
            $strform .= "<tr><td><span>" . __('Ads', 'mscra-automation') . ":</span></td><td>";

            $strform .= "<select name=\"select\" >";
            $strform .= "<option value = 0>" . __('All', 'mscra-automation') . "</option>";
            $counter = 0;
            while ($counter < $total_rows):
                $strform .= "<option value = " . $list['ADV'][$counter]['ID'] . ">" . $list['ADV'][$counter]['NAME'] . "</option>";
                $counter = $counter + 1;
            endwhile;
            $strform .= "</select>";

            $strform .= "</td></tr>";
            $strform .= "<tr><td><span></span></td><td><input name=\"cliID\" type=\"hidden\" value=" . $client . "><input name=falca type=submit value=" . __('List', 'mscra-automation') . "></td></tr>";
            $strform .= "</table></form>";
        }
        //construir la taula de radiació
        $strform .= "<figure class='wp-block-table is-style-stripes'>";
        $strform .= "<TABLE><thead><TR>"
                . "<TH scope='col'>" . __('Name', 'mscra-automation') . "</TH>"
                . "<TH scope='col'>" . __('Client', 'mscra-automation') . "</TH>"
                . "<TH scope='col'>" . __('Radiation Date', 'mscra-automation') . "</TH>"
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
            $strform .= __("Number of times they have been radiated", 'mscra-automation') . ": " . $MyRadio->RESPOSTA_ROWS;
        } else {
            $strform .= __("Number of times it has been radiated", 'mscra-automation') . ": " . $MyRadio->RESPOSTA_ROWS;
        }
        $strform .= '</figcaption></figure>';
    } else {
        $strform .= "<CENTER>" . __('There are no ads', 'mscra-automation') . ".</CENTER><P>";
    }

    return $strform;
}

function mscra_logOut() {
    unset($_SESSION["client_expire"]);
    /* $strform .= "You have been logged out";
      $strform .= "<META HTTP-EQUIV='Refresh' CONTENT='3;URL=".get_home_url()."'>";
      return $strform; */

    $strform .= "<h3>" . __('You have been logged out', 'mscra-automation') . "</h3>";
    $strform .= show_formLogin();
    return $strform;
}

function mscra_changePassword() {
    if (isset($_POST['oldPsw'])) {
        
        include MSCRA_PLUGIN_DIR.'connect_api.php';

        $Vars[0] = "op=" . bin2hex($_POST['oldPsw']);
        $Vars[1] = "np=" . bin2hex($_POST['newPsw']);
        $Vars[2] = "c=" . bin2hex($_POST['cliID']);
        $info_client = $MyRadio->QueryGetTable(seccions::ADVERTISING, sub_seccions::CREDENTIALS, $Vars);
        if ($MyRadio->RESPOSTA_STATUS == SUCCES) {
            $strform .= "<h3>" . __('The changes have been successful', 'mscra-automation') . "...</h3>";
            unset($_SESSION["client_expire"]);
            $strform .= show_formLogin();
        } else {
            $strform .= "<h3>" . __('Error setting changes', 'mscra-automation') . "...</h3>";
        }
    } else {
        $client = $_GET['c'];
        $strform .= "<h3>" . __('Change your password', 'mscra-automation') . "...</h3>";
        $strform .= "<FORM METHOD=POST ACTION=" . get_permalink() . " >\n";
        $strform .= "<table>";
        $strform .= "<tr><td><span>" . __('Current password', 'mscra-automation') . ":</span></td><td><input name=oldPsw type=password></td></tr>";
        $strform .= "<tr><td><span>" . __('New Password', 'mscra-automation') . ":</span></td><td><input name=newPsw type=password></td></tr>";
        $strform .= "</td></tr>";
        $strform .= "<tr><td><span></span></td><td><input name=cliID type=hidden value=" . $client . "><input name=falca type=submit value=" . __('Send', 'mscra-automation') . "></td></tr>";
        $strform .= "</table></form>";
    }

    return $strform;
}

function mscra_show_mnu_client($client) {

    $strform = "<div class='topnav'>
                    <a href='" . get_permalink() . "?logout=1&c=" . $client . "'><b>" . __('Logout', 'mscra-automation') . "</b></a> | 
                    <a href='" . get_permalink() . "?cpsw=1&c=" . $client . "'><b>" . __('Change Password', 'mscra-automation') . "</b></a>                    
                </div>";
    return $strform;
}
