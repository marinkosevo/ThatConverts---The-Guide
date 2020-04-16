<?php
function thatconverts_shortcode($atts) { 
    global $wpdb;
    //Tables
    $quiz_table = $wpdb->prefix.'quizzes';
    $questions_table = $wpdb->prefix.'quiz_questions';
    $answers_table = $wpdb->prefix.'quiz_answers';
    $results_table = $wpdb->prefix.'quiz_results';
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

    if(!empty($results_data)){
    // Things that you want to do. 
   
    $quiz_page .= '<div class="quiz_wrap">'; 
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
                  <h1>'.$quiz_data[0]['name'].'</h1><br> 
                  <p class="description">  '.$quiz_data[0]['description'].'</p><br>'; 

    $quiz_page .= '<button type="button" id="start_btn"class="quiz_btn">Start quiz <i class="button_icon"></i></button> 
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

        $quiz_page .= '<h3>' .$question['question']. '</h3>';
        $quiz_page .= '<h4>' .$question['name']. '</h4>';
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
                if($answer['result_nr'] > 0){
                    $quiz_page .= '<div class="option_container">';
                    $quiz_page .= '<label class="radio_container" style="background-image: url('.$answer['answer_icon'].')"><input type="'.$input.'" id="" name="'.$name.'" value="'.$answer['id'].'">
                    </label>';
                    $quiz_page .= '<span class="option_description">'.$answer['text'].'</span></div>';

                }
                
                else{
                    $quiz_page .= '<div class="option_container">';
                    $quiz_page .= '<label class="radio_container" style="background-image: url('.$answer['answer_icon'].')"><input type="'.$input.'" id="" name="'.$name.'" value="-'.$answer['id'].'">
                    </label>';
                    $quiz_page .= '<span class="option_description">'.$answer['text'].'</span></div>';

                }
            }
            $quiz_page .= '</div>';
        }
        //Pagination, if last page then submit button
        $quiz_page .= '<p class="prev">'. __('Previous', 'thatconverts_theguide').' </p> '; 

        if($question_key != (count($questions_data)-1)){
            $quiz_page .= '<button type="button" class="quiz_btn next" disabled>'. __('Next', 'thatconverts_theguide').' <i class="button_icon"></i></button> '; 
        }
        else if ($quiz_data[0]['collect_email'] == 1){
            $quiz_page .= '<button type="button" class="quiz_btn next email" disabled>'. __('Next', 'thatconverts_theguide').' <i class="button_icon"></i></button> '; 
        }
        else 
        $quiz_page .= '<button type="submit" class="quiz_btn next" id="quiz_submit" disabled>'. __('Submit', 'thatconverts_theguide').'<i class="button_icon"></i></button>';
        $quiz_page .= '</div> </div>';

    } 
    if ($quiz_data[0]['collect_email'] == 1){
        $quiz_page .= '<div class="question_wrap" id="question'.$i.'">
                        <h3>' .$quiz_data[0]['email_description'] .'</h3>
                        <div class="answers_wrap">
                            <input style="margin-bottom:10px;" type="text" class="email" placeholder="@" name="quiz_email">
                            <div class="email_wrap">
                                <input type="checkbox" id="email_contact" name="email_contact" value="1">
                                <label for="email_contact">'.__('Use this email for contact purposes', 'thatconverts_theguide').'</label><br>
                            </div>
                         <p class="prev">'. __('Previous', 'thatconverts_theguide').' </p>
                        <button type="submit" class="quiz_btn next" id="quiz_submit" disabled>'. __('Submit', 'thatconverts_theguide').'<i class="button_icon"></i></button> 
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
// register shortcode
add_shortcode('thatconverts_quiz', 'thatconverts_shortcode'); 