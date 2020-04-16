<?php
//Admin page for listing all quizzes

    function deleteQuiz($quiz_id){

            global $wpdb;

            $questions_get_sql = "SELECT * FROM {$wpdb->prefix}quiz_questions WHERE quiz_id = $quiz_id";
            $questions_data = $wpdb->get_results( $questions_get_sql, 'ARRAY_A' );
            foreach($questions_data as $question_key=>$question){       
                $question_id = $question['id'];
                $wpdb->query(" DELETE FROM {$wpdb->prefix}quiz_answers WHERE question_id = $question_id ");

            }        
            $wpdb->query(" DELETE FROM {$wpdb->prefix}quizzes WHERE id = $quiz_id ");
            $wpdb->query(" DELETE FROM {$wpdb->prefix}quiz_results WHERE quiz_id = $quiz_id ");
            $wpdb->query(" DELETE FROM  {$wpdb->prefix}quiz_questions WHERE quiz_id = $quiz_id ");    
    }