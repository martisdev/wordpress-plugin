<div class="wrap">
    <h1><?php _e('Practical guide to wordpress plugin','msc-automation'); ?></h1>
    <h3><?php _e('General description','msc-automation'); ?></h3>
    <p><?php _e('To give functionality to your website you just have to enter shortcodes in the pages. You can go to the section \'initialize\' and generate all the basic pages automatically and then modify the ones you want to customize','msc-automation'); ?></p>
    <p><?php _e('You also have a shortcode widget now_playing available. You\'ll find it in the widgets section','msc-automation'); ?></p>
    <p><?php _e('Please, if you like this extension and the system <b>MSC Automation</b> collaborates with us to promote it. Set the menu \'MSC Footer\' somewhere in your web.','msc-automation'); ?></p>
    <h2><?php _e('Avariable shortcodes','msc-automation'); ?></h2>
    
    <!-- calendar-->
    <h1><?php _e('Calendar','msc-automation') ?></h1>
    <h2>calendar_day</h2>
    <p><?php _e('Show a table with the programming of a specific day','msc-automation') ?></p>
    <p><?php _e('Example','msc-automation') ?>: <input type='text' value='[calendar_day]'> </p>
    
    <h2>now_onair</h2>
    <p><?php _e('Show information on the current and next program','msc-automation') ?></p>
    <p><?php _e('Example','msc-automation') ?>: <input type='text' value='[now_onair]'> </p>
    
    <h2>home</h2>
    <p><?php _e('Show the shortcodes now_onair and player_streaming at the same time','msc-automation') ?></p>
    <p><?php _e('Example','msc-automation') ?>: <input type='text' value='[home]'> </p>
    
    
    
    
    <!-- Music-->
    <h1><?php _e('Music','msc-automation') ?></h1>
    <h2>last_played</h2>
    <p><?php _e('Show a table with the latest songs broadcast','msc-automation'); ?></p>
        <dl>
        <dt><strong><?php _e('Params','msc-automation') ?></strong></dt>
        <dd>
            <ul>
                <li>rows="10" (<?php _e('optional','msc-automation'); ?>): <?php _e('Number of records to show. By default 10','msc-automation'); ?></li>
                <li>refresh="false" (<?php _e('optional','msc-automation'); ?>): <?php _e('Auto refresh the table, by default false','msc-automation'); ?></li>
                <li>image="false" (<?php _e('optional','msc-automation'); ?>): <?php _e('Show image if exist','msc-automation'); ?></li>
            </ul>
        </dd>
        </dl>
    <p><?php _e('Example','msc-automation') ?>: <input type='text' value='[last_played rows="25" refresh="true" refresh="false"]' size="50"> </p>
    
    <h2>now_playing</h2>
    <p><?php _e('On radio formula shows information about the current song','msc-automation') ?></p>
        <dl>
        <dt><strong><?php _e('Params','msc-automation') ?></strong></dt>
        <dd>
            <ul>                
                <li>image="false" (<?php _e('optional','msc-automation'); ?>): <?php _e('Show image if exist','msc-automation'); ?></li>
                <li>img_width="false" (<?php _e('optional','msc-automation'); ?>): <?php _e('Width of the image in pixels. By default 200','msc-automation'); ?></li>                
            </ul>
        </dd>
        </dl>
    <p><?php _e('Example','msc-automation') ?>: <input type='text' value='[now_playing]'> </p>
    
    <h2>public_vote_player</h2>
    <p><?php _e('Form in which the public can choose the following song given several options','msc-automation') ?></p>
    <p><?php _e('Example','msc-automation') ?>: <input type='text' value='[public_vote_player]'> </p>
    
    <h2>search_music</h2>
    <p><?php _e('Form to do song searches and schedule them','msc-automation') ?></p>
    <p><?php _e('Example','msc-automation') ?>: <input type='text' value='[search_music]'> </p>
    
    <h2>last_albums</h2>
    <p><?php _e('Show the latest record albums','msc-automation') ?></p>
        <dl>
        <dt><strong><?php _e('Params','msc-automation') ?></strong></dt>
        <dd>
            <ul>                
                <li>rows="5" (<?php _e('optional','msc-automation'); ?>): <?php _e('Number of records to show. By default 5','msc-automation'); ?></li>                
            </ul>
        </dd>
        </dl>
    <p><?php _e('Example','msc-automation') ?>: <input type='text' value='[last_albums rows="10"]'> </p>
    
    <!-- Programs -->
    <h1><?php _e('Programs','msc-automation') ?></h1>
    <h2>show_program</h2>
    <p><?php _e('Show program information, facebook, twitter and podcasting files','msc-automation') ?></p>
    <dl>
        <dt><strong><?php _e('Params','msc-automation') ?></strong></dt>
        <dd>
            <ul>                
                <li>prg="1" : <?php _e('Program identifier','msc-automation'); ?></li>                
                <li>download="false" (<?php _e('optional','msc-automation'); ?>): <?php _e('Downloadable programs. by default false','msc-automation'); ?></li>                
            </ul>
        </dd>
        </dl>
    <p><?php _e('Example','msc-automation'); ?>: <input type='text' value='[show_program prg="1" download="false"]'  size='50'></p>
    
    
    
    <!-- Podcast-->
    <h1><?php _e('Podcast','msc-automation') ?></h1>
    <h2>last_podcast</h2>
    <p><?php _e('Listed with the latest published podcasting files','msc-automation') ?></p>    
    <p><?php _e('Example','msc-automation') ?>: <input type='text' value='[last_podcast]'> </p>    
    
