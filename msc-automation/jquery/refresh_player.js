var myrefresh = function () {

    var refresh = document.getElementById('refresh').innerHTML;
    if (refresh == 1) {
        
        var r = (-0.5) + (Math.random() * (1000.99));
        key = msc_data.key,
        img_dir = msc_data.img_dir,
        img_url = msc_data.img_url,        
        jamendo_url = msc_data.jamendo_url,
        download_url = msc_data.download_url,
        url_podcast = msc_data.url_podcast,
        share_url = document.getElementById('URL_Share').innerHTML,        
        def_image = document.getElementById('def_image').innerHTML,
        path = msc_data.path;
        
        var urlRequest = path  + "wp-snippets/refresh_player.php?key=" + key + "&img_dir=" + img_dir + "&img_url=" + img_url + "&share_url=" + share_url + "&url_download=" + download_url + "&url_jamendo=" + jamendo_url + "&url_podcast=" + url_podcast + "&di=" + def_image + "&ram=" + r;
        
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
                    //$base_url_download = MSC_PLUGIN_URL.'inc/download.php?fileurl=';                                

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
                    document.getElementById('ifr').innerHTML = URL_Iframe;
                    if (t_remain < 1000) {
                        tmr = 15000;
                    } else {
                        tmr = t_remain + 1000;
                    }
                } else if (xmlhttp.status === 404) {
                }
            }
        }
        xmlhttp.open("GET", urlRequest, true);
        xmlhttp.send();
        //https://stackoverflow.com/questions/1280263/changing-the-interval-of-setinterval-while-its-running
        //setTimeout(myrefresh, tmr);
    }
};

var tmr = 15000;
setInterval(myrefresh, tmr);
//setTimeout(myrefresh, tmr);

function StopRefresh() {
    clearInterval(myrefresh);
    console.log('Stop refresh');
}
