<?php
echo '<div class="wrap">';
    echo '<h1>'.__('Edit basic pages for your Radio Automation System','mscra-automation').'</h1>';
    $test_ini_option = get_option('mscra_initialize','sth');
    
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
            mscra_ini_all_action();        
        }else{
            echo '<form name="ini_progs-submit" method="post">';    
            wp_nonce_field('ini_progs_clicked');
            ?>
                <table class="form-table">                  
                <tr valign="top">
                    <th scope="row">                                                       
                        <label for="create_home"><?php _e('Home page','mscra-automation')?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="create_home" id="create_home" value="true" checked="checked"/>
                        <p class="description"><?php _e('Create the static page whith actual info of your radio','mscra-automation')?></p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">                                                       
                        <label for="create_news"><?php _e('News page','mscra-automation')?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="create_news" id="create_news" value="true" checked="checked"/>
                        <p class="description"><?php _e('Create the static page for news (wordpress posts)','mscra-automation')?></p>
                    </td>
                </tr>                
                <tr valign="top">
                    <th scope="row">                                                       
                        <label for="create_calendar"><?php _e('Calendar page','mscra-automation')?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="create_calendar" id="create_calendar" value="true" checked="checked"/>
                        <p class="description"><?php _e('Create the calendar of radiation','mscra-automation')?></p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">                                                       
                        <label for="create_podcast"><?php _e('On Demand page','mscra-automation')?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="create_podcast" id="create_podcast" value="true" checked="checked"/>
                        <p class="description"><?php _e('Create the On Demand page','mscra-automation')?></p>
                    </td>
                </tr>                
                <!-- -->
                <tr valign="top">
                    <th scope="row">                                                       
                        <label for="create_search"><?php _e('Search music','mscra-automation')?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="create_search" id="create_search" value="true" checked="checked"/>
                        <p class="description"><?php _e('Create the Search music page','mscra-automation')?></p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">                                                       
                        <label for="create_new_album"><?php _e('Albums release','mscra-automation')?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="create_new_album" id="create_new_album" value="true" checked="checked"/>
                        <p class="description"><?php _e('Create the Albums release page','mscra-automation')?></p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">                                                       
                        <label for="create_history_play"><?php _e('History played','mscra-automation')?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="create_history_play" id="create_history_play" value="true" checked="checked"/>
                        <p class="description"><?php _e('Create the History played page','mscra-automation')?></p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">                                                       
                        <label for="create_vote_payer"><?php _e('Vote music to play','mscra-automation')?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="create_vote_payer" id="create_vote_payer" value="true" checked="checked"/>
                        <p class="description"><?php _e('Create the Vote music to play page','mscra-automation')?></p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">                                                       
                        <label for="create_Advertising"><?php _e('Advertising','mscra-automation')?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="create_Advertising" id="create_vote_payer" value="true" checked="checked"/>
                        <p class="description"><?php _e('Create the advertising page','mscra-automation')?></p>
                    </td>
                </tr>
                </table> 
                <input type="hidden" value="true" name="ini_progs" />
            <?php
            submit_button(__('Inicialize all system','mscra-automation'));
            echo '</form>';
        }        
    }else{
        if (!empty($_POST['reset_progs'])&& check_admin_referer('reset_progs_clicked')) {
            mscra_reset_system();            
            echo '<div id="message" class="updated fade">
                <p>'.__('Removed menus and pages created','mscra-automation').'</p></div>';
        }else{
            echo '<form name="reset-prg" method="post">';    
            wp_nonce_field('reset_progs_clicked');
            echo '<input type="hidden" value="true" name="reset_progs" />';
            submit_button(__('Reset all system','mscra-automation'));
            echo '</form>';            
        }        
    }
echo '</div>';
  