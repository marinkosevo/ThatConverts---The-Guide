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
            <form action="admin.php?page=thatconverts_add_new_quiz" id="quiz_form" method="post">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="name" placeholder="Enter quiz name...">
                </div>
                <!--Accordion wrapper-->
                
                <!-- Accordion wrapper -->
                <div class="form-group">
                    <label for="button">Add new question (Max 8)</label>
                    <button type="button" class="btn btn-default" id="add_question" aria-label="Left Align">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </button>
                </div>
            <?php 
            wp_nonce_field( 'create_quiz', 'create_quiz_nonce' );
            submit_button('Submit');
            ?>
            </form>

        </div>
        <?php
    }

    function create_quiz($data){
        global $wpdb;

        //Quiz table insert
        $quiz_name = $data['name'];
        $date = date("Y-m-d h:i:sa");
        $quiz_table = $wpdb->prefix.'quizzes';
        $questions_table = $wpdb->prefix.'quiz_questions';
        $answers_table = $wpdb->prefix.'quiz_answers';
        $result = $wpdb->insert($quiz_table, array(
            'name' => $quiz_name,
            'createdAt' => $date
            ));
        //End Quiz table insert

        //Questions and answers table insert
        $quiz_id = $wpdb->insert_id;
        $questions = $_POST['question'];
        foreach($questions as $question_key=>$question){
            $questions_values = "($quiz_id, '$question[title]', '$question[question]', '$date')";
            $wpdb->query("INSERT INTO $questions_table
            (`quiz_id`, `name`, `question`, `createdAt`)
            VALUES
            $questions_values");
            $question_id = $wpdb->insert_id;

            //Answers table insert
            $answers = $_POST['answers'][$question_key];
            foreach($answers as $answer){
                $answers_values = "($question_id, '$answer', '$date')";
                $wpdb->query("INSERT INTO $answers_table
                (`question_id`, `text`, `createdAt`)
                VALUES
                $answers_values");   
            }
        }
        $questions_values = substr($questions_values, 0, -1);
        //End Questions table insert

        if($result){
            echo "<br><h2>";
            echo 'Successfully added new quiz: '.$quiz_name;
            echo "</h2>";
        }

    }