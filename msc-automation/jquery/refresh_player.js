function mscra_PlayThisFile(elmnt) {
    
    //var my_text = elmnt.text;
    var nodes = [], values = [];
    var data_pos = 0;
    var id_pod = 0;
    var url_play = '';
    for (var att, i = 0, atts = elmnt.attributes, n = atts.length; i < n; i++) {
        att = atts[i];
        nodes.push(att.nodeName);
        values.push(att.nodeValue);
        if (att.nodeName == "data-pos") {
            data_pos = att.nodeValue;
        }
        if (att.nodeName == "data-pod") {
            id_pod = att.nodeValue;
        }
        if (att.nodeName == "data-href") {
            url_play = att.nodeValue;
        }
    }

    var my_jPlayer = jQuery("#jquery_jplayer");
    //var my_trackName = jQuery("#jp_container .track-name");
    // Some options
    var opt_play_first = true; // If true, will attempt to auto-play the default track on page loads. No effect on mobile devices, like iOS.
    var opt_auto_play = true; // If true, when a track is selected, it will auto-play.    

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
        sepHour: ':',
        sepMin: ':',
        sepSec: ''
    };
    
    // Instance jPlayer
    my_jPlayer.jPlayer({
        ready: function () {
            jQuery("#jp_container .track-default").click();
        },
        timeupdate: function (event) {
            jQuery("#jp_container .jp-ball").css("left", event.jPlayer.status.currentPercentAbsolute + "%");
        },
        play: function (event) {
            //my_playState.text(opt_text_playing);
        },
        pause: function (event) {
            //my_playState.text(opt_text_selected);
        },
        ended: function (event) {
            //my_playState.text(opt_text_selected);
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
        jQuery('.jp-ball').css('left', percentage + '%');
        jQuery('.jp-play-bar').css('width', percentage + '%');
        jQuery("#jquery_jplayer").jPlayer.currentTime = maxduration * percentage / 100;        
    };
    //my_trackName.text(my_text);
    my_jPlayer.jPlayer("setMedia", {
        mp3: url_play
    });
    if ((opt_play_first && first_track) || (opt_auto_play && !first_track)) {
        my_jPlayer.jPlayer("play", Number(data_pos));
    }

    var r = (-0.5) + (Math.random() * (1000.99));
    var key = msc_data.key;
    var img_dir = msc_data.img_dir;
    var img_url = msc_data.img_url//document.getElementById('img_url').innerHTML
    var download_url = msc_data.download_url;
    var url_podcast = msc_data.url_podcast;
    var share_url = msc_data.share_url;
    var def_image = msc_data.def_image;
    var path = msc_data.path;
    
    var IsPodcast = url_play.includes('.mp3');    
    if (IsPodcast == true) {        
        var elements = document.getElementsByClassName("fpod");
        for (var i = 0; i < elements.length; i++) {
            elements[i].style.fontWeight = "normal";
        }
        elmnt.style.fontWeight = "bold";
        document.getElementById('refresh').innerHTML = 0;
        jQuery('.jp-stream').css('display', 'inline');        
        //S'ha de capturar el id i buscar resultats via XML                            
        var urlRequest = path + "wp-snippets/refresh_player_podcast.php?key=" + key + "&img_dir=" + img_dir + "&img_url=" + img_url + "&share_url=" + share_url + "&url_download=" + download_url + "&id=" + id_pod + "&url_podcast=" + url_podcast + "&di=" + def_image + "&ram=" + r;
        //mscra_StopRefresh();
    } else {
        document.getElementById('refresh').innerHTML = 1;
        jQuery('.jp-stream').css('display', 'none');                
        var jamendo_url = msc_data.jamendo_url;
        var urlRequest = path + "wp-snippets/refresh_player_live.php?key=" + key + "&img_dir=" + img_dir + "&img_url=" + img_url + "&share_url=" + share_url + "&url_download=" + download_url + "&url_jamendo=" + jamendo_url + "&url_podcast=" + url_podcast + "&di=" + def_image + "&ram=" + r;        
    }        
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState < 4) {
            //document.getElementById('demo').innerHTML = "Loading...";                            
        } else if (xmlhttp.readyState === 4) {
            if (xmlhttp.status === 200 && xmlhttp.status < 300)
            {
                xmlDoc = xmlhttp.responseXML;
                titles = xmlDoc.getElementsByTagName("title");
                subtit = xmlDoc.getElementsByTagName("subtitle");
                img = xmlDoc.getElementsByTagName("image");
                t_remain = xmlDoc.getElementsByTagName("remain");
                URL_Share = xmlDoc.getElementsByTagName("url_share");
                URL_download = xmlDoc.getElementsByTagName("url_download");

                my_title = titles[0].childNodes[0].nodeValue;
                my_subtitle = subtit[0].childNodes[0].nodeValue;
                var descrip = my_title + ' - ' + my_subtitle;


                URL_Facebook = "https://www.facebook.com/sharer/sharer.php?u=" + URL_Share[0].childNodes[0].nodeValue + "&t=" + descrip;
                URL_twitter = 'https://twitter.com/share?url=' + URL_Share[0].childNodes[0].nodeValue + '&via=TWITTER_HANDLE&text=' + descrip;
                URL_Pinterest = 'https://pinterest.com/pin/create/button/?&url=' + URL_Share[0].childNodes[0].nodeValue + '&description=' + descrip;
                URL_LinkedIn = 'https://www.linkedin.com/shareArticle?mini=true&url=' + URL_Share[0].childNodes[0].nodeValue + '&title=' + descrip;
                URL_WhatsApp = 'https://wa.me/?text=' + descrip + '+-+' + URL_Share[0].childNodes[0].nodeValue;
                URL_Iframe = '<iframe src="' + URL_Share[0].childNodes[0].nodeValue + '" allowfullscreen scrolling="no" frameborder="0" width="270px" height="370px"></iframe>';

                //Title page
                document.getElementById('jp_title').innerHTML = my_title;
                document.getElementById("jp_subtitle-name").innerHTML = my_subtitle;
                document.title = my_title + ' | ' + my_subtitle

                //Image page
                my_img = img[0].childNodes[0].nodeValue;
                document.getElementById("jp-image-src").src = my_img;
                var link = document.querySelector("link[rel*='icon']") || document.createElement('link');
                link.type = 'image/x-icon';
                link.rel = 'icon';
                link.href = my_img;
                document.getElementsByTagName('head')[0].appendChild(link);                
                document.getElementById('download').href = URL_download[0].childNodes[0].nodeValue;
                if (URL_download[0].childNodes[0].nodeValue.length > 3)
                {
                    document.getElementById('download').style.display = "inline";
                } else {
                    document.getElementById('download').style.display = "none";
                }
                 
                
                document.getElementById("like").style.color = 'inherit';
                document.getElementById('fb').href = URL_Facebook;
                document.getElementById('tw').href = URL_twitter;
                document.getElementById('pt').href = URL_Pinterest;
                document.getElementById('li').href = URL_LinkedIn;
                document.getElementById('wa').href = URL_WhatsApp;
                document.getElementById('ifr').innerHTML = URL_Iframe;
                    
                //mscra_ChangeInterval(t_remain);                   
            } else if (xmlhttp.status === 404) {
                //Page Not Found
            }
        }
    }
    xmlhttp.open("GET", urlRequest, true);
    xmlhttp.send();
    first_track = false;
    
    //https://stackoverflow.com/questions/1280263/changing-the-interval-of-setinterval-while-its-running
    //setTimeout(mscra_refreshPlayer, tmr);
        
    //jQuery(this).blur();                
    //return false;
}


var mscra_refreshPlayer = function () {
    var refresh = document.getElementById('refresh').innerHTML;
    if (refresh == 1) {        
        var r = (-0.5) + (Math.random() * (1000.99));
        key = msc_data.key,
        img_dir = msc_data.img_dir,
        img_url = msc_data.img_url,        
        jamendo_url = msc_data.jamendo_url,
        download_url = msc_data.download_url,
        url_podcast = msc_data.url_podcast,
        share_url = msc_data.share_url,        
        def_image = msc_data.def_image,
        path = msc_data.path;
        
        var urlRequest = path + "wp-snippets/refresh_player_live.php?key=" + key + "&img_dir=" + img_dir + "&img_url=" + img_url + "&share_url=" + share_url + "&url_download=" + download_url + "&url_jamendo=" + jamendo_url + "&url_podcast=" + url_podcast + "&di=" + def_image + "&ram=" + r;        
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState < 4) {
                //document.getElementById('demo').innerHTML = "Loading...";                            
            } else if (xmlhttp.readyState === 4) {
                if (xmlhttp.status === 200 && xmlhttp.status < 300)
                {
                    xmlDoc = xmlhttp.responseXML;
                    titles = xmlDoc.getElementsByTagName("title");
                    subtit = xmlDoc.getElementsByTagName("subtitle");
                    my_title = titles[0].childNodes[0].nodeValue;
                    my_subtitle = subtit[0].childNodes[0].nodeValue;
                    img = xmlDoc.getElementsByTagName("image");
                    t_remain = xmlDoc.getElementsByTagName("remain");
                    URL_Share = xmlDoc.getElementsByTagName("url_share");
                    URL_download = xmlDoc.getElementsByTagName("url_download");
                    
                    var descrip = my_title + ' - '+ my_subtitle;
                    URL_Facebook = "https://www.facebook.com/sharer/sharer.php?u=" + URL_Share[0].childNodes[0].nodeValue + "&t=" + descrip;
                    URL_twitter = 'https://twitter.com/share?url=' + URL_Share[0].childNodes[0].nodeValue + '&via=TWITTER_HANDLE&text=' + descrip;                    
                    URL_Pinterest = 'https://pinterest.com/pin/create/button/?&url=' + URL_Share[0].childNodes[0].nodeValue + '&description=' + descrip ;
                    URL_LinkedIn = 'https://www.linkedin.com/shareArticle?mini=true&url=' + URL_Share[0].childNodes[0].nodeValue + '&title=' + descrip;
                    URL_WhatsApp = 'https://wa.me/?text=' + descrip + '+-+' + URL_Share[0].childNodes[0].nodeValue;                    
                    URL_Iframe = '<iframe src="' + URL_Share[0].childNodes[0].nodeValue + '" allowfullscreen scrolling="no" frameborder="0" width="270px" height="370px"></iframe>';
                    //Title page
                    
                    document.getElementById('jp_title').innerHTML = my_title;
                    document.getElementById("jp_subtitle-name").innerHTML = my_subtitle;
                    document.title = my_title + ' | ' + my_subtitle

                    //Image page
                    my_img = img[0].childNodes[0].nodeValue;
                    document.getElementById("jp-image-src").src = my_img;
                    var link = document.querySelector("link[rel*='icon']") || document.createElement('link');
                    link.type = 'image/x-icon';
                    link.rel = 'icon';
                    link.href = my_img;
                    document.getElementsByTagName('head')[0].appendChild(link);                    
                    document.getElementById('download').href = URL_download[0].childNodes[0].nodeValue;
                    if (URL_download[0].childNodes[0].nodeValue.length > 3)
                    {
                        document.getElementById('download').style.display = "inline";
                    } else {
                        document.getElementById('download').style.display = "none";
                    }
                    document.getElementById("like").style.color = 'inherit';
                    document.getElementById('fb').href = URL_Facebook;
                    document.getElementById('tw').href = URL_twitter;
                    document.getElementById('pt').href = URL_Pinterest;
                    document.getElementById('li').href = URL_LinkedIn;
                    document.getElementById('wa').href = URL_WhatsApp;
                    document.getElementById('ifr').innerHTML = URL_Iframe;
                    t_remain = t_remain[0].childNodes[0].nodeValue
                    if (t_remain < 1000) {
                        tmr = t_remain;
                    } else {
                        tmr = t_remain + 1000;
                    }
                    //const now = new Date();
                    //console.log('Time remain:'+t_remain+' date: '+now.getTime());
                    //mscra_ChangeInterval(tmr);
                } else if (xmlhttp.status === 404) {
                }
            }
        }
        xmlhttp.open("GET", urlRequest, true);
        xmlhttp.send();
        //https://stackoverflow.com/questions/1280263/changing-the-interval-of-setinterval-while-its-running        
    }
};

//setTimeout(mscra_refreshPlayer, tmr);

setInterval(mscra_refreshPlayer, 15000)

function mscra_ChangeInterval(tmr){
    setInterval(mscra_refreshPlayer, tmr);
    console.log('Change interval:'+tmr);
}

function mscra_StopRefresh() {
    clearInterval(mscra_refreshPlayer);
    console.log('Stop refresh');
}