<?php
add_action("wp_ajax_thatconverts_theguide_submit", "thatconverts_theguide_submit");
add_action("wp_ajax_nopriv_thatconverts_theguide_submit", "thatconverts_theguide_submit");

function thatconverts_theguide_submit(){
    global $wpdb;
    $quiz_table = $wpdb->prefix.'quizzes';
    $questions_table = $wpdb->prefix.'quiz_questions';
    $answers_table = $wpdb->prefix.'quiz_answers';
    $results_table = $wpdb->prefix.'quiz_results';
    $data_table = $wpdb->prefix.'quiz_data';

    $results = array();
    $questions = $_POST['question'];
    $quiz_id = $_POST['quiz_id'];
    $result = 0;

    $all_results_sql = "SELECT `result_nr` FROM $results_table WHERE quiz_id = $quiz_id";
    $all_results_data = $wpdb->get_results( $all_results_sql, 'ARRAY_A' );
    foreach($all_results_data as $all_results_data_key=>$result){
        $results[$result['result_nr']] = 0;
    }
    //Collecting data in $quiz_data
    $quiz_data = array();

    foreach($questions as $question_key=>$question){
        $database_questions_sql = "SELECT * FROM $questions_table WHERE quiz_id = $question_key";
        $database_questions = $wpdb->get_results( $database_questions_sql, 'ARRAY_A' );
    
        $quiz_data[$question_key]['question_title'] = $question['name'];
        $quiz_data[$question_key]['question_text'] = $question['question'];

        $answer = $question['answer'];
        if($question['type'] == 'selection'){
            if(isset($question['multiple_answers'])){

                foreach($answer as $selected){
                    $answer_sql = "SELECT * FROM $answers_table WHERE id = $selected";
                    $answer_data = $wpdb->get_results( $answer_sql, 'ARRAY_A' );
                    $result_nrs = explode(',', $answer_data[0]['result_nr']);
                    $disqualify_nrs = explode(',', $answer_data[0]['answer_dq_nr']);

                    foreach($result_nrs as $result_nr){
                        $results[$result_nr] += 1;

                    }
                    foreach($disqualify_nrs as $disqualify_nr){
                        $results[$disqualify_nr] += (-999);

                    }
                    $quiz_data[$question_key]['answer'] = $answer_data[0]['text'];
                }
            }
            else{
                $answer_sql = "SELECT * FROM $answers_table WHERE id = $answer";
                $answer_data = $wpdb->get_results( $answer_sql, 'ARRAY_A' );
                $result_nrs = explode(',', $answer_data[0]['result_nr']);
                $disqualify_nrs = explode(',', $answer_data[0]['answer_dq_nr']);

                foreach($result_nrs as $result_nr){
                    $results[$result_nr] += 1;

                }
                foreach($disqualify_nrs as $disqualify_nr){
                    $results[$disqualify_nr] += (-999);

                }
                $quiz_data[$question_key]['answer'] = $answer_data[0]['text'];
            }
        }
        else if($question['type'] == 'text'){
            $quiz_data[$question_key]['answer'] = $answer;

            //Check conditions
            $answers_sql = "SELECT `result_nr`, `answer_dq_nr` FROM $answers_table WHERE question_id = $question_key AND text = '$answer'";
            $answer_data = $wpdb->get_results( $answers_sql, 'ARRAY_A' );
            if(!empty($answer_data)){
                $result_nrs = explode(',', $answer_data[0]['result_nr']);
                $disqualify_nrs = explode(',', $answer_data[0]['answer_dq_nr']);

                foreach($result_nrs as $result_nr){
                    $results[$result_nr] += 1;
                }
                foreach($disqualify_nrs as $disqualify_nr){
                    $results[$disqualify_nr] += (-999);
                }
            }
            //Select default answer if none of the conditions are met
            else{
                $answers_sql = "SELECT `result_nr`, `answer_dq_nr` FROM $answers_table WHERE question_id = $question_key AND answer_nr = 0";
                $answer_data = $wpdb->get_results( $answers_sql, 'ARRAY_A' );
                $result_nrs = explode(',', $answer_data[0]['result_nr']);
                $disqualify_nrs = explode(',', $answer_data[0]['answer_dq_nr']);

                foreach($result_nrs as $result_nr){
                    $results[$result_nr] += 1;
                }
                foreach($disqualify_nrs as $disqualify_nr){
                    $results[$disqualify_nr] += (-999);
                }

                /* 
                foreach($answers_data as $key=>$answer){
                    if($answer['result_nr'] > 0)
                    $results[$answers_data[$key]['result_nr']] += 1;
                    else
                    $results[$answers_data[$key]['answer_dq_nr']] += (-999);
                } */
            }

        }
        else{
            $quiz_data[$question_key]['answer'] = $answer;

            //Check conditions
            $answers_sql = "SELECT `result_nr`, `answer_dq_nr` FROM $answers_table WHERE question_id = $question_key AND number1 <= $answer AND number2 >= $answer";
            $answers_data = $wpdb->get_results( $answers_sql, 'ARRAY_A' );
            if(!empty($answers_data)){
                foreach($answers_data as $answer_data){
                    $result_nrs = explode(',', $answer_data['result_nr']);
                    $disqualify_nrs = explode(',', $answer_data['answer_dq_nr']);

                    foreach($result_nrs as $result_nr){
                        $results[$result_nr] += 1;
                    }
                    foreach($disqualify_nrs as $disqualify_nr){
                        $results[$disqualify_nr] += (-999);
                    }
                }
            }
            //Select default answer if none of the conditions are met
            else{
                $answers_sql = "SELECT `result_nr`, `answer_dq_nr` FROM $answers_table WHERE question_id = $question_key AND answer_nr = 0";
                $answers_data = $wpdb->get_results( $answers_sql, 'ARRAY_A' );
                foreach($answers_data as $answer_data){
                    $result_nrs = explode(',', $answer_data['result_nr']);
                    $disqualify_nrs = explode(',', $answer_data['answer_dq_nr']);

                    foreach($result_nrs as $result_nr){
                        $results[$result_nr] += 1;
                    }
                    foreach($disqualify_nrs as $disqualify_nr){
                        $results[$disqualify_nr] += (-999);
                    }
                }
            }


        }
    }

    //Calculation of input data
    //remove all zeros first!
    unset($results["0"]);

    $max_indexes = array_keys($results, max($results));
    $array = implode("','",$max_indexes);

    $result_description_sql = "SELECT * FROM $quiz_table WHERE id = $quiz_id";
    $result_description_data = $wpdb->get_results( $result_description_sql, 'ARRAY_A' );

    $results_sql = "SELECT * FROM $results_table WHERE quiz_id = $quiz_id AND result_nr IN ('".$array."')";
    $results_data = $wpdb->get_results( $results_sql, 'ARRAY_A' );
    
    $results_html = '';
    $i = 1;
        $results_html .= '<div class="results_wrap"><h1>'.$result_description_data[0]['results_title'].'</h1><br> 
                            <p class="description">  '.$result_description_data[0]['results_description'].'</p><br>';
    foreach($results_data as $result_key=>$result){
        $results_html .= '<div class="result" id="result'.$i.'">';
        $i++;
        if($result['image'] != '')
        $results_html .= '<img src="'.$result['image'].'">';
        $results_html .= '<div class="result_description"><h2>' .$result['title']. '</h2>';
        $results_html .= '<span>' .$result['description']. '</span></div>';
        if($result['link'] != '')
        $results_html .= '<a href="'.$result['link']. '"><button type="button" style="margin-top: 10px;" class="quiz_btn">'. __('More here', 'thatconverts_theguide').'</button></a>';
        
        $results_html .= '</div>';
    } 
    $results_html .=  '<div id="back_buttons"><p id="prev_last">'. __('Back to quiz', 'thatconverts_theguide').' </p>';
    $results_html .=  '<p id="back_to_start">'. __('Back to start', 'thatconverts_theguide').' </p></div>';

    //Data collection (if selected so)
    if($_POST['quiz_collect']){
            $date = date("Y-m-d h:i:sa");

            $email = $_POST['quiz_email'];
            $quiz_data = serialize($quiz_data);
            $values = "($quiz_id, '$quiz_data', '$email', '$date')";

            $query="INSERT INTO $data_table
            (`quiz_id`, `quiz_data`, `email`, `createdAt`)
            VALUES
            $values";   
            $wpdb->query($query);   

    }
    echo json_encode($results_html, JSON_UNESCAPED_SLASHES);

    wp_die(); 
}
