<?php

function mscra_get_manager_adv()
{
    if (is_admin()) {return;}
    
    $lOut = (isset($_GET['logout'])) ? sanitize_text_field($_GET['logout']) : 0;
    if ($lOut == 1) {
        return mscra_logOut();
    }
    $cuPass = (isset($_GET['cpsw'])) ? sanitize_text_field($_GET['cpsw']) : '';
    $nePass = (isset($_GET['npsw'])) ? sanitize_text_field($_GET['npsw']) : '';
    if ($cuPass != '' || $cuPass != '') {
        $cli = (isset($_GET['cliID'])) ? $_GET['cliID'] : 0;
        if (is_numeric($cli) || $cli > 0) {
            return mscra_changePassword($cuPass, $nePass, $cli);
        } else {
            $strReturn = mscra_show_formLogin(true);
        }
    }

    $falcaID = (isset($_GET['select'])) ? sanitize_text_field($_GET['select']) : '';
    $ClientID = (isset($_GET['cliID'])) ? sanitize_text_field($_GET['cliID']) : 0;
    if ($falcaID != '' || $ClientID != 0) {
        //list inform radiotion
        $strReturn = mscra_show_ListRadiation($ClientID, $falcaID);
    } else {
        $CliUsusari = (isset($_GET['CliUser'])) ? sanitize_text_field($_GET['CliUser']) : '';
        $CliPassword = (isset($_GET['CliPsw'])) ? sanitize_text_field($_GET['CliPsw']) : '';
        if ($CliUsusari != '' || $CliPassword != '') {
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

function mscra_show_formLogin($errorlog = false)
{
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
    $strform .= "</table></form>";
    return $strform;
}

//<button type="submit" class="search-submit">" . __('Send', 'mscra-automation') . "</button>
function mscra_show_listAdvertising($user, $psw)
{

    include MSCRA_PLUGIN_DIR . 'connect_api.php';

    $Vars[0] = "u=" . $user;
    $Vars[1] = "p=" . $psw;
    
    $info_client = $MyRadio->QueryGetTable(seccions::ADVERTISING, sub_seccions::LOGIN, $Vars);
    if ($MyRadio->RESPOSTA_CODE == ADV_LOGIN_OK) {
        if ($MyRadio->RESPOSTA_ROWS > 0) {
            $_SESSION["client_expire"] = time() + 300;
            //Commercial list
            $CliId = sanitize_text_field($info_client['item']['ID']);
            $Empresa = sanitize_text_field($info_client['item']['NAME']);
            $Titular = sanitize_text_field($info_client['item']['CONTACT']);

            $strform = mscra_show_mnu_client($CliId);
            $strform .= "<h2>" . sprintf(__('Welcome Mr/Ms %s from %s', 'mscra-automation'), $Titular, $Empresa) . "</h2>";
            //Busca totes les seves falques
            $falques = $info_client['item']['ADVS']['ADV'];
            if (is_array($falques)) {
                $total_rows = count($falques);
            } else {
                $total_rows = 0;
            }

            if ($total_rows == 1) {
                $strform .= "<h3>" . __('Choose the ad you want to list', 'mscra-automation') . "...</h3>";

                $strform .= "<FORM METHOD=POST ACTION=" . get_permalink() . " >\n";
                $strform .= "<table>";
                $strform .= "<tr><td><span>" . __('Ads', 'mscra-automation') . ":</span></td><td>";

                $strform .= "<select name=select>";
                $strform .= "<option value = 0>" . __('All', 'mscra-automation') . "</option>";
                $strform .= "<option value = " . sanitize_text_field($falques['ID']) . ">" . sanitize_text_field($falques['NAME']) . "</option>";
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
                    $strform .= "<option value = " . sanitize_text_field($falques[$counter]['ID']) . ">" . sanitize_text_field($falques[$counter]['NAME']) . "</option>";
                    $counter = $counter + 1;
                endwhile;
                $strform .= "</select>";

                $strform .= "</td></tr>";
                $strform .= "<tr><td><span></span></td><td><input name=\"cliID\" type=\"hidden\" value=" . $CliId . "><input name=falca type=submit value=" . __('Send', 'mscra-automation') . "></td></tr>";
                $strform .= "</table></form>";
            } else {
                $strform .= __('There are no ads for this client.', 'mscra-automation');
            }
        }
    } else {                
        $strform = mscra_show_formLogin(true);
    }

    return $strform;
}

function mscra_show_ListRadiation($client, $falca)
{
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
    include MSCRA_PLUGIN_DIR . 'connect_api.php';

    $Vars[0] = "f=" . $falca;
    $Vars[1] = "c=" . $client;
    $list = $MyRadio->QueryGetTable(seccions::ADVERTISING, sub_seccions::RADIATION, $Vars);
    $strform = show_mnu_client($client);
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
        if ($falca == 0) { // Zero equival a totes les d'aquest client.
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

function mscra_logOut()
{
    unset($_SESSION["client_expire"]);
    /* $strform .= "You have been logged out";
    $strform .= "<META HTTP-EQUIV='Refresh' CONTENT='3;URL=".get_home_url()."'>";
    return $strform; */

    $strform = "<h3>" . __('You have been logged out', 'mscra-automation') . "</h3>";
    $strform .= show_formLogin();
    return $strform;
}

function mscra_changePassword($cuPass, $nePass, $cli)
{
    if ($cuPass != '') {
        include MSCRA_PLUGIN_DIR . 'connect_api.php';

        $Vars[0] = "op=" . bin2hex($cuPass);
        $Vars[1] = "np=" . bin2hex($nePass);
        $Vars[2] = "c=" . bin2hex($cli);
        $info_client = $MyRadio->QueryGetTable(seccions::ADVERTISING, sub_seccions::CREDENTIALS, $Vars);
        if ($MyRadio->RESPOSTA_STATUS == SUCCES) {
            $strform = "<h3>" . __('The changes have been successful', 'mscra-automation') . "...</h3>";
            unset($_SESSION["client_expire"]);
            $strform .= show_formLogin();
        } else {
            $strform .= "<h3>" . __('Error setting changes', 'mscra-automation') . "...</h3>";
        }
    } else {

        $strform = "<h3>" . __('Change your password', 'mscra-automation') . "...</h3>";
        $strform .= "<FORM METHOD=POST ACTION=" . get_permalink() . " >\n";
        $strform .= "<table>";
        $strform .= "<tr><td><span>" . __('Current password', 'mscra-automation') . ":</span></td><td><input name=cpsw type=password></td></tr>";
        $strform .= "<tr><td><span>" . __('New Password', 'mscra-automation') . ":</span></td><td><input name=npsw type=password></td></tr>";
        $strform .= "</td></tr>";
        $strform .= "<tr><td><span></span></td><td><input name=cliID type=hidden value=" . $cli . "><input name=falca type=submit value=" . __('Send', 'mscra-automation') . "></td></tr>";
        $strform .= "</table></form>";
    }

    return $strform;
}

function mscra_show_mnu_client($client)
{

    $params = array('logout' => 1, 'c' => $client);
    $urlLogout = add_query_arg($params, get_permalink());
    $params = array('cpsw' => 1, 'c' => $client);
    $urlCPass = add_query_arg($params, get_permalink());

    $strform = "<div class='topnav'>
                    <a href='" . $urlLogout . "'><b>" . __('Logout', 'mscra-automation') . "</b></a> |
                    <a href='" . $urlCPass . "'><b>" . __('Change Password', 'mscra-automation') . "</b></a>
                </div>";
    return $strform;
}
