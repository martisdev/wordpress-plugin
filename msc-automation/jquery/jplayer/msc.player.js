jQuery(document).ready(function(){
        
	// Local copy of jQuery selectors, for performance.
	var	my_jPlayer = jQuery("#jquery_jplayer"),
		my_trackName = jQuery("#jp_container .track-name"),
		my_playState = jQuery("#jp_container .play-state");
		//my_progressValue = jQuery("#jp_container .progress-value");
                
                //
	// Some options
	var	opt_play_first = true, // If true, will attempt to auto-play the default track on page loads. No effect on mobile devices, like iOS.
		opt_auto_play = true, // If true, when a track is selected, it will auto-play.
		opt_text_playing = "Now playing", // Text when playing
		opt_text_selected = "Track selected"; // Text when not playing
                

	// A flag to capture the first track
	var first_track = true;

	// Change the time format
        
        jQuery.jPlayer.timeFormat = {
            showHour: true,
            showMin: true,
            showSec: true,
            padHour: false,
            padMin: true,
            padSec: true,
            sepHour: ":",
            sepMin: ":",
            sepSec: ""
          };

	// Initialize the play state text
	my_playState.text(opt_text_selected);

	// Instance jPlayer
	my_jPlayer.jPlayer({
		ready: function () {
			jQuery("#jp_container .track-default").click();
		},
		timeupdate: function(event) {
                        jQuery("#jp_container .jp-ball").css("left",event.jPlayer.status.currentPercentAbsolute + "%");			
		},
		play: function(event) {
			my_playState.text(opt_text_playing);
		},
		pause: function(event) {
			my_playState.text(opt_text_selected);
		},
		ended: function(event) {
			my_playState.text(opt_text_selected);
		},		
                swfPath: "/jquery",
		cssSelectorAncestor: "#jp_container",
		supplied: "mp3",
		wmode: "window"
	});

    /* Modern Seeking */
    var timeDrag = false; /* Drag status */
    jQuery('.jp-play-bar').mousedown(function (event) {
        timeDrag = true;
        updatebar(event.pageX);
    });
    jQuery(document).mouseup(function (event) {
        if (timeDrag) {
            timeDrag = false;
            updatebar(event.pageX);
        }
    });
    jQuery(document).mousemove(function (event) {
        if (timeDrag) {
            updatebar(event.pageX);
        }
    });
    //update Progress Bar control
    var updatebar = function (x) {

        var progress = jQuery('.jp-progress');
        var maxduration = jQuery("#jquery_jplayer").jPlayer.duration; //audio duration        
        var position = x - progress.offset().left; //Click pos
        var percentage = 100 * position / progress.width();

        //Check within range
        if (percentage > 100) {
            percentage = 100;
        }
        if (percentage < 0) {
            percentage = 0;
        }

        jQuery("#jquery_jplayer").jPlayer("playHead", percentage);

        //Update progress bar and video currenttime
        
        jQuery('.jp-ball').css('left', percentage+'%');
        jQuery('.jp-play-bar').css('width', percentage + '%');
        jQuery("#jquery_jplayer").jPlayer.currentTime = maxduration * percentage / 100;
    };

    // Create click handlers for the different tracks
	jQuery("#jp_container .track").click(function(e) {		                
                my_trackName.text(jQuery(this).text());
		my_jPlayer.jPlayer("setMedia", {
			mp3: jQuery(this).attr("href")
		});
                
                play_position = jQuery(this).attr("data-pos");                                
                                    
                if((opt_play_first && first_track) || (opt_auto_play && !first_track)) {                                        
                    my_jPlayer.jPlayer("play",Number(play_position));    
		}                               
                my_url = jQuery(this).attr("href");                
                if(!my_url.includes('/stream')){                    
                    //S'ha de capturar el id i buscar resultats via XML
                    var id = jQuery(this).attr("data-pod");                                                                                                        
                    var r = (-0.5)+(Math.random()*(1000.99));                                        
                    var key         = document.getElementById('key').innerHTML
                    var img_dir     = document.getElementById('img_dir').innerHTML
                    var img_url     = document.getElementById('img_url').innerHTML
                    var share_url   = document.getElementById('url_share').innerHTML;                    
                    var download_url   = document.getElementById('url_download').innerHTML;
                    var url_podcast   = document.getElementById('url_podcast').innerHTML;                                
                    var path = document.getElementById("path").innerHTML+"wp-snippets/podcast_player.php?key="+key+"&img_dir="+img_dir+"&img_url="+img_url+"&share_url="+share_url+"&url_download="+download_url+"&id="+id+"&url_podcast="+url_podcast+"&ram=" +r;                                                                                    
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
                                document.getElementById('refresh').innerHTML = 0;
                            }else if(xmlhttp.status === 404){                                  
                            } 
                        }
                    }                    
                    xmlhttp.open("GET", path, true);
                    xmlhttp.send();                                                        
                    
                    //--------
                    jQuery('.jp-stream').css('display', 'inline'); 
                    //console.log(tmr);
                    //setInterval(myrefresh, tmr);
                    //clearInterval(myrefresh);
                    //StopRefresh();
                }else{                    
                    jQuery('.jp-stream').css('display', 'none');                                        
                    document.getElementById('refresh').innerHTML = 1;
                    //clearInterval(myrefresh);                    
                }                
		first_track = false;
		jQuery(this).blur();                
		return false;           
	});        
});

