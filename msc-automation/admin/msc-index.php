<div class="wrap">
    <h1><?php _e('MSC Radio Automation', 'mscra-automation'); ?></h1> 
    <h2><?php _e('Radio Automation Software | The radio on cloud', 'mscra-automation'); ?></h2>
    <h3><?php _e('Thank you', 'mscra-automation'); ?></h3>
    <p><?php _e('Thank you for choosing MSC-soft.com and ours services, we hope that this software meets all your espectactives and contribute to a better quality of work and broadcast of your radio.', 'mscra-automation'); ?></p>
    <dd>
        <p><i><?php _e('The MSC-Soft.com team', 'mscra-automation'); ?></i></p>
    </dd>
    <h3><?php _e('Contact information', 'mscra-automation'); ?></h3>
    <p><?php _e('For more information, please contact us.', 'mscra-automation'); ?></p>
    <dl>
        <dt><strong><?php _e('Phone and WhatsApp', 'mscra-automation'); ?></strong></dt>
        <dd>
            <p >+34 686 298 198</p>
        </dd>
        <dt><strong><?php _e('Postal address', 'mscra-automation'); ?></strong></dt>
        <dd>
            <p >C/ Onze de setembre núm. 9 , 08787 Can Bou, Orpí (BCN)</p>
        </dd>
        <dt><strong><?php _e('E-mail', 'mscra-automation'); ?></strong></dt>
        <dd>
            <p ><?php _e('General information', 'mscra-automation'); ?>: <a href="mailto:info@msc-soft.com">info@msc-soft.com</a></p>
            <p ><?php _e('Client support', 'mscra-automation'); ?>: <a href="mailto:support@msc-soft.com">support@msc-soft.com</a>        
        </dd>
        <dt><strong>Web</strong></dt>
        <dd>
            <p><?php _e('General', 'mscra-automation'); ?>: <a href='https://msc-soft.com'>msc-soft.com</a></p>
            <p><?php _e('Help', 'mscra-automation'); ?>: <a href='https://msc-soft.com/help'>msc-soft.com/help</a></p>
        </dd>
        <dt><strong><?php _e('Test API', 'mscra-automation'); ?></strong></dt>
        <dd>
            <?php
            include MSCRA_PLUGIN_DIR . 'connect_api.php';
            if ($MyRadio->RESPOSTA_STATUS !== SUCCES) {
                echo'<h3 style="color:red;">' . __('The API fail!', 'mscra-automation') . '</h3>';
            } else {
                echo'<h3>' . __('The API work fine!', 'mscra-automation') . '</h3>';
            }            
                if (!function_exists('get_plugin_data')) {
                    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                }
                $plugin_data = get_plugin_data(MSCRA_PLUGIN_DIR.'msc-automation.php');        
                $plugin_version = $plugin_data['Version'];            
                $str_version = __('Plugin version', 'mscra-automation') . ' : ' . $plugin_version;
            ?>                     
            <p class="alignleft"><?php echo $str_version; ?></p>
        </dd>
    </dl>
    <div id="wpfooter">        
    </div>

</div>
