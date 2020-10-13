<?php

class mscra_twitter{
    var $URL_TWITTER_USUARI =  '';
    var $TWITTER_USUARI =  '';
    var $Lang = '';    
    /**
     * Inicia la classe Twitter
     * @param type $UsuariTwitter
     * @param type $Idioma
     */
    function __construct($UsuariTwitter,$Idioma) {
        $this->TWITTER_USUARI = $UsuariTwitter;
        $this->URL_TWITTER_USUARI= 'https://twitter.com/'.$UsuariTwitter ;
        $this->Lang = $Idioma;
        echo '<script>(function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "//platform.twitter.com/widgets.js#xfbml=1";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, "script", "twitter-wjs"));
       </script> ';
    }

    /**
     * Ensenya el bot� segueix a
     */
    function show_FollowButton(){
        //TODO: Segons idioma canviar expressi�
        return '<a href="'.$this->URL_TWITTER_USUARI.'" 
                    class="twitter-follow-button" 
                    data-show-count="false" 
                    data-lang="'.$this->Lang.'"
                    >Segueix a @'.$this->TWITTER_USUARI.'</a>';
    }
    
    /**
     * 
     * @param type $ShowCount
     */
    function show_ShareLink($ShowCount=true){
        //TODO: Segons idioma canviar expressi�
        $strEcho = '<a href="https://twitter.com/share" 
                    class="twitter-share-button" 
                    data-url="'.$this->URL_TWITTER_USUARI.'" 
                    data-lang="'.$this->Lang.'" 
                    data-size="large"';
        if($ShowCount==false){$strEcho = $strEcho.'data-count="none"';}                    
        $strEcho = $strEcho.'>Tuiteja</a>';
        return $strEcho ;
    }
 
    /**
     * 
     * @param type $HashTag
     */
    function show_Hashtag($HashTag){
        //TODO: tractar l'estring
        return '<a href="https://twitter.com/intent/tweet?button_hashtag='.$HashTag.'" 
                    class="twitter-hashtag-button" 
                    data-lang="'.$this->Lang.'" 
                    data-size="large"
                    >Tweet #'.$HashTag.'</a>';
    }
    
    /**
     * 
     * @param type $UsuariTwitter
     */
    function show_Mention($UsuariTwitter){
        return '<a href="https://twitter.com/intent/tweet?screen_name='.$UsuariTwitter.'" 
            class="twitter-mention-button" 
            data-lang="'.$this->Lang.'" 
            data-size="large"
            >Tweet to @'.$UsuariTwitter.'</a>';
    } 
    
    /**
     * 
     * @param type $KEY_GINY Clau �nica del giny
     */
    function show_Cronologia($twiterKey){
        return '<a class="twitter-timeline"  
                    href="'.$this->URL_TWITTER_USUARI.'"  
                    data-widget-id="'.$twiterKey.'"
                    >Tuits de @'.$this->TWITTER_USUARI.'</a>';
    }
    
    function GetTwitterAvatar($username){
        $xml = simplexml_load_file("http://twitter.com/users/".$username.".xml");
        $imgurl = $xml->profile_image_url;
        return $imgurl;
    }
    
    function GetTwitterAPILimit($username, $password){
        $xml = simplexml_load_file("http://$username:$password@twitter.com/account/rate_limit_status.xml");
        $left = $xml->{"remaining-hits"};
        $total = $xml->{"hourly-limit"};
        return $left."/".$total;
    }
}
?>
