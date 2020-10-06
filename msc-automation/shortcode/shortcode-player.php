<?php

function msc_load_scripts() {

    $def_image = get_site_icon_url('120');
    $base_URL_Share = get_home_url(0, NAME_PAGE_TRACK . '/');
    $PathToSaveImg = DIR_TEMP_IMAGE . '/' . $img_mame;
    $PathToShowImg = URL_TEMP_IMAGE . '/' . $img_mame;
    $base_url_download = MSC_PLUGIN_URL . 'inc/download.php?fileurl=';
    $upload_dir = wp_upload_dir();
    $url_podcast = $upload_dir['baseurl'] . '/' . PODCAST_DIR;

    $msc_data = array(
        'share_url' => $base_URL_Share,
        'def_image' => $def_image,
        'path' => MSC_PLUGIN_URL,
        'key' => get_option('msc_client_key'),
        'img_dir' => DIR_TEMP_IMAGE,
        'img_url' => URL_TEMP_IMAGE,
        'jamendo_url' => URL_JAMENDO_TRACK,
        'download_url' => $base_url_download,
        'url_podcast' => $url_podcast
    );
    wp_enqueue_script('msc-data-js', MSC_JQUERY_URL . 'refresh_player.js', '', '1.0.0', false);
    wp_localize_script('msc-data-js', 'msc_data', $msc_data);
}

add_action('wp_enqueue_scripts', 'msc_load_scripts');

function get_player() {
    if (is_admin()) {
        return;
    }
    $name_template = get_page_template_slug($post->ID);
    if ($name_template == NAME_TEMPLATE_IFRAME) {
        return;
    }

    wp_enqueue_script('script_play_js1', MSC_JQUERY_URL . 'jplayer/jquery.min.js', array(), '1.0.0');
    wp_enqueue_script('script_play_js2', MSC_JQUERY_URL . 'jplayer/jquery.jplayer.min.js', array(), '1.0.0');
    wp_enqueue_script('script_player_js', MSC_JQUERY_URL . 'jplayer/msc.player.js', array(), '1.0.0');

    $name_container_player = '';
    if (get_option('msc_player') == 'head') {
        $name_container_player = 'dvPlayerTop';
        wp_enqueue_style('style_msc_player', MSC_CSS_URL . 'head.css', array(), '1.0.0');
        if (is_admin_bar_showing()) {
            ?><style type="text/css"> .dvPlayerTop { top: 28px; }</style><?php
        }
    } else {
        $name_container_player = 'dvPlayerBottom';
        wp_enqueue_style('style_msc_player', MSC_CSS_URL . 'footer.css', array(), '1.0.0');
    }
    /* Consulta a la dbs */
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

    $list = $MyRadio->QueryGetTable(seccions::CALENDAR, sub_seccions::NOWPLAYING);

    $img_width = '100';
    if ($MyRadio->RESPOSTA_ROWS > 0) {
        $counter = 0;
        $id = $list['item']['ID'];
        $title = $list['item']['NAME'];
        $subtitle = $list['item']['DESCRIP'];
        $time_end = $list['item']['TIME_END'];

        $type = $list['item']['TYPE'];
        switch ($type) {
            case TIP_AUTOMATIC_LLISTA:
                $img_mame = 'disc_img-' . $id . '.jpg';
                if (strlen($list['item']['LINK']) > 0) {
                    $URL_Download = URL_JAMENDO_TRACK . $list['item']['LINK'];
                } else {
                    $URL_Download = '';
                }
                break;
            case TIP_AUTOMATIC_RADIOFORMULA:
                $img_mame = 'disc_img-' . $id . '.jpg';
                if (strlen($list['item']['LINK']) > 0) {
                    $URL_Download = URL_JAMENDO_TRACK . $list['item']['LINK'];
                } else {
                    $URL_Download = '';
                }
                break;
            case TIP_AUTOMATIC_PROGRAMA:
                $img_mame = 'prg_img-' . $id . '.jpg';
                //todo: download prg
                $urlmp3 = $url_podcast . '/' . $list['item']['LINK'];
                $URL_Download = MSC_PLUGIN_URL . 'inc/download.php?fileurl=' . $urlmp3 . '&filename=' . urlencode($title);
                break;
            case TIP_DIRECTE_:
                $img_mame = 'prg_img-' . $id . '.jpg';
                $URL_Download = '';
                break;
            case TIP_CONEX_CENTRAL_:
                $img_mame = 'radio_img.jpg';
                $URL_Download = '';
                break;
        }

        if (strlen($URL_Download) == 0) {
            $dwn_display = 'none';
        } else {
            $dwn_display = 'inline';
        }
        $def_image = get_site_icon_url('120');
        $PathToSaveImg = DIR_TEMP_IMAGE . '/' . $img_mame;
        $PathToShowImg = URL_TEMP_IMAGE . '/' . $img_mame;
        $base_URL_Share = get_home_url(0, NAME_PAGE_TRACK . '/') . '?id=';
        $base_url_download = MSC_PLUGIN_URL . 'inc/download.php?fileurl=';

        //$url_podcast = wp_get_upload_dir('baseurl').'/'.PODCAST_DIR; 
        $upload_dir = wp_upload_dir();
        $url_podcast = $upload_dir['baseurl'] . '/' . PODCAST_DIR;
        //$base_url_jamendo = URL_JAMENDO_TRACK;   

        if (!file_exists($PathToSaveImg)) {
            if (getImage(base64_decode($list['item']['IMAGE']), $PathToSaveImg, $img_width) == false) {
                //canvia a imatge per defecte                              
                $PathToShowImg = $def_image;
            }
        }
        $decrip = $title . ' - ' . $subtitle;
        $URL_Share = $base_URL_Share . $list['item']['ID'] . '&type=' . $type;
        $URL_Facebook = 'https://www.facebook.com/sharer/sharer.php?u=' . $URL_Share . '&t=' . $decrip;
        $URL_Twitter = 'https://twitter.com/share?url=' . $URL_Share . '&via=TWITTER_HANDLE&text=' . $decrip;
        $URL_Pinterest = 'https://pinterest.com/pin/create/button/?&url=' . $URL_Share . '&description=' . $decrip . '&media=' . $urlmp3;
        $URL_Linked_in = 'https://www.linkedin.com/shareArticle?mini=true&url=' . $URL_Share . '&title=' . $decrip;
        $URL_WhatsApp = 'https://wa.me/?text=' . $decrip . '+-+' . $URL_Share;
        $URL_Iframe = '<iframe src="' . $URL_Share . '" allowfullscreen scrolling="no" frameborder="0" width="270px" height="370px"></iframe>';
    }
    ?>    
    <div class="<?php echo $name_container_player; ?>" style="background-color:<?php echo get_option('msc_color'); ?>">                                                    
        <div id="msc-left"></div>            
        <div id="msc-middle">
            <div id="jquery_jplayer"></div> 
            <div id="jp_container">                 
                <div class="jp-controls"> 
                    <div id="msc-box-l">                                    
                        <div class="jp-time-mute">
                            <span class="jp-current-time"></span><span class="slash">/</span><span class="jp-duration"></span>                             
                            <i class="jp-mute fas fa-volume-mute"></i>
                            <i class="jp-unmute fas fa-volume-up"></i>  
                            <a data-pos="0" class="jp-stream track track-default fas fa-broadcast-tower fa-sm" data-href="<?php echo $MyRadio->URLStreaming; ?>" href="#" style="display:none;" onclick="playThisFile(this)"></a>
                        </div>                                                    

                        <i class="jp-play fa fa-play-circle fa-4x" style="display:none;"></i>                                          
                        <i class="jp-pause fa fa-pause-circle fa-4x"></i>                               
                    </div>
                    <div id="msc-box-r">                                    
                        <div id="jp-image">
                            <img id="jp-image-src" src="<?php echo $PathToShowImg; ?>">                                
                        </div> 
                        <div class="jp-progress">
                            <div class="jp-seek-bar">
                                <div class="jp-play-bar">
                                    <div class="jp-ball"></div>
                                </div>
                            </div>
                        </div>
                        <div id="jp-info">
                            <div><span id="jp_title"><?php echo $title; ?></span></div>
                            <i id="jp_subtitle-name"><?php echo $subtitle; ?></i>   
                            <div id="jp-socialbuttons" >
                                <a id="like" class="fas fa-heart" aria-hidden="true" href="javascript:void" onclick="LikeTrack()"></a>
                                <a id="download" class="fas fa-download" aria-hidden="true" href="<?php echo $URL_Download; ?>" target="_blank" style="display:<?php echo $dwn_display; ?>"></a>
                                <a id="BtnShare" class="fas fa-share-alt" aria-hidden="true" onclick="ShowModalshare()" href="javascript:void"></a>                                            
                                <!-- Modal Share -->
                                <div id="myModalShare" class="modalShare">
                                    <!-- Modal content -->
                                    <div class="modal-content_share">
                                        <span class="closeShare"><i class="fas fa-times fa-2x"></i></span>

                                        <a id="fb" class="fab fa-facebook-square fa-2x" href="<?php echo $URL_Facebook; ?>"
                                           onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');
                                                       return false;"
                                           target="_blank" title="<?php _e('Share on Facebook', 'msc-automation'); ?>">
                                        </a>                                                
                                        <a id="tw" class="fab fa-twitter-square fa-2x" href="<?php echo $URL_Twitter; ?>"
                                           onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');
                                                       return false;"
                                           target="_blank" title="<?php _e('Share on WhatsApp', 'msc-automation'); ?>">
                                        </a>                                            
                                        <a id="pt" class="fab fa-pinterest-square fa-2x" href="<?php echo $URL_Pinterest; ?>"
                                           onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;"
                                           target="_blank" title="<?php _e('Share on Pinterest', 'msc-automation'); ?>">
                                        </a>                                            
                                        <a id="li" class="fab fa-linkedin fa-2x" href="<?php echo $URL_Linked_in; ?>"
                                           onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');
                                                       return false;"
                                           target="_blank" title="<?php _e('Share on LinkedIn', 'msc-automation'); ?>">
                                        </a>                                            
                                        <a id="wa" class="fab fa-whatsapp-square fa-2x" href="<?php echo $URL_WhatsApp; ?>"
                                           onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');
                                                       return false;"
                                           target="_blank" title="<?php _e('Share on LinkedIn', 'msc-automation'); ?>">
                                        </a>
                                        <a class="fas fa-code fa-2x" onclick="ShowIframeCode()" title="<?php _e('Share on your web', 'msc-automation'); ?>" href="javascript:void"></a>
                                        <div id="iframe" style="display:none">
                                            <textarea type="text"  id="ifr"><?php echo $URL_Iframe; ?></textarea>
                                            <i><?php _e('Copy this code for add in your web', 'msc-automation'); ?></i>
                                        </div>
                                    </div>
                                </div><!--END  Modal Share -->
                            </div>
                        </div>

                    </div>                                                                                             
                    <div id="msc-hide">                                                
                        <i id="refresh">1</i>
                        <i id="ID"><?php echo $id; ?></i>
                        <i id="IDTYPE"><?php echo $type; ?></i>
                        <i id="URL_Share"><?php echo $base_URL_Share; ?></i>
                        <i id="def_image"><?php echo $def_image; ?></i>
                    </div>  
                    <div class="jp-no-solution">
                        <span>Update Required</span>
                        To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
                    </div>  
                </div>
            </div>        
        </div>
        <?php
    }

    add_shortcode('player_streaming', 'get_player');

    function get_iframe_player() {
        if (is_admin()) {
            return;
        }
        wp_enqueue_script('script_play_js1', MSC_JQUERY_URL . 'jplayer/jquery.min.js', array(), '1.0.0');
        wp_enqueue_script('script_play_js2', MSC_JQUERY_URL . 'jplayer/jquery.jplayer.min.js', array(), '1.0.0');
        wp_enqueue_script('script_player_js', MSC_JQUERY_URL . 'jplayer/msc.player.js', array(), '1.0.0');

        /* Consulta a la dbs */
        $key = get_option('msc_client_key');
        global $MyRadio;
        if (!isset($MyRadio)) {
            $MyRadio = new my_radio($key, get_locale(), get_option('msc_debug'));
            if ($MyRadio->RESPOSTA_STATUS !== SUCCES) {
                if ($MyRadio->IS_DEGUG == true) {
                    $msg = 'STATUS: ' . $MyRadio->RESPOSTA_STATUS . ' CODE: ' . $MyRadio->RESPOSTA_CODE . ' MSG: ' . $MyRadio->RESPOSTA_MESSAGE;
                    show_msc_message($msg, message_type::DANGER);
                    return;
                }
            }
        }
        $list = $MyRadio->QueryGetTable(seccions::CALENDAR, sub_seccions::NOWPLAYING);

        $img_width = '100';
        if ($MyRadio->RESPOSTA_ROWS > 0) {
            $counter = 0;
            $id = $list['item']['ID'];
            $title = utf8_encode($list['item']['NAME']);
            $subtitle = utf8_encode($list['item']['DESCRIP']);
            $time_end = $list['item']['TIME_END'];

            $type = $list['item']['TYPE'];
            switch ($type) {
                case TIP_AUTOMATIC_LLISTA:
                    $img_mame = 'disc_img-' . $id . '.jpg';
                    if (strlen($list['item']['LINK']) > 0) {
                        $URL_Download = URL_JAMENDO_TRACK . $list['item']['LINK'];
                    } else {
                        $URL_Download = '';
                    }
                    break;
                case TIP_AUTOMATIC_RADIOFORMULA:
                    $img_mame = 'disc_img-' . $id . '.jpg';
                    if (strlen($list['item']['LINK']) > 0) {
                        $URL_Download = URL_JAMENDO_TRACK . $list['item']['LINK'];
                    } else {
                        $URL_Download = '';
                    }
                    break;
                case TIP_AUTOMATIC_PROGRAMA:
                    $img_mame = 'prg_img-' . $id . '.jpg';
                    //todo: download prg
                    $urlmp3 = $url_podcast . '/' . $list['item']['LINK'];
                    $URL_Download = MSC_PLUGIN_URL . 'inc/download.php?fileurl=' . $urlmp3 . '&filename=' . urlencode($title);
                    break;
                case TIP_DIRECTE_:
                    $img_mame = 'prg_img-' . $id . '.jpg';
                    $URL_Download = '';
                    break;
                case TIP_CONEX_CENTRAL_:
                    $img_mame = 'radio_img.jpg';
                    $URL_Download = '';
                    break;
            }
            if (strlen($URL_Download) == 0) {
                $dwn_display = 'none';
            } else {
                $dwn_display = 'inline';
            }

            $PathToSaveImg = DIR_TEMP_IMAGE . '/' . $img_mame;
            $PathToShowImg = URL_TEMP_IMAGE . '/' . $img_mame;
            $base_URL_Share = get_home_url(0, NAME_PAGE_TRACK . '/') . '?id=';
            $base_url_download = MSC_PLUGIN_URL . 'inc/download.php?fileurl=';
            $url_podcast = $upload_dir['baseurl'] . '/' . PODCAST_DIR;

            $base_url_jamendo = URL_JAMENDO_TRACK;
            if (!file_exists($PathToSaveImg)) {
                if (getImage(base64_decode($list['item']['IMAGE']), $PathToSaveImg, $img_width) == TRUE) {
                    //canvia a imatge per defecte                            
                }
            }
            $URL_Share = $base_URL_Share . $list['item']['ID'] . '&type=' . $type;
            $URL_Facebook = 'https://www.facebook.com/sharer/sharer.php?u=' . $URL_Share . '&t=' . $title;
            $URL_Twitter = 'https://twitter.com/share?url=' . $URL_Share . '&via=TWITTER_HANDLE&text=' . $title;
            $URL_Iframe = '<iframe src="' . $URL_Share . '" allowfullscreen scrolling="no" frameborder="0" width="270px" height="370px"></iframe>';


            /* $refresh_data = array(
              'path' => MSC_PLUGIN_URL ,
              'key' => $key,
              'img_dir' => DIR_TEMP_IMAGE,
              'img_url' => URL_TEMP_IMAGE,
              'url_share' => $base_URL_Share,
              'url_download' => $base_url_download,
              'url_jamendo' => $base_url_jamendo,
              'url_podcast' => $url_podcast
              );
              wp_register_script('msc-refresh', 'jquery/myrefresh.js' );
              wp_localize_script('msc-refresh', 'refresh_data', $refresh_data); */
        }
        ?>    
        <div class="dvPlayerTop" style="background-color:<?php echo get_option('msc_color'); ?>">

            <div id="jquery_jplayer"></div>   
            <div id="jp_container" class="jp-audio">                                                     
                <div class="jp-controls"> 
                    <div id="msc-box-l">                                    
                        <div class="jp-time-mute">
                            <span class="jp-current-time"></span> / <span class="jp-duration"></span>                             
                            <i class="jp-mute fas fa-volume-mute"></i>
                            <i class="jp-unmute fas fa-volume-up"></i>                            
                        </div>                                                    
                        <a data-pos="0" class="jp-stream track track-default fas fa-broadcast-tower" data-href="<?php echo $MyRadio->URLStreaming; ?>" href="#"  style="display:none;"></a>
                        <i class="jp-play fa fa-play-circle fa-4x" style="display:none;"></i>                                          
                        <i class="jp-pause fa fa-pause-circle fa-4x"></i>   
                    </div>
                    <div id="msc-box-r">                                    
                        <div id="jp-image">
                            <img id="jp-image-src" src="<?php echo $PathToShowImg; ?>">                                
                        </div> 
                        <div class="jp-progress">
                            <div class="jp-seek-bar">
                                <div class="jp-play-bar">
                                    <div class="jp-ball"></div>
                                </div>
                            </div>
                        </div>
                        <div id="jp-info">
                            <div><span id="jp_title"><?php echo $title; ?></span></div>
                            <i id="jp_subtitle-name"><?php echo $subtitle; ?></i>   
                            <div id="jp-socialbuttons" >
                                <a id="like" class="fas fa-heart" aria-hidden="true" href="javascript:void" onclick="LikeTrack()"></a>
                                <a id="download" class="fas fa-download" aria-hidden="true" href="<?php echo $URL_Download; ?>" target="_blank" style="display:<?php echo $dwn_display; ?>"></a>
                                <a id="BtnShare" class="fas fa-share-alt" aria-hidden="true" onclick="ShowModalshare()" href="javascript:void"></a>                                            

                                <!-- Modal Share -->
                                <div id="myModalShare" class="modalShare">
                                    <!-- Modal content -->
                                    <div class="modal-content_share">
                                        <span class="closeShare"><i class="fas fa-times fa-2x"></i></span>

                                        <a id="fb" class="fab fa-facebook-square fa-2x" href="<?php echo $URL_Facebook; ?>"
                                           onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');
                                                       return false;"
                                           target="_blank" title="<?php _e('Share on Facebook', 'msc-automation'); ?>">
                                        </a>                                                
                                        <a id="tw" class="fab fa-twitter-square fa-2x" href="<?php echo $URL_Twitter; ?>"
                                           onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');
                                                       return false;"
                                           target="_blank" title="<?php _e('Share on Twitter', 'msc-automation'); ?>">
                                        </a>
                                        <a class="fas fa-code fa-2x" onclick="ShowIframeCode()" title="<?php _e('Share on your web', 'msc-automation'); ?>" href="javascript:void"></a>
                                        <div id="iframe" style="display:none">
                                            <textarea type="text"  id="ifr"><?php echo $URL_Iframe; ?></textarea>
                                            <i><?php _e('Copy this code for add in your web', 'msc-automation'); ?></i>
                                        </div>
                                    </div>
                                </div><!--END  Modal Share -->
                            </div>
                        </div>

                    </div>                                         
                    <div id="msc-hide">
                        <i id="path" style="display:none;"><?php echo MSC_PLUGIN_URL; ?></i>
                        <i id="key"  style="display:none;"><?php echo $key; ?></i>
                        <i id="img_dir"  style="display:none;"><?php echo DIR_TEMP_IMAGE; ?></i>
                        <i id="img_url"  style="display:none;"><?php echo URL_TEMP_IMAGE; ?></i>
                        <i id="url_share"  style="display:none;"><?php echo $base_URL_Share; ?></i>
                        <i id="url_download"  style="display:none;"><?php echo $base_url_download; ?></i>
                        <i id="url_jamendo"  style="display:none;"><?php echo $base_url_jamendo; ?></i>
                        <i id="url_podcast"  style="display:none;"><?php echo $url_podcast; ?></i>
                    </div>

                </div>                           
                <div class="jp-no-solution">
                    <span>Update Required</span>
                    To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
                </div>                                                
            </div>
        </div>
        <div id="link_home">
            <?php _e('On', 'msc-automation') . ' '; ?> <b><a href="<?php echo get_home_url(); ?>" title="<?php echo get_bloginfo('description'); ?>" target="_blank"><?php echo get_bloginfo('name'); ?></a></b>
        </div> 
    </div> 
    <?php
}

add_shortcode('iframe_player_streaming', 'get_iframe_player');
