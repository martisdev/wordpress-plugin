jQuery(document).ready(function(){                
    var meta = document.createElement('meta');
    meta.setAttribute('property','og:image');
    meta.content = object_params.i;
    document.getElementsByTagName('head')[0].appendChild(meta);

    var meta = document.createElement('meta');
    meta.setAttribute('property','og:title');
    meta.content = object_params.t;
    document.getElementsByTagName('head')[0].appendChild(meta);

    var meta = document.createElement('meta');
    meta.setAttribute('property','og:url');
    meta.content = document.location.href;
    document.getElementsByTagName('head')[0].appendChild(meta);

    var meta = document.createElement('meta');
    meta.setAttribute('property','og:type');
    meta.content = "music.song";
    document.getElementsByTagName('head')[0].appendChild(meta);
    document.title =  object_params.t;

    var link = document.querySelector("link[rel*='icon']") || document.createElement('link');
    link.type = 'image/x-icon';
    link.rel = 'icon';
    link.href = object_params.i;
    document.getElementsByTagName('head')[0].appendChild(link);
    
});