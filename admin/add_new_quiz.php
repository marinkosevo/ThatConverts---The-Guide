<?php
//Admin page for listing all quizzes

    function addNewQuiz()
    {
        if (isset($_POST['create_quiz_nonce'])) {
            create_quiz($_POST);
        }
        ?>
        <div class="quizForm_wrap">
            <h1> <?php echo esc_html( get_admin_page_title() ); ?> </h1>
            <div class="quiz_navigation">
                <ul>
                    <li><a class="nav_links active" id="Quiz_link"><?php _e('Quiz info', 'thatconverts_theguide'); ?></a></li>
                    <li><a class="nav_links" id="Results_link" ><?php _e('Results', 'thatconverts_theguide'); ?></a></li>
                    <li><a class="nav_links" id="Questions_link" ><?php _e('Questions', 'thatconverts_theguide'); ?></a></li>
                </ul>
            </div>
            <form action="admin.php?page=thatconverts_add_new_quiz" id="quiz_form" method="post" enctype="multipart/form-data">
            <div class="section_wrap" id="Quiz">

                <div class="quiz_heading">

                        <div class="question">
                                <div class="form-group">
                                    <label for="name"><?php _e('Quiz name', 'thatconverts_theguide'); ?></label><br>
                                    <input type="text" class="form-control" name="name" placeholder="<?php _e('Enter quiz name', 'thatconverts_theguide'); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="description"><?php _e('Quiz desription', 'thatconverts_theguide'); ?></label><br>
                                    <input type="text" class="form-control" name="description" placeholder="<?php _e('Enter quiz description', 'thatconverts_theguide'); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="description"><?php _e('Results page title', 'thatconverts_theguide'); ?></label><br>
                                    <input type="text" class="form-control" name="results_title" placeholder="<?php _e('Enter results page title', 'thatconverts_theguide'); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="description"><?php _e('Results page desription', 'thatconverts_theguide'); ?></label><br>
                                    <input type="text" class="form-control" name="results_description" placeholder="<?php _e('Enter results page description', 'thatconverts_theguide'); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="quiz_image">Quiz image (optional)</label><br>
                                    <img class="quiz_img_preview" src=""/>
                                    <input type="hidden" name="quiz_image">
                                    <input type="button" class="button-primary img_upload" value="<?php esc_attr_e( 'Select a image', 'thatconverts_theguide' ); ?>">
                                </div>
                        </div>
                        <div class="question">
                                <div class="form-group">
                                    <input type="checkbox" id="collect_results" name="collect_results" value="1">
                                    <label for="collect_results"><?php _e('Collect quiz results?', 'thatconverts_theguide'); ?></label><br>
                                </div>
                        </div>
                        <div class="question">

                                <div class="form-group">
                                    <input type="checkbox" id="collect_email" name="collect_email" value="1">
                                    <label for="collect_email"><?php _e('Email address required?', 'thatconverts_theguide'); ?></label><br>
                                </div>
                                <div class="form-group email" style="display:none;">
                                    <label for="email_description"><?php _e('Email descripton', 'thatconverts_theguide'); ?></label><br>
                                    <input type="text" class="form-control" id="email_description" name="email_description" name="name" placeholder="<?php _e('Enter email text', 'thatconverts_theguide'); ?>" disabled>
                                </div>

                        </div>
                        <div class="button_navigation">
                            <button type="button" value="Next" id="first_next">Next </button>
                            <div class="warning" id="first_warning"></div>
                        </div>
                </div>
            </div>
            <!--- Result -->
            <div class="section_wrap" id="Results" style="display:none">
                    <div id="results_accordion" class="collapse">
                        <div class="form-group">
                            <label for="button"><?php _e('Add new result (Max 20)', 'thatconverts_theguide'); ?></label>
                            <button type="button" class="btn btn-default" id="add_result" aria-label="Left Align">+</button>
                        </div>
                    </div>
                    <div class="button_navigation">
                            <button type="button" value="Next" id="second_next">Next </button>
                            <div class="warning" id="second_warning">
                            </div>
                        </div>

            </div>
            <!--- End result -->
            <!--- Questions -->
            <div class="section_wrap" id="Questions" style="display:none">
                    <div id="questions_accordion" class="collapse">
                        <div class="form-group">
                            <label for="button"><?php _e('Add new question (Max 10)', 'thatconverts_theguide'); ?></label>
                            <select name="question_type" id="question_type">
                                <option value="" selected disabled hidden><?php _e('Type of question', 'thatconverts_theguide'); ?></option>
                                <option value="selection"><?php _e('Multiple answer selection', 'thatconverts_theguide'); ?></option>
                                <option value="text"><?php _e('Text input', 'thatconverts_theguide'); ?></option>
                                <option value="number"><?php _e('Number input', 'thatconverts_theguide'); ?></option>
                            </select>
                            <button disabled type="button" class="btn btn-default" id="add_question" aria-label="Left Align">+</button>
                        </div>
                    </div>
                    <div class="button_navigation">
                        <button id="submit_btn" type="submit" value="Submit" disabled>Submit </button>
                        <div class="warning" id="third_warning">
                        </div>
                        <div class="warning question_details_w"></div>
                        <div class="warning empty_answers_w"></div>
                        <div class="warning answer_details_w"></div>

                    </div>

            </div>
            <!--- End questions -->
            <?php 
            wp_nonce_field( 'create_quiz', 'create_quiz_nonce' );
            ?>
            </form>

        </div>
        <?php
    }

    function create_quiz($data){
        global $wpdb;

        //Quiz table insert
        $quiz_name = $data['name'];
        $quiz_description = $data['description'];
        $results_title = $data['results_title'];
        $results_description = $data['results_description'];
        if(isset($data['collect_results']))
            $collect_results = $data['collect_results'];
        else
            $collect_results = 0;
        if(isset($data['collect_email']))
            $collect_email = $data['collect_email'];
        else
            $collect_email = 0;
            if(isset($data['email_description']))
            $email_description = $data['email_description'];
        else
            $email_description = '';
        if(isset($data['quiz_image']))
            $quiz_image = $data['quiz_image'];
        else
            $quiz_image = '';
                                        
        $date = date("Y-m-d h:i:sa");
        $quiz_table = $wpdb->prefix.'quizzes';
        $questions_table = $wpdb->prefix.'quiz_questions';
        $answers_table = $wpdb->prefix.'quiz_answers';
        $results_table = $wpdb->prefix.'quiz_results';
        $result = $wpdb->insert($quiz_table, array(
            'name' => $quiz_name,
            'description' => $quiz_description,
            'results_title' => $results_title,
            'results_description' => $results_description,
            'collect_results' => $collect_results,
            'collect_email' => $collect_email,
            'quiz_image' => $quiz_image,
            'email_description' => $email_description,
            'createdAt' => $date
            ));
        $quiz_id = $wpdb->insert_id;

        //End Quiz table insert
        //Results table insert
         if(isset($_POST['result'])){
            $results = $_POST['result'];
                foreach($results as $result_key=>$result){
                    $result = $wpdb->insert($results_table, array(
                        'quiz_id' => $quiz_id,
                        'result_nr' => $result_key,
                        'title' => $result['title'],
                        'description' => $result['description'],
                        'image' => $result['image'],
                        'link' => $result['link'],
                        'createdAt' => $date
                        ));
                }
            }
        //End results table insert
        //Questions and answers table insert
        if(isset($_POST['question'])){
            $questions = $_POST['question'];
            foreach($questions as $question_key=>$question){
                if(!isset($question['multiple_answers']))
                    $question['multiple_answers'] = 0;
                $result = $wpdb->insert($questions_table, array(
                        'quiz_id' => $quiz_id,
                        'name' => $question['title'],
                        'question_nr' => $question['question_nr'],
                        'question' => $question['question'],
                        'question_type' => $question['question_type'],
                        'multiple_answers' => $question['multiple_answers'],
                        'createdAt' => $date
                        ));
                $question_id = $wpdb->insert_id;

                
                //Answers table insert

                if($question['question_type'] == 'number'){
                    if(isset($_POST['answers'][$question_key])){
                        $answers = $_POST['answers'][$question_key];
                            foreach($answers as $answer){
                                if(!isset($answer['answer_text']))
                                    $answer['answer_text'] = '';
                                if(!isset($answer['disqualify']))
                                    $answer['disqualify'] = 0;
                                else
                                    $answer['disqualify'] = implode("," , $answer['disqualify']);
                                if(!isset($answer['result']))
                                    $answer['result'] = 0;
                                else
                                    $answer['result'] = implode("," , $answer['result']);
                                $result = $wpdb->insert($answers_table, array(
                                        'question_id' => $question_id,
                                        'result_nr' => $answer['result'],
                                        'answer_nr' => $answer['answer_nr'],
                                        'answer_dq_nr' => $answer['disqualify'],
                                        'text' => $answer['answer_text'],
                                        'number1' => $answer['number1'],
                                        'number2' => $answer['number2'],
                                        'createdAt' => $date
                                        ));
                            }
                        }
                    }
                else{
                    if(isset($_POST['answers'][$question_key])){
                        $answers = $_POST['answers'][$question_key];
                            foreach($answers as $key=>$answer){

                            //Check if image is set
                                if(!isset($answer['image']))
                                    $answer['image'] = '';
                                if(!isset($answer['answer_text']))
                                    $answer['answer_text'] = '';
                                if(!isset($answer['disqualify']))
                                    $answer['disqualify'] = 0;
                                else
                                    $answer['disqualify'] = implode("," , $answer['disqualify']);
                                if(!isset($answer['result']))
                                    $answer['result'] = 0;
                                else
                                    $answer['result'] = implode("," , $answer['result']);
                                $result = $wpdb->insert($answers_table, array(
                                        'question_id' => $question_id,
                                        'result_nr' => $answer['result'],
                                        'answer_nr' => $answer['answer_nr'],
                                        'answer_dq_nr' => $answer['disqualify'],
                                        'answer_icon' => $answer['image'],
                                        'text' => $answer['answer_text'],
                                        'createdAt' => $date
                                        ));
                            }
                        }
                    }   
 
                         
            }
            //End Questions table insert
        }
 
        if($result){
            echo "<br><h2>";
            printf(__('Successfully added new quiz: %s', 'thatconverts_theguide'), $quiz_name);
                echo "</h2>";
        }

    }
    
// Ajax action to refresh the user image
add_action( 'wp_ajax_quiz_get_image', 'quiz_get_image'   );
function quiz_get_image() {
    if(isset($_GET['id']) ){
        $image = wp_get_attachment_image_src( filter_input( INPUT_GET, 'id', FILTER_VALIDATE_INT ), 'thumbnail' );
        wp_send_json_success( $image[0] );
    } else {
        wp_send_json_error();
    }
}