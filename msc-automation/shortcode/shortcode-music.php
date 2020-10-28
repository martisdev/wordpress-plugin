<?php

function mscra_get_last_played($attributes) {
    if (is_admin()) {
        return;
    }
    $refresh = (isset($attributes['refresh'])) ? $attributes['refresh'] : FALSE;
    $image = (isset($attributes['image'])) ? $attributes['image'] : FALSE;
    $NumRows = (isset($attributes['rows'])) ? $attributes['rows'] : 10;
    $cssbutton = (isset($attributes['stylebutton'])) ? $attributes['stylebutton'] : "";    

    $dradi = (isset($_GET['dradi']))? sanitize_text_field($_GET['dradi']):NULL;
    
    include MSCRA_PLUGIN_DIR.'connect_api.php';

    $col_name[0] = __('Title', 'mscra-automation');
    $col_name[1] = __('Artist', 'mscra-automation');
    $col_name[2] = __('Style', 'mscra-automation');
    $col_name[3] = __('Hour', 'mscra-automation');

    $_SESSION['col_name'] = $col_name;
    $_SESSION['NumRows'] = $NumRows;
    $_SESSION['dradi'] = $dradi;
    $_SESSION['image'] = $image;
    $upload_dir = wp_upload_dir();
    $_SESSION['upload_dir'] = $upload_dir;

    $doc_refresh = MSCRA_WP_SNIPPETS_DIR . 'list_radia.php';
    $name_div = 'list-radia';
    //if($refresh==false || $dradi<> null){$name_div = 'list-radia';}else{$name_div = 'refresh';}            
    if ($refresh == TRUE) {
        $name_div = 'refresh-radia';
        $doc_refresh_js = MSCRA_WP_SNIPPETS_URL . 'list_radia.php';
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
        <p><label for="dradi"><h4><?php _e('What a song played at...', 'mscra-automation') ?></h4></label><br>                
            <input type="date" name="dradi" max="<?php echo date("Y-m-d"); ?>" value="<?php echo ($dradi == NULL) ? current_time('mysql', true) : $dradi; /* current_time( 'mysql', true ); */ ?>"></p>            
        <p align=center><input class="<?php echo $cssbutton; ?>" type=submit name=vot value="<?php _e('Search...', 'mscra-automation') ?>"></p><br>
    </form>
    <?php
}

add_shortcode('mscra_last_played', 'mscra_get_last_played');

function mscra_get_now_playing($attributes) {
    if (is_admin()) {
        return;
    }
    $image = (isset($attributes['image'])) ? $attributes['image'] : FALSE;
    $img_width = (isset($attributes['img_width'])) ? $attributes['img_width'] : 200;
    
    include MSCRA_PLUGIN_DIR.'connect_api.php';

    $_SESSION['image'] = $image;
    $upload_dir = wp_upload_dir();
    $_SESSION['upload_dir'] = $upload_dir;
    $_SESSION['img_width'] = $img_width;

    $name_div = 'refresh-radia';
    $doc_refresh = 'info_song.php';
    ?>
    <div id="dom-source" style="display: none;"><?php echo MSCRA_WP_SNIPPETS_URL . $doc_refresh; ?></div>
    <div id="dom-div" style="display: none;"><?php echo '#refresh'; ?></div>
    <?php
    $file_js = MSCRA_JQUERY_URL . 'refresh_now_playing.js';
    wp_enqueue_script('handle-now_playing', $file_js, array('jquery'), '1.0.0', true);
    $params = array(
        'nom_div' => '#refresh',
        'time' => 15000,
        'source' => $file_js
    );
    wp_localize_script('handle-list_radia', 'Params_refresh', $params);
    ?>        
    <div id="refresh">
        <?php include (MSCRA_WP_SNIPPETS_DIR . $doc_refresh); ?>
    </div>      

    <?php
}

add_shortcode('mscra_now_playing', 'mscra_get_now_playing');

function mscra_get_public_vote_player($attributes) {
    if (is_admin()) {
        return;
    }
    $cssbutton = (isset($attributes['stylebutton'])) ? $attributes['stylebutton'] : "";
    
    include MSCRA_PLUGIN_DIR.'connect_api.php';
    $temid = (isset($_POST['GrupOpc']))? sanitize_text_field($_POST['GrupOpc']):0;
    if ($temid==0) {
        //llista formulari
        $list = $MyRadio->QueryGetTable(seccions::MUSIC, sub_seccions::LISTOPCIONSWEB);
        if ($MyRadio->RESPOSTA_ROWS > 0) {
            $counter = 0;
            $url_form = get_permalink();
            ?>
            <form action="<?php echo $url_form; ?>" method=POST>
                <TABLE>
                    <TR><TH scope="col"><?php _e('Votes', 'mscra-automation'); ?></TH>
                        <TH scope="col"><?php _e('Title', 'mscra-automation'); ?></TH>
                        <TH scope="col"><?php _e('Artist', 'mscra-automation'); ?></TH>
                        <TH scope="col"><?php _e('Select', 'mscra-automation'); ?></TH></TR>
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
                <p align=center><input class="<?php echo $cssbutton; ?>" type=submit name=vot value="<?php _e('Vote music to play', 'mscra-automation'); ?>"></p><br>
            </form>
            <?php
        } else {
            _e('Wait a moment and try again later.', 'mscra-automation');
        }
    } else {
        //Ja s'ha votat, registrar el vot        
        $Vars[0] = 'id=' . $temid;
        $MyRadio->QueryGetTable(seccions::MUSIC, sub_seccions::VOTEOPCIONWEB, $Vars);
        ?>                        
        <h2><?php _e('Thanks for participating', 'mscra-automation'); ?></h2>
        <?php
        //informació del tema votat.             
        include MSCRA_WP_SNIPPETS_DIR . 'detail_song.php';
        echo $strReturn;
    }
}

add_shortcode('mscra_public_vote_player', 'mscra_get_public_vote_player');

function mscra_get_search_music($attributes) {
    if (is_admin()) {
        return;
    }    
    $hp = get_query_var('hp',0);
    if (($hp!=0)){         
        //register the cookie 'mscra_date_vote' in msc-automation.php        
        //Programem la cançó            
        $HoraPrg = strftime("%Y-%m-%d %H:%M:%S", $hp);        
        $temid = (isset($_GET['sid']))? sanitize_text_field($_GET['sid']):0;
        $strReturn = '<h2>' . _e('Scheduled song, thanks for participating', 'mscra-automation') . '</h2>';
        include MSCRA_WP_SNIPPETS_DIR . 'detail_song.php';
        return $strReturn;
        
    } else {
        $strReturn='';
        $espera = 0;
        if (isset($_COOKIE["mscra_date_vote"])) {
            $hora = time(); //mktime(date("H"), date("i"), date("s"), date("m")  , date("d"), date("Y"));
            $expiration_date = strtotime($_COOKIE["mscra_date_vote"]);
            $Resta = $expiration_date - $hora;
            if ($Resta < 0) {
                // Espera't
                $espera = 1;
            }
        }
        if ($espera == 0) {
            //seguim endavant	
            $temid =  (isset($_GET['opt']))? sanitize_text_field($_GET['opt']):0;
            if ($temid != 0) {
                // ensenyem info tema                                    
                include MSCRA_WP_SNIPPETS_DIR . 'detail_song.php';
                //Ensenyem formulari del tema seleccionat per escollir una hora a sonar   
                //canvi hora local                    
                $mydate_1 = strtotime(current_time('mysql')) + 5 * 60; //date_i18n( 'Y-m-d g:i:s',  strtotime( get_the_time( "'Y-m-d g:i:s'" ) ) );
                $mydate_2 = strtotime(current_time('mysql')) + 10 * 60;
                $mydate_3 = strtotime(current_time('mysql')) + 15 * 60;

                $strReturn .= "<P align=center><a class='capsule'>" . __('Select one hour', 'mscra-automation') . "</a></P>";
                $strReturn .= '<FORM class="search-form" METHOD=POST ACTION="' . get_permalink() . '" >';
                $strReturn .= "<TABLE align=center>";
                $strReturn .= "<tr><TD><input type=radio name=hp value=$mydate_1>" . strftime("%H:%M", $mydate_1) . "</TD></tr>\n";
                $strReturn .= "<tr><TD><input type=radio name=hp value=$mydate_2>" . strftime("%H:%M", $mydate_2) . "</TD></tr>\n";
                $strReturn .= "<tr><TD><input type=radio name=hp value=$mydate_3>" . strftime("%H:%M", $mydate_3) . "</TD></tr>\n";
                $strReturn .= "<input type=hidden  name=sid value=" . $temid . "></table>";
                $strReturn .= "<p align=center><input type=submit value=" . __('Send', 'mscra-automation') . "></p>";
                $strReturn .= "</form>";
                return $strReturn;
            } else {                
                $strsql = (isset($_GET['q']))? sanitize_text_field($_GET['q']):'';
                $str_disc = (isset($_GET['disc']))? sanitize_text_field($_GET['disc']):0;
                if (strlen($strsql) > 0 or $str_disc > 0) {
                    $enc_strsql = urldecode($strsql);                    
                    include MSCRA_PLUGIN_DIR.'connect_api.php';                                        
                    $pageNum = (isset($_GET['o']))? sanitize_text_field($_GET['o']):0;                  
                    if ($str_disc > 0) {
                        //Query per un disc concret                            
                        $Vars[0] = 'query=' . $str_disc;                                                
                        $Vars[1] = 'offset=' . $pageNum;
                        $list = $MyRadio->QueryGetTable(seccions::MUSIC, sub_seccions::SEARCHALBUM, $Vars);
                    } else {
                        //Query general                            
                        $Vars[0] = 'query=' . $enc_strsql;                                                
                        $Vars[1] = 'offset=' . $pageNum;
                        $list = $MyRadio->QueryGetTable(seccions::MUSIC, sub_seccions::SEARCHSONG, $Vars);
                    }                    
                    
                    $offset = ($pageNum - 1) * WP_MSCRA_ROWS_PER_PAGE;
                    if ($MyRadio->RESPOSTA_ROWS > 0) {
                        // how many rows to show per page
                        // by default we show first page
                        // if $_GET['page'] defined, use it as page number                                                                  
                        if ($pageNum > 0) {
                            // counting the offset
                            $offset = ($pageNum - 1) * WP_MSCRA_ROWS_PER_PAGE;
                        }

                        // how many pages we have when using paging?
                        $maxPage = ceil($MyRadio->RESPOSTA_ROWS / WP_MSCRA_ROWS_PER_PAGE);
                        if (is_array($list['track'])){
                            $rows_this_page = count($list['track']);
                        }else{
                            $rows_this_page = 0;
                        }                                                
                        if ($maxPage > 1) {
                            // print the link to access each page                                
                            $self = get_permalink().'?';
                            $nav = '';                            
                            for ($page = 1; $page <= $maxPage; $page++) {
                                if ($page == $pageNum) {
                                    $nav .= $page; // no need to create a link to current page	  
                                } else {
                                    $params = array('q' => $enc_strsql, 'o' => $page);
                                    $url = add_query_arg($params);
                                    $nav .= "<a href=".$url.">".$page."</a>".' | ';
                                }
                            }
                            if ($pageNum > 1) {
                                $page = $pageNum - 1;                                
                                $params = array('q' => $enc_strsql, 'o' => $page);
                                $url = add_query_arg($params);
                                $prev = '<a href="'.$url.'"><i class="fas fa-angle-left" aria-hidden="true"></i></a>'.' | ';
                                $params = array('q' => $enc_strsql, 'o' => '1');
                                $url = add_query_arg($params);
                                $first = '<a href="'.$url.'"><i class="fas fa-angle-double-left" aria-hidden="true"></i></i></a>'.' | ';                                
                            } else {
                                $prev = '&nbsp;'; // we're on page one, don't print previous link
                                $first = '&nbsp;'; // nor the first page link
                            }

                            if ($pageNum < $maxPage) {
                                $page = $pageNum + 1;
                                $params = array('q' => $enc_strsql, 'o' => $page);
                                $url = add_query_arg($params);
                                $next = '<a href="'.$url.'"><i class="fas fa-angle-right" aria-hidden="true"></i></i></a>'.' | ';
                                $params = array('q' => $enc_strsql, 'o' => $maxPage);
                                $url = add_query_arg($params);
                                $last = '<a href="'.$url.'"><i class="fas fa-angle-double-right" aria-hidden="true"></i></i></a>'.' | ';
                                
                            } else {
                                $next = '&nbsp;'; // we're on the last page, don't print next link
                                $last = '&nbsp;'; // nor the last page link
                            }
                            // print the navigation link
                            $Navigator = "<p align=center>" . $first . $prev . $nav . $next . $last . "<p>";

                            $nIni = (($pageNum ) * WP_MSCRA_ROWS_PER_PAGE) + 1;                            
                            $nFi = ($nIni + $rows_this_page) - 1;
                            $strReturn .= $nav = $Navigator;
                            $strReturn .= "<p align=right>". $nIni . " - " . $nFi . " ,Total results: " . $MyRadio->RESPOSTA_ROWS . "</p>";
                        }

                        $strReturn .= '<FORM class="search-form" METHOD=GET ACTION="' . get_permalink() . '" >
                                        <TABLE align=center>
                                        <TR><TH scope="col"></TH>
                                        <TH scope="col">' . __('Title', 'mscra-automation') . '</TH>
                                        <TH scope="col">' . __('Artist', 'mscra-automation') . '</TH></TR>';
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
                        $strReturn .= "<p align=center><input type=submit value=" . __('Send', 'mscra-automation') . "></p>";
                        $strReturn .= "</form>";                        
                    }                    
                    //Tornem a posar el formulari inputtex de la consulat                    
                    include MSCRA_WP_SNIPPETS_DIR . 'form_search_song.php';                    
                    return $strReturn;
                } else {
                    //formulari inputtex de la consulat
                    $strReturn = '';
                    include MSCRA_WP_SNIPPETS_DIR . 'form_search_song.php';
                    return $strReturn;
                }
            }
        }
    }
}

add_shortcode('mscra_search_music', 'mscra_get_search_music');

function mscra_get_last_albums($attributes) {
    if (is_admin()) {
        return;
    }
    $NumRows = (isset($attributes['rows'])) ? $attributes['rows'] : 5;
    include MSCRA_PLUGIN_DIR.'connect_api.php';
    $Vars[0] = 'rows=' . $NumRows;
    $list = $MyRadio->QueryGetTable(seccions::MUSIC, sub_seccions::LASTALBUMS, $Vars);
    if ($MyRadio->RESPOSTA_ROWS > 0) {
        $counter = 0;
        $img_width = 150;
        $upload_dir = wp_upload_dir();        
       //$page_seach= '#';
        $page_seach = mscra_get_page_by_meta(MSCRA_HOOK_SEARCH);        
        $strReturn = '<TABLE align=center>';
        $loop_return = '';
        while ($counter < $MyRadio->RESPOSTA_ROWS):
            $id = $list['albums'][$counter]['ID'];
            $titol = $list['albums'][$counter]['NAME'];
            $interp = $list['albums'][$counter]['INTERP'];
            $style = $list['albums'][$counter]['STYLE'];
            $comment = $list['albums'][$counter]['COMMENT'];
            $link = $list['albums'][$counter]['LINK'];

            $PathToSaveImg = $upload_dir['basedir'] . '/' . WP_MSCRA_TMP_IMG_DIR . '/disc_img-' . $id . '.jpg';
            $PathToShowImg = $upload_dir['baseurl'] . '/' . WP_MSCRA_TMP_IMG_DIR . '/disc_img-' . $id . '.jpg';
            $have_image = file_exists($PathToSaveImg);
            if ($have_image == FALSE) {
                $have_image = mscra_getImage(base64_decode($list['albums'][$counter]['IMAGE']), $PathToSaveImg, $img_width);
            }

            $loop_return .= '<TR><th>';
            if ($have_image == TRUE) {
                if (strlen($link) > 1) {
                    $loop_return .= "<a href='" . $link . "' target='_blank'>";
                }
                $loop_return .= '<img src=' . $PathToShowImg . '>';
                if (strlen($link) > 1) {
                    $loop_return .= '</a>';
                }
            }
            $loop_return .= '</th><th><h3>' . htmlentities($interp) . '</h3>';
            $url = add_query_arg('disc', $id, $page_seach->guid);
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

add_shortcode('mscra_last_albums', 'mscra_get_last_albums');

function mscra_get_detail_song($attributes) {
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

    include MSCRA_PLUGIN_DIR.'connect_api.php';
    
    $Vars[0] = 'id=' . $id_song;
    $list = $MyRadio->QueryGetTable(seccions::MUSIC, sub_seccions::SHOWINFO, $Vars);
    if ($MyRadio->RESPOSTA_ROWS > 0) {
        if ($img_width == 0) {
            $img_exist = FALSE;
        } else {
            $upload_dir = wp_upload_dir();
            $PathToSaveImg = $upload_dir['basedir'] . '/' . WP_MSCRA_TMP_IMG_DIR . '/disc_img-' . $list['track']['ID'] . '.jpg';
            $PathToShowImg = $upload_dir['baseurl'] . '/' . WP_MSCRA_TMP_IMG_DIR . '/disc_img-' . $list['track']['ID'] . '.jpg';
            $img_exist = mscra_getImage(base64_decode($list['track']['IMAGE']), $PathToSaveImg, $img_width);
        }
        $interp = $list['track']['INTERP'];
        $title = $list['track']['TITLE'];
        $album = $list['track']['ALBUM'];
        /* global $post;
          $my_post = array(
          'ID' => $post->ID,
          'post_title' => $title . ' | ' . $album
          );

          // Update the post into the database
          wp_update_post($my_post); */
        $post_title = $title . ' | ' . $album;
        echo "<script> document.title =" . $post_title . " ; </script>";

        // hook to add Open Graph Namespace
        //add_filter( 'language_attributes', 'prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#"' );            
        wp_register_script('script_opengraph', MSCRA_JQUERY_URL . 'refresh_og.js', '1.0.0');
        $params_og = array('i' => $PathToShowImg, 't' => $title . ' | ' . $interp);
        wp_localize_script('script_opengraph', 'object_params', $params_og);
        wp_enqueue_script('script_opengraph');
        ?>                                            
        <div class="detail_track_top"> 
            <?php
            if ($img_exist == TRUE) {
                echo '<img class="jp-image" src=' . $PathToShowImg . '>';
            }
            ?>
            <div class="jp_title"><span><?php echo $interp; ?></span></div>
            <div class="jp_subtitle-name"><?php echo $title; ?></div>
            <div><i><?php echo __('From album', 'mscra-automation') . ': ' . $album; ?></i></div>                
            <div><?php _e('On', 'mscra-automation'); ?> <b><a href="<?php echo get_home_url(); ?>" title="<?php echo get_bloginfo('description'); ?>" target="_blank"><?php echo get_bloginfo('name'); ?></a></b></div>
        </div>
        <?php
    }
}

add_shortcode('mscra_detail_song', 'mscra_get_detail_song');
