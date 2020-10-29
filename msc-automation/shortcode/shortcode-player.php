<?php

function mscra_load_scripts()
{

    $def_image = get_site_icon_url('120');
    $page = mscra_get_page_by_meta(MSCRA_HOOK_TRACK);
    $base_URL_Share = $page->guid;
    $PathToSaveImg = MSCRA_DIR_TEMP_IMAGE . '/';
    $PathToShowImg = MSC_URL_TEMP_IMAGE . '/';
    $base_url_download = MSCRA_PLUGIN_URL . 'inc/download.php?fileurl=';
    $upload_dir = wp_upload_dir();
    $url_podcast = $upload_dir['baseurl'] . '/' . WP_MSCRA_PODCAST_DIR;

    $msc_data = array(
        'share_url' => $base_URL_Share,
        'def_image' => $def_image,
        'path' => MSCRA_PLUGIN_URL,
        'key' => get_option('mscra_client_key'),
        'img_dir' => MSCRA_DIR_TEMP_IMAGE,
        'img_url' => MSC_URL_TEMP_IMAGE,
        'jamendo_url' => WP_MSCRA_URL_JAMENDO_TRACK,
        'download_url' => $base_url_download,
        'url_podcast' => $url_podcast,
    );
    wp_enqueue_script('msc-data-js', MSCRA_JQUERY_URL . 'refresh_player.js', '', '1.0.0', false);
    wp_localize_script('msc-data-js', 'msc_data', $msc_data);
}

add_action('wp_enqueue_scripts', 'mscra_load_scripts');

function mscra_get_player()
{
    if (is_admin()) {
        return;
    }
    global $post;
    if (isset($post)) {
        $name_template = get_page_template_slug($post->ID);
        if ($name_template == MSCRA_NAME_TEMPLATE_IFRAME) {
            return;
        }
    }

    wp_enqueue_script('script_play_js1', MSCRA_JQUERY_URL . 'jplayer/jquery.min.js', array(), '1.0.0');
    wp_enqueue_script('script_play_js2', MSCRA_JQUERY_URL . 'jplayer/jquery.jplayer.min.js', array(), '1.0.0');
    wp_enqueue_script('script_player_js', MSCRA_JQUERY_URL . 'jplayer/msc.player.js', array(), '1.0.0');

    $name_container_player = '';
    if (get_option('mscra_player') == 'head') {
        $name_container_player = 'dvPlayerTop';
        wp_enqueue_style('style_mscra_player', MSCRA_CSS_URL . 'head.css', array(), '1.0.0');
        if (is_admin_bar_showing()) {
            ?><style type="text/css"> .dvPlayerTop { top: 28px; }</style><?php
}
    } else {
        $name_container_player = 'dvPlayerBottom';
        wp_enqueue_style('style_mscra_player', MSCRA_CSS_URL . 'footer.css', array(), '1.0.0');
    }
    /* Consulta a la dbs */
    include MSCRA_PLUGIN_DIR . 'connect_api.php';

    $list = $MyRadio->QueryGetTable(seccions::CALENDAR, sub_seccions::NOWPLAYING);

    $img_width = '100';
    if ($MyRadio->RESPOSTA_ROWS > 0) {
        $urlmp3 = '';
        $counter = 0;
        $id = sanitize_text_field($list['item']['ID']);
        $title = sanitize_text_field($list['item']['NAME']);
        $subtitle = sanitize_text_field($list['item']['DESCRIP']);
        $time_end = sanitize_text_field($list['item']['TIME_END']);

        $type = sanitize_text_field($list['item']['TYPE']);
        switch ($type) {
            case TIP_AUTOMATIC_LLISTA:
                $img_mame = 'disc_img-' . $id . '.jpg';
                if (strlen($list['item']['LINK']) > 0) {
                    $URL_Download = WP_MSCRA_URL_JAMENDO_TRACK . sanitize_text_field($list['item']['LINK']);
                } else {
                    $URL_Download = '';
                }
                break;
            case TIP_AUTOMATIC_RADIOFORMULA:
                $img_mame = 'disc_img-' . $id . '.jpg';
                if (strlen($list['item']['LINK']) > 0) {
                    $URL_Download = WP_MSCRA_URL_JAMENDO_TRACK . sanitize_text_field($list['item']['LINK']);
                } else {
                    $URL_Download = '';
                }
                break;
            case TIP_AUTOMATIC_PROGRAMA:
                $img_mame = 'prg_img-' . $id . '.jpg';
                //todo: download prg
                $urlmp3 = $url_podcast . '/' . $list['item']['LINK'];
                $URL_Download = MSCRA_PLUGIN_URL . 'inc/download.php?fileurl=' . $urlmp3 . '&filename=' . urlencode($title);
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
        $PathToSaveImg = MSCRA_DIR_TEMP_IMAGE . '/' . $img_mame;
        $PathToShowImg = MSC_URL_TEMP_IMAGE . '/' . $img_mame;

        $page = mscra_get_page_by_meta(MSCRA_HOOK_TRACK);
        $base_URL_Share = $page->guid;
        $base_url_download = MSCRA_PLUGIN_URL . 'inc/download.php?fileurl=';
        
        $upload_dir = wp_upload_dir();
        $url_podcast = $upload_dir['baseurl'] . '/' . WP_MSCRA_PODCAST_DIR;       
        if (!file_exists($PathToSaveImg)) {
            if (mscra_getImage(base64_decode($list['item']['IMAGE']), $PathToSaveImg, $img_width) == false) {
                //canvia a imatge per defecte
                $PathToShowImg = $def_image;
            }
        }
        $decrip = $title . ' - ' . $subtitle;

        $params = array('ref' => bin2hex($id.','.$type));
        $URL_Share = add_query_arg($params, $base_URL_Share);

        $URL_Facebook = 'https://www.facebook.com/sharer/sharer.php?u=' . $URL_Share . '&t=' . $decrip;
        $URL_Twitter = 'https://twitter.com/share?url=' . $URL_Share . '&via=TWITTER_HANDLE&text=' . $decrip;
        $URL_Pinterest = 'https://pinterest.com/pin/create/button/?&url=' . $URL_Share . '&description=' . $decrip . '&media=' . $urlmp3;
        $URL_Linked_in = 'https://www.linkedin.com/shareArticle?mini=true&url=' . $URL_Share . '&title=' . $decrip;
        $URL_WhatsApp = 'https://wa.me/?text=' . $decrip . '+-+' . $URL_Share;
        $URL_Iframe = '<iframe src="' . $URL_Share . '" allowfullscreen scrolling="no" frameborder="0" width="270px" height="370px"></iframe>';
    }
    ?>
    <div class="<?php echo $name_container_player; ?>" style="background-color:<?php echo get_option('mscra_color'); ?>">
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
                            <a data-pos="0" class="jp-stream track track-default fas fa-broadcast-tower fa-sm" data-href="<?php echo $MyRadio->URLStreaming; ?>" href="#" style="display:none;" onclick="mscra_PlayThisFile(this)"></a>
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
                                <a id="like" class="fas fa-heart" aria-hidden="true" href="javascript:void" onclick="mscra_LikeTrack()"></a>
                                <a id="download" class="fas fa-download" aria-hidden="true" href="<?php echo $URL_Download; ?>" target="_blank" style="display:<?php echo $dwn_display; ?>"></a>
                                <a id="BtnShare" class="fas fa-share-alt" aria-hidden="true" onclick="mscra_ShowModalShare()" href="javascript:void"></a>
                                <!-- Modal Share -->
                                <div id="myModalShare" class="modalShare">
                                    <!-- Modal content -->
                                    <div class="modal-content_share">
                                        <span class="closeShare"><i class="fas fa-times fa-2x"></i></span>
                                        <a id="fb" class="fab fa-facebook-square fa-2x" href="<?php echo $URL_Facebook; ?>"
                                           onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');
                                                       return false;"
                                           target="_blank" title="<?php _e('Share on Facebook', 'mscra-automation');?>">
                                        </a>
                                        <a id="tw" class="fab fa-twitter-square fa-2x" href="<?php echo $URL_Twitter; ?>"
                                           onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');
                                                       return false;"
                                           target="_blank" title="<?php _e('Share on WhatsApp', 'mscra-automation');?>">
                                        </a>
                                        <a id="pt" class="fab fa-pinterest-square fa-2x" href="<?php echo $URL_Pinterest; ?>"
                                           onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;"
                                           target="_blank" title="<?php _e('Share on Pinterest', 'mscra-automation');?>">
                                        </a>
                                        <a id="li" class="fab fa-linkedin fa-2x" href="<?php echo $URL_Linked_in; ?>"
                                           onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');
                                                       return false;"
                                           target="_blank" title="<?php _e('Share on LinkedIn', 'mscra-automation');?>">
                                        </a>
                                        <a id="wa" class="fab fa-whatsapp-square fa-2x" href="<?php echo $URL_WhatsApp; ?>"
                                           onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');
                                                       return false;"
                                           target="_blank" title="<?php _e('Share on LinkedIn', 'mscra-automation');?>">
                                        </a>
                                        <a class="fas fa-code fa-2x" onclick="mscra_ShowIframeCode()" title="<?php _e('Share on your web', 'mscra-automation');?>" href="javascript:void"></a>
                                        <div id="iframe" style="display:none">
                                            <textarea type="text"  id="ifr"><?php echo $URL_Iframe; ?></textarea>
                                            <i><?php _e('Copy this code for add in your web', 'mscra-automation');?></i>
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

add_shortcode('mscra_player_streaming', 'mscra_get_player');

function mscra_get_iframe_player()
{
    if (is_admin()) {return;}

    wp_enqueue_script('script_play_js1', MSCRA_JQUERY_URL . 'jplayer/jquery.min.js', array(), '1.0.0');
    wp_enqueue_script('script_play_js2', MSCRA_JQUERY_URL . 'jplayer/jquery.jplayer.min.js', array(), '1.0.0');
    wp_enqueue_script('script_player_js', MSCRA_JQUERY_URL . 'jplayer/msc.player.js', array(), '1.0.0');

    include MSCRA_PLUGIN_DIR . 'connect_api.php';
    $list = $MyRadio->QueryGetTable(seccions::CALENDAR, sub_seccions::NOWPLAYING);

    $img_width = '100';
    if ($MyRadio->RESPOSTA_ROWS > 0) {
        $urlmp3 = '';
        $counter = 0;
        $id = sanitize_text_field($list['item']['ID']);
        $title = utf8_encode(sanitize_text_field($list['item']['NAME']));
        $subtitle = utf8_encode(sanitize_text_field($list['item']['DESCRIP']));
        $time_end = sanitize_text_field($list['item']['TIME_END']);

        $type = $list['item']['TYPE'];
        switch ($type) {
            case TIP_AUTOMATIC_LLISTA:
                $img_mame = 'disc_img-' . $id . '.jpg';
                if (strlen($list['item']['LINK']) > 0) {
                    $URL_Download = WP_MSCRA_URL_JAMENDO_TRACK . sanitize_text_field($list['item']['LINK']);
                } else {
                    $URL_Download = '';
                }
                break;
            case TIP_AUTOMATIC_RADIOFORMULA:
                $img_mame = 'disc_img-' . $id . '.jpg';
                if (strlen($list['item']['LINK']) > 0) {
                    $URL_Download = WP_MSCRA_URL_JAMENDO_TRACK . sanitize_text_field($list['item']['LINK']);
                } else {
                    $URL_Download = '';
                }
                break;
            case TIP_AUTOMATIC_PROGRAMA:
                $img_mame = 'prg_img-' . $id . '.jpg';
                //todo: download prg
                $urlmp3 = $url_podcast . '/' . sanitize_text_field($list['item']['LINK']);
                $URL_Download = MSCRA_PLUGIN_URL . 'inc/download.php?fileurl=' . $urlmp3 . '&filename=' . urlencode($title);
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

        $PathToSaveImg = MSCRA_DIR_TEMP_IMAGE . '/' . $img_mame;
        $PathToShowImg = MSC_URL_TEMP_IMAGE . '/' . $img_mame;
        
        $page = mscra_get_page_by_meta(MSCRA_HOOK_TRACK);
        $base_URL_Share = $page->guid;
        
        $base_url_download = MSCRA_PLUGIN_URL . 'inc/download.php?fileurl=';
        $url_podcast = $upload_dir['baseurl'] . '/' . WP_MSCRA_PODCAST_DIR;

        $base_url_jamendo = WP_MSCRA_URL_JAMENDO_TRACK;
        if (!file_exists($PathToSaveImg)) {
            if (mscra_getImage(base64_decode($list['item']['IMAGE']), $PathToSaveImg, $img_width) == true) {
                //canvia a imatge per defecte
            }
        }
        
        $params = array('ref' => bin2hex($id.','.$type));
        $URL_Share = add_query_arg($params, $base_URL_Share);

        $URL_Facebook = 'https://www.facebook.com/sharer/sharer.php?u=' . $URL_Share . '&t=' . $decrip;
        $URL_Twitter = 'https://twitter.com/share?url=' . $URL_Share . '&via=TWITTER_HANDLE&text=' . $decrip;
        $URL_Pinterest = 'https://pinterest.com/pin/create/button/?&url=' . $URL_Share . '&description=' . $decrip . '&media=' . $urlmp3;
        $URL_Linked_in = 'https://www.linkedin.com/shareArticle?mini=true&url=' . $URL_Share . '&title=' . $decrip;
        $URL_WhatsApp = 'https://wa.me/?text=' . $decrip . '+-+' . $URL_Share;
        $URL_Iframe = '<iframe src="' . $URL_Share . '" allowfullscreen scrolling="no" frameborder="0" width="270px" height="370px"></iframe>';
    }
    ?>
        <div class="dvPlayerTop" style="background-color:<?php echo get_option('mscra_color'); ?>">

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
                                <a id="like" class="fas fa-heart" aria-hidden="true" href="javascript:void" onclick="mscra_LikeTrack()"></a>
                                <a id="download" class="fas fa-download" aria-hidden="true" href="<?php echo $URL_Download; ?>" target="_blank" style="display:<?php echo $dwn_display; ?>"></a>
                                <a id="BtnShare" class="fas fa-share-alt" aria-hidden="true" onclick="mscra_ShowModalShare()" href="javascript:void"></a>
                                <!-- Modal Share -->
                                <div id="myModalShare" class="modalShare">
                                    <!-- Modal content -->
                                    <div class="modal-content_share">
                                        <span class="closeShare"><i class="fas fa-times fa-2x"></i></span>

                                        <a id="fb" class="fab fa-facebook-square fa-2x" href="<?php echo $URL_Facebook; ?>"
                                           onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');
                                                       return false;"
                                           target="_blank" title="<?php _e('Share on Facebook', 'mscra-automation');?>">
                                        </a>
                                        <a id="tw" class="fab fa-twitter-square fa-2x" href="<?php echo $URL_Twitter; ?>"
                                           onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');
                                                       return false;"
                                           target="_blank" title="<?php _e('Share on WhatsApp', 'mscra-automation');?>">
                                        </a>
                                        <a id="pt" class="fab fa-pinterest-square fa-2x" href="<?php echo $URL_Pinterest; ?>"
                                           onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');
                                                       return false;"
                                           target="_blank" title="<?php _e('Share on Pinterest', 'mscra-automation');?>">
                                        </a>
                                        <a id="li" class="fab fa-linkedin fa-2x" href="<?php echo $URL_Linked_in; ?>"
                                           onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');
                                                       return false;"
                                           target="_blank" title="<?php _e('Share on LinkedIn', 'mscra-automation');?>">
                                        </a>
                                        <a id="wa" class="fab fa-whatsapp-square fa-2x" href="<?php echo $URL_WhatsApp; ?>"
                                           onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');
                                                       return false;"
                                           target="_blank" title="<?php _e('Share on LinkedIn', 'mscra-automation');?>">
                                        </a>
                                        <a class="fas fa-code fa-2x" onclick="mscra_ShowIframeCode()" title="<?php _e('Share on your web', 'mscra-automation');?>" href="javascript:void"></a>
                                        <div id="iframe" style="display:none">
                                            <textarea type="text"  id="ifr"><?php echo $URL_Iframe; ?></textarea>
                                            <i><?php _e('Copy this code for add in your web', 'mscra-automation');?></i>
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
                    </div>
                    <div class="jp-no-solution">
                        <span>Update Required</span>
                        To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
                    </div>
                </div>
            </div>
        </div>
        <div id="link_home">
            <?php _e('On', 'mscra-automation') . ' ';?> <b><a href="<?php echo get_home_url(); ?>" title="<?php echo get_bloginfo('description'); ?>" target="_blank"><?php echo get_bloginfo('name'); ?></a></b>
        </div>
    </div>
    <?php
}

add_shortcode('mscra_iframe_player_streaming', 'mscra_get_iframe_player');
