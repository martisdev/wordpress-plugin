jQuery(document).ready(function () {
    
    var elmnt = document.getElementsByClassName('jp-stream');
    
    var nodes = [], values = [];
    var data_pos = 0;    
    var url_play = '';
    
    var c = 0;
    for(var i in elmnt) {
        if(c == elmnt.length) break;
        c ++;    
        var url_play = elmnt[i].getAttribute('data-href');
    }

    // Local copy of jQuery selectors, for performance.
    var my_jPlayer = jQuery("#jquery_jplayer");                
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

    // Initialize the play state text
    //my_playState.text(opt_text_selected);

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
    
})