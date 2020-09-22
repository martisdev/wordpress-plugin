<?php
echo '<div class="wrap">';
    echo '<h1>'._e('Edit basic pages for your Radio Automation System','msc-automation').'</h1>';
    $test_ini_option = get_option('msc_initialize','sth');
    
    if ($test_ini_option == 'sth'){
        $initialize_options = FALSE;        
    }else{        
        if($test_ini_option == 'false'){
            $initialize_options = FALSE;            
        }else{
            $initialize_options = TRUE;            
        }        
    }
    
    if($initialize_options == FALSE ){
        // Check whether the button has been pressed AND also check the nonce
        if (isset($_POST['ini_progs']) && check_admin_referer('ini_progs_clicked')) {
            // the button has been pressed AND we've passed the security check      
            ini_all_action();        
        }else{
            echo '<form name="ini_progs-submit" method="post">';    
            wp_nonce_field('ini_progs_clicked');
            ?>
                <table class="form-table">                  
                <tr valign="top">
                    <th scope="row">                                                       
                        <label for="create_home"><?php _e('Home page','msc-automation')?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="create_home" id="create_home" value="true" checked="checked"/>
                        <p class="description"><?php _e('Create the static page whith actual info of your radio','msc-automation')?></p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">                                                       
                        <label for="create_news"><?php _e('News page','msc-automation')?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="create_news" id="create_news" value="true" checked="checked"/>
                        <p class="description"><?php _e('Create the static page for news (wordpress posts)','msc-automation')?></p>
                    </td>
                </tr>                
                <tr valign="top">
                    <th scope="row">                                                       
                        <label for="create_calendar"><?php _e('Calendar page','msc-automation')?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="create_calendar" id="create_calendar" value="true" checked="checked"/>
                        <p class="description"><?php _e('Create the calendar of radiation','msc-automation')?></p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">                                                       
                        <label for="create_podcast"><?php _e('On Demand page','msc-automation')?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="create_podcast" id="create_podcast" value="true" checked="checked"/>
                        <p class="description"><?php _e('Create the On Demand page','msc-automation')?></p>
                    </td>
                </tr>                
                <!-- -->
                <tr valign="top">
                    <th scope="row">                                                       
                        <label for="create_search"><?php _e('Search music','msc-automation')?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="create_search" id="create_search" value="true" checked="checked"/>
                        <p class="description"><?php _e('Create the Search music page','msc-automation')?></p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">                                                       
                        <label for="create_new_album"><?php _e('Albums release','msc-automation')?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="create_new_album" id="create_new_album" value="true" checked="checked"/>
                        <p class="description"><?php _e('Create the Albums release page','msc-automation')?></p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">                                                       
                        <label for="create_history_play"><?php _e('History played','msc-automation')?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="create_history_play" id="create_history_play" value="true" checked="checked"/>
                        <p class="description"><?php _e('Create the History played page','msc-automation')?></p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">                                                       
                        <label for="create_vote_payer"><?php _e('Vote music to play','msc-automation')?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="create_vote_payer" id="create_vote_payer" value="true" checked="checked"/>
                        <p class="description"><?php _e('Create the Vote music to player page ','msc-automation')?></p>
                    </td>
                </tr>
                
                </table> 
                <input type="hidden" value="true" name="ini_progs" />
            <?php
            submit_button(_e('Inicialize all system','msc-automation'));
            echo '</form>';
        }
        
    }else{
        if (!empty($_POST['reset_progs'])&& check_admin_referer('reset_progs_clicked')) {
            reset_system();            
            echo '<div id="message" class="updated fade">
                <p>'._e('Removed menus and pages created','msc-automation').'</p></div>';
        }else{
            echo '<form name="reset-prg" method="post">';    
            wp_nonce_field('reset_progs_clicked');
            echo '<input type="hidden" value="true" name="reset_progs" />';
            submit_button(_e('Reset all system','msc-automation'));
            echo '</form>';            
        }        
    }
echo '</div>';
  