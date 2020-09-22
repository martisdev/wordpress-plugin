<?php

function get_last_played($attributes) {
    if (is_admin()) {
        return;
    }
    $refresh = (isset($attributes['refresh'])) ? $attributes['refresh'] : FALSE;
    $image = (isset($attributes['image'])) ? $attributes['image'] : FALSE;
    $NumRows = (isset($attributes['rows'])) ? $attributes['rows'] : 10;
    $cssbutton = (isset($attributes['stylebutton'])) ? $attributes['stylebutton'] : "";
    //$_SESSION['MyUrlRoot'] = get_site_url();        

    $dradi = (isset($_POST['dradi'])) ? $_POST['dradi'] : NULL;
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

    $col_name[0] = __('Title', 'msc-automation');
    $col_name[1] = __('Artist', 'msc-automation');
    $col_name[2] = __('Style', 'msc-automation');
    $col_name[3] = __('Hour', 'msc-automation');

    $_SESSION['col_name'] = $col_name;
    $_SESSION['NumRows'] = $NumRows;
    $_SESSION['dradi'] = $dradi;
    $_SESSION['image'] = $image;
    $upload_dir = wp_upload_dir();
    $_SESSION['upload_dir'] = $upload_dir;

    $doc_refresh = WP_SNIPPETS_DIR . 'list_radia.php';
    $name_div = 'list-radia';
    //if($refresh==false || $dradi<> null){$name_div = 'list-radia';}else{$name_div = 'refresh';}            
    if ($refresh == TRUE) {
        $name_div = 'refresh-radia';
        $doc_refresh_js = WP_SNIPPETS_URL . 'list_radia.php';
        ?>
        <div id="dom-source" style="display: none;"><?php echo $doc_refresh_js; ?></div>
        <div id="dom-div" style="display: none;"><?php echo '#' . $name_div; ?></div>
        <?php
        wp_enqueue_script('handle-list_radia', plugins_url('/../../jquery/refresh_list_radia.js', __FILE__, '', TRUE), array('jquery'), '1.0.0', true);

        $params = array(
            'nom_div' => '#' . $name_div,
            'time' => 15000,
            'source' => plugins_url('/../../jquery/refresh_list_radia.js', __FILE__, '', TRUE)
        );
        wp_localize_script('handle-list_radia', 'Params_refresh', $params);
    }
    ?>
    <div id="<?php echo $name_div; ?>">
    <?php include ($doc_refresh); ?>
    </div>

        <form action="<?php echo get_permalink() ?>" method=post>            
        <p><label for="dradi"><h4><?php _e('What song did it played at ...', 'msc-automation') ?></h4></label><br>                
            <input type="date" name="dradi" max="<?php echo date("Y-m-d"); ?>" value="<?php echo ($dradi == NULL) ? current_time('mysql', true) : $dradi; /* current_time( 'mysql', true ); */ ?>"></p>            
        <p align=center><input class="<?php echo $cssbutton; ?>" type=submit name=vot value="<?php _e('Search ...', 'msc-automation') ?>"></p><br>
    </form>
    <?php
}

add_shortcode('last_played', 'get_last_played');

function get_now_playing($attributes) {
    if (is_admin()) {
        return;
    }
    $image = (isset($attributes['image'])) ? $attributes['image'] : FALSE;
    $img_width = (isset($attributes['img_width'])) ? $attributes['img_width'] : 200;
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

    $_SESSION['image'] = $image;
    $upload_dir = wp_upload_dir();
    $_SESSION['upload_dir'] = $upload_dir;
    $_SESSION['img_width'] = $img_width;

    $name_div = 'refresh-radia';
    $doc_refresh = 'info_song.php';
    ?>
    <div id="dom-source" style="display: none;"><?php echo WP_SNIPPETS_URL . $doc_refresh; ?></div>
    <div id="dom-div" style="display: none;"><?php echo '#refresh'; ?></div>
    <?php
    $file_js = MSC_JQUERY_URL . 'refresh_now_playing.js';
    wp_enqueue_script('handle-now_playing', $file_js, array('jquery'), '1.0.0', true);
    $params = array(
        'nom_div' => '#refresh',
        'time' => 15000,
        'source' => $file_js
    );
    wp_localize_script('handle-list_radia', 'Params_refresh', $params);
    ?>        
    <div id="refresh">
    <?php include (WP_SNIPPETS_DIR . $doc_refresh); ?>
    </div>      

    <?php
}

add_shortcode('now_playing', 'get_now_playing');

function get_now_playing_widget($attributes) {
    if (is_admin()) {
        return;
    }
    $image = (isset($attributes['image_w'])) ? $attributes['image_w'] : FALSE;
    $img_width = (isset($attributes['img_width_w'])) ? $attributes['img_width_w'] : 200;
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
    $_SESSION['image_w'] = $image;
    $upload_dir = wp_upload_dir();
    $_SESSION['upload_dir'] = $upload_dir;
    $_SESSION['img_width_w'] = $img_width;


    $doc_refresh = 'info_song_widget.php';
    ?>
    <div id="dom-source" style="display: none;"><?php echo WP_SNIPPETS_URL . $doc_refresh; ?></div>
    <div id="dom-div" style="display: none;"><?php echo '#refresh-widget'; ?></div>
    <?php
    wp_enqueue_script('handle-now_playing_widget', plugins_url('/refresh_now_playing_widget.js', __FILE__, '', TRUE), array('jquery'), '1.0.0', true);
    $params = array(
        'nom_div' => '#refresh-widget',
        'time' => 15000,
        'source' => plugins_url('/jquery/refresh_now_playing_widget.js', __FILE__, '', TRUE)
    );
    wp_localize_script('handle-list_radia', 'Params_refresh', $params);
    ?>        
    <div id="refresh-widget">
    <?php include ( WP_SNIPPETS_DIR . $doc_refresh); ?>
    </div>      

    <?php
}

function get_public_vote_player($attributes) {
    if (is_admin()) {
        return;
    }
    $cssbutton = (isset($attributes['stylebutton'])) ? $attributes['stylebutton'] : "";
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
    if (!isset($_POST['GrupOpc'])) {
        //llista formulari
        $list = $MyRadio->QueryGetTable(seccions::MUSIC, sub_seccions::LISTOPCIONSWEB);
        if ($MyRadio->RESPOSTA_ROWS > 0) {
            $counter = 0;
            $url_form = get_permalink();
            ?>
            <form action="<?php echo $url_form; ?>" method=POST>
                <TABLE>
                    <TR><TH scope="col"><?php _e('Votes', 'msc-automation'); ?></TH>
                        <TH scope="col"><?php _e('Title', 'msc-automation'); ?></TH>
                        <TH scope="col"><?php _e('Artist', 'msc-automation'); ?></TH>
                        <TH scope="col"><?php _e('Select', 'msc-automation'); ?></TH></TR>
            <?php
            while ($counter < $MyRadio->RESPOSTA_ROWS):
                $interp = $list['track'][$counter]['INTERP'];
                $id = $list['track'][$counter]['ID'];
                $votes = $list['track'][$counter]['VOTES'];
                $titol = $list['track'][$counter]['TITLE'];
                ?>                   
                        <TR><TD><?php echo $votes; ?></TD>
                            <TD><?php echo $titol; ?></TD>
                            <TD><?php echo $interp; ?></TD>                            
                                <?php
                                if ($counter == 0) {
                                    ?><TD><input type=radio name='GrupOpc' value=<?php echo $id; ?> checked="checked"></TD></TR><?php
                                } else {
                                    ?><TD><input type=radio name='GrupOpc' value=<?php echo $id; ?>></TD></TR><?php
                }
                $counter++;
            endwhile;
            ?>
                </TABLE></BR>
                <p align=center><input class="<?php echo $cssbutton; ?>" type=submit name=vot value="<?php _e('Vote music to play', 'msc-automation'); ?>"></p><br>
            </form>
            <?php
        } else {
            _e('Wait a moment and try again later', 'msc-automation');
        }
    } else {
        //Ja s'ha votat, registrar el vot
        $temid = $_POST['GrupOpc'];
        $Vars[0] = 'id=' . $temid;
        $MyRadio->QueryGetTable(seccions::MUSIC, sub_seccions::VOTEOPCIONWEB, $Vars);
        ?>                        
        <h2><?php _e('Thanks for participating', 'msc-automation'); ?></h2>
        <?php
        //informació del tema votat.             
        include WP_SNIPPETS_DIR . 'detail_song.php';
        echo $strReturn;
    }
}

add_shortcode('public_vote_player', 'get_public_vote_player');

function get_search_music($attributes) {
    if (is_admin()) {
        return;
    }
    if (isset($_GET['HoraPrg'])) {
        //Programem la cançó            

        $HoraPrg = strftime("%Y-%m-%d %H:%M:%S", $_REQUEST['hp']);
        $temid = $_REQUEST['sid'];
        $strReturn = '<h2>' . _e('Scheduled song, thanks for participating', 'msc-automation') . '</h2>';
        include WP_SNIPPETS_DIR . 'detail_song.php';
        echo $strReturn;
        setcookie("bot", time(), time() + 3600, COOKIEPATH, COOKIE_DOMAIN); //espera una hora
    } else {
        $espera = 0;
        if (isset($_COOKIE["bot"])) {
            $hora = time(); //mktime(date("H"), date("i"), date("s"), date("m")  , date("d"), date("Y"));
            $expiration_date = strtotime($_COOKIE["bot"]);
            $Resta = $expiration_date - $hora;
            if ($Resta < 0) {
                // Espera't
                $espera = 1;
            }
        }
        if ($espera == 0) {
            //seguim endavant	            
            if (isset($_GET['opt'])) {
                // ensenyem info tema                    
                $temid = $_GET['opt'];
                include WP_SNIPPETS_DIR . 'detail_song.php';
                //Ensenyem formulari del tema seleccionat per escollir una hora a sonar   
                //canvi hora local                    
                $mydate_1 = strtotime(current_time('mysql')) + 5 * 60; //date_i18n( 'Y-m-d g:i:s',  strtotime( get_the_time( "'Y-m-d g:i:s'" ) ) );
                $mydate_2 = strtotime(current_time('mysql')) + 10 * 60;
                $mydate_3 = strtotime(current_time('mysql')) + 15 * 60;

                $strReturn .= "<P align=center><a class='capsule'>" . __('Select one hour', 'msc-automation') . "</a></P>";
                $strReturn .= '<FORM class="search-form" METHOD=POST ACTION="' . get_permalink() . '" >';
                $strReturn .= "<TABLE align=center>";
                $strReturn .= "<tr><TD><input type=radio name=hp value=$mydate_1>" . strftime("%H:%M", $mydate_1) . "</TD></tr>\n";
                $strReturn .= "<tr><TD><input type=radio name=hp value=$mydate_2>" . strftime("%H:%M", $mydate_2) . "</TD></tr>\n";
                $strReturn .= "<tr><TD><input type=radio name=hp value=$mydate_3>" . strftime("%H:%M", $mydate_3) . "</TD></tr>\n";
                $strReturn .= "<input type=hidden  name=sid value=" . $temid . "></table>";
                $strReturn .= "<p align=center><input type=submit value=" . __('Program', 'msc-automation') . "></p>";
                $strReturn .= "</form>";
                echo $strReturn;
            } else {
                $strsql = "";
                if (isset($_GET['q'])) {
                    $strsql = urldecode($_GET['q']);
                }
                $str_disc = (isset($_GET['disc'])) ? $_GET['disc'] : 0;
                if (strlen($strsql) > 0 or $str_disc > 0) {
                    // how many rows we have in database
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
                    //consulta a la API i construim formulari llistat.
                    $pageNum = get_query_var('page', 0);
                    if ($str_disc > 0) {
                        //Query per un disc concret                            
                        $Vars[0] = 'query=' . $str_disc;
                        $Vars[1] = 'offset=' . $pageNum;
                        $list = $MyRadio->QueryGetTable(seccions::MUSIC, sub_seccions::SEARCHALBUM, $Vars);
                    } else {
                        //Query general                            
                        $Vars[0] = 'query=' . urlencode($strsql);
                        $Vars[1] = 'offset=' . $pageNum;
                        $list = $MyRadio->QueryGetTable(seccions::MUSIC, sub_seccions::SEARCHSONG, $Vars);
                    }

                    $offset = ($pageNum - 1) * ROWS_PER_PAGE;
                    if ($MyRadio->RESPOSTA_ROWS > 0) {
                        // how many rows to show per page
                        // by default we show first page
                        // if $_GET['page'] defined, use it as page number                                                                  
                        if ($pageNum > 0) {
                            // counting the offset
                            $offset = ($pageNum - 1) * ROWS_PER_PAGE;
                        }

                        // how many pages we have when using paging?
                        $maxPage = ceil($MyRadio->RESPOSTA_ROWS / ROWS_PER_PAGE);
                        $rows_this_page = count($list['track']);
                        if ($maxPage > 1) {
                            // print the link to access each page                                
                            $self = get_bloginfo('url') . "?p=" . get_the_ID();
                            $nav = '';
                            $enc_strsql = urlencode($strsql);
                            for ($page = 1; $page <= $maxPage; $page++) {
                                if ($page == $pageNum) {
                                    $nav .= $page; // no need to create a link to current page	  
                                } else {
                                    $nav .= " <a href=" . $self . "&page=$page&strsql=$enc_strsql \">$page</a> ";
                                }
                            }
                            if ($pageNum > 1) {
                                $page = $pageNum - 1;
                                $prev = " <a href=\"$self&page=$page&strsql=$enc_strsql\"><i class='fas fa-angle-left' aria-hidden='true'></i></a> ";
                                $first = " <a href=\"$self&page=1&strsql=$enc_strsql\"><i class='fas fa-angle-double-left' aria-hidden='true'></i></a> ";
                            } else {
                                $prev = '&nbsp;'; // we're on page one, don't print previous link
                                $first = '&nbsp;'; // nor the first page link
                            }

                            if ($pageNum < $maxPage) {
                                $page = $pageNum + 1;
                                $next = " <a href=\"$self&page=$page&strsql=$enc_strsql\"><i class='fas fa-angle-right' aria-hidden='true'></i></i></a> ";
                                $last = " <a href=\"$self&page=$maxPage&strsql=$enc_strsql\"><i class='fas fa-angle-double-right' aria-hidden='true'></i></a> ";
                            } else {
                                $next = '&nbsp;'; // we're on the last page, don't print next link
                                $last = '&nbsp;'; // nor the last page link
                            }
                            // print the navigation link
                            $Navigator = "<p align=center>" . $first . $prev . $nav . $next . $last . "<p>";

                            $nIni = (($pageNum - 1) * ROWS_PER_PAGE) + 1;
                            $nFi = ($nIni + $rows_this_page) - 1;
                            $strReturn .= $nav = $Navigator;
                            $strReturn .= "<p align=right>resultats " . $nIni . " - " . $nFi . " d&rsquo;un total de " . $MyRadio->RESPOSTA_ROWS . "</p>";
                        }

                        $strReturn .= '<FORM class="search-form" METHOD=GET ACTION="' . get_permalink() . '" >
                                        <TABLE align=center>
                                        <TR><TH scope="col"></TH>
                                        <TH scope="col">' . __('Title', 'msc-automation') . '</TH>
                                        <TH scope="col">' . __('Artist', 'msc-automation') . '</TH></TR>';
                        $counter = 0;
                        while ($counter < $rows_this_page):
                            if ($MyRadio->RESPOSTA_ROWS == 1) {
                                $tema_titol = $list['track']['TITLE'];
                                $interp_nom = $list['track']['INTERP'];
                                $id = $list['track']['ID'];
                                $counter = 2;
                            } else {
                                $tema_titol = $list['track'][$counter]['TITLE'];
                                $interp_nom = $list['track'][$counter]['INTERP'];
                                $id = $list['track'][$counter]['ID'];
                            }
                            $strReturn .= "<TR>\n";
                            if ($counter == 0) {
                                $strReturn .= "<TD><input type=radio name=opt value=" . $id . " checked></TD>\n";
                            } else {
                                $strReturn .= "<TD><input type=radio name=opt value=" . $id . "></TD>\n";
                            }
                            $strReturn .= "<TD>" . htmlentities($tema_titol) . "</TD>\n";
                            $strReturn .= "<TD>" . htmlentities($interp_nom) . "</TD>\n";

                            $counter++;
                        endwhile;
                        $strReturn .= "</TABLE>\n";
                        $strReturn .= "<p align=center><input type=submit value=" . __('Program', 'msc-automation') . "></p>";
                        $strReturn .= "</form>";
                        echo $strReturn;
                    }
                    //Tornem a posar el formulari inputtex de la consulat
                    include WP_SNIPPETS_DIR . 'form_search_song.php';
                } else {
                    //formulari inputtex de la consulat
                    include WP_SNIPPETS_DIR . 'form_search_song.php';
                }
            }
        }
    }
}

add_shortcode('search_music', 'get_search_music');

function get_last_albums($attributes) {
    if (is_admin()) {
        return;
    }
    $NumRows = (isset($attributes['rows'])) ? $attributes['rows'] : 5;
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
    $Vars[0] = 'rows=' . $NumRows;
    $list = $MyRadio->QueryGetTable(seccions::MUSIC, sub_seccions::LASTALBUMS, $Vars);
    if ($MyRadio->RESPOSTA_ROWS > 0) {
        $counter = 0;
        $img_width = 150;
        $upload_dir = wp_upload_dir();
        $page_seach = '#';
        $args = array(
            'post_type' => 'page',
            'posts_per_page' => '10',
            'post_status' => 'publish',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => '_msc_hook_id',
                    'value' => 'search',
                    'compare' => '='
                )
            )
        );
        $query = new WP_Query($args);
        while ($query->have_posts()) {
            $query->the_post();
            $id = get_the_ID();
            $page = get_page($id);
            //$page_seach = $page->guid;
            $page_seach = get_permalink($id);

            break;
        }

        $strReturn .= '<TABLE align=center>';
        $loop_return = '';
        while ($counter < $MyRadio->RESPOSTA_ROWS):
            $id = $list['albums'][$counter]['ID'];
            $titol = $list['albums'][$counter]['NAME'];
            $interp = $list['albums'][$counter]['INTERP'];
            $style = $list['albums'][$counter]['STYLE'];
            $comment = $list['albums'][$counter]['COMMENT'];
            $link = $list['albums'][$counter]['LINK'];

            $PathToSaveImg = $upload_dir['basedir'] . '/' . TMP_IMG_DIR . '/disc_img-' . $id . '.jpg';
            $PathToShowImg = $upload_dir['baseurl'] . '/' . TMP_IMG_DIR . '/disc_img-' . $id . '.jpg';
            $have_image = file_exists($PathToSaveImg);
            if ($have_image == FALSE) {
                $have_image = getImage(base64_decode($list['albums'][$counter]['IMAGE']), $PathToSaveImg, $img_width);
            }

            $loop_return .= '<TR><th>';
            if ($have_image == TRUE) {
                if (strlen($link) > 1) {
                    $loop_return .= "<a href='" . $LINKS . "' target='_blank'>" . $link . ">";
                }
                $loop_return .= '<img src=' . $PathToShowImg . '>';
                if (strlen($link) > 1) {
                    $loop_return .= '</a>';
                }
            }
            $loop_return .= '</th><th><h3>' . htmlentities($interp) . '</h3>';
            $url = add_query_arg('disc', $id, $page_seach);
            $loop_return .= '<a href="' . $url . '"><h4>' . htmlentities($titol) . '</h4></a>';
            $loop_return .= '<p><i>' . htmlentities($comment) . '</i></p>';
            $strStyle = htmlentities($style);
            if (strlen($strStyle) > 0) {
                $loop_return .= '[' . htmlentities($style) . ']';
            }
            $loop_return .= '</th></TR>';

            $counter++;
        endwhile;

        $strReturn = $strReturn . $loop_return;
        $strReturn .= "</TABLE>";
        echo $strReturn;
    }
}

add_shortcode('last_albums', 'get_last_albums');

function get_detail_song($attributes) {
    if (is_admin()) {
        return;
    }
    if (isset($_GET['id'])) {
        $id_song = $_GET['id'];
    } else {
        $id_song = $attributes['id'];
    }
    if (!isset($attributes['img_width'])) {
        $img_width = 200;
    } else {
        $img_width = $attributes['img_width'];
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
    $Vars[0] = 'id=' . $id_song;
    $list = $MyRadio->QueryGetTable(seccions::MUSIC, sub_seccions::SHOWINFO, $Vars);
    if ($MyRadio->RESPOSTA_ROWS > 0) {
        if ($img_width == 0) {
            $img_exist = FALSE;
        } else {
            $upload_dir = wp_upload_dir();
            $PathToSaveImg = $upload_dir['basedir'] . '/' . TMP_IMG_DIR . '/disc_img-' . $list['track']['ID'] . '.jpg';
            $PathToShowImg = $upload_dir['baseurl'] . '/' . TMP_IMG_DIR . '/disc_img-' . $list['track']['ID'] . '.jpg';
            $img_exist = getImage(base64_decode($list['track']['IMAGE']), $PathToSaveImg, $img_width);
        }
        $interp = $list['track']['INTERP'];
        $title = $list['track']['TITLE'];
        $album = $list['track']['ALBUM'];
        global $post;
        $my_post = array(
            'ID' => $post->ID,
            'post_title' => $title . ' | ' . $album
        );

        // Update the post into the database
        wp_update_post($my_post);

        // hook to add Open Graph Namespace
        //add_filter( 'language_attributes', 'prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#"' );            
        wp_register_script('script_opengraph', MSC_JQUERY_URL . 'refresh_og.js', '1.0.0');
        $params_og = array('i' => $PathToShowImg, 't' => $title . ' | ' . $interp);
        wp_localize_script('script_opengraph', 'object_params', $params_og);
        wp_enqueue_script('script_opengraph');
        ?>                                            
        <div  style="padding:20px 20px 20px 20px">                    
        <?php
        if ($img_exist == TRUE) {
            echo '<img class="jp-image" src=' . $PathToShowImg . '>';
        }
        ?>
            <div class="jp_title"><span><?php echo $interp; ?></span></div>
            <div class="jp_subtitle-name"><?php echo $title; ?></div>
            <div><i><?php echo __('From album', 'msc-automation') . ': ' . $album; ?></i></div>                
            <div><?php _e('On', 'msc-automation'); ?> <b><a href="<?php echo get_home_url(); ?>" title="<?php echo get_bloginfo('description'); ?>" target="_blank"><?php echo get_bloginfo('name'); ?></a></b></div>
        </div>
        <?php
    }
}

add_shortcode('detail_song', 'get_detail_song');
