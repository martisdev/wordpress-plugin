<div class="wrap">
    <h2><?php _e('Params Settings','mscra-automation') ?></h2>
    <form action="options.php" method="post">
        <?php      
            settings_fields('mscra_settings');
            do_settings_sections('mscra_settings');                
        ?>
        <table class="form-table">              
            <tr valign="top">
                <th scope="row">                                                       
                    <label for="mscra_client_key"><?php _e('Client Key','mscra-automation')?>:(*)</label>
                </th>
                <td>
                    <input type="text" name="mscra_client_key" id="mscra_client_key" size="50" value="<?php echo get_option('mscra_client_key','') ?>" />
                    <p class="description"><?php _e('Provided by the service provider','mscra-automation')?></p>
                </td>
            </tr>   
            <tr valign="top">
                <th scope="row">                                   
                    <label for="mscra_debug"><?php _e('Debug','mscra-automation')?></label>
                </th>
                <td>                                                
                    <input type="checkbox" name="mscra_debug" id="mscra_debug" 
                           value="true" <?php if (get_option('mscra_debug','false') == "true") {echo' checked="checked"';}?>/>
                    <p class="description"><?php _e('For Testing this plugin (console.log())','mscra-automation') ?></p>                        
                </td>
                <td>                                                
                    <input type="hidden"  name="mscra_initialize"  id="mscra_initialize" 
                           value="<?php echo get_option('mscra_initialize','false');?>"/>                                                
                </td>                                                           
            </tr>
            <!-- Player-->
            <tr valign="top">
                <th scope="row">                                   
                    <label for="mscra_player"><?php _e('Player','mscra-automation')?></label>
                </th>
                <td>                                                
                    <select name="mscra_player" id="mscra_player">                            
                        <option value="bottom" <?php selected(get_option('mscra_player'), "bottom"); ?>><?php _e('On Bottom','mscra-automation')?></option>
                        <option value="head" <?php selected(get_option('mscra_player'), "head"); ?>><?php _e('On Top','mscra-automation')?></option>                            
                        <option value="nothing" <?php selected(get_option('mscra_player'), "nothing"); ?>><?php _e('Nothing','mscra-automation')?></option>
                      </select>                        
                    <p class="description"><?php _e('Showing a streaming player','mscra-automation') ?></p>                        
                </td>                    
            </tr>
            <!-- Player Color-->
            <tr valign="top">
                <th scope="row">                                   
                    <label for="mscra_color"><?php _e('Player color','mscra-automation')?></label>
                </th>
                <td>                                                
                    <input type="color" id="mscra_color" name="mscra_color" onchange="clickColor(0, -1, -1, 5)" value="<?php echo get_option('mscra_color','#003399') ?>" size="50">
                    <p class="description"><?php _e('Change the background color player','mscra-automation') ?></p>                        
                </td>                    
            </tr>
            <!-- Player iframe-->
            <?php 
                $page = mscra_get_page_by_meta(__('Player Stream', 'mscra-automation'));       
                $url_player_stream = $page->guid;
            ?>
            
            <tr valign="top">
                <th scope="row">                                   
                    <label for="msc_iframe_player"><?php _e('Share your streaming','mscra-automation')?></label>                    
                </th>                
                <td>
                    <textarea id="msc_iframe_player" name="msc_iframe_player" style="width:400px" ><iframe src="<?php echo $url_player_stream ; ?>" allowfullscreen scrolling="no" frameborder="0" width="400px" height="135px"></iframe></textarea>
                    <p class="description"><?php _e('Copy and paste on everywhere','mscra-automation') ?></p>                        
                </td>                                
            </tr>        
            <!-- Player iframe example-->
            <tr valign="top">
                <th scope="row">                                   
                    <label for="msc_iframe_player_ex"><?php _e('Example','mscra-automation');?></label>
                </th>                
                <td>                    
                    <iframe id="msc_iframe_player_ex" src="<?php echo $url_player_stream ; ?>" allowfullscreen scrolling="no" frameborder="0" width="400px" height="135px"></iframe>
                </td>                
            </tr>
            <tr valign="top">
                <th scope="row">
                    <h2>Ajaxify Options</h2> 
                    <p class="description"><?php _e('reload only the main container','mscra-automation') ?></p>                               
                </th>
                <td></td>
            </tr>
            <tr valign="top">
                <th scope="row">                                   
                    <label for="mscra_enable_aws"><?php _e('Enable Ajaxify Effect','mscra-automation')?>:</label>
                </th>				
                <td><input type="checkbox" name="mscra_enable_aws" id="mscra_enable_aws" 
                value="true" <?php if (get_option('mscra_enable_aws',1) == "true") {echo' checked="checked"';}?>/></td>
            </tr>
            <tr valign="top">
                <th scope="row">                                    
                        <label for="mscra_no-ajax-ids"><?php _e('No ajax container IDs','mscra-automation')?>:</label>
                </th>				
                <td>
                    <textarea id="mscra_no-ajax-ids" name="mscra_no-ajax-ids"><?php echo get_option('mscra_no-ajax-ids'); ?></textarea>
                    <p class="description"><?php _e('Provide the ids of the parent tag whose child anchor(a) tags you dont want to handled by this plugin.<br/><b>NOTE:</b> ids should be separated by comma(,) without any spaces. eg: id1,id2,id3','mscra-automation')?>
                    </p>
                </td>
            </tr>			
            <tr valign="top">
                <th scope="row">                                   
                    <label for="mscra_container-id"><?php _e('Ajax container ID','mscra-automation')?>:(*)</label>
                </th>				
                <td>
                    <input type="text" name="mscra_container-id" value="<?php echo get_option('mscra_container-id','main'); ?>" />
                    <p class="description"><?php _e('ID of the container div whose data needs to be ajaxify. eg: main/page any one.','mscra-automation')?></p> 
                </td>
            </tr>			
            <tr valign="top">
                <th scope="row">                                   
                    <label for="mscra_mcdc"><?php _e('Menu container class','mscra-automation')?>:(*)</label>
                </th>								
                <td>
                    <input type="text" name="mscra_mcdc" value="<?php echo get_option('mscra_mcdc','menu'); ?>" />
                    <p class="description"><?php _e('Class of the div in which menu\'s ul, li present. eg: menu','mscra-automation')?></p>
                </td>
            </tr>			
            <tr valign="top">
                <th scope="row">                                   
                    <label for="mscra_search-form"><?php _e('Search form ID/CLASS','mscra-automation')?>:(*)</label>
                </th>								
                <td>
                    <input type="text" name="mscra_search-form" value="<?php echo get_option('mscra_search-form','search-form'); ?>" />
                    <p class="description"><?php _e('To make your search ajaxify provide the search form ID/CLASS.<br><strong>Example:</strong> if form tag class is search-form then provide <strong><i>.search-form</i></strong> if ID is search-form the provide <strong><i>#search-form</i></strong>','mscra-automation')?></p>
                </td>
            </tr>			
            <tr valign="top">
                <th scope="row">                                   
                    <label for="mscra_transition"><?php _e('Enable Transition Effect','mscra-automation')?>:</label>
                </th>												
                <td>                            
                    <input type="checkbox" name="msc_transition" id="mscra_transition" 
                       value="true" <?php if (get_option('mscra_transition',0) == "true") {echo' checked="checked"';}?>/>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">                                   
                    <label for="mscra_scrollTop"><?php _e('Enable scroll to top Effect','mscra-automation')?>:</label>
                </th>																
                <td>                            
                    <input type="checkbox" name="mscra_scrollTop" id="mscra_scrollTop" 
                    value="true" <?php if (get_option('mscra_scrollTop',0) == "true") {echo' checked="checked"';}?>/>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">                                   
                    <label for="mscra_loader"><?php _e('Loader Image','mscra-automation')?>:</label>
                </th>                        
                <td>
                    <?php $loader = get_option('mscra_loader'); ?>                                    
                    <select name="mscra_loader" id="mscra_loader">
                        <option value=''>Select Loader</option>
                        <?php
                        if ($file = opendir(MSCRA_PLUGIN_DIR . '/images/ajaxify/')) {
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
            <th scope="row"><label ><?php _e('Your language interface is','mscra-automation') ?></label></th>
            <td><label><?php echo get_locale();?></label>
            <p class="description" ><?php echo _e('Languages avariables','mscra-automation').' : ca, en, es_ES'?>.</p>
        </td>
        </tr>
    </table> 
</div>                      