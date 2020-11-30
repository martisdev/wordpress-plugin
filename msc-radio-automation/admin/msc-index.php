<div class="wrap">
    <h1><?php _e('MSC Radio Automation', 'mscra-automation'); ?></h1> 
    <h2><?php _e('Radio Automation Software | The radio on cloud', 'mscra-automation'); ?></h2>
    <p><?php _e('Syncronize your radio station with your web page and powered it for advanced functionalities.', 'mscra-automation');?></p>

    <h3><?php _e('Thank you', 'mscra-automation'); ?></h3>
    <p><?php _e('Thank you for choosing <a href="https://msc-soft.com/" target=_blank>MSC-Soft.com</a> and <a href="https://msc-soft.com/services-and-prices/" target=_blank>ours services</a>. We hope that this software meets all your espectactives and contribute to a better quality of work and broadcast for your radio station.', 'mscra-automation'); ?></p>
    <dd>
        <h3><?php _e('Global service', 'mscra-automation'); ?></h3>
        <p><?php _e('All the computer technology of your radio into a single service.', 'mscra-automation'); ?></p>
        <h3><?php _e('Total control', 'mscra-automation'); ?></h3>
        <p><?php _e('We give you the tools to easily create quality programming and expand the contents for all channels. ', 'mscra-automation'); ?></p>
        <h3><?php _e('We grow with you', 'mscra-automation'); ?></h3>
        <p><?php _e('We adapt to your needs, we offer new tools to go much further. ', 'mscra-automation'); ?></p>                
    </dd>
    
    <h3><?php _e('About this plugin', 'mscra-automation'); ?></h3>
    <p><?php _e('This plugin is the tool to offer our advanced services, you need to hire <a href="https://msc-soft.com/services-and-prices/" target=_blank>some type of service</a> and  works in conjunction with our <a href="https://msc-soft.com/download/" target=_blank>desktop applications</a>.', 'mscra-automation');?>
    <p>
    <p><?php _e('The application engine is an API that calls our servers and responds with your data account.<br><b>Example</b>: On the page on you want show a grid calendar (shortcode <b>[mscra_calendar_day]</b>) the pluging call to:<br>
<i>http://api.msc-soft.com/V2/{YOUR_CLIENT_KEY}/CALENDAR/GRIDDAY/?&date=2020-11-12&user={USER_COOKIE}&lang=en_US</i>
and them return information about your calendar.', 'mscra-automation');?>
    <p>
    <p><?php _e('Please, if you like this extension and the system <b>MSC Radio Automation</b> collaborates with us to promote it. Set the menu \'MSC Footer\' somewhere in your web.  <a href="https://msc-soft.com/donate/" target=_blank>Or make a donation</a>.', 'mscra-automation');?></p>
    
    
    <p><i><?php _e('The MSC-Soft.com team', 'mscra-automation'); ?></i></p>
    
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
</div>