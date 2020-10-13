<div class="wrap">
    <h1><?php _e('Practical guide for this wordpress plugin', 'mscra-automation'); ?></h1>
    <h3><?php _e('General description', 'mscra-automation'); ?></h3>
    <p><?php _e('To give functionality to your website you just have to enter shortcodes in the pages. You can go to the section \'initialize\' and generate all the basic pages automatically and then modify the ones you want to customize', 'mscra-automation'); ?></p>
    <p><?php _e('You also have a shortcode widget now_playing available. You\'ll find it in the widgets section', 'mscra-automation'); ?></p>
    <p><?php _e('Please, if you like this extension and the system <b>MSC Radio Automation</b> collaborates with us to promote it. Set the menu \'MSC Footer\' somewhere in your web.', 'mscra-automation'); ?></p>
    <h2><?php _e('Avariable shortcodes', 'mscra-automation'); ?></h2>

    <!-- Advertising-->    
    <h1><?php _e('Advertising', 'mscra-automation') ?></h1>
    <dd>
        <ul>
            <h2>mscra_manager_adv</h2>
            <p><?php _e('Section for customers where they can check the radiation of their advertising', 'mscra-automation') ?></p>
            <p><?php _e('Example', 'mscra-automation') ?>: <input type='text' value='[mscra_manager_adv]'> </p>            
        </ul>
    </dd>
    <!-- calendar-->
    <h1><?php _e('Calendar', 'mscra-automation') ?></h1>
    <dd>
        <ul>
            <h2>mscra_calendar_day</h2>
            <p><?php _e('Shows a table with the programming of a specific day', 'mscra-automation') ?></p>
            <p><?php _e('Example', 'mscra-automation') ?>: <input type='text' value='[mscra_calendar_day]'> </p>

            <h2>mscra_now_onair</h2>
            <p><?php _e('Shows information about the current and next program', 'mscra-automation') ?></p>
            <p><?php _e('Example', 'mscra-automation') ?>: <input type='text' value='[mscra_now_onair]'> </p>

            <h2>mscra_home</h2>
            <p><?php _e('Shows the shortcodes now_onair and player_streaming at the same time', 'mscra-automation') ?></p>
            <p><?php _e('Example', 'mscra-automation') ?>: <input type='text' value='[mscra_home]'> </p>
        </ul>
    </dd>   

    <!-- Music-->
    <h1><?php _e('Music', 'mscra-automation') ?></h1>
    <dd>
        <ul>
            <h2>mscra_last_played</h2>
            <p><?php _e('Shows a table with the latest songs broadcast', 'mscra-automation'); ?></p>
            <dl>
                <dt><strong><?php _e('Params', 'mscra-automation') ?></strong></dt>
                <dd>
                    <ul>
                        <li>rows="10" (<?php _e('optional', 'mscra-automation'); ?>): <?php _e('Number of records to show. By default 10', 'mscra-automation'); ?></li>
                        <li>refresh="false" (<?php _e('optional', 'mscra-automation'); ?>): <?php _e('Auto refresh the table, by default false', 'mscra-automation'); ?></li>
                        <li>image="false" (<?php _e('optional', 'mscra-automation'); ?>): <?php _e('Show image if exist', 'mscra-automation'); ?></li>
                        <li>stylebutton="" (<?php _e('optional', 'mscra-automation'); ?>): <?php _e('button class', 'mscra-automation'); ?></li>
                    </ul>
                </dd>
            </dl>
            <p><?php _e('Example', 'mscra-automation') ?>: <input type='text' value='[mscra_last_played rows="25" refresh="true" refresh="false"]' size="50"> </p>

            <h2>mscra_now_playing</h2>
            <p><?php _e('On radio formula shows information about the current song', 'mscra-automation') ?></p>
            <dl>
                <dt><strong><?php _e('Params', 'mscra-automation') ?></strong></dt>
                <dd>
                    <ul>                
                        <li>image="false" (<?php _e('optional', 'mscra-automation'); ?>): <?php _e('Show image if exist', 'mscra-automation'); ?></li>
                        <li>img_width="false" (<?php _e('optional', 'mscra-automation'); ?>): <?php _e('Width of the image in pixels. By default 200', 'mscra-automation'); ?></li>                
                    </ul>
                </dd>
            </dl>
            <p><?php _e('Example', 'mscra-automation') ?>: <input type='text' value='[mscra_now_playing]'> </p>

            <h2>mscra_public_vote_player</h2>
            <p><?php _e('Form in which the public can choose the following song given several options', 'mscra-automation') ?></p>
            <dl>
                <dt><strong><?php _e('Params', 'mscra-automation') ?></strong></dt>
                <dd>
                    <ul>             
                        <li>stylebutton="" (<?php _e('optional', 'mscra-automation'); ?>): <?php _e('button class', 'mscra-automation'); ?></li>
                    </ul>
                </dd>
            </dl>
            <p><?php _e('Example', 'mscra-automation') ?>: <input type='text' value='[mscra_public_vote_player]'> </p>

            <h2>mscra_search_music</h2>
            <p><?php _e('Form to do song searches and schedule them', 'mscra-automation') ?></p>
            <p><?php _e('Example', 'mscra-automation') ?>: <input type='text' value='[mscra_search_music]'> </p>

            <h2>mscra_last_albums</h2>
            <p><?php _e('Show the latest record albums', 'mscra-automation') ?></p>
            <dl>
                <dt><strong><?php _e('Params', 'mscra-automation') ?></strong></dt>
                <dd>
                    <ul>                
                        <li>rows="5" (<?php _e('optional', 'mscra-automation'); ?>): <?php _e('Number of records to show. By default 5', 'mscra-automation'); ?></li>                
                    </ul>
                </dd>
            </dl>
            <p><?php _e('Example', 'mscra-automation') ?>: <input type='text' value='[mscra_last_albums rows="10"]'> </p>


            <h2>mscra_detail_song</h2>
            <p><?php _e('Shows the latest record albums', 'mscra-automation') ?></p>
            <dl>
                <dt><strong><?php _e('Params', 'mscra-automation') ?></strong></dt>
                <dd>
                    <ul>                
                        <li>id="1": <?php _e('Song identifier', 'mscra-automation'); ?></li> 
                        <li>img_width="false" (<?php _e('optional', 'mscra-automation'); ?>): <?php _e('Width of the image in pixels. By default 200', 'mscra-automation'); ?></li>
                    </ul>
                </dd>
            </dl>
            <p><?php _e('Example', 'mscra-automation') ?>: <input type='text' value='[mscra_detail_song id="10"]'> </p>

        </ul>
    </dd>

    <!-- Programs -->
    <h1><?php _e('Programs', 'mscra-automation') ?></h1>
    <dd>
        <ul>
            <h2>mscra_show_program</h2>
            <p><?php _e('Shows program information, facebook, twitter and podcasting files', 'mscra-automation') ?></p>
            <dl>
                <dt><strong><?php _e('Params', 'mscra-automation') ?></strong></dt>
                <dd>
                    <ul>                
                        <li>prg="1" : <?php _e('Program identifier', 'mscra-automation'); ?></li>                
                        <li>download="false" (<?php _e('optional', 'mscra-automation'); ?>): <?php _e('Downloadable programs. by default false', 'mscra-automation'); ?></li>                
                    </ul>
                </dd>
            </dl>
            <p><?php _e('Example', 'mscra-automation'); ?>: <input type='text' value='[mscra_show_program prg="1" download="true"]'  size='50'></p>

            <h2>mscra_list_programs</h2>
            <p><?php _e('Shows a list of programs with image and link', 'mscra-automation') ?></p>   
            <p><?php _e('Example', 'mscra-automation'); ?>: <input type='text' value='[mscra_list_programs]'  size='50'></p>
        </ul>
    </dd>

    <!-- Podcast-->
    <h1><?php _e('Podcast', 'mscra-automation') ?></h1>
    <dd>
        <ul>
            <h2>mscra_last_podcast</h2>
            <p><?php _e('Shows the latest published podcasting files', 'mscra-automation') ?></p>    
            <p><?php _e('Example', 'mscra-automation') ?>: <input type='text' value='[mscra_last_podcast]'> </p>    
        </ul>
    </dd>
<!-- Others-->
    <h1><?php _e('Others', 'mscra-automation') ?></h1>
    <dd>
        <ul>            
            <h2>mscra_detail_track</h2>
            <p><?php _e('Show information about a program or song', 'mscra-automation') ?></p>
            <dl>
                <dt><strong><?php _e('Params', 'mscra-automation') ?></strong></dt>
                <dd>
                    <ul>
                        <li>id="10" : <?php _e('File identifier', 'mscra-automation'); ?></li>
                        <li>type=1 : <?php _e('1=song, 2=program, ', 'mscra-automation'); ?></li>                
                    </ul>
                </dd>
            </dl>
            <p><?php _e('Example', 'mscra-automation') ?>: <input type='text' value='[mscra_detail_track]'> </p>
        </ul>
    </dd>    
    
    <!-- Social media-->
    <h1><?php _e('Social media', 'mscra-automation') ?></h1>
    <dd>
        <ul>
            <h2>mscra_timeline_FaceBook</h2>
            <p><?php _e('Shows the radio station timeline', 'mscra-automation') ?></p>
            <p><?php _e('Example', 'mscra-automation') ?>: <input type='text' value='[mscra_timeline_FaceBook]'> </p>

            <h2>mscra_timeline_twitter</h2>
            <p><?php _e('Shows the radio station timeline', 'mscra-automation') ?></p>
            <p><?php _e('Example', 'mscra-automation') ?>: <input type='text' value='[mscra_timeline_twitter]'> </p>
        </ul>
    </dd>    
