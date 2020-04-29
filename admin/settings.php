<?php
//Admin page for listing all quizzes

    function quiz_settings()
    {
        if (isset($_POST['settings_nonce'])) {
            settings_update($_POST);
        }
        global $wpdb;
        $settings_table = $wpdb->prefix.'quiz_settings';

        $setting_sql = "SELECT * FROM $settings_table";
        $settings_data = $wpdb->get_results( $setting_sql, 'ARRAY_A' );
        if(!isset($settings_data[0]['start_btn']))
            $settings_data[0]['start_btn'] = '';            
        if(!isset($settings_data[0]['next_btn']))
            $settings_data[0]['next_btn'] = '';            
        if(!isset($settings_data[0]['prev_btn']))
            $settings_data[0]['prev_btn'] = '';            
        if(!isset($settings_data[0]['submit_btn']))
            $settings_data[0]['submit_btn'] = '';            
        if(!isset($settings_data[0]['title_color']))
            $settings_data[0]['title_color'] = '';            
        if(!isset($settings_data[0]['desc_color']))
            $settings_data[0]['desc_color'] = '';            
        if(!isset($settings_data[0]['btn_color']))
            $settings_data[0]['btn_color'] = '';            
        if(!isset($settings_data[0]['back_quiz']))
            $settings_data[0]['back_quiz'] = '';            
        if(!isset($settings_data[0]['back_start']))
            $settings_data[0]['back_start'] = '';            
        if(!isset($settings_data[0]['more_btn']))
            $settings_data[0]['more_btn'] = '';            
        ?>
        <div class="quizForm_wrap">
            <h1> <?php echo esc_html( get_admin_page_title() ); ?> </h1>
            <form action="admin.php?page=thatconverts_settings" id="settings_form" method="post">
            <div class="section_wrap" id="Quiz">

                <div class="quiz_heading">
                        <h2> <?php _e('Buttons', 'thatconverts_theguide'); ?> </h2>
                        <div class="question">
                                <div class="form-group">
                                    <label for="name"><?php _e('Start Button', 'thatconverts_theguide'); ?></label><br>
                                    <input type="text" class="form-control" name="start" value="<?php  echo (($settings_data[0]['start_btn'] != '')) ? $settings_data[0]['start_btn'] : 'Start quiz'; ?>" placeholder="<?php _e('Enter "Start" button text', 'thatconverts_theguide'); ?>">
                                </div>
                        </div>
                        <div class="question">
                                <div class="form-group">
                                    <label for="name"><?php _e('Next button', 'thatconverts_theguide'); ?></label><br>
                                    <input type="text" class="form-control" name="next" value="<?php  echo (($settings_data[0]['next_btn'] != '')) ? $settings_data[0]['next_btn'] : 'Next'; ?>" placeholder="<?php _e('Enter "Next" button text', 'thatconverts_theguide'); ?>">
                                </div>
                        </div>
                        <div class="question">
                                <div class="form-group">
                                    <label for="name"><?php _e('Previous button', 'thatconverts_theguide'); ?></label><br>
                                    <input type="text" class="form-control" name="previous" value="<?php  echo (($settings_data[0]['prev_btn'] != '')) ? $settings_data[0]['prev_btn'] : 'Previous'; ?>" placeholder="<?php _e('Enter "Previous" button text', 'thatconverts_theguide'); ?>">
                                </div>
                        </div>
                        <div class="question">
                                <div class="form-group">
                                    <label for="name"><?php _e('Submit button', 'thatconverts_theguide'); ?></label><br>
                                    <input type="text" class="form-control" name="submit" value="<?php  echo (($settings_data[0]['submit_btn'] != '')) ? $settings_data[0]['submit_btn'] : 'Submit'; ?>" placeholder="<?php _e('Enter "Submit" button text', 'thatconverts_theguide'); ?>">
                                </div>
                        </div>
                        <div class="question">
                                <div class="form-group">
                                    <label for="name"><?php _e('Back to quiz button', 'thatconverts_theguide'); ?></label><br>
                                    <input type="text" class="form-control" name="back_quiz" value="<?php  echo (($settings_data[0]['back_quiz'] != '')) ? $settings_data[0]['back_quiz'] : 'Back to quiz'; ?>" placeholder="<?php _e('Enter "Back to quiz" button text', 'thatconverts_theguide'); ?>">
                                </div>
                        </div>
                        <div class="question">
                                <div class="form-group">
                                    <label for="name"><?php _e('Back to start button', 'thatconverts_theguide'); ?></label><br>
                                    <input type="text" class="form-control" name="back_start" value="<?php  echo (($settings_data[0]['back_start'] != '')) ? $settings_data[0]['back_start'] : 'Back to start'; ?>" placeholder="<?php _e('Enter "Back to start" button text', 'thatconverts_theguide'); ?>">
                                </div>
                        </div>
                        <div class="question">
                                <div class="form-group">
                                    <label for="name"><?php _e('More here button', 'thatconverts_theguide'); ?></label><br>
                                    <input type="text" class="form-control" name="more_btn" value="<?php  echo (($settings_data[0]['more_btn'] != '')) ? $settings_data[0]['more_btn'] : 'More here'; ?>" placeholder="<?php _e('Enter "More here" button text', 'thatconverts_theguide'); ?>">
                                </div>
                        </div>
                        <h2> <?php _e('Colors', 'thatconverts_theguide'); ?> </h2>

                        <div class="question">
                                <div class="form-group">
                                    <label for="name"><?php _e('Title color', 'thatconverts_theguide'); ?></label><br>
                                    <input class="jscolor" name="title_color" value="<?php  echo (($settings_data[0]['title_color'] != '')) ? $settings_data[0]['title_color'] : '#12008a'; ?>"><br><br>
                                </div>
                        </div>
                        <div class="question">
                                <div class="form-group">
                                    <label for="name"><?php _e('Description color', 'thatconverts_theguide'); ?></label><br>
                                    <input class="jscolor" name="desc_color" value="<?php  echo (($settings_data[0]['desc_color'] != '')) ? $settings_data[0]['desc_color'] : '#3d366c'; ?>"><br><br>
                                </div>
                        </div>

                        <div class="question">
                                <div class="form-group">
                                    <label for="name"><?php _e('Main color', 'thatconverts_theguide'); ?></label><br>
                                    <input class="jscolor" name="btn_color" value="<?php  echo (($settings_data[0]['btn_color'] != '')) ? $settings_data[0]['btn_color'] : '#1600a9'; ?>"><br><br>
                                </div>
                        </div>

                        <div class="button_navigation">
                            <button type="submit" value="submit">Submit </button>
                            <div class="warning" id="warning"></div>
                        </div>
                </div>
            </div>
            <!--- Result -->
            <?php 
            wp_nonce_field( 'settings', 'settings_nonce' );
            ?>
            </form>

        </div>
        <?php
    }

    function settings_update($data){
        global $wpdb;

        //Settings table insert
        $start_button = $data['start'];
        if($start_button == '')
            $start_button = 'Start quiz';

        $next_button = $data['next'];
        if($next_button == '')
            $next_button = 'Next';

        $prev_button = $data['previous'];
        if($prev_button == '')
            $prev_button = 'Previous';

        $submit_button = $data['submit'];        
        if($submit_button == '')
            $submit_button = 'Submit';

        $back_quiz = $data['back_quiz'];        
        if($back_quiz == '')
            $back_quiz = 'Back to the quiz';

        $back_start = $data['back_start'];        
        if($back_start == '')
            $back_start = 'Back to the start';

        $more_btn = $data['more_btn'];        
        if($more_btn == '')
            $more_btn = 'More here';
            
        $title_color = $data['title_color'];
        $desc_color = $data['desc_color'];
        $btn_color = $data['btn_color'];
        
        $settings_table = $wpdb->prefix.'quiz_settings';
        $delete = $wpdb->query("TRUNCATE TABLE $settings_table");
        $result = $wpdb->insert($settings_table, array(
            'start_btn' => $start_button,
            'next_btn' => $next_button,
            'prev_btn' => $prev_button,
            'submit_btn' => $submit_button,
            'back_quiz' => $back_quiz,
            'back_start' => $back_start,
            'more_btn' => $more_btn,
            'title_color' => ('#' .$title_color),
            'desc_color' => ('#' .$desc_color),
            'btn_color' => ('#' .$btn_color),
            ));

        if($result){
            echo "<br><h2>";
            printf(__('Successfully updated settings', 'thatconverts_theguide'));
                echo "</h2>";
        }

    }