function displayList(myParent ,myElement) {        
    if (myElement.style.display === "none") {      
      myElement.style.display = "inline";     
    } else {
      myElement.style.display = "none";     
    }        
    
    if ( myParent.className == "fas fa-plus-square"  ) {        
        myParent.className =  "fas fa-minus-square";                                            
    } else {
        myParent.className =  "fas fa-plus-square";                
    } 
}

