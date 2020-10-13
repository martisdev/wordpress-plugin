<?php //Used in functions: get_search_music, ?>
 <H3><?php _e('Enter the title, artist or album','mscra-automation')?></H3>                                                
                             
<FORM class="search-form" METHOD=GET ACTION="<?php echo get_permalink();?>" align=center >
    <p><label for="q"><?php _e('Text','mscra-automation')?></label>
    <input name='q' type='text' size="70%" value="<?php echo $strsql;?>">
    <input type=hidden  name=sql value="<?php echo $strsql;?>"></p>
    <p align=center><input type='submit' value="<?php _e('Search','mscra-automation')?>" ></p>
</form>                        

