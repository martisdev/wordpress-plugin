<?php
    // Constants del FaceBook
    // Layout Style
    interface LayoutSyle{
        const LAYOUT_STYLE_STANDARD = "standard";
        const LAYOUT_STYLE_BOX_COUNT = "box_count";
        const LAYOUT_STYLE_BUTTON_COUNT = "button_count";
        const LAYOUT_STYLE_BUTTON = "button";        
    }
    

   
    // Pel ShareBotton
    interface ShareBottonStyle{
        const SHARE_BOX_COUNT = "box_count";
        const SHARE_BUTTON_COUNT = "button_count";
        const SHARE_BUTTON = "button";
        const SHARE_ICON_LINK = "icon_link";
        const SHARE_ICON = "icon";
        const SHARE_LINK = "link";        
    }

    // Pel Color Scheme
    INTERFACE ColorScheme{
        const COLOR_SCHEME_LIGHT = "light";
        const COLOR_SCHEME_DARK = "dark";
    }

    // End FaceBook
       
    class Facebook{ 
    
    var $URL_FACEBOOK_CLIENT =  '';
    /**
     * Crea la clase i incorpora el codi a la web
     * @param type $url_fb URL to comment on
     */
    function __construct($url_fb) {
       $this->URL_FACEBOOK_CLIENT = $url_fb; 
       echo '<script>(function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/ca_ES/all.js#xfbml=1";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, "script", "facebook-jssdk"));
       </script> ';
    }
    
    //Funció finalitza
    function __destruct() {
    }       
    /**
     * The Like button is the quickest way for people to share content with their friends.
     * @param type $LayoutStyle Selects one of the different layouts that are available for the plugin. Can be one of "standard", "button_count", or "box_count". See the FAQ for more details.
     * @param type $width The width of the plugin. The layout you choose affects the minimum and default widths you can use, please see the FAQ below for more details (min 55).
     * @param type $ShowFaces Ensenya les cares de els seguidors
     * @param type $ShareButton Ensenya el botó compartir
     */
    function show_LikeButton($LayoutStyle,$width='',$ShowFaces = false,$ShareButton= false){

        $StrDIV = '<div class="fb-like" 
                    data-href="'.$this->URL_FACEBOOK_CLIENT.'" ' ;
        if ($width<>''){
           $StrDIV = $StrDIV.'data-width="'.$width.'" ' ;
        }                                     
        $StrDIV = $StrDIV.' data-layout="'.$LayoutStyle.'" 
                    data-action="like"         
                    data-show-faces="'.$this->strbool($ShowFaces).'" 
                    data-share="'.$this->strbool($ShareButton).'"></div>';
        return $StrDIV;        
    }
    
    /**
     * The Share button lets people add a personalized message to links before sharing on their timeline, in groups, or to their friends via a Facebook Message. If your app is native to iOS or Android, we recommend that you use the native Share Dialog on iOS and Share Dialog on Android instead.
     * @param type $Style Selects one of the different layouts that are available for the plugin. Can be one of "box_count", "button_count", "button", or "icon".
     * @param type $width The width of the plugin. The layout you choose affects whether the width value will have any effect.
     */
    function show_ShareButton($Style,$width){
        return '<div class="fb-share-button" 
                        data-href="'.$this->URL_FACEBOOK_CLIENT.'" 
                        data-width="'.$width.'" 
                        data-type="'.$Style.'"></div>';
    }
    
   /**
    * The Send button lets people privately send content on your site to one or more friends in a Facebook message, to an email address, or share it with a Facebook group.
    * @param type $colorscheme The color scheme used by the plugin. Can be "light" or "dark".
    * @param type $width
    * @param type $height
    */
    function show_SendButton($colorscheme,$width,$height){
        return '<div class="fb-send" 
                    data-href="'.$this->URL_FACEBOOK_CLIENT.'" 
                    data-width="'.$width.'" 
                    data-height="'.$height.'" 
                    data-colorscheme="'.$colorscheme.'"></div>';
    }
    
    
    /**
     * Embedded Posts are a simple way to put public posts - by a Page or a person on Facebook - into the content of your web site or web page. Only public posts from Facebook Pages and profiles can be embedded.
     * @param type $width The pixel width of the post (between 350 and 750)
     */
    function show_EmbeddedPosts($width){
        return '<div class="fb-post" 
                    data-href="'.$this->URL_FACEBOOK_CLIENT.'" 
                    data-width="'.$width.'"></div>';        
    }
    
    /**
     * The Follow button lets people subscribe to the public updates of others on Facebook
     * @param type $colorscheme Color Scheme
     * @param type $width Width
     * @param type $height Height
     * @param type $LayoutStyle Layout Style
     * @param type $ShowFaces Ensenya les cares dels seguidors
     */
    function show_FollowButton($colorscheme,$width,$height,$LayoutStyle,$ShowFaces = false){        
        return '<div class="fb-follow" 
                    data-href="'.$this->URL_FACEBOOK_CLIENT.'" 
                    data-colorscheme="'.$colorscheme.'" 
                    data-layout="'.$LayoutStyle.'" 
                    data-show-faces="'.$this->strbool($ShowFaces).'" 
                    data-width="'.$width.'" 
                    data-height="'.$height.'"></div>';
    }
    
    /**
     * The Comments box lets people comment on content 
     * on your site using their Facebook profile and shows this activity to their 
     * friends in news feed. It also contains built-in moderation tools and special 
     * social relevance ranking.
     * @param type $numpost Number of Posts
     * @param type $width The width of the plugin in pixels.
     * @param type $colorscheme The color scheme used by the plugin. Can be "light" or "dark".
     */ 
    function show_Comments($numpost,$width,$colorscheme){
        return '<div class="fb-comments" 
                    data-href="'.$this->URL_FACEBOOK_CLIENT.'" 
                    data-numposts="'.$numpost.'"
                    data-width="'.$width.'" 
                    data-colorscheme="'.$colorscheme.'"></div>';
    }

    
    /**
     * The Like Box is a special version of the Like Button designed only for Facebook Pages.
     *  It allows admins to promote their Pages and embed a simple feed of content from a 
     * Page into other sites.
     * @param type $colorscheme The color scheme used by the plugin. Can be "light" or "dark".
     * @param type $width The width of the plugin in pixels. Minimum is 292.
     * @param type $height The height of the plugin in pixels. 
     * The default height varies based on number of faces to display, 
     * and whether the stream is displayed. With stream set to true and 10 photos displayed 
     * (via show_faces) the default height is 556px. 
     * With stream and show_faces both false, the default height is 63px.
     */
    function show_LikeBox($colorscheme,$width,$height,
                            $ShowFaces=false,$header=false,$stream=false,$showborder=true){
                
        return '<div class="fb-like-box" 
                    data-href="'.$this->URL_FACEBOOK_CLIENT.'" 
                    data-width="'.$width.'" 
                    data-height="'.$height.'" 
                    data-colorscheme="'.$colorscheme.'" 
                    data-show-faces="'.$this->strbool($ShowFaces).'" 
                    data-header="'.$this->strbool($header) .'" 
                    data-stream="'.$this->strbool($stream) .'" 
                    data-show-border="'.$this->strbool($showborder).'"></div>';
    }
       
    
    protected function strbool($value)
    {
        return $value ? 'true' : 'false';
    }
    
   /* function show_ActivityFeed(){
        
    }*/
    
    /*function show_RecommendationsFeed(){
        
    }*/
    
    /*function show_RecommendationsBar(){
        
    }*/
}
?>
