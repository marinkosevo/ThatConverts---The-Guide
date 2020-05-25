<?php
//Admin page for listing all quizzes

    function editQuiz()
    {
        if (isset($_POST['update_quiz_nonce'])) {
            update_quiz($_POST);
        }

    global $wpdb;
    $quiz_id = $_GET['quiz'];
    //Tables
    $quiz_table = $wpdb->prefix.'quizzes';
    $questions_table = $wpdb->prefix.'quiz_questions';
    $answers_table = $wpdb->prefix.'quiz_answers';
    $results_table = $wpdb->prefix.'quiz_results';
    $settings_table = $wpdb->prefix.'quiz_settings';
    //Selecting data from database
    
    $quiz_sql = "SELECT * FROM $quiz_table WHERE id = $quiz_id";
    $quiz_data = $wpdb->get_results( $quiz_sql, 'ARRAY_A' );
    //Quiz image
    $image = wp_get_attachment_image_src(  $quiz_data[0]['quiz_image'], 'thumbnail' );
    $image_src = $image[0];

    $questions_sql = "SELECT * FROM $questions_table WHERE quiz_id = $quiz_id";
    $questions_data = $wpdb->get_results( $questions_sql, 'ARRAY_A' );

    $results_sql = "SELECT * FROM $results_table WHERE quiz_id = $quiz_id ORDER BY result_nr";
    $results_data = $wpdb->get_results( $results_sql, 'ARRAY_A' );

    $setting_sql = "SELECT * FROM $settings_table";
    $settings_data = $wpdb->get_results( $setting_sql, 'ARRAY_A' );


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
            <form action="admin.php?page=thatconverts&action=edit&quiz=<?php echo $quiz_id;?>" id="quiz_form" method="post" enctype="multipart/form-data">
            <div class="section_wrap" id="Quiz">
                <div class="quiz_heading">

                        <div class="question">
                                <div class="form-group">
                                    <label for="name"><?php _e('Quiz name', 'thatconverts_theguide'); ?></label><br>
                                    <input type="text" class="form-control" name="name" value="<?php echo $quiz_data[0]['name'];?>" placeholder="<?php _e('Enter quiz name', 'thatconverts_theguide'); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="description"><?php _e('Quiz desription', 'thatconverts_theguide'); ?></label><br>
                                    <input type="text" class="form-control" name="description" value="<?php echo $quiz_data[0]['description'];?>"  placeholder="<?php _e('Enter quiz description', 'thatconverts_theguide'); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="description"><?php _e('Results page title', 'thatconverts_theguide'); ?></label><br>
                                    <input type="text" class="form-control" name="results_title" value="<?php echo $quiz_data[0]['results_title'];?>"  placeholder="<?php _e('Enter results page title', 'thatconverts_theguide'); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="description"><?php _e('Results page desription', 'thatconverts_theguide'); ?></label><br>
                                    <input type="text" class="form-control" name="results_description" value="<?php echo $quiz_data[0]['results_description'];?>"  placeholder="<?php _e('Enter results page description', 'thatconverts_theguide'); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="quiz_image">Quiz image (optional)</label><br>
                                    <img class="quiz_img_preview" src="<?php echo $image_src; ?>" style="<?php echo ($image_src != '') ? 'display:block;' : '';?>"/>
                                    <input type="hidden" name="quiz_image" value="<?php echo ($image_src != '') ? $quiz_data[0]['quiz_image'] : '';?>">
                                    <input type="button" class="button-primary img_upload" value="<?php esc_attr_e( 'Select a image', 'thatconverts_theguide' ); ?>">
                                </div>
                        </div>
                        <div class="question">
                                <div class="form-group">
                                    <input type="checkbox" id="collect_results" name="collect_results" value="1" <?php echo ($quiz_data[0]['collect_results'] == 1) ? 'checked' : '';?>>
                                    <label for="collect_results"><?php _e('Collect quiz results?', 'thatconverts_theguide'); ?></label><br>
                                </div>
                        </div>
                        <div class="question">

                                <div class="form-group">
                                    <input type="checkbox" id="collect_email" name="collect_email" value="1"  <?php echo ($quiz_data[0]['collect_email'] == 1) ? 'checked' : '';?>>
                                    <label for="collect_email"><?php _e('Email address required?', 'thatconverts_theguide'); ?></label><br>
                                </div>
                                <div class="form-group email" style="<?php echo ($quiz_data[0]['collect_email'] == 1) ? '' : 'display:none';?>">
                                    <label for="email_description"><?php _e('Email descripton', 'thatconverts_theguide'); ?></label><br>
                                    <input type="text" class="form-control" id="email_description" name="email_description" name="name" value="<?php echo $quiz_data[0]['email_description'];?>"  placeholder="<?php _e('Enter email text', 'thatconverts_theguide'); ?>" <?php echo ($quiz_data[0]['collect_email'] == 1) ? '' : 'disabled';?>>
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
            <input type="hidden" id="results_number" value="<?php echo count($results_data);?>">
                    <div id="results_accordion" class="collapse">
                    <?php
                    foreach($results_data as $key => $result){
                        $res_image = wp_get_attachment_image_src(  $result['image'], 'thumbnail' );
                        $res_image_src = $res_image[0];
                        
                    
                    ?>
                        <div class="form-group results">
                            <h3>Result </h3>
                            <button type="button" class="delete">Delete</button>
                            <div id="result<?php echo $result['result_nr'];?>" class="result">   
                                <div class="form-group">
                                    <label for="name"><?php _e('Title', 'thatconverts_theguide'); ?></label>
                                    <input type="text" class="form-control result_title" name="result[<?php echo $result['result_nr'];?>][title]"  value="<?php echo $result['title'];?>" placeholder="Enter result title...">
                                </div>
                                <div class="form-group">
                                    <label for="name"><?php _e('Description', 'thatconverts_theguide'); ?></label>
                                    <input type="text" class="form-control result_description" name="result[<?php echo $result['result_nr'];?>][description]" value="<?php echo $result['description'];?>" placeholder="Enter result text...">
                                </div>
                                <div class="form-group">
                                    <label for="name"><?php _e('Image (optional)', 'thatconverts_theguide'); ?></label>
                                    <img class="quiz_img_preview" src="<?php echo $res_image_src;?>" style="<?php echo ($res_image_src != '') ? 'display:block;' : '';?>"/>
                                    <input type="hidden" name="result[<?php echo $result['result_nr'];?>][image]" value="<?php echo ($res_image_src != '') ? $result['image'] : '';?>">
                                    <input type="button" class="button-primary img_upload" value="Select a image">
                                </div>
                                <div class="form-group">
                                    <label for="name"><?php _e('Link (optional)', 'thatconverts_theguide'); ?></label>
                                    <input type="text" class="form-control" name="result[<?php echo $result['result_nr'];?>][link]" value="<?php echo $result['link'];?>" placeholder="Enter result link...">
                                </div>
                            </div>
                        </div>
                    <?php
                        }
                    ?>
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
                    <?php
                    foreach($questions_data as $key => $question){
                        if($question['question_type'] == 'selection'){
                    ?>
                    <div class="form-group question_wrap">
                        <div class="question_heading">
                        <input type="hidden" name="question[<?php echo $question['question_nr'];?>][question_type]" value="<?php echo $question['question_type'];?>">
                        <input type="hidden" name="question[<?php echo $question['question_nr'];?>][question_nr]" value="<?php echo $question['question_nr'];?>">
                        <input type="hidden" name="question[<?php echo $question['question_nr'];?>][question_id]" value="<?php echo $question['id'];?>">
                            <h3><?php _e('Question', 'thatconverts_theguide'); ?></h3>
                            </a>
                        </div>
                        <button type="button" class="delete">Delete</button>
                        <div id="question<?php echo $question['question_nr'];?>" class="">
                            <div class="question">
                                <div class="form-group">
                                    <label for="name"><?php _e('Multiple possible answers', 'thatconverts_theguide'); ?></label>
                                    <input type="radio" name="question[<?php echo $question['question_nr'];?>][multiple_answers]" value="0" <?php echo ($question['multiple_answers'] == 0) ? 'checked' : '';?>>
                                    <label><?php _e('No', 'thatconverts_theguide'); ?></label>
                                    <input type="radio" name="question[<?php echo $question['question_nr'];?>][multiple_answers]" value="1" <?php echo ($question['multiple_answers'] == 1) ? 'checked' : '';?>>
                                    <label><?php _e('Yes', 'thatconverts_theguide'); ?></label><br>
                                </div>

                                <div class="form-group">
                                    <label for="name"><?php _e('Title', 'thatconverts_theguide'); ?></label>
                                    <input type="text" class="form-control question_text" name="question[<?php echo $question['question_nr'];?>][title]" value="<?php echo $question['name'];?>" placeholder="Enter question title...">
                                </div>
                                <div class="form-group">
                                    <label for="name"><?php _e('Question', 'thatconverts_theguide'); ?></label>
                                    <input type="text" class="form-control question_text" name="question[<?php echo $question['question_nr'];?>][question]" value="<?php echo $question['question'];?>" placeholder="Enter question text...">
                                </div>
                            </div>
                            <?php

                                    $question_id = $question['id'];
                                    $answers_sql = "SELECT * FROM $answers_table WHERE question_id = $question_id";
                                    $answers_data = $wpdb->get_results( $answers_sql, 'ARRAY_A' );        
                                    foreach($answers_data as $answer){    
                                        $ans_image = wp_get_attachment_image_src(  $answer['answer_icon'], 'thumbnail' );
                                        $ans_image_src = $ans_image[0];

                                        
                                    $logic_select = '<div class="form-group logic_select"><label>Select corresponding result</label>
                                                    <input type="hidden" class="checkbox_nr" value="[' .$question['question_nr'].'][' . $answer['answer_nr'].']">';
                                    $disqualify_select = '<div class="form-group disqualify_select">
                                    <label>Select if this answer disqualifies a result</label>
                                    <input type="hidden" class="checkbox_nr" value="[' .$question['question_nr'].'][' . $answer['answer_nr'].']">';

                                    $i = 1;
                                    foreach($results_data as $key => $result){
                                        if (strpos($answer['result_nr'],$result['result_nr']) !== false){
                                            $checked = 'checked';
                                        }
                                        else
                                            $checked = '';
                                        if (strpos($answer['answer_dq_nr'],$result['result_nr']) !== false){
                                            $dq_checked = 'checked';
                                        }
                                        else
                                            $dq_checked = '';
                                        $logic_select .=  '<div class="checkbox"><input type="checkbox" id="answers[' .$question['question_nr'].'][' . $answer['answer_nr'].'][result][' .$i. ']" name="answers[' .$question['question_nr']. '][' . $answer['answer_nr']. '][result][' .$i. ']"  value="'.$i. '" '.$checked.'>
                                        <label for="answers[' .$question['question_nr']. '][' . $answer['answer_nr']. '][result][' .$i. ']"> ' .$result['title']. '</label></div>';
                                        
                                        $disqualify_select .=  '<div class="checkbox"><input type="checkbox" id="answers[' .$question['question_nr'].'][' . $answer['answer_nr'].'][disqualify][' .$i. ']" name="answers[' .$question['question_nr']. '][' . $answer['answer_nr']. '][disqualify][' .$i. ']"  value="'.$i. '" '.$dq_checked.'>
                                        <label for="answers[' .$question['question_nr']. '][' . $answer['answer_nr']. '][disqualify][' .$i. ']"> ' .$result['title']. '</label></div>';
                                        $i++;
                                        }            
                                        $logic_select .= '</div>';
                                        $disqualify_select .= '</div>';
                                    
                                                        
                            ?>
                            <h4><?php _e('Answer', 'thatconverts_theguide'); ?><?php echo $answer['answer_nr'];?></h4>
                            <div class="answer_wrap" id="answer">
                                <div class="form-group">
                                    <label for="logic"><?php _e('Answer', 'thatconverts_theguide'); ?></label>
                                    <input type="hidden" name="answers[<?php echo $question['question_nr'];?>][<?php echo $answer['answer_nr'];?>][answer_nr]" value="<?php echo $answer['answer_nr'];?>">
                                    <input type="text" class="form-control answer_text" name="answers[<?php echo $question['question_nr'];?>][<?php echo $answer['answer_nr'];?>][answer_text]" value="<?php echo $answer['text'];?>" placeholder="Enter answer text...">
                                </div>
                                    <div class="form-group">
                                        <label for="name"><?php _e('Answer icon (optional)', 'thatconverts_theguide'); ?></label>
                                        <img class="quiz_img_preview" src="<?php echo $ans_image_src;?>" style="<?php echo ($res_image_src != '') ? 'display:block;' : '';?>"/>
                                        <input type="hidden" name="answers[<?php echo $question['question_nr'];?>][<?php echo $answer['answer_nr'];?>][image]" value="<?php echo ($ans_image_src != '') ? $answer['answer_icon'] : '';?>">
                                        <input type="button" class="button-primary img_upload" value="Select a image">
                    
                                        </div>
                                        <?php echo $logic_select; ?>
                                        <?php echo $disqualify_select; ?>
                                </div>

                            <?php 
                                    }
                            ?>
                            <div class="form-group add_answer_btn">
                            <input type="hidden" class="question_type" name="question[<?php echo $question['question_nr'];?>][question_type]" value="<?php echo $question['question_type'];?>">
                                <label for="button"><?php _e('Add new answer (Max 8)', 'thatconverts_theguide'); ?></label>
                                <button type="button" class="btn btn-default add_answer" value="<?php echo $question['question_nr'];?>" aria-label="Left Align">+</button>
                            </div>
                        </div>
                    </div>

                    <?php 
                        }
                        else{
                            $question_id = $question['id'];
                            $answers_sql = "SELECT * FROM $answers_table WHERE question_id = $question_id";
                            $answers_data = $wpdb->get_results( $answers_sql, 'ARRAY_A' );        
                            foreach($answers_data as $answer){    
                                $ans_image = wp_get_attachment_image_src(  $answer['answer_icon'], 'thumbnail' );
                                $ans_image_src = $ans_image[0];

                                
                            $logic_select_def = '<div class="form-group logic_select"><label>Select corresponding result</label>
                                            <input type="hidden" class="checkbox_nr" value="[' .$question['question_nr'].'][' . $answer['answer_nr'].']">';
                            $disqualify_select_def = '<div class="form-group disqualify_select">
                            <label>Select if this answer disqualifies a result</label>
                            <input type="hidden" class="checkbox_nr" value="[' .$question['question_nr'].'][' . $answer['answer_nr'].']">';

                            $i = 1;
                            foreach($results_data as $key => $result){
                                if (strpos($answer['result_nr'],$result['result_nr']) !== false){
                                    $checked = 'checked';
                                }
                                else
                                    $checked = '';
                                if (strpos($answer['answer_dq_nr'],$result['result_nr']) !== false){
                                    $dq_checked = 'checked';
                                }
                                else
                                    $dq_checked = '';
                                $logic_select_def .=  '<div class="checkbox"><input type="checkbox" id="answers[' .$question['question_nr'].'][' . $answer['answer_nr'].'][result][' .$i. ']" name="answers[' .$question['question_nr']. '][' . $answer['answer_nr']. '][result][' .$i. ']"  value="'.$i. '" '.$checked.'>
                                <label for="answers[' .$question['question_nr']. '][' . $answer['answer_nr']. '][result][' .$i. ']"> ' .$result['title']. '</label></div>';
                                
                                $disqualify_select_def .=  '<div class="checkbox"><input type="checkbox" id="answers[' .$question['question_nr'].'][' . $answer['answer_nr'].'][disqualify][' .$i. ']" name="answers[' .$question['question_nr']. '][' . $answer['answer_nr']. '][disqualify][' .$i. ']"  value="'.$i. '" '.$dq_checked.'>
                                <label for="answers[' .$question['question_nr']. '][' . $answer['answer_nr']. '][disqualify][' .$i. ']"> ' .$result['title']. '</label></div>';
                                $i++;
                                }            
                                $logic_select_def .= '</div>';
                                $disqualify_select_def .= '</div>';
                            }

                    ?>
                    
                       <div class="form-group question_wrap">
                            <div class="question_heading">
                            <input type="hidden" name="question[<?php echo $question['question_nr'];?>][question_id]" value="<?php echo $question['id'];?>">
                            <input type="hidden" name="question[<?php echo $question['question_nr'];?>][question_nr]" value="<?php echo $question['question_nr'];?>">
                                <h3><?php _e('Question', 'thatconverts_theguide'); ?></h3>
                            </div>
                            <button type="button" class="delete">Delete</button>
                            <div class="question">
                                <div class="form-group">
                                    <label for="name"><?php _e('Title', 'thatconverts_theguide'); ?></label>
                                    <input type="text" class="form-control question_text" name="question[<?php echo $question['question_nr'];?>][title]" value="<?php echo $question['name'];?>" placeholder="Enter question title...">
                                </div>
                                <div class="form-group">
                                    <label for="name"><?php _e('Question', 'thatconverts_theguide'); ?></label>
                                    <input type="text" class="form-control question_text" name="question[<?php echo $question['question_nr'];?>][question]" value="<?php echo $question['question'];?>" placeholder="Enter question text...">
                                </div>
                            </div>
                            
                            <?php

                                    $question_id = $question['id'];
                                    $answers_sql = "SELECT * FROM $answers_table WHERE question_id = $question_id";
                                    $answers_data = $wpdb->get_results( $answers_sql, 'ARRAY_A' );        
                                    foreach($answers_data as $answer){    
                                        $ans_image = wp_get_attachment_image_src(  $answer['answer_icon'], 'thumbnail' );
                                        $ans_image_src = $ans_image[0];
                                    if($answer['answer_nr'] != 0){
                                        
                                    $logic_select = '<div class="form-group logic_select"><label>Select corresponding result</label>
                                                    <input type="hidden" class="checkbox_nr" value="[' .$question['question_nr'].'][' . $answer['answer_nr'].']">';
                                    $disqualify_select = '<div class="form-group disqualify_select">
                                    <label>Select if this answer disqualifies a result</label>
                                    <input type="hidden" class="checkbox_nr" value="[' .$question['question_nr'].'][' . $answer['answer_nr'].']">';

                                    $i = 1;
                                    foreach($results_data as $key => $result){
                                        if (strpos($answer['result_nr'],$result['result_nr']) !== false){
                                            $checked = 'checked';
                                        }
                                        else
                                            $checked = '';
                                        if (strpos($answer['answer_dq_nr'],$result['result_nr']) !== false){
                                            $dq_checked = 'checked';
                                        }
                                        else
                                            $dq_checked = '';
                                        $logic_select .=  '<div class="checkbox"><input type="checkbox" id="answers[' .$question['question_nr'].'][' . $answer['answer_nr'].'][result][' .$i. ']" name="answers[' .$question['question_nr']. '][' . $answer['answer_nr']. '][result][' .$i. ']"  value="'.$i. '" '.$checked.'>
                                        <label for="answers[' .$question['question_nr']. '][' . $answer['answer_nr']. '][result][' .$i. ']"> ' .$result['title']. '</label></div>';
                                        
                                        $disqualify_select .=  '<div class="checkbox"><input type="checkbox" id="answers[' .$question['question_nr'].'][' . $answer['answer_nr'].'][disqualify][' .$i. ']" name="answers[' .$question['question_nr']. '][' . $answer['answer_nr']. '][disqualify][' .$i. ']"  value="'.$i. '" '.$dq_checked.'>
                                        <label for="answers[' .$question['question_nr']. '][' . $answer['answer_nr']. '][disqualify][' .$i. ']"> ' .$result['title']. '</label></div>';
                                        $i++;
                                        }            
                                        $logic_select .= '</div>';
                                        $disqualify_select .= '</div>';
                                    
                                                        
                            ?>
                            <h4><?php _e('Condition ', 'thatconverts_theguide'); ?><?php echo $answer['answer_nr'];?></h4>
                            <div class="answer_wrap" id="answer">
                                <div class="form-group">
                                <?php if($question['question_type'] == 'text'){ ?>
                                    <label for="logic"><?php _e('If input is:', 'thatconverts_theguide'); ?></label>
                                    <input type="hidden" name="answers[<?php echo $question['question_nr'];?>][<?php echo $answer['answer_nr'];?>][answer_nr]" value="<?php echo $answer['answer_nr'];?>">
                                    <input type="text" class="form-control answer_text" name="answers[<?php echo $question['question_nr'];?>][<?php echo $answer['answer_nr'];?>][answer_text]" value="<?php echo $answer['text'];?>" placeholder="Enter answer text...">
                                <?php }
                                else { ?>
                                    <label for="logic"><?php _e('If input is between: ', 'thatconverts_theguide'); ?></label>
                                    <input type="hidden" name="answers[<?php echo $question['question_nr'];?>][<?php echo $answer['answer_nr'];?>][answer_nr]" value="<?php echo $answer['answer_nr'];?>">
                                    <input type="number" class="form-control answer_text" name="answers[<?php echo $question['question_nr'];?>][<?php echo $answer['answer_nr'];?>][number1]" placeholder="Enter answer number..." value="<?php echo $answer['number1'];?>">
                                    and 
                                    <input type="number" class="form-control answer_text" name="answers[<?php echo $question['question_nr'];?>][<?php echo $answer['answer_nr'];?>][number2]" placeholder="Enter answer number..." value="<?php echo $answer['number2'];?>">

                                <?php } ?>
                                </div>
                                        <?php echo $logic_select; ?>
                                        <?php echo $disqualify_select; ?>
                                </div>

                                    <?php }
                                    }
                            ?>
                            <div class="form-group add_answer_btn">
                                <input type="hidden" class="question_type" name="question[<?php echo $question['question_nr'];?>][question_type]" value="<?php echo $question['question_type'];?>">
                                <label for="button"><?php _e('Add new condition (Max 8)', 'thatconverts_theguide'); ?></label>
                                <button type="button" class="btn btn-default add_answer" value="<?php echo $question['question_nr'];?>" aria-label="Left Align">+</button>
                            </div>
                                <h4><?php _e('Default condition (if anything other than conditions is written)', 'thatconverts_theguide'); ?></h4>
                                <div class="def_answer_wrap">
                                    <input type="hidden" name="answers[<?php echo $question['question_nr'];?>][0][answer_nr]" value="0">
                                    <input type="hidden" name="answers[<?php echo $question['question_nr'];?>][0][number1]" value="0">
                                    <input type="hidden" name="answers[<?php echo $question['question_nr'];?>][0][number2]" value="0">
                                    <input type="hidden" name="answers[<?php echo $question['question_nr'];?>][0][answer_text]" value="default">
                                    <?php echo $logic_select_def; ?>
                                    <?php echo $disqualify_select_def; ?>
                                </div>
                    
                            </div>
                    <?php
                            } 
                        }
                    ?>
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
            wp_nonce_field( 'update_quiz', 'update_quiz_nonce' );
            ?>
            </form>

        </div>
        <?php
    }

    function update_quiz($data){
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
        $quiz_id =  $_GET['quiz']; 

        $result = $wpdb->delete($results_table, 
        array( 'quiz_id' => $quiz_id ));

        $result = $wpdb->delete($questions_table, 
        array( 'quiz_id' => $quiz_id ));

        $result = $wpdb->delete($quiz_table, array( 'id' => $quiz_id ));
        $result = $wpdb->insert($quiz_table, array(
            'id' => $quiz_id,
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
            printf(__('Successfully edited quiz!', 'thatconverts_theguide'));
                echo "</h2>";
        }

    }