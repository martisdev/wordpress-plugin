<?php 
    //Used in functions: get_search_music, 
    $strReturn .= '<H3>'.__('Enter the title, artist or album','mscra-automation').'</H3>';
    $strReturn .= '<FORM class="search-form" METHOD=GET ACTION="'. get_permalink().'" align=center >
    <p><label for="q">'. __('Text','mscra-automation').'</label>
    <input name="q" type="text" size="70%" value="'. $strsql.'" required><p>    
    <p align=center><button type="submit">'.__('Search','mscra-automation').'</button></p>
    </form> ';   
?>