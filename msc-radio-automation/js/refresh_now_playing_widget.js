var auto_refresh = setInterval(
    function () {
        var r = (-0.5) + (Math.random() * (1000.99));
        var key = msc_data.key;
        var img_dir = msc_data.img_dir;
        var img_url = msc_data.img_url;
        var download_url = window.location.href;
        var url_podcast = msc_data.url_podcast;
        var share_url = msc_data.share_url;
        var def_image = msc_data.def_image;
        var path = msc_data.path;
        var jamendo_url = msc_data.jamendo_url;

        var urlRequest = path + "wp-snippets/refresh_player_live.php?key=" + key + "&img_dir=" + img_dir + "&img_url=" + img_url + "&share_url=" + share_url + "&url_download=" + download_url + "&url_jamendo=" + jamendo_url + "&url_podcast=" + url_podcast + "&di=" + def_image + "&ram=" + r;
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState < 4) {
                //document.getElementById('demo').innerHTML = "Loading...";                            
            } else if (xmlhttp.readyState === 4) {
                xmlDoc = xmlhttp.responseXML;
                titles = xmlDoc.getElementsByTagName("title");
                subtit = xmlDoc.getElementsByTagName("subtitle");
                img = xmlDoc.getElementsByTagName("image");
                t_remain = xmlDoc.getElementsByTagName("remain");
                document.getElementById('artist-refresh').innerHTML = my_title;
                document.getElementById("song-refresh").innerHTML = my_subtitle;
                //Image page
                my_img = img[0].childNodes[0].nodeValue;
                document.getElementById("img-refresh").src = my_img;
            } else if (xmlhttp.status === 404) {
                //Page Not Found
            }
        }
        xmlhttp.open("GET", urlRequest, true);
        xmlhttp.send();
    }, 15000); // refresh every 10000 milliseconds


