function mscra_displayList(myParent, myElement) {
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

function mscra_ShowModalShare() {
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

function mscra_ShowIframeCode() {    
    var ifra = document.getElementById("iframe");
    if (ifra.style.display == "none") {
        ifra.style.display = "block";
    } else {
        ifra.style.display = "none";
    }
}

function mscra_LikeTrack() {
    var key = msc_data.key,
        hear = document.getElementById('like'),
        share = document.getElementById('BtnShare'),
        id = document.getElementById('ID').innerHTML,
        type = document.getElementById('IDTYPE').innerHTML;

    var xmlhttp = new XMLHttpRequest();
    var path = msc_data.path + "wp-snippets/refresh_like.php?id=" + id + "&type=" + type + "&key=" + key;        
    
    if (hear.style.color !== share.style.color) {
        hear.style.color = share.style.color;        
        //unregister the vote                            
        path += '&val=0';        
    } else {
        hear.style.color = 'red' ;
        //register the vote         
        path += '&val=1';        
    }
    xmlhttp.open("GET", path, true);
    xmlhttp.send();
}