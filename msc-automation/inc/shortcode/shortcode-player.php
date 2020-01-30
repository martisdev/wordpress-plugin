<?php

function get_player( ){                        
            
        $name_template = get_page_template_slug($post->ID);            
        if ($name_template == NAME_TEMPLATE_IFRAME) {return;}    
        
        $name_container_player = '';
        if (get_option('msc_player')=='head'){
            $name_container_player = 'dvPlayerTop';
            wp_enqueue_style( 'style_msc_player',MSC_CSS_URL.'head.css', array(), '1.0.0' );                        
            if ( is_admin_bar_showing()) {?><style type="text/css"> .dvPlayerTop { top: 28px; }</style><?php }
        }else{
            $name_container_player = 'dvPlayerBottom';
            wp_enqueue_style( 'style_msc_player',MSC_CSS_URL.'footer.css', array(), '1.0.0' );                        
        }                          
        wp_enqueue_script( 'script_play_js1', MSC_JQUERY_URL.'jplayer/jquery.min.js' , array(), '1.0.0' );            
        wp_enqueue_script( 'script_play_js2', MSC_JQUERY_URL.'jplayer/jquery.jplayer.min.js' , array(), '1.0.0' );            
        wp_enqueue_script( 'script_player_js', MSC_JQUERY_URL.'jplayer/msc.player.js' , array(), '1.0.0' );              
        
        /*Consulta a la dbs*/
        $key = get_option('msc_client_key');        
        global $MyRadio;          
        if(!isset($MyRadio)){ $MyRadio = new my_radio($key,get_locale(),get_option('msc_debug'));}        
        if ($MyRadio->RESPOSTA_STATUS !== SUCCES ){
            if ($MyRadio->IS_DEGUG == true){                                                
                $msg = 'STATUS: '.$MyRadio->RESPOSTA_STATUS.' CODE: '.$MyRadio->RESPOSTA_CODE.' MSG: '.$MyRadio->RESPOSTA_MESSAGE ;
                show_msc_message($msg ,message_type::DANGER);                
                return;
            }
        }                        
        $url_images = URL_TEMP_IMAGE;
        $dir_images = DIR_TEMP_IMAGE;           
        $list = $MyRadio->QueryGetTable(seccions::CALENDAR,sub_seccions::NOWPLAYING);                        
        
        $img_width= '100';
        if ($MyRadio->RESPOSTA_ROWS>0){                 
            $counter = 0;  
            $id = $list['item']['ID'];
            $title      = $list['item']['NAME'];
            $subtitle   = $list['item']['DESCRIP'];    
            $time_end = $list['item']['TIME_END'];
            
            $type = $list['item']['TYPE'];            
            switch ($type){
                case TIP_AUTOMATIC_LLISTA:
                    $img_mame = 'disc_img-'.$id.'.jpg';                    
                    if (strlen($list['item']['LINK'])>0){
                        $URL_Download = URL_JAMENDO_TRACK.$list['item']['LINK'];                        
                    }else{$URL_Download ='';}                    
                    break;
                case TIP_AUTOMATIC_RADIOFORMULA:                    
                    $img_mame = 'disc_img-'.$id.'.jpg';                    
                    if (strlen($list['item']['LINK'])>0){                        
                        $URL_Download = URL_JAMENDO_TRACK.$list['item']['LINK'];                        
                    }else{$URL_Download ='';}                    
                    break;
                case TIP_AUTOMATIC_PROGRAMA:
                    $img_mame = 'prg_img-'.$id.'.jpg';               
                    //todo: download prg
                    $urlmp3 = $url_podcast.'/'.$list['item']['LINK'];                                                        
                    $URL_Download = MSC_PLUGIN_URL.'inc/download.php?fileurl='.$urlmp3.'&filename='.urlencode($title);                    
                    break;
                case TIP_DIRECTE_:
                    $img_mame = 'prg_img-'.$id.'.jpg';
                    $URL_Download = '';                    
                    break;
                case TIP_CONEX_CENTRAL:
                    $img_mame = 'radio_img.jpg';
                    $URL_Download = '';                    
                    break;
            }  
            
            if (strlen($URL_Download)==0){
                $dwn_display = 'none';
            }else{
                $dwn_display = 'inline';
            }
            $def_image = get_site_icon_url('120');
            $PathToSaveImg = $dir_images.'/'.$img_mame; 
            $PathToShowImg = $url_images.'/'.$img_mame;                          
            $base_URL_Share = get_home_url(0,NAME_PAGE_TRACK.'/').'?id=';
            $base_url_download = MSC_PLUGIN_URL.'inc/download.php?fileurl=';
            
            //$url_podcast = wp_get_upload_dir('baseurl').'/'.PODCAST_DIR; 
            $upload_dir = wp_upload_dir();
            $url_podcast = $upload_dir['baseurl'].'/'.PODCAST_DIR; 
            $base_url_jamendo = URL_JAMENDO_TRACK;   
            
            if (!file_exists($PathToSaveImg)){                
                if (getImage(base64_decode($list['item']['IMAGE']),$PathToSaveImg,$img_width)==false){                 
                //canvia a imatge per defecte                              
                    $PathToShowImg = $def_image;
                }            
            }                           
            $URL_Share = $base_URL_Share.$list['item']['ID'].'&type='.$type;            
            $URL_Facebook = 'https://www.facebook.com/sharer/sharer.php?u='.$URL_Share.'&t='.$title;
            $URL_Twitter = 'https://twitter.com/share?url='.$URL_Share.'&via=TWITTER_HANDLE&text='.$title;
            $URL_Iframe = '<iframe src="'.$URL_Share.'" allowfullscreen scrolling="no" frameborder="0" width="270px" height="370px"></iframe>';            
            
        }           
        ?>
        <script type="text/javascript">   
                                                    
            var myrefresh = function(){
                    
                var refresh = document.getElementById('refresh').innerHTML;
                if (refresh==1){
                    var r = (-0.5)+(Math.random()*(1000.99));                                        
                    var key         = document.getElementById('key').innerHTML
                    var img_dir     = document.getElementById('img_dir').innerHTML
                    var img_url     = document.getElementById('img_url').innerHTML
                    var share_url   = document.getElementById('url_share').innerHTML;
                    var jamendo_url   = document.getElementById('url_jamendo').innerHTML;
                    var download_url   = document.getElementById('url_download').innerHTML;
                    var url_podcast   = document.getElementById('url_podcast').innerHTML;            
                    var def_image   = document.getElementById('def_image').innerHTML;            
        
                    var path = document.getElementById("path").innerHTML+"wp-snippets/refresh_player.php?key="+key+"&img_dir="+img_dir+"&img_url="+img_url+"&share_url="+share_url+"&url_download="+download_url+"&url_jamendo="+jamendo_url+"&url_podcast="+url_podcast+"&di"+def_image+"&ram=" +r;                                                            
                    var xmlhttp = new XMLHttpRequest();
                    
                    xmlhttp.onreadystatechange = function () {
                        if (xmlhttp.readyState < 4){
                            //document.getElementById('demo').innerHTML = "Loading...";                            
                        }else if (xmlhttp.readyState === 4) {
                            if (xmlhttp.status === 200 && xmlhttp.status < 300)
                            {                                
                                xmlDoc = xmlhttp.responseXML;
                                titles = xmlDoc.getElementsByTagName("title");
                                subtit = xmlDoc.getElementsByTagName("subtitle");                                
                                img = xmlDoc.getElementsByTagName("image");                                
                                t_remain = xmlDoc.getElementsByTagName("remain");
                                URL_Share = xmlDoc.getElementsByTagName("url_share");
                                URL_download =xmlDoc.getElementsByTagName("url_download");
                                
                                URL_Facebook = "https://www.facebook.com/sharer/sharer.php?u="+URL_Share[0].childNodes[0].nodeValue+"&t="+titles[0].childNodes[0].nodeValue;
                                URL_twitter = 'https://twitter.com/share?url='+URL_Share[0].childNodes[0].nodeValue+'&via=TWITTER_HANDLE&text='+titles[0].childNodes[0].nodeValue;
                                URL_Iframe = '<iframe src="'+URL_Share[0].childNodes[0].nodeValue+'" allowfullscreen scrolling="no" frameborder="0" width="270px" height="370px"></iframe>';
                                
                                //Title page
                                my_title = titles[0].childNodes[0].nodeValue;
                                my_subtitle = subtit[0].childNodes[0].nodeValue;
                                document.getElementById('jp_title').innerHTML = my_title; 
                                document.getElementById("jp_subtitle-name").innerHTML= my_subtitle;
                                document.title = my_title +' | '+ my_subtitle                                
                                
                                //Image page
                                my_img = img[0].childNodes[0].nodeValue;
                                document.getElementById("jp-image-src").src = my_img;
                                var link = document.querySelector("link[rel*='icon']") || document.createElement('link');
                                link.type = 'image/x-icon';
                                link.rel = 'icon';
                                link.href = my_img;
                                document.getElementsByTagName('head')[0].appendChild(link);
                                                                
                                document.getElementById('download').href = URL_download[0].childNodes[0].nodeValue;
                                if (URL_download[0].childNodes[0].nodeValue.length >3)
                                {
                                    document.getElementById('download').style.display = "inline";
                                }else{
                                    document.getElementById('download').style.display = "none";
                                }                                
                                document.getElementById('fb').href = URL_Facebook;
                                document.getElementById('tw').href = URL_twitter;
                                document.getElementById('ifr').innerHTML = URL_Iframe;
                                
                                if (t_remain< 1000){
                                    tmr = 15000 ;
                                }else{
                                    tmr = t_remain+1000 ;
                                }
                            }else if(xmlhttp.status === 404){                                
                            } 
                        }
                    }                    
                    xmlhttp.open("GET", path, true);
                    xmlhttp.send();                                                        
                    //https://stackoverflow.com/questions/1280263/changing-the-interval-of-setinterval-while-its-running
                    //setInterval(mysrefresh, tmr);
                }                    
            };          
            
            var tmr = 15000;
            setInterval(myrefresh, tmr);
            
            function StopRefresh() {
                clearInterval(myrefresh);
                console.log('Stop refresh');                
            }
            
            function ShowModalshare(){
                   // Get the modal
                var modal = document.getElementById("myModalShare");

                // Get the button that opens the modal
                var btn = document.getElementById("BtnShare");

                // Get the <span> element that closes the modal
                var span = document.getElementsByClassName("closeShare")[0];

                // When the user clicks the button, open the modal 
                btn.onclick = function() {
                  modal.style.display = "block";
                }

                // When the user clicks on <span> (x), close the modal
                span.onclick = function() {
                  modal.style.display = "none";
                }

                // When the user clicks anywhere outside of the modal, close it
                window.onclick = function(event) {
                  if (event.target == modal) {
                    modal.style.display = "none";
                  }
                }
           }     
           
            function ShowIframeCode(){
               var ifra = document.getElementById("iframe");
               if (ifra.style.display == "none"){
                   ifra.style.display = "block";
               }else{
                   ifra.style.display = "none";
               }    
           }
           
            function LikeTrack(){
                var key  = document.getElementById('key').innerHTML
                var hear = document.getElementById("like");                
                if (hear.style.color!=='red'){
                    hear.style.color = 'red';                    
                    //register the vote                    
                    
                }else{
                    hear.style.color = 'inherit';                    
                    //unregister the vote                    
                }
           }
           
        </script>

        <div class="<?php echo $name_container_player;?>" style="background-color:<?php echo get_option('msc_color');?>">                                                    
            <div id="msc-left"></div>            
            <div id="msc-middle">
                <div id="jquery_jplayer"></div> 
                    <div id="jp_container">                 
                        <div class="jp-controls"> 
                            <div id="msc-box-l">                                    
                                <div class="jp-time-mute">
                                    <span class="jp-current-time"></span> / <span class="jp-duration"></span>                             
                                    <i class="jp-mute fas fa-volume-mute"></i>
                                    <i class="jp-unmute fas fa-volume-up"></i>                            
                                </div>                                                    
                                <a data-pos="0" class="jp-stream track track-default fas fa-broadcast-tower" href="<?php echo $MyRadio->URLStreaming ; ?>" style="display:none;"></a>
                                <i class="jp-play fa fa-play-circle fa-4x" style="display:none;"></i>                                          
                                <i class="jp-pause fa fa-pause-circle fa-4x"></i>                               
                            </div>
                            <div id="msc-box-r">                                    
                                <div id="jp-image">
                                    <img id="jp-image-src" src="<?php echo $PathToShowImg;?>">                                
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
                                        <a id="download" class="fas fa-download" aria-hidden="true" href="<?php echo $URL_Download ;?>" target="_blank" style="display:<?php echo $dwn_display;?>"></a>
                                        <a id="BtnShare" class="fas fa-share-alt" aria-hidden="true" onclick="ShowModalshare()" href="javascript:void"></a>                                            

                                        <!-- Modal Share -->
                                        <div id="myModalShare" class="modalShare">
                                          <!-- Modal content -->
                                          <div class="modal-content_share">
                                            <span class="closeShare"><i class="fas fa-times fa-2x"></i></span>

                                            <a id="fb" class="fab fa-facebook-square fa-2x" href="<?php echo $URL_Facebook;?>"
                                                    onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;"
                                                    target="_blank" title="<?php _e('Share on Facebook','msc-automation'); ?>">
                                            </a>                                                
                                            <a id="tw" class="fab fa-twitter-square fa-2x" href="<?php echo $URL_Twitter;?>"
                                                onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;"
                                                target="_blank" title="<?php _e('Share on Twitter','msc-automation'); ?>">
                                            </a>
                                            <a class="fas fa-code fa-2x" onclick="ShowIframeCode()" title="<?php _e('Share on your web','msc-automation'); ?>" href="javascript:void"></a>
                                            <div id="iframe" style="display:none">
                                                <textarea type="text"  id="ifr"><?php echo $URL_Iframe;?></textarea>
                                                <i><?php _e('Copy this code for add in your web','msc-automation'); ?></i>
                                            </div>
                                          </div>
                                        </div><!--END  Modal Share -->
                                    </div>
                                </div>

                            </div>                                         
                            <div id="msc-hide">
                                <i id="path" style="display:none;"><?php echo MSC_PLUGIN_URL ;?></i>
                                <i id="key"  style="display:none;"><?php echo $key ;?></i>
                                <i id="img_dir"  style="display:none;"><?php echo $dir_images ;?></i>
                                <i id="img_url"  style="display:none;"><?php echo $url_images ;?></i>
                                <i id="url_share"  style="display:none;"><?php echo $base_URL_Share ;?></i>
                                <i id="url_download"  style="display:none;"><?php echo $base_url_download ;?></i>
                                <i id="url_jamendo"  style="display:none;"><?php echo $base_url_jamendo ;?></i>
                                <i id="url_podcast"  style="display:none;"><?php echo $url_podcast ;?></i>
                                <i id="def_image"  style="display:none;"><?php echo $def_image ;?></i>
                                <i id="refresh"  style="display:none;">1</i>
                            </div>                                
                        </div>  
                        <div class="jp-no-solution">
                            <span>Update Required <?php echo $MyRadio->URLStreaming; ?></span>
                            To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
                        </div>  
                    </div>
            </div>        
        </div>
        <?php                 
    }
    
add_shortcode('player_streaming', 'get_player');


function get_iframe_player( ){                        
        
        wp_enqueue_script( 'script_play_js1', MSC_JQUERY_URL.'jplayer/jquery.min.js' , array(), '1.0.0' );            
        wp_enqueue_script( 'script_play_js2', MSC_JQUERY_URL.'jplayer/jquery.jplayer.min.js' , array(), '1.0.0' );            
        wp_enqueue_script( 'script_player_js', MSC_JQUERY_URL.'jplayer/msc.player.js' , array(), '1.0.0' );              
        
        /*Consulta a la dbs*/
        $key = get_option('msc_client_key');        
        global $MyRadio;          
        if(!isset($MyRadio)){ $MyRadio = new my_radio($key,get_locale(),get_option('msc_debug'));}        
        if ($MyRadio->RESPOSTA_STATUS !== SUCCES ){
            if ($MyRadio->IS_DEGUG == true){                                                
                $msg = 'STATUS: '.$MyRadio->RESPOSTA_STATUS.' CODE: '.$MyRadio->RESPOSTA_CODE.' MSG: '.$MyRadio->RESPOSTA_MESSAGE ;
                show_msc_message($msg ,message_type::DANGER);                
                return;
            }
        }                        
        $url_images = URL_TEMP_IMAGE;
        $dir_images = DIR_TEMP_IMAGE;           
        $list = $MyRadio->QueryGetTable(seccions::CALENDAR,sub_seccions::NOWPLAYING);                        
        
        $img_width= '100';
        if ($MyRadio->RESPOSTA_ROWS>0){                 
            $counter = 0;  
            $id = $list['item']['ID'];
            $title      = utf8_encode($list['item']['NAME']);
            $subtitle   = utf8_encode($list['item']['DESCRIP']);    
            $time_end = $list['item']['TIME_END'];
            
            $type = $list['item']['TYPE'];            
            switch ($type){
                case TIP_AUTOMATIC_LLISTA:
                    $img_mame = 'disc_img-'.$id.'.jpg';                    
                    if (strlen($list['item']['LINK'])>0){
                        $URL_Download = URL_JAMENDO_TRACK.$list['item']['LINK'];                        
                    }else{$URL_Download ='';}                    
                    break;
                case TIP_AUTOMATIC_RADIOFORMULA:                    
                    $img_mame = 'disc_img-'.$id.'.jpg';                    
                    if (strlen($list['item']['LINK'])>0){                        
                        $URL_Download = URL_JAMENDO_TRACK.$list['item']['LINK'];                        
                    }else{$URL_Download ='';}                    
                    break;
                case TIP_AUTOMATIC_PROGRAMA:
                    $img_mame = 'prg_img-'.$id.'.jpg';               
                    //todo: download prg
                    $urlmp3 = $url_podcast.'/'.$list['item']['LINK'];                                                        
                    $URL_Download = MSC_PLUGIN_URL.'inc/download.php?fileurl='.$urlmp3.'&filename='.urlencode($title);                    
                    break;
                case TIP_DIRECTE_:
                    $img_mame = 'prg_img-'.$id.'.jpg';
                    $URL_Download = '';                    
                    break;
                case TIP_CONEX_CENTRAL:
                    $img_mame = 'radio_img.jpg';
                    $URL_Download = '';                    
                    break;
            }              
            if (strlen($URL_Download)==0){
                $dwn_display = 'none';
            }else{
                $dwn_display = 'inline';
            }
                
            $PathToSaveImg = $dir_images.'/'.$img_mame; 
            $PathToShowImg = $url_images.'/'.$img_mame;              
            $base_URL_Share = get_home_url(0,NAME_PAGE_TRACK.'/').'?id=';
            $base_url_download = MSC_PLUGIN_URL.'inc/download.php?fileurl=';
            $url_podcast = $upload_dir['baseurl'].'/'.PODCAST_DIR; 
            
            $base_url_jamendo = URL_JAMENDO_TRACK;            
            if (!file_exists($PathToSaveImg)){
                if (getImage(base64_decode($list['item']['IMAGE']),$PathToSaveImg,$img_width)==TRUE){                 
                //canvia a imatge per defecte                            
                }            
            }                                    
            $URL_Share = $base_URL_Share.$list['item']['ID'].'&type='.$type;            
            $URL_Facebook = 'https://www.facebook.com/sharer/sharer.php?u='.$URL_Share.'&t='.$title;
            $URL_Twitter = 'https://twitter.com/share?url='.$URL_Share.'&via=TWITTER_HANDLE&text='.$title;
            $URL_Iframe = '<iframe src="'.$URL_Share.'" allowfullscreen scrolling="no" frameborder="0" width="270px" height="370px"></iframe>';            
        
            
            /*$refresh_data = array(
                'path' => MSC_PLUGIN_URL ,
                'key' => $key,
                'img_dir' => $dir_images,
                'img_url' => $url_images,
                'url_share' => $base_URL_Share,
                'url_download' => $base_url_download,
                'url_jamendo' => $base_url_jamendo,
                'url_podcast' => $url_podcast                
            );
            wp_register_script('msc-refresh', 'jquery/myrefresh.js' );
            wp_localize_script('msc-refresh', 'refresh_data', $refresh_data);*/
        }           
        ?>
        <script type="text/javascript">   
                                                    
            var myrefresh = function(){
                    
                    var r = (-0.5)+(Math.random()*(1000.99));                                        
                    var key         = document.getElementById('key').innerHTML
                    var img_dir     = document.getElementById('img_dir').innerHTML
                    var img_url     = document.getElementById('img_url').innerHTML
                    var share_url   = document.getElementById('url_share').innerHTML;
                    var jamendo_url   = document.getElementById('url_jamendo').innerHTML;
                    var download_url   = document.getElementById('url_download').innerHTML;
                    var url_podcast   = document.getElementById('url_podcast').innerHTML;            
        
                    var path = document.getElementById("path").innerHTML+"wp-snippets/refresh_player.php?key="+key+"&img_dir="+img_dir+"&img_url="+img_url+"&share_url="+share_url+"&url_download="+download_url+"&url_jamendo="+jamendo_url+"&url_podcast="+url_podcast+"&ram=" +r;                                                            
                    var xmlhttp = new XMLHttpRequest();
                    
                    xmlhttp.onreadystatechange = function () {
                        if (xmlhttp.readyState < 4){
                            //document.getElementById('demo').innerHTML = "Loading...";                            
                        }else if (xmlhttp.readyState === 4) {
                            if (xmlhttp.status === 200 && xmlhttp.status < 300)
                            {                                
                                xmlDoc = xmlhttp.responseXML;
                                titles = xmlDoc.getElementsByTagName("title");
                                subtit = xmlDoc.getElementsByTagName("subtitle");                                
                                img = xmlDoc.getElementsByTagName("image");                                
                                t_remain = xmlDoc.getElementsByTagName("remain");
                                URL_Share = xmlDoc.getElementsByTagName("url_share");
                                URL_download =xmlDoc.getElementsByTagName("url_download");
                                
                                URL_Facebook = "https://www.facebook.com/sharer/sharer.php?u="+URL_Share[0].childNodes[0].nodeValue+"&t="+titles[0].childNodes[0].nodeValue;
                                URL_twitter = 'https://twitter.com/share?url='+URL_Share[0].childNodes[0].nodeValue+'&via=TWITTER_HANDLE&text='+titles[0].childNodes[0].nodeValue;
                                URL_Iframe = '<iframe src="'+URL_Share[0].childNodes[0].nodeValue+'" allowfullscreen scrolling="no" frameborder="0" width="270px" height="370px"></iframe>';
                                
                                document.getElementById('jp_title').innerHTML = titles[0].childNodes[0].nodeValue; 
                                document.getElementById("jp_subtitle-name").innerHTML= subtit[0].childNodes[0].nodeValue;
                                document.getElementById("jp-image-src").src= img[0].childNodes[0].nodeValue;                                
                                                                
                                document.getElementById('download').href = URL_download[0].childNodes[0].nodeValue;
                                if (URL_download[0].childNodes[0].nodeValue.length >3)
                                {
                                    document.getElementById('download').style.display = "inline";
                                }else{
                                    document.getElementById('download').style.display = "none";
                                }                                
                                document.getElementById('fb').href = URL_Facebook;
                                document.getElementById('tw').href = URL_twitter;
                                document.getElementById('ifr').innerHTML = URL_Iframe;
                                
                                if (t_remain< 1000){
                                    tmr = 15000 ;
                                }else{
                                    tmr = t_remain+1000 ;
                                }
                            }else if(xmlhttp.status === 404){                                
                            } 
                        }
                    }                    
                    xmlhttp.open("GET", path, true);
                    xmlhttp.send();                                                        
                    //https://stackoverflow.com/questions/1280263/changing-the-interval-of-setinterval-while-its-running
                    //setInterval(mysrefresh, tmr);
                    
            
            };          
            
            var tmr = 15000;
            setInterval(myrefresh, tmr);
            
            
            function ShowModalshare(){
                   // Get the modal
                var modal = document.getElementById("myModalShare");

                // Get the button that opens the modal
                var btn = document.getElementById("BtnShare");

                // Get the <span> element that closes the modal
                var span = document.getElementsByClassName("closeShare")[0];

                // When the user clicks the button, open the modal 
                btn.onclick = function() {
                  modal.style.display = "block";
                }

                // When the user clicks on <span> (x), close the modal
                span.onclick = function() {
                  modal.style.display = "none";
                }

                // When the user clicks anywhere outside of the modal, close it
                window.onclick = function(event) {
                  if (event.target == modal) {
                    modal.style.display = "none";
                  }
                }
           }     
           
            function ShowIframeCode(){
               var ifra = document.getElementById("iframe");
               if (ifra.style.display == "none"){
                   ifra.style.display = "block";
               }else{
                   ifra.style.display = "none";
               }    
           }
           
            function LikeTrack(){
                var key  = document.getElementById('key').innerHTML
                //idtrak, type
                var hear = document.getElementById("like");                
                if (hear.style.color!=='red'){
                    hear.style.color = 'red';                    
                    //register the vote                    
                    
                }else{
                    hear.style.color = 'inherit';                    
                    //unregister the vote                    
                }
           }
           
        </script>
        <div class="dvPlayerTop" style="background-color:<?php echo get_option('msc_color');?>">
           
            <div id="jquery_jplayer"></div>   
                <div id="jp_container" class="jp-audio">                                                     
                    <div class="jp-controls"> 
                                <div id="msc-box-l">                                    
                                    <div class="jp-time-mute">
                                        <span class="jp-current-time"></span> / <span class="jp-duration"></span>                             
                                        <i class="jp-mute fas fa-volume-mute"></i>
                                        <i class="jp-unmute fas fa-volume-up"></i>                            
                                    </div>                                                    
                                    <a data-pos="0" class="jp-stream track track-default fas fa-broadcast-tower" href="<?php echo $MyRadio->URLStreaming ; ?>" style="display:none;"></a>
                                    <i class="jp-play fa fa-play-circle fa-4x" style="display:none;"></i>                                          
                                    <i class="jp-pause fa fa-pause-circle fa-4x"></i>   
                                </div>
                                <div id="msc-box-r">                                    
                                    <div id="jp-image">
                                        <img id="jp-image-src" src="<?php echo $PathToShowImg;?>">                                
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
                                            <a id="download" class="fas fa-download" aria-hidden="true" href="<?php echo $URL_Download ;?>" target="_blank" style="display:<?php echo $dwn_display;?>"></a>
                                            <a id="BtnShare" class="fas fa-share-alt" aria-hidden="true" onclick="ShowModalshare()" href="javascript:void"></a>                                            

                                            <!-- Modal Share -->
                                            <div id="myModalShare" class="modalShare">
                                              <!-- Modal content -->
                                              <div class="modal-content_share">
                                                <span class="closeShare"><i class="fas fa-times fa-2x"></i></span>

                                                <a id="fb" class="fab fa-facebook-square fa-2x" href="<?php echo $URL_Facebook;?>"
                                                        onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;"
                                                        target="_blank" title="<?php _e('Share on Facebook','msc-automation'); ?>">
                                                </a>                                                
                                                <a id="tw" class="fab fa-twitter-square fa-2x" href="<?php echo $URL_Twitter;?>"
                                                    onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;"
                                                    target="_blank" title="<?php _e('Share on Twitter','msc-automation'); ?>">
                                                </a>
                                                <a class="fas fa-code fa-2x" onclick="ShowIframeCode()" title="<?php _e('Share on your web','msc-automation'); ?>" href="javascript:void"></a>
                                                <div id="iframe" style="display:none">
                                                    <textarea type="text"  id="ifr"><?php echo $URL_Iframe;?></textarea>
                                                    <i><?php _e('Copy this code for add in your web','msc-automation'); ?></i>
                                                </div>
                                              </div>
                                            </div><!--END  Modal Share -->
                                        </div>
                                    </div>
                                    
                                </div>                                         
                                <div id="msc-hide">
                                    <i id="path" style="display:none;"><?php echo MSC_PLUGIN_URL ;?></i>
                                    <i id="key"  style="display:none;"><?php echo $key ;?></i>
                                    <i id="img_dir"  style="display:none;"><?php echo $dir_images ;?></i>
                                    <i id="img_url"  style="display:none;"><?php echo $url_images ;?></i>
                                    <i id="url_share"  style="display:none;"><?php echo $base_URL_Share ;?></i>
                                    <i id="url_download"  style="display:none;"><?php echo $base_url_download ;?></i>
                                    <i id="url_jamendo"  style="display:none;"><?php echo $base_url_jamendo ;?></i>
                                    <i id="url_podcast"  style="display:none;"><?php echo $url_podcast ;?></i>
                                </div>
                                
                            </div>                           
                    <div class="jp-no-solution">
                        <span>Update Required <?php echo $MyRadio->URLStreaming; ?></span>
                        To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
                    </div>                                                
                </div>
        </div>
        <div id="link_home">
            <?php _e('On', 'msc-automation').' '; ?> <b><a href="<?php echo get_home_url(); ?>" title="<?php echo get_bloginfo('description'); ?>" target="_blank"><?php echo get_bloginfo('name'); ?></a></b>
        </div> 
        </div> 
        <?php                 
    }
    
    add_shortcode('iframe_player_streaming', 'get_iframe_player');