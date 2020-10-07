<div class="wrap">
    <h2><?php _e('Params Settings','msc-automation') ?></h2>
    <form action="options.php" method="post">
        <?php      
            settings_fields('msc_settings');
            do_settings_sections('msc_settings');                
        ?>
        <table class="form-table">              
            <tr valign="top">
                <th scope="row">                                                       
                    <label for="msc_client_key"><?php _e('Client Key','msc-automation')?>:(*)</label>
                </th>
                <td>
                    <input type="text" name="msc_client_key" id="msc_client_key" size="50" value="<?php echo get_option('msc_client_key','') ?>" />
                    <p class="description"><?php _e('Provided by the service provider','msc-automation')?></p>
                </td>
            </tr>   
            <tr valign="top">
                <th scope="row">                                   
                    <label for="msc_debug"><?php _e('Debug','msc-automation')?></label>
                </th>
                <td>                                                
                    <input type="checkbox" name="msc_debug" id="msc_debug" 
                           value="true" <?php if (get_option('msc_debug','false') == "true") {echo' checked="checked"';}?>/>
                    <p class="description"><?php _e('For Testing this plugin (console.log())','msc-automation') ?></p>                        
                </td>
                <td>                                                
                    <input type="hidden"  name="msc_initialize"  id="msc_initialize" 
                           value="<?php echo get_option('msc_initialize','false');?>"/>                                                
                </td>                                                           
            </tr>
            <!-- Player-->
            <tr valign="top">
                <th scope="row">                                   
                    <label for="msc_player"><?php _e('Player','msc-automation')?></label>
                </th>
                <td>                                                
                    <select name="msc_player" id="msc_player">                            
                        <option value="bottom" <?php selected(get_option('msc_player'), "bottom"); ?>><?php _e('On Bottom','msc-automation')?></option>
                        <option value="head" <?php selected(get_option('msc_player'), "head"); ?>><?php _e('On Top','msc-automation')?></option>                            
                        <option value="nothing" <?php selected(get_option('msc_player'), "nothing"); ?>><?php _e('Nothing','msc-automation')?></option>
                      </select>                        
                    <p class="description"><?php _e('Showing a streaming player','msc-automation') ?></p>                        
                </td>                    
            </tr>
            <!-- Player Color-->
            <tr valign="top">
                <th scope="row">                                   
                    <label for="msc_color"><?php _e('Player color','msc-automation')?></label>
                </th>
                <td>                                                
                    <input type="color" id="msc_color" name="msc_color" onchange="clickColor(0, -1, -1, 5)" value="<?php echo get_option('msc_color','#003399') ?>" size="50">
                    <p class="description"><?php _e('Change the background color player','msc-automation') ?></p>                        
                </td>                    
            </tr>
            <!-- Player iframe-->
            <?php $url_player_stream =  get_permalink( get_page_by_title( __('Player Stream', 'msc-automation')));?>
            <tr valign="top">
                <th scope="row">                                   
                    <label for="msc_iframe_player"><?php _e('Share your streaming','msc-automation')?></label>                    
                </th>                
                <td>
                    <textarea id="msc_iframe_player" name="msc_iframe_player" style="width:400px" ><iframe src="<?php echo $url_player_stream ; ?>" allowfullscreen scrolling="no" frameborder="0" width="400px" height="135px"></iframe></textarea>
                    <p class="description"><?php _e('Copy and paste on everywhere','msc-automation') ?></p>                        
                </td>                                
            </tr>        
            <!-- Player iframe example-->
            <tr valign="top">
                <th scope="row">                                   
                    <label for="msc_iframe_player_ex"><?php _e('Example','msc-automation');?></label>
                </th>                
                <td>                    
                    <iframe id="msc_iframe_player_ex" src="<?php echo $url_player_stream ; ?>" allowfullscreen scrolling="no" frameborder="0" width="400px" height="135px"></iframe>
                </td>                
            </tr>
            <tr valign="top">
                <th scope="row">
                    <h2>Ajaxify Options</h2> 
                    <p class="description"><?php _e('reload only the main container','msc-automation') ?></p>                               
                </th>
                <td></td>
            </tr>
            <tr valign="top">
                <th scope="row">                                   
                    <label for="msc_enable_aws"><?php _e('Enable Ajaxify Effect','msc-automation')?>:</label>
                </th>				
                <td><input type="checkbox" name="msc_enable_aws" id="msc_enable_aws" 
                value="true" <?php if (get_option('msc_enable_aws',1) == "true") {echo' checked="checked"';}?>/></td>
            </tr>
            <tr valign="top">
                <th scope="row">                                    
                        <label for="msc_no-ajax-ids"><?php _e('No ajax container IDs','msc-automation')?>:</label>
                </th>				
                <td>
                    <textarea id="msc_no-ajax-ids" name="msc_no-ajax-ids"><?php echo get_option('msc_no-ajax-ids'); ?></textarea>
                    <p class="description"><?php _e('Provide the ids of the parent tag whose child anchor(a) tags you dont want to handled by this plugin.<br/><b>NOTE:</b> ids should be separated by comma(,) without any spaces. eg: id1,id2,id3','msc-automation')?>
                    </p>
                </td>
            </tr>			
            <tr valign="top">
                <th scope="row">                                   
                    <label for="msc_container-id"><?php _e('Ajax container ID','msc-automation')?>:(*)</label>
                </th>				
                <td>
                    <input type="text" name="msc_container-id" value="<?php echo get_option('msc_container-id','main'); ?>" />
                    <p class="description"><?php _e('ID of the container div whose data needs to be ajaxify. eg: main/page any one.','msc-automation')?></p> 
                </td>
            </tr>			
            <tr valign="top">
                <th scope="row">                                   
                    <label for="msc_mcdc"><?php _e('Menu container class','msc-automation')?>:(*)</label>
                </th>								
                <td>
                    <input type="text" name="msc_mcdc" value="<?php echo get_option('msc_mcdc','menu'); ?>" />
                    <p class="description"><?php _e('Class of the div in which menu\'s ul, li present. eg: menu','msc-automation')?></p>
                </td>
            </tr>			
            <tr valign="top">
                <th scope="row">                                   
                    <label for="msc_search-form"><?php _e('Search form ID/CLASS','msc-automation')?>:(*)</label>
                </th>								
                <td>
                    <input type="text" name="msc_search-form" value="<?php echo get_option('msc_search-form','search-form'); ?>" />
                    <p class="description"><?php _e('To make your search ajaxify provide the search form ID/CLASS.<br><strong>Example:</strong> if form tag class is search-form then provide <strong><i>.search-form</i></strong> if ID is search-form the provide <strong><i>#search-form</i></strong>','msc-automation')?></p>
                </td>
            </tr>			
            <tr valign="top">
                <th scope="row">                                   
                    <label for="msc_transition"><?php _e('Enable Transition Effect','msc-automation')?>:</label>
                </th>												
                <td>                            
                    <input type="checkbox" name="msc_transition" id="msc_transition" 
                       value="true" <?php if (get_option('msc_transition',0) == "true") {echo' checked="checked"';}?>/>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">                                   
                    <label for="msc_scrollTop"><?php _e('Enable scroll to top Effect','msc-automation')?>:</label>
                </th>																
                <td>                            
                    <input type="checkbox" name="msc_scrollTop" id="msc_scrollTop" 
                    value="true" <?php if (get_option('msc_scrollTop',0) == "true") {echo' checked="checked"';}?>/>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">                                   
                    <label for="msc_loader"><?php _e('Loader Image','msc-automation')?>:</label>
                </th>                        
                <td>
                    <?php $loader = get_option('msc_loader'); ?>                                    
                    <select name="msc_loader" id="msc_loader">
                        <option value=''>Select Loader</option>
                        <?php
                        if ($file = opendir(MSC_PLUGIN_DIR . '/images/ajaxify/')) {
                            while (false !== ($entry = readdir($file))) {
                                if ($entry != "." && $entry != "..") {
                                $selected = '';
                                    if ($entry == $loader) {
                                            $selected = "selected";
                                    }
                                    echo "<option value='", $entry, "'", $selected, ">", $entry, "</option>\n";
                                }
                            }
                            closedir($file);
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr valign="top">
                <td></td>
                <td>
                    <?php submit_button(); ?>            
                </td>
            </tr>
        </table>	                
    </form>
    <table class="form-table">  
        <tr>
            <th scope="row"><label ><?php _e('Your language interface is','msc-automation') ?></label></th>
            <td><label><?php echo get_locale();?></label>
            <p class="description" ><?php echo _e('Languages avariables','msc-automation').' : ca, en, es_ES'?>.</p>
        </td>
        </tr>
    </table> 
</div>                      