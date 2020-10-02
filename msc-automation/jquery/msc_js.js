function displayList(myParent, myElement) {
    if (myElement.style.display === "none") {
        myElement.style.display = "inline";
    } else {
        myElement.style.display = "none";
    }

    if (myParent.className == "fas fa-plus-square") {
        myParent.className = "fas fa-minus-square";
    } else {
        myParent.className = "fas fa-plus-square";
    }
}

function ShowModalshare() {
    // Get the modal
    var modal = document.getElementById("myModalShare");

    // Get the button that opens the modal
    var btn = document.getElementById("BtnShare");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("closeShare")[0];

    // When the user clicks the button, open the modal 
    btn.onclick = function () {
        modal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    span.onclick = function () {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
}

function ShowIframeCode() {
    //function ShowIframeCode(elmnt) {
    //var id = elmnt.data("pod"); 
    //alert(id);
    //var ifra = document.getElementById("iframe-"+id);
    var ifra = document.getElementById("iframe");
    if (ifra.style.display == "none") {
        ifra.style.display = "block";
    } else {
        ifra.style.display = "none";
    }
}

function LikeTrack() {
    var key = msc_data.key,
            hear = document.getElementById("like"),
            id = document.getElementById('ID').innerHTML,
            type = document.getElementById('IDTYPE').innerHTML;
    var xmlhttp = new XMLHttpRequest();
    if (hear.style.color !== 'red') {
        //hear.style.color = 'initial';
        hear.style.color = 'red';
        //register the vote recollir tipus de fitxer i id 
        var path = msc_data.path + "wp-snippets/refresh_like.php?id=" + id + "&type=" + type + "&val=1&key=" + key;
        console.log(path);
    } else {
        hear.style.color = 'inherit';
        //unregister the vote                    
        var path = msc_data.path + "wp-snippets/refresh_like.php?id=" + id + "&type=" + type + "&val=0&key=" + key;
        console.log(path);
    }
    xmlhttp.open("GET", path, true);
    xmlhttp.send();
}

function playThisFile(elmnt) {
    //elmnt.style.color = 'red';
    elmnt.style.fontWeight="bold";
    var my_text = elmnt.text;
    var nodes = [], values = [];
    for (var att, i = 0, atts = elmnt.attributes, n = atts.length; i < n; i++) {
        att = atts[i];
        nodes.push(att.nodeName);
        values.push(att.nodeValue);
        if (att.nodeName == "data-pos") {
            var data_pos = att.nodeValue;
        }
        if (att.nodeName == "data-pod") {
            var data_pod = att.nodeValue;
        }
        if (att.nodeName == "data-href") {
            var data_href = att.nodeValue;
        }
    }
    
    var my_jPlayer = jQuery("#jquery_jplayer"),
            my_trackName = jQuery("#jp_container .track-name");
    // Some options
    var opt_play_first = true, // If true, will attempt to auto-play the default track on page loads. No effect on mobile devices, like iOS.
            opt_auto_play = true; // If true, when a track is selected, it will auto-play.
    //opt_text_playing = "Now playing", // Text when playing
    //opt_text_selected = "Track selected"; // Text when not playing

    // A flag to capture the first track
    var first_track = true;
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
    my_trackName.text(my_text);
    my_jPlayer.jPlayer("setMedia", {
        mp3: data_href
    });        
    if ((opt_play_first && first_track) || (opt_auto_play && !first_track)) {
        my_jPlayer.jPlayer("play", Number(data_pos));
    }    
    if (!data_href.includes('/stream')) {
        //S'ha de capturar el id i buscar resultats via XML                    
        var id = data_pod;
        var r = (-0.5) + (Math.random() * (1000.99));
        var key = msc_data.key;
        var img_dir = msc_data.img_dir;
        var img_url = msc_data.img_url;
        var share_url = msc_data.share_url;
        var def_img = msc_data.def_image;
        var download_url = msc_data.download_url;
        var url_podcast = msc_data.path;
        var path = msc_data.path;
        var urlRequest = path + "wp-snippets/podcast_player.php?key=" + key + "&img_dir=" + img_dir + "&img_url=" + img_url + "&share_url=" + share_url + "&url_download=" + download_url + "&id=" + id + "&url_podcast=" + url_podcast + "&di=" + def_img + "&ram=" + r;
        
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

                    URL_Facebook = "https://www.facebook.com/sharer/sharer.php?u=" + URL_Share[0].childNodes[0].nodeValue + "&t=" + titles[0].childNodes[0].nodeValue;
                    URL_twitter = 'https://twitter.com/share?url=' + URL_Share[0].childNodes[0].nodeValue + '&via=TWITTER_HANDLE&text=' + titles[0].childNodes[0].nodeValue;
                    URL_Iframe = '<iframe src="' + URL_Share[0].childNodes[0].nodeValue + '" allowfullscreen scrolling="no" frameborder="0" width="270px" height="370px"></iframe>';

                    document.getElementById('jp_title').innerHTML = titles[0].childNodes[0].nodeValue;
                    document.getElementById("jp_subtitle-name").innerHTML = subtit[0].childNodes[0].nodeValue;
                    document.getElementById("jp-image-src").src = img[0].childNodes[0].nodeValue;

                    document.getElementById('download').href = URL_download[0].childNodes[0].nodeValue;
                    if (URL_download[0].childNodes[0].nodeValue.length > 3)
                    {
                        document.getElementById('download').style.display = "inline";
                    } else {
                        document.getElementById('download').style.display = "none";
                    }
                    document.getElementById('fb').href = URL_Facebook;
                    document.getElementById('tw').href = URL_twitter;
                    document.getElementById('ifr').innerHTML = URL_Iframe;

                    if (t_remain < 1000) {
                        tmr = 15000;
                    } else {
                        tmr = t_remain + 1000;
                    }
                    document.getElementById('refresh').innerHTML = 0;
                } else if (xmlhttp.status === 404) {
                    
                }
            }
        }
        xmlhttp.open("GET", urlRequest, true);
        xmlhttp.send();

        //--------
        jQuery('.jp-stream').css('display', 'inline');
        //console.log(tmr);
        //setInterval(myrefresh, tmr);
        //clearInterval(myrefresh);
        //StopRefresh();
    } else {
        jQuery('.jp-stream').css('display', 'none');
        document.getElementById('refresh').innerHTML = 1;
       clearInterval(myrefresh);                    
    }
    first_track = false;
    //jQuery(this).blur();                
    //return false;
}