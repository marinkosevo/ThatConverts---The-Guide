<?php


function thatconverts_shortcode($atts) { 
    global $wpdb;
    //Tables
    $quiz_table = $wpdb->prefix.'quizzes';
    $questions_table = $wpdb->prefix.'quiz_questions';
    $answers_table = $wpdb->prefix.'quiz_answers';
    $results_table = $wpdb->prefix.'quiz_results';
    $settings_table = $wpdb->prefix.'quiz_settings';
    $args = shortcode_atts(
        array(
            'id' => '',
        ), $atts );
    $quiz_id = $args['id'];

    $quiz_page = "";

    //Selecting data from database
    
    $quiz_sql = "SELECT * FROM $quiz_table WHERE id = $quiz_id";
    $quiz_data = $wpdb->get_results( $quiz_sql, 'ARRAY_A' );
    
    $questions_sql = "SELECT * FROM $questions_table WHERE quiz_id = $quiz_id";
    $questions_data = $wpdb->get_results( $questions_sql, 'ARRAY_A' );

    $results_sql = "SELECT * FROM $results_table WHERE quiz_id = $quiz_id";
    $results_data = $wpdb->get_results( $results_sql, 'ARRAY_A' );

    $setting_sql = "SELECT * FROM $settings_table";
    $settings_data = $wpdb->get_results( $setting_sql, 'ARRAY_A' );
    if(!empty($settings_data)){
        $start_btn = $settings_data[0]['start_btn'];            
        $next_btn = $settings_data[0]['next_btn'] ;            
        $prev_btn = $settings_data[0]['prev_btn'] ;            
        $submit_btn = $settings_data[0]['submit_btn'] ;            
        $title_color = $settings_data[0]['title_color'];            
        $desc_color = $settings_data[0]['desc_color'];            
        $btn_color = $settings_data[0]['btn_color'];            
    }
    else{
        $start_btn = 'Start quiz';            
        $next_btn = 'Next' ;            
        $prev_btn = 'Previous' ;            
        $submit_btn = 'Submit' ;            
        $title_color = '#12008a';            
        $desc_color = '#3d366c';            
        $btn_color = '#1600a9';
    }
        $hex_btn = hex2RGB($btn_color, true);
    if(!empty($results_data)){
    // Things that you want to do. 
   
    $quiz_page .= '<div class="quiz_wrap"><style>
        .quiz_wrap .quiz_title, .quiz_wrap h1, .quiz_wrap h2, .quiz_wrap h3 {
            color: '.$title_color.';
        }
        .quiz_wrap .quiz_desc, .quiz_wrap span, .quiz_wrap .description{
            color: '.$desc_color.';
        }
        .quiz_wrap .quiz_btn {
            background: '.$btn_color.';
        }
                
        .quiz_wrap .step {
            background: '.$btn_color.';
            opacity: 0.4;
        }

        .quiz_wrap .step.active {
            opacity: 1;
        }

        .quiz_wrap .step.done {
            opacity: 0.6;
        }
        .quiz_wrap .radio_container {
            border: solid 1px rgba('.$hex_btn.', 0.4);
        }

        .quiz_wrap .radio_container.active {
            border: solid 2.5px '.$btn_color.';
        }

        .quiz_wrap .radio_container:hover {
            box-shadow: 0 10px 30px 0 rgba('.$hex_btn.', 0.3);
        }

        .quiz_wrap .quiz_btn:hover, 
        .quiz_wrap .quiz_btn:focus {
            box-shadow: 0 10px 20px 0 rgba('.$hex_btn.', 0.4);
        }
        
        .quiz_wrap .prev,
        .quiz_wrap #prev_last,
        .quiz_wrap #back_to_start {
            color: '.$btn_color.';
        }
        
        .quiz_wrap .prev:hover,
        .quiz_wrap #prev_last:hover {
            opacity: 0.6;
        }
        

        #quiz_form input[type="text"],
        #quiz_form input[type="email"],
        #quiz_form input[type="number"] {
            border-bottom: 2px solid rgba('.$hex_btn.', 0.4);
            color: '.$btn_color.';
        }
        
        #quiz_form input[type="email"]:focus,
        #quiz_form input[type="email"]:hover,
        #quiz_form input[type="number"]:focus,
        #quiz_form input[type="number"]:hover,
        #quiz_form input[type="text"]:focus,
        #quiz_form input[type="text"]:hover {
            border-color: rgba('.$hex_btn.', 1);
        }
        
        #quiz_form ::placeholder,        
        #quiz_form :-ms-input-placeholder,
        #quiz_form ::-ms-input-placeholder
          {
            color: rgba('.$hex_btn.', 0.4);
        }
        
        #quiz_form label {
            opacity: 0.8;
            color: '.$btn_color.';
        }
        
    </style>
    '; 
    $quiz_page .= '<div class="mask"></div>';
    $quiz_page .= '<div id="lottie" style="display:none"></div>';

    $quiz_page .= ' <div class="counter_wrap">';
    $i = 1;  
    foreach($questions_data as $question_key=>$question){
        $quiz_page .= '<span class="step" id="step'.$i.'"></span>';
        $i++;
    };
    $quiz_page .= '</div>';
    $quiz_page .= '<div class="first_page">
                  <h2 class="quiz_title">'.$quiz_data[0]['name'].'</h2><br> 
                  <p class="quiz_desc description">  '.$quiz_data[0]['description'].'</p>';
    if(isset($quiz_data[0]['quiz_image'])) {
        $img = wp_get_attachment_image_src($quiz_data[0]['quiz_image'], 'large');
    $quiz_page .= '<img src="'.$img[0].'" class="quiz_img">'; 
    }

    $quiz_page .= '<button type="button" id="start_btn" class="quiz_btn">'.$start_btn.'<i class="button_icon"></i></button> 
                    </div>'; 
    $quiz_page .= '<form id="quiz_form">'; 
    $quiz_page .= '<input type="hidden" name="action" value="thatconverts_theguide_submit">';
    $quiz_page .= '<input type="hidden" name="quiz_id" value="'. $quiz_data[0]['id'] .'">';
    $quiz_page .= '<input type="hidden" name="quiz_collect" value="' . $quiz_data[0]['collect_results'] . '">';

    $i=1;
    foreach($questions_data as $question_key=>$question){
        $quiz_page .= '<div class="question_wrap" id="question'.$i.'">';
        $quiz_page .= '<input type="hidden" name="question['.$question['id'].'][type]" value="'. $question['question_type'] .'">';

        $i++;

        $question_id = $question['id'];
        $answers_sql = "SELECT * FROM $answers_table WHERE question_id = $question_id";
        $answers_data = $wpdb->get_results( $answers_sql, 'ARRAY_A' );

        $quiz_page .= '<h3 class="quiz_title">' .$question['question']. '</h3>';
        $quiz_page .= '<h4 class="quiz_desc">' .$question['name']. '</h4>';
        $quiz_page .= '<div class="answers_wrap">';
        if($question['question_type'] == 'text'){
            $quiz_page .= '<input type="text" name="question['.$question['id'].'][answer]">';
        }
        else if($question['question_type'] == 'number'){
            $quiz_page .= '<input type="number" name="question['.$question['id'].'][answer]">';
        }
        else {
            if($question['multiple_answers'] == 1){
                $quiz_page .= '<input type="hidden" name="question['.$question['id'].'][multiple_answers]" value="1">';
                $input = 'checkbox';
                $name = 'question['.$question['id'].'][answer][]';
            }
            else{
                $input = 'radio';
                $name = 'question['.$question['id'].'][answer]';
            }
            $quiz_page .= '<div class="options">';

            foreach($answers_data as $answer){
                
                if(isset($answer['answer_icon'])) {
                    $img = wp_get_attachment_image_src($answer['answer_icon'], 'thumbnail');
                }
                if($answer['result_nr'] > 0){
                
                    $quiz_page .= '<div class="option_container">';
                    $quiz_page .= '<label class="radio_container" style="background-image: url('.$img[0].')"><input type="'.$input.'" id="" name="'.$name.'" value="'.$answer['id'].'">
                    </label>';
                    $quiz_page .= '<span class="quiz_desc option_description">'.$answer['text'].'</span></div>';

                }
                
                else{
                    $quiz_page .= '<div class="option_container">';
                    $quiz_page .= '<label class="radio_container" style="background-image: url('.$img[0].')"><input type="'.$input.'" id="" name="'.$name.'" value="-'.$answer['id'].'">
                    </label>';
                    $quiz_page .= '<span class="quiz_desc option_description">'.$answer['text'].'</span></div>';

                }
            }
            $quiz_page .= '</div>';
        }
        //Pagination, if last page then submit button
        $quiz_page .= '<p class="prev">'. __($prev_btn, 'thatconverts_theguide').' </p> '; 

        if($question_key != (count($questions_data)-1)){
            $quiz_page .= '<button type="button" class="quiz_btn next" disabled>'. __($next_btn, 'thatconverts_theguide').' <i class="button_icon"></i></button> '; 
        }
        else if ($quiz_data[0]['collect_email'] == 1){
            $quiz_page .= '<button type="button" class="quiz_btn next email" disabled>'. __($next_btn, 'thatconverts_theguide').' <i class="button_icon"></i></button> '; 
        }
        else 
        $quiz_page .= '<button type="submit" class="quiz_btn next" id="quiz_submit" disabled>'. __($submit_btn, 'thatconverts_theguide').'<i class="button_icon"></i></button>';
        $quiz_page .= '</div> </div>';

    } 
    if ($quiz_data[0]['collect_email'] == 1){
        $quiz_page .= '<div class="question_wrap" id="question'.$i.'">
                        <h3>' .$quiz_data[0]['email_description'] .'</h3>
                        <div class="answers_wrap">
                            <input type="text" class="email" placeholder="@" name="quiz_email">
                            <div class="email_wrap">
                                <input type="checkbox" id="email_contact" name="email_contact" value="1">
                                <label for="email_contact">'.__('Use this email for contact purposes', 'thatconverts_theguide').'</label><br>
                            </div>
                         <p class="prev">'. __($prev_btn, 'thatconverts_theguide').' </p>
                        <button type="submit" class="quiz_btn next" id="quiz_submit" disabled>'. __($submit_btn, 'thatconverts_theguide').'<i class="button_icon"></i></button> 
                        </div>
                    </div>';
    }
    $quiz_page .= '</form>'; 
    $quiz_page .= '<div class="mask2"></div>';
    $quiz_page .= '</div>';
}

    // Output needs to be return
    return $quiz_page;
    } 
    function hex2RGB($hexStr, $returnAsString = false, $seperator = ',') {
        $hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
        $rgbArray = array();
        if (strlen($hexStr) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster
            $colorVal = hexdec($hexStr);
            $rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
            $rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
            $rgbArray['blue'] = 0xFF & $colorVal;
        } elseif (strlen($hexStr) == 3) { //if shorthand notation, need some string manipulations
            $rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
            $rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
            $rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
        } else {
            return false; //Invalid hex color code
        }
        return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray; // returns the rgb string or the associative array
    }
// register shortcode
add_shortcode('thatconverts_quiz', 'thatconverts_shortcode'); 