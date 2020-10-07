<div class="wrap">
    <h1><?php _e('Practical guide for this wordpress plugin', 'msc-automation'); ?></h1>
    <h3><?php _e('General description', 'msc-automation'); ?></h3>
    <p><?php _e('To give functionality to your website you just have to enter shortcodes in the pages. You can go to the section \'initialize\' and generate all the basic pages automatically and then modify the ones you want to customize', 'msc-automation'); ?></p>
    <p><?php _e('You also have a shortcode widget now_playing available. You\'ll find it in the widgets section', 'msc-automation'); ?></p>
    <p><?php _e('Please, if you like this extension and the system <b>MSC Radio Automation</b> collaborates with us to promote it. Set the menu \'MSC Footer\' somewhere in your web.', 'msc-automation'); ?></p>
    <h2><?php _e('Avariable shortcodes', 'msc-automation'); ?></h2>

    <!-- Advertising-->    
    <h1><?php _e('Advertising', 'msc-automation') ?></h1>
    <dd>
        <ul>
            <h2>manager_adv</h2>
            <p><?php _e('Section for customers where they can check the radiation of their advertising', 'msc-automation') ?></p>
            <p><?php _e('Example', 'msc-automation') ?>: <input type='text' value='[manager_adv]'> </p>

            <!-- General-->
            <h1><?php _e('Home', 'msc-automation') ?></h1>
            <h2>home</h2>
            <p><?php _e('Show current schedule', 'msc-automation') ?></p>
            <p><?php _e('Example', 'msc-automation') ?>: <input type='text' value='[home]'> </p>

            <h1><?php _e('Detail track', 'msc-automation') ?></h1>
            <h2>detail_track</h2>
            <p><?php _e('Show information about a program or song', 'msc-automation') ?></p>
            <dl>
                <dt><strong><?php _e('Params', 'msc-automation') ?></strong></dt>
                <dd>
                    <ul>
                        <li>id="10" : <?php _e('File identifier', 'msc-automation'); ?></li>
                        <li>type=1 : <?php _e('1=song, 2=program, ', 'msc-automation'); ?></li>                
                    </ul>
                </dd>
            </dl>
            <p><?php _e('Example', 'msc-automation') ?>: <input type='text' value='[detail_track]'> </p>
        </ul>
    </dd>


    <!-- calendar-->
    <h1><?php _e('Calendar', 'msc-automation') ?></h1>
    <dd>
        <ul>
            <h2>calendar_day</h2>
            <p><?php _e('Shows a table with the programming of a specific day', 'msc-automation') ?></p>
            <p><?php _e('Example', 'msc-automation') ?>: <input type='text' value='[calendar_day]'> </p>

            <h2>now_onair</h2>
            <p><?php _e('Shows information about the current and next program', 'msc-automation') ?></p>
            <p><?php _e('Example', 'msc-automation') ?>: <input type='text' value='[now_onair]'> </p>

            <h2>home</h2>
            <p><?php _e('Shows the shortcodes now_onair and player_streaming at the same time', 'msc-automation') ?></p>
            <p><?php _e('Example', 'msc-automation') ?>: <input type='text' value='[home]'> </p>
        </ul>
    </dd>   

    <!-- Music-->
    <h1><?php _e('Music', 'msc-automation') ?></h1>
    <dd>
        <ul>
            <h2>last_played</h2>
            <p><?php _e('Shows a table with the latest songs broadcast', 'msc-automation'); ?></p>
            <dl>
                <dt><strong><?php _e('Params', 'msc-automation') ?></strong></dt>
                <dd>
                    <ul>
                        <li>rows="10" (<?php _e('optional', 'msc-automation'); ?>): <?php _e('Number of records to show. By default 10', 'msc-automation'); ?></li>
                        <li>refresh="false" (<?php _e('optional', 'msc-automation'); ?>): <?php _e('Auto refresh the table, by default false', 'msc-automation'); ?></li>
                        <li>image="false" (<?php _e('optional', 'msc-automation'); ?>): <?php _e('Show image if exist', 'msc-automation'); ?></li>
                        <li>stylebutton="" (<?php _e('optional', 'msc-automation'); ?>): <?php _e('button class', 'msc-automation'); ?></li>
                    </ul>
                </dd>
            </dl>
            <p><?php _e('Example', 'msc-automation') ?>: <input type='text' value='[last_played rows="25" refresh="true" refresh="false"]' size="50"> </p>

            <h2>now_playing</h2>
            <p><?php _e('On radio formula shows information about the current song', 'msc-automation') ?></p>
            <dl>
                <dt><strong><?php _e('Params', 'msc-automation') ?></strong></dt>
                <dd>
                    <ul>                
                        <li>image="false" (<?php _e('optional', 'msc-automation'); ?>): <?php _e('Show image if exist', 'msc-automation'); ?></li>
                        <li>img_width="false" (<?php _e('optional', 'msc-automation'); ?>): <?php _e('Width of the image in pixels. By default 200', 'msc-automation'); ?></li>                
                    </ul>
                </dd>
            </dl>
            <p><?php _e('Example', 'msc-automation') ?>: <input type='text' value='[now_playing]'> </p>

            <h2>public_vote_player</h2>
            <p><?php _e('Form in which the public can choose the following song given several options', 'msc-automation') ?></p>
            <dl>
                <dt><strong><?php _e('Params', 'msc-automation') ?></strong></dt>
                <dd>
                    <ul>             
                        <li>stylebutton="" (<?php _e('optional', 'msc-automation'); ?>): <?php _e('button class', 'msc-automation'); ?></li>
                    </ul>
                </dd>
            </dl>
            <p><?php _e('Example', 'msc-automation') ?>: <input type='text' value='[public_vote_player]'> </p>

            <h2>search_music</h2>
            <p><?php _e('Form to do song searches and schedule them', 'msc-automation') ?></p>
            <p><?php _e('Example', 'msc-automation') ?>: <input type='text' value='[search_music]'> </p>

            <h2>last_albums</h2>
            <p><?php _e('Show the latest record albums', 'msc-automation') ?></p>
            <dl>
                <dt><strong><?php _e('Params', 'msc-automation') ?></strong></dt>
                <dd>
                    <ul>                
                        <li>rows="5" (<?php _e('optional', 'msc-automation'); ?>): <?php _e('Number of records to show. By default 5', 'msc-automation'); ?></li>                
                    </ul>
                </dd>
            </dl>
            <p><?php _e('Example', 'msc-automation') ?>: <input type='text' value='[last_albums rows="10"]'> </p>


            <h2>detail_song</h2>
            <p><?php _e('Shows the latest record albums', 'msc-automation') ?></p>
            <dl>
                <dt><strong><?php _e('Params', 'msc-automation') ?></strong></dt>
                <dd>
                    <ul>                
                        <li>id="1": <?php _e('Song identifier', 'msc-automation'); ?></li> 
                        <li>img_width="false" (<?php _e('optional', 'msc-automation'); ?>): <?php _e('Width of the image in pixels. By default 200', 'msc-automation'); ?></li>
                    </ul>
                </dd>
            </dl>
            <p><?php _e('Example', 'msc-automation') ?>: <input type='text' value='[detail_song id="10"]'> </p>

        </ul>
    </dd>

    <!-- Programs -->
    <h1><?php _e('Programs', 'msc-automation') ?></h1>
    <dd>
        <ul>
            <h2>show_program</h2>
            <p><?php _e('Shows program information, facebook, twitter and podcasting files', 'msc-automation') ?></p>
            <dl>
                <dt><strong><?php _e('Params', 'msc-automation') ?></strong></dt>
                <dd>
                    <ul>                
                        <li>prg="1" : <?php _e('Program identifier', 'msc-automation'); ?></li>                
                        <li>download="false" (<?php _e('optional', 'msc-automation'); ?>): <?php _e('Downloadable programs. by default false', 'msc-automation'); ?></li>                
                    </ul>
                </dd>
            </dl>
            <p><?php _e('Example', 'msc-automation'); ?>: <input type='text' value='[show_program prg="1" download="true"]'  size='50'></p>

            <h2>list_programs</h2>
            <p><?php _e('Shows a list of programs with image and link', 'msc-automation') ?></p>   
            <p><?php _e('Example', 'msc-automation'); ?>: <input type='text' value='[list_programs]'  size='50'></p>
        </ul>
    </dd>



    <!-- Podcast-->
    <h1><?php _e('Podcast', 'msc-automation') ?></h1>
    <dd>
        <ul>
            <h2>last_podcast</h2>
            <p><?php _e('Shows the latest published podcasting files', 'msc-automation') ?></p>    
            <p><?php _e('Example', 'msc-automation') ?>: <input type='text' value='[last_podcast]'> </p>    
        </ul>
    </dd>


    <!-- Social media-->
    <h1><?php _e('Social media', 'msc-automation') ?></h1>
    <dd>
        <ul>
            <h2>timeline_FaceBook</h2>
            <p><?php _e('Shows the radio station timeline', 'msc-automation') ?></p>
            <p><?php _e('Example', 'msc-automation') ?>: <input type='text' value='[timeline_FaceBook]'> </p>

            <h2>timeline_twitter</h2>
            <p><?php _e('Shows the radio station timeline', 'msc-automation') ?></p>
            <p><?php _e('Example', 'msc-automation') ?>: <input type='text' value='[timeline_twitter]'> </p>
        </ul>
    </dd>    
