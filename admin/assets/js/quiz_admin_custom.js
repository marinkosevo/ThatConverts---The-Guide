jQuery(document).ready(function() {
    let i = 1;
    let r = 1;
    var j = new Array();

    jQuery('#first_next').click(function() {
        var empty = 0;
        jQuery(".quiz_heading input[type=text]:not(:disabled)").each(function() {
            if (jQuery(this).val() == '') {
                jQuery(this).css('border-color', 'red');
                jQuery('#first_warning').html('Please fill in all details!');
                empty++;
            } else {
                jQuery(this).css('border-color', '#7e8993');
                jQuery('#first_warning').html('');
            }

        });
        if (empty == 0)
            openTab('Results');
    });
    jQuery('#second_next').click(function() {
        var empty = 0;
        var results = 0;
        if (jQuery(".result_title").html() == undefined) {
            results = 1;
            jQuery('#second_warning').html('Please add at least one result!');

        }
        jQuery(".result_title").each(function() {
            if (jQuery(this).val() == '') {
                jQuery(this).css('border-color', 'red');
                jQuery('#second_warning').html('Please fill in all details!');
                empty++;
            } else {
                jQuery(this).css('border-color', '#7e8993');
                jQuery('#second_warning').html('');
            }

        });
        jQuery(".result_description").each(function() {
            if (jQuery(this).val() == '') {
                jQuery(this).css('border-color', 'red');
                jQuery('#second_warning').html('Please fill in all details!');
                empty++;
            } else {
                jQuery(this).css('border-color', '#7e8993');
                jQuery('#second_warning').html('');
            }

        });
        if (empty == 0 && results == 0) {
            openTab('Questions');
            jQuery('#submit_btn').attr('disabled', false);
        }
    });

    jQuery("#quiz_form").submit(function(e) {
        e.preventDefault();

        var empty = 0;
        var results = 0;
        //Check if there are any questions added
        if (jQuery(".question_heading").html() == undefined) {
            results++;
            jQuery('#third_warning').html('Please add at least one question!');
        } else {
            jQuery('#third_warning').html('');
        }
        //Check if all questions have title and description
        jQuery(".question_text").each(function() {
            if (jQuery(this).val() == '') {
                jQuery(this).css('border-color', 'red');
                jQuery('.question_details_w').html('Please fill all details!');
                empty++;
            } else {
                jQuery(this).css('border-color', '#7e8993');
                jQuery('.question_details_w').html('');
            }

        });
        //Check if all questions have at least one answer
        jQuery(".question_wrap").each(function() {
            if (jQuery(this).find('.answer_wrap').html() == undefined) {
                jQuery(this).css('border-color', 'red');
                jQuery('.empty_answers_w').html('Please add at least one answer to all questions!');
                empty++;
            } else {
                jQuery(this).css('border-color', '#7e8993');
                jQuery('.empty_answers_w').html('');
            }

        });
        //Check if all answers have text
        jQuery(".answer_text").each(function() {
            if (jQuery(this).val() == '') {
                jQuery(this).css('border-color', 'red');
                jQuery('.answer_details_w').html('Please fill all answers!');
                empty++;
            } else {
                jQuery(this).css('border-color', '#7e8993');
                jQuery('.answer_details_w').html('');
            }

        });

        if (empty == 0 && results == 0) {
            jQuery('#quiz_form').unbind('submit').submit();

        }
    });
    //Dynamically adding questions
    jQuery("#add_question").click(function() {
        if (jQuery(".results").html() == undefined) {
            alert("Please add at least one quiz result before adding questions!");
        } else {

            let question_type = jQuery(this).parent().find('#question_type').val();
            var answer_select = `
        <div class="form-group add_answer_btn">
        <input type="hidden" class="question_type" name="question[` + i + `][question_type]" value="` + jQuery("#question_type").val() + `">
            <label for="button">Add new answer (Max 5)</label>
            <button type="button" class="btn btn-default add_answer" value="` + i + `" aria-label="Left Align">+</button>
        </div>`;
            var answer_input = `
        <div class="form-group add_answer_btn">
            <input type="hidden" class="question_type" name="question[` + i + `][question_type]" value="` + jQuery("#question_type").val() + `">
            <label for="button">Add new condition (Max 5)</label>
            <button type="button" class="btn btn-default add_answer" value="` + i + `" aria-label="Left Align">+</button>
        </div>`;
            if (question_type == "selection") {
                jQuery(`
        <div class="form-group question_wrap">
            <div class="question_heading">
            <input type="hidden" name="question[` + i + `][question_type]" value="` + question_type + `">
            <input type="hidden" name="question[` + i + `][question_nr]" value="` + i + `">
                <h3>Question ` + i + `</h3>
                </a>
            </div>
            <div id="question` + i + `" class="">
                <div class="question">
                    <div class="form-group">
                        <label for="name">Multiple possible answers</label>
                        <input type="radio" name="question[` + i + `][multiple_answers]" value="0" checked>
                        <label>No</label>
                        <input type="radio" name="question[` + i + `][multiple_answers]" value="1">
                        <label>Yes</label><br>
                    </div>

                    <div class="form-group">
                        <label for="name">Title</label>
                        <input type="text" class="form-control question_text" name="question[` + i + `][title]" placeholder="Enter question title...">
                    </div>
                    <div class="form-group">
                        <label for="name">Question</label>
                        <input type="text" class="form-control question_text" name="question[` + i + `][question]" placeholder="Enter question text...">
                    </div>
                </div>
                ` + answer_select + `
            </div>
        </div>
        `).insertBefore(jQuery(this).parent());
            } else {
                let j = 1;

                logic = `<div class="form-group logic_select"><label>Select corresponding result</label>`;
                disqualify_select = `<div class="form-group logic_select">
                <label>Select if this answer disqualifies a result</label>`;

                jQuery(".results").each(function() {
                    logic += `<div class="checkbox"><input type="checkbox" name="answers[` + i + `][0][result][` + j + `]"  value="` + j + `">
                    <label for="answers[` + i + `][0][result][` + i + `]"> ` + jQuery(this).find('.result_title').val() + `</label></div>`;
                    disqualify_select += `<div class="checkbox"><input type="checkbox" name="answers[` + i + `][0][disqualify][` + j + `]"  value="` + j + `">
                    <label for="answers[` + i + `][0][disqualify][` + i + `]"> ` + jQuery(this).find('.result_title').val() + `</label></div>`;
                    j++;
                });
                logic += '</div>';
                disqualify_select += '</div>';

                jQuery(`
            <div class="form-group question_wrap">
                <div class="question_heading">
                <input type="hidden" name="question[` + i + `][question_nr]" value="` + i + `">
                    <h3>Question ` + i + `</h3>
                    </a>
                </div>
                <div class="question">
                    <div class="form-group">
                        <label for="name">Title</label>
                        <input type="text" class="form-control question_text" name="question[` + i + `][title]" placeholder="Enter question title...">
                    </div>
                    <div class="form-group">
                        <label for="name">Question</label>
                        <input type="text" class="form-control question_text" name="question[` + i + `][question]" placeholder="Enter question text...">
                    </div>
                </div>

                    ` + answer_input + `
                    <h4>Default condition (if anything other than conditions is written)</h4>
                    <div class="answer_wrap">
                        <input type="hidden" name="answers[` + i + `][0][answer_nr]" value="0">
                        <input type="hidden" name="answers[` + i + `][0][number1]" value="0">
                        <input type="hidden" name="answers[` + i + `][0][number2]" value="0">
                        <input type="hidden" name="answers[` + i + `][0][answer_text]" value="default">
                        ` + logic + `
                        ` + disqualify_select + `
                        </div>
        
                        </div>
                    </div>
                    
            </div>
            `).insertBefore(jQuery(this).parent());
            }
            //Disable after 8 questions (Maximum allowed)
            if (i == 8)
                jQuery('#add_question').prop('disabled', true);
            i++;
        }
    });

    //Dynamically adding quiz results

    jQuery("#add_result").click(function() {
        jQuery(`
        <div class="form-group results">
                <h3>Result ` + r + `</h3>
            <div id="result` + r + `" class="result">   
                <div class="form-group">
                    <label for="name">Title</label>
                    <input type="text" class="form-control result_title" name="result[` + r + `][title]" placeholder="Enter result title...">
                </div>
                <div class="form-group">
                    <label for="name">Description</label>
                    <input type="text" class="form-control result_description" name="result[` + r + `][description]" placeholder="Enter result text...">
                </div>
                <div class="form-group">
                    <label for="name">Image (optional)</label>
                    <img class="quiz_img_preview" src=""/>
                    <input type="hidden" name="result[` + r + `][image]">
                    <input type="button" class="button-primary img_upload" value="Select a image">
                </div>
                <div class="form-group">
                    <label for="name">Link (optional)</label>
                    <input type="text" class="form-control" name="result[` + r + `][link]" placeholder="Enter result link...">
                </div>
            </div>
        </div>
        `).insertBefore(jQuery(this).parent());
        if (r == 5)
            jQuery('#add_result').prop('disabled', true);
        r++;
    });


    //Dymnamically adding answers   
    jQuery(document.body).on('click', '.add_answer', function() {
        let type = jQuery(this).parent().find('.question_type').val();
        let question_nr = jQuery(this).val();
        if (isNaN(j[question_nr]))
            j[question_nr] = 1;
        else
            j[question_nr]++;
        if (j[question_nr] == 5)
            jQuery(this).prop('disabled', true);

        let logic_select = `<div class="form-group logic_select"><label>Select corresponding result</label>`;
        disqualify_select = `<div class="form-group logic_select">
                                        <label>Select if this answer disqualifies a result</label>`;

        let i = 1;
        jQuery(".results").each(function() {
            logic_select += `<div class="checkbox"><input type="checkbox" id="answers[` + question_nr + `][` + j[question_nr] + `][result][` + i + `]" name="answers[` + question_nr + `][` + j[question_nr] + `][result][` + i + `]"  value="` + i + `">
            <label for="answers[` + question_nr + `][` + j[question_nr] + `][result][` + i + `]"> ` + jQuery(this).find('.result_title').val() + `</label></div>`;
            disqualify_select += `<div class="checkbox"><input type="checkbox" id="answers[` + question_nr + `][` + j[question_nr] + `][disqualify][` + i + `]" name="answers[` + question_nr + `][` + j[question_nr] + `][disqualify][` + i + `]"  value="` + i + `">
            <label for="answers[` + question_nr + `][` + j[question_nr] + `][disqualify][` + i + `]"> ` + jQuery(this).find('.result_title').val() + `</label></div>`;
            i++;
        });
        logic_select += '</div>';
        disqualify_select += '</div>';

        //Type of answer selection
        if (type == "selection") {
            jQuery(`
            <h4>Answer ` + j[question_nr] + `</h4>
            <div class="answer_wrap" id="answer` + question_nr + `-` + j[question_nr] + `">
                <div class="form-group">
                    <label for="logic">Answer</label>
                    <input type="hidden" name="answers[` + question_nr + `][` + j[question_nr] + `][answer_nr]" value="` + j[question_nr] + `">
                    <input type="text" class="form-control answer_text" name="answers[` + question_nr + `][` + j[question_nr] + `][answer_text]" placeholder="Enter answer text...">
                </div>
                    <div class="form-group">
                        <label for="name">Answer icon (optional)</label>
                        <img class="quiz_img_preview" src=""/>
                        <input type="hidden" name="answers[` + question_nr + `][` + j[question_nr] + `][image]">
                        <input type="button" class="button-primary img_upload" value="Select a image">
    
                        </div>` + logic_select + disqualify_select + `
                </div>

                </div>
            </div>`).insertBefore(jQuery(this).parent());
        }
        //Type of answer text input
        else {
            if (type == "text") {
                var input = 'text';
                jQuery(`
                <h4>Condition ` + j[question_nr] + ` </h4>
                <div class="answer_wrap" id="condition` + question_nr + `-` + j[question_nr] + `">
                    <div class="form-group">
                        <label for="logic">If input is: </label>
                        <input type="hidden" name="answers[` + question_nr + `][` + j[question_nr] + `][answer_nr]" value="` + j[question_nr] + `">
                        <input type="` + input + `" class="form-control answer_text" name="answers[` + question_nr + `][` + j[question_nr] + `][answer_text]" placeholder="Enter answer text...">
                        </div>
                    ` + logic_select + disqualify_select + `    
                </div>`).insertBefore(jQuery(this).parent());

            } else {
                var input = 'number';

                jQuery(`
                <h4>Condition ` + j[question_nr] + `</h4>
                <div class="answer_wrap" id="condition` + question_nr + `-` + j[question_nr] + `">
                    <div class="form-group">
                        <label for="logic">If input is between: </label>
                        <input type="hidden" name="answers[` + question_nr + `][` + j[question_nr] + `][answer_nr]" value="` + j[question_nr] + `">
                        <input type="` + input + `" class="form-control answer_text" name="answers[` + question_nr + `][` + j[question_nr] + `][number1]" placeholder="Enter answer number...">
                         and 
                        <input type="` + input + `" class="form-control answer_text" name="answers[` + question_nr + `][` + j[question_nr] + `][number2]" placeholder="Enter answer number...">
                    </div>
                    ` + logic_select + disqualify_select + `    
                    </div>
                </div>`).insertBefore(jQuery(this).parent());

            }

        }
    });

    //Add question disabled if question type not selected
    jQuery("#question_type").change(function() {
        if (jQuery("#question_type").val() != null) {
            jQuery('#add_question').prop('disabled', false);
        }
    });
    if (jQuery("#question_type").val() != null) {
        jQuery('#add_question').prop('disabled', false);
    }
    /* 
        //Adding new results to select options
        jQuery(document.body).on('change', '.result_title', function() {
            var counter = 1;
            var new_logic_select = '';
            var new_disqualify_select = '';

            jQuery('.logic_select').html('');
            jQuery(".result_title").each(function() {
                new_logic_select += `<input type="checkbox" name="answers[` + question_nr + `][` + j[question_nr] + `][result][` + counter + `]"  value="` + i + `">
                <label for="answers[` + question_nr + `][` + j[question_nr] + `][result][` + i + `]"> ` + jQuery(this).find('.result_title').val() + `</label><br>`;
                new_disqualify_select += `<input type="checkbox" name="answers[` + question_nr + `][` + j[question_nr] + `][disqualify][` + i + `]"  value="` + i + `">
                <label for="answers[` + question_nr + `][` + j[question_nr] + `][disqualify][` + i + `]"> ` + jQuery(this).find('.result_title').val() + `</label><br>`;
                counter++;
            });
            jQuery('.logic_select').html(new_select);

        });
     */
    //Disabling corresponding/disqualifying answer
    /*  jQuery(document.body).on('change', '.corresponding', function() {
            if (jQuery(this).val() != 0)
                jQuery(this).parent().find('.disqualifying').prop("disabled", true);
            else
                jQuery(this).parent().find('.disqualifying').prop("disabled", false);
        });

        jQuery(document.body).on('change', '.disqualifying', function() {
            if (jQuery(this).val() != 0)
                jQuery(this).parent().find('.corresponding').prop("disabled", true);
            else
                jQuery(this).parent().find('.corresponding').prop("disabled", false);

        });
     */
    //Email text toggle


    jQuery(document.body).on('click', '#collect_email', function() {
        if (jQuery(this).is(":checked")) {
            jQuery('#email_description').prop("disabled", false)
            jQuery('.email').css("display", "block");
        } else {
            jQuery('#email_description').prop("disabled", true)
            jQuery('.email').css("display", "none");

        }
    });

});


//Opening navigation tabs

function openTab(tabName) {
    var i;
    var x = document.getElementsByClassName("section_wrap");
    var y = document.getElementsByClassName("nav_links");
    for (i = 0; i < x.length; i++) {
        x[i].style.display = "none";
        y[i].classList.remove("active");
    }
    document.getElementById(tabName).style.display = "block";
    document.getElementById(tabName + "_link").classList.add("active");

}


function export_csv(id) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', ajax_admin_object.ajax_url, true);
    var data = new FormData();
    data.append('id', id);
    data.append('action', 'export_csv');
    // Set up a handler for when the request finishes.
    xhr.onload = function() {
        if (xhr.status === 200) {
            console.log();
            var response = JSON.parse(xhr.responseText);
            var results = response.data.data;
            var csv = 'Question title;Question description;Answer\n';
            results.forEach(function(row) {
                csv += row.join(';');
                csv += "\n";
            });
            var hiddenElement = document.createElement('a');
            hiddenElement.href = 'data:text/csv;charset=utf-8,' + csv;
            hiddenElement.target = '_blank';
            hiddenElement.download = response.data.email + '.csv';
            hiddenElement.click();

        } else {
            alert('An error occurred!');
        }
    };

    xhr.send(data);

}