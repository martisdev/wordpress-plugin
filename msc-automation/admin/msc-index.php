<div class="wrap">
    <h1><?php _e('MSC Radio Automation', 'msc-automation'); ?></h1> 
    <h2><?php _e('Radio Automation Software | The radio on cloud', 'msc-automation'); ?></h2>
    <h3><?php _e('Thank you', 'msc-automation'); ?></h3>
    <p><?php _e('Thank you for choosing MSC-soft.com and ours services, we hope that this software meets all your espectactives and contribute to a better quality of work and broadcast of your radio.', 'msc-automation'); ?></p>
    <dd>
        <p><i><?php _e('The MSC-Soft.com team', 'msc-automation'); ?></i></p>
    </dd>
    <h3><?php _e('Contact information', 'msc-automation'); ?></h3>
    <p><?php _e('For more information, please contact us.', 'msc-automation'); ?></p>
    <dl>
        <dt><strong><?php _e('Phone and WhatsApp', 'msc-automation'); ?></strong></dt>
        <dd>
            <p >+34 686 298 198</p>
        </dd>
        <dt><strong><?php _e('Postal address', 'msc-automation'); ?></strong></dt>
        <dd>
            <p >C/ Onze de setembre núm. 9 , 08787 Can Bou, Orpí (BCN)</p>
        </dd>
        <dt><strong><?php _e('E-mail', 'msc-automation'); ?></strong></dt>
        <dd>
            <p ><?php _e('General information', 'msc-automation'); ?>: <a href="mailto:info@msc-soft.com">info@msc-soft.com</a></p>
            <p ><?php _e('Client support', 'msc-automation'); ?>: <a href="mailto:support@msc-soft.com">support@msc-soft.com</a>        
        </dd>
        <dt><strong>Web</strong></dt>
        <dd>
            <p><?php _e('General', 'msc-automation'); ?>: <a href='https://msc-soft.com'>msc-soft.com</a></p>
            <p><?php _e('Help', 'msc-automation'); ?>: <a href='https://msc-soft.com/help'>msc-soft.com/help</a></p>
        </dd>
        <dt><strong><?php _e('Test API', 'msc-automation'); ?></strong></dt>
        <dd>
            <?php
            include MSC_PLUGIN_DIR . 'connect_api.php';
            if ($MyRadio->RESPOSTA_STATUS !== SUCCES) {                
                echo'<h3 style="color:red;">'.__('The API fail!', 'msc-automation').'</h3>';
            } else {                
                echo'<h3>'.__('The API work fine!', 'msc-automation').'</h3>';
            }
            /* $plugin_data = get_plugin_data(__FILE__);                    
              $plugin_version = $plugin_data['Version'];
              echo __('Plugin version','msc-automation'),' :'.$plugin_version ; */
            $str_version = __('Plugin version', 'msc-automation') . ' : ' . MSC_PLUGIN_VERSION;
            ?>                     
            <p class="alignleft"><?php echo $str_version; ?></p>
        </dd>
    </dl>
    <div id="wpfooter">        
    </div>

</div>
