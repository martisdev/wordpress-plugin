<?php

function mscra_func_manager_cloud()
{
    if (is_admin()) {return;}
    $lOut = (isset($_GET['logout'])) ? sanitize_text_field($_GET['logout']) : 0;
    if ($lOut == 1) {
        return mscra_logOut_cloud();
    }

    if (isset($_GET['reg'])) {
        return msc_create_user_cloud();
    } else {
        $CliUsusari = (isset($_GET['CliUser'])) ? sanitize_text_field($_GET['CliUser']) : '';
        $CliPassword = (isset($_GET['CliPsw'])) ? sanitize_text_field($_GET['CliPsw']) : '';
        if ($CliUsusari != '' || $CliPassword != '') {
            if (!$CliUsusari || !$CliPassword) {
                //Error falten dades al formulari
                return msc_login_form_user_cloud(true);
            } else {

            }
        } else {
            //Login Client
            return msc_login_form_user_cloud();
        }
    }

}

add_shortcode('mscra_manager_cloud', 'mscra_func_manager_cloud');

function msc_create_user_cloud()
{
    // Create an user in cloud
    include MSCRA_PLUGIN_DIR . 'connect_api.php';

    $Vars[0] = "u=" . $user;
    $Vars[1] = "p=" . $psw;    
    $info_client = $MyRadio->QueryGetTable(seccions::ADVERTISING, sub_seccions::LOGIN, $Vars);

    $strform = "<p>" . __('asdasda', 'mscra-automation') . " <b>" . __('register', 'mscra-automation') . "</b></p>";

    return $strform;
}

function msc_login_form_user_cloud($errorlog = false)
{
    $strform = "<FORM METHOD=GET ACTION=" . get_permalink() . " class='search-form' >\n";
    $strform .= "<table>";
    if ($errorlog == true) {
        $_SESSION["client_expire"] = time() - 10;
        $strform .= "<tr><td><p><H1>" . __('Wrong username or password', 'mscra-automation') . "</H></p><p>&nbsp;</p></td></tr>";
    } else {
        $strform .= "<tr><td><p><H1>" . __('Area reserved', 'mscra-automation') . "</H></p><p>&nbsp;</p></td></tr>";
    }
    $strform .= "<tr><td><label for=CliUser>" . __('Customer', 'mscra-automation') . ":</label></td><td ><input name=CliUser type=password></td></tr>";
    $strform .= "<tr><td><label for=CliPsw>" . __('Password', 'mscra-automation') . ":</label></td><td><input name=CliPsw type=password></td></tr>";
    $strform .= "<tr><td><span></span></td><td><input name=submit type=submit value=" . __('Send', 'mscra-automation') . "></td></tr>";
    $strform .= "</table></form>";

    $params = array('reg' => 1);
    $urlreg = add_query_arg($params, get_permalink());

    $strform .= "<p>" . __('Are you new, don\'t have an acount?', 'mscra-automation') . " <a href='" . $urlreg . "'><b>" . __('register', 'mscra-automation') . "</b></a></p>";

    return $strform;
}

function msc_upload_file_cloud()
{
    // Upload file
    if (isset($_POST['but_submit'])) {

        if ($_FILES['file']['name'] != '') {
            $uploadedfile = $_FILES['file'];
            $upload_overrides = array('test_form' => false);

            $movefile = wp_handle_upload($uploadedfile, $upload_overrides);
            $imageurl = "";
            if ($movefile && !isset($movefile['error'])) {
                $imageurl = $movefile['url'];
                echo "url : " . $imageurl;
            } else {
                echo $movefile['error'];
            }
        }
    }
    ?>
    <h1>Upload File</h1>

    <!-- Form -->
    <form method='post' action='' name='myform' enctype='multipart/form-data'>
        <table>
            <tr>
                <td>Upload file</td>
                <td><input type='file' name='file' accept="audio/mp3"></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><input type='submit' name='but_submit' value='Submit'></td>
            </tr>
        </table>
    </form>
<?php
}

function mscra_logOut_cloud()
{
    unset($_SESSION["client_expire"]);

    $strform = "<h3>" . __('You have been logged out', 'mscra-automation') . "</h3>";
    $strform .= msc_register_form_user_cloud();
    return $strform;
}