<?php //Used in functions: get_search_music, ?>
 <H3><?php _e('Enter the title, artist or album','msc-automation')?></H3>                                                
                             
<FORM METHOD=POST ACTION="<?php echo get_permalink();?>" align=center >
    <p><label for="strsql"><?php _e('Text','msc-automation')?></label>
    <input name='strsql' type='text' size="70%" value="<?php echo $strsql;?>">
    <input type=hidden  name=sql value="<?php echo $strsql;?>"></p>
    <p align=center><input name='send' type='submit' value='<?php _e('Search','msc-automation')?>'></p>
</form>                        

