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
                    <li><a class="nav_links active" id="Quiz_link" onclick="openTab('Quiz')"><?php _e('Quiz info', 'thatconverts_theguide'); ?></a></li>
                    <li><a class="nav_links" id="Results_link" onclick="openTab('Results')"><?php _e('Results', 'thatconverts_theguide'); ?></a></li>
                    <li><a class="nav_links" id="Questions_link" onclick="openTab('Questions')"><?php _e('Questions', 'thatconverts_theguide'); ?></a></li>
                </ul>
            </div>
            <form action="admin.php?page=thatconverts_add_new_quiz" id="quiz_form" method="post" enctype="multipart/form-data">
            <div class="section_wrap" id="Quiz">

                <div class="quiz_heading">

                        <div class="form-group">
                            <div class="quiz_heading">
                                <h2><?php _e('Quiz name', 'thatconverts_theguide'); ?></h2>
                            </div>
                                <div class="form-group">
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
                                    <input type="checkbox" id="collect_results" name="collect_results" value="1">
                                    <label for="collect_results"><?php _e('Collect quiz results?', 'thatconverts_theguide'); ?></label><br>
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" id="collect_email" name="collect_email" value="1">
                                    <label for="collect_email"><?php _e('Email address required?', 'thatconverts_theguide'); ?></label><br>
                                </div>
                                <div class="form-group email" style="display:none;">
                                    <label for="email_description"><?php _e('Email descripton', 'thatconverts_theguide'); ?></label><br>
                                    <input type="text" class="form-control" id="email_description" name="email_description" name="name" placeholder="<?php _e('Enter email text', 'thatconverts_theguide'); ?>" disabled>
                                </div>

                        </div>
                </div>
            </div>
            <!--- Result -->
            <div class="section_wrap" id="Results" style="display:none">
                    <div class="quiz_heading">
                        <h2><?php _e('Results', 'thatconverts_theguide'); ?></h2>
                   </div>
                    <div id="results_accordion" class="collapse">
                        <div class="form-group">
                            <label for="button"><?php _e('Add new result (Max 5)', 'thatconverts_theguide'); ?></label>
                            <button type="button" class="btn btn-default" id="add_result" aria-label="Left Align">+</button>
                        </div>
                    </div>
            </div>
            <!--- End result -->
            <!--- Questions -->
            <div class="section_wrap" id="Questions" style="display:none">

                    <div class="quiz_heading">
                        <h2><?php _e('Questions', 'thatconverts_theguide'); ?></h2>
                    </div>
                    <div id="questions_accordion" class="collapse">
                        <div class="form-group">
                            <label for="button"><?php _e('Add new question (Max 8)', 'thatconverts_theguide'); ?></label>
                            <select name="question_type" id="question_type">
                                <option value="" selected disabled hidden><?php _e('Type of question', 'thatconverts_theguide'); ?></option>
                                <option value="selection"><?php _e('Multiple answer selection', 'thatconverts_theguide'); ?></option>
                                <option value="text"><?php _e('Text input', 'thatconverts_theguide'); ?></option>
                                <option value="number"><?php _e('Number input', 'thatconverts_theguide'); ?></option>
                            </select>
                            <button disabled type="button" class="btn btn-default" id="add_question" aria-label="Left Align">+</button>
                        </div>
                    </div>
            </div>
            <!--- End questions -->
            <?php 
            wp_nonce_field( 'create_quiz', 'create_quiz_nonce' );
            ?>
                <input class="quiz_button" type="submit" value="Submit">
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
            'email_description' => $email_description,
            'createdAt' => $date
            ));
        $quiz_id = $wpdb->insert_id;

        //End Quiz table insert
        //Results table insert
        $uploads = wp_upload_dir();
        $upload_path = str_replace('\\', '/', $uploads['path']); // now how to get just the directory name?
        $upload_url = str_replace('\\', '/', $uploads['url']); // now how to get just the directory name?
         if(isset($_POST['result'])){
            $results = $_POST['result'];
                foreach($results as $result_key=>$result){
                    //Check if image is set
                    if($_FILES['result']['name'][$result_key]['image'] != ''){ 
                        $target_file = $upload_path .'/' . $_FILES['result']['name'][$result_key]['image'];
                        $target_url = $upload_url .'/' . $_FILES['result']['name'][$result_key]['image'];
                        move_uploaded_file($_FILES['result']['tmp_name'][$result_key]['image'], $target_file);
                        }
                    else 
                        $target_url = '';
                    
                    $result_values = "($quiz_id, $result_key, '$result[title]', '$result[description]', '$target_url', '$result[link]', '$date')";
                    $wpdb->query("INSERT INTO $results_table
                    (`quiz_id`, `result_nr`, `title`, `description`, `image`, `link`, `createdAt`)
                    VALUES
                    $result_values");   


                }
            }
        //End results table insert
        //Questions and answers table insert
        if(isset($_POST['question'])){
            $questions = $_POST['question'];
            foreach($questions as $question_key=>$question){
                if(!isset($question['multiple_answers']))
                    $question['multiple_answers'] = 0;
                $questions_values = "($quiz_id, '$question[title]', '$question[question_nr]', '$question[question]', '$question[question_type]', '$question[multiple_answers]', '$date')";
                $wpdb->query("INSERT INTO $questions_table
                (`quiz_id`, `name`, `question_nr`, `question`, `question_type`, `multiple_answers`, `createdAt`)
                VALUES
                $questions_values");
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
                                if(!isset($answer['result']))
                                    $answer['result'] = 0;

                                $answers_values = "($question_id, '$answer[result]', '$answer[answer_nr]', '$answer[disqualify]', '$answer[answer_text]', '$answer[number1]', '$answer[number2]', '$date')";
                                $query = "INSERT INTO $answers_table
                                (`question_id`, `result_nr`, `answer_nr`, `answer_dq_nr`, `text`, `number1`, `number2`, `createdAt`)
                                VALUES
                                $answers_values";
                                $wpdb->query($query);   
                            }
                        }
                    }
                else{
                    if(isset($_POST['answers'][$question_key])){
                        $answers = $_POST['answers'][$question_key];
                            foreach($answers as $key=>$answer){

                            //Check if image is set
                            if($_FILES['answers']['name'][$question_key][$key]['image'] != ''){ 
                                $target_file = $upload_path .'/' . $_FILES['answers']['name'][$question_key][$key]['image'];
                                $target_url = $upload_url .'/' . $_FILES['answers']['name'][$question_key][$key]['image'];
                                move_uploaded_file($_FILES['answers']['tmp_name'][$question_key][$key]['image'], $target_file);#
                                }
                            else 
                                $target_url = '';
                    
                                if(!isset($answer['answer_text']))
                                    $answer['answer_text'] = '';
                                if(!isset($answer['disqualify']))
                                    $answer['disqualify'] = 0;
                                if(!isset($answer['result']))
                                    $answer['result'] = 0;

                                    $answers_values = "($question_id, '$answer[result]', '$answer[answer_nr]', '$target_url', '$answer[disqualify]', '$answer[answer_text]', '$date')";
                                    $query="INSERT INTO $answers_table
                                    (`question_id`, `result_nr`, `answer_nr`, `answer_icon`, `answer_dq_nr`, `text`, `createdAt`)
                                    VALUES
                                    $answers_values";   
                                    $wpdb->query($query);   

                            }
                        }
                    }   
 
                         
            }
            $questions_values = substr($questions_values, 0, -1);
            //End Questions table insert
        }
 
        if($result){
            echo "<br><h2>";
            printf(__('Successfully added new quiz: %s', 'thatconverts_theguide'), $quiz_name);
                echo "</h2>";
        }

    }