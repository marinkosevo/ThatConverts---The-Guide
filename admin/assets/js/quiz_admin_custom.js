jQuery(document).ready(function() {
    var j = new Array();
    var i = 1;
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
        if (localStorage.delete == 1) {
            jQuery('.logic_select').each(function() {
                var x = 1;
                var checkbox_nr = jQuery(this).find('.checkbox_nr').val();
                var logic_element = jQuery(this);
                jQuery(this).find('.checkbox').remove();
                jQuery(".results").each(function() {
                    jQuery(logic_element).append(`<div class="checkbox"><input type="checkbox" id="answers` + checkbox_nr + `[result][` + x + `]" name="answers` + checkbox_nr + `[result][` + x + `]"  value="` + x + `">
                <label for="answers` + checkbox_nr + `[result][` + x + `]"> ` + jQuery(this).find('.result_title').val() + `</label></div>`);
                });

            });
            jQuery('.disqualify_select').each(function() {
                var x = 1;
                var checkbox_nr = jQuery(this).find('.checkbox_nr').val();
                var disqualify_element = jQuery(this);
                jQuery(this).find('.checkbox').remove();
                jQuery(".results").each(function() {
                    jQuery(disqualify_element).append(`<div class="checkbox"><input type="checkbox" id="answers` + checkbox_nr + `[disqualify][` + x + `]" name="answers` + checkbox_nr + `[disqualify][` + x + `]"  value="` + x + `">
                <label for="answers` + checkbox_nr + `[disqualify][` + x + `]"> ` + jQuery(this).find('.result_title').val() + `</label></div>`);
                });

            });
        }



    });

    jQuery("#quiz_form").submit(function(e) {
        e.preventDefault();
        localStorage.delete = 0;
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
            var question_nr = (jQuery('.question_wrap').length + 1);
            if ((jQuery('.question_wrap').length + 1) == 10)
                jQuery('#add_question').prop('disabled', true);
            while (jQuery('input[name="question[' + question_nr + '][question_type]"]').length) {
                question_nr++;
            }


            var question_type = jQuery(this).parent().find('#question_type').val();
            var answer_select = `
        <div class="form-group add_answer_btn">
        <input type="hidden" class="question_type" name="question[` + question_nr + `][question_type]" value="` + jQuery("#question_type").val() + `">
            <label for="button">Add new answer (Max 8)</label>
            <button type="button" class="btn btn-default add_answer" value="` + question_nr + `" aria-label="Left Align">+</button>
        </div>`;
            var answer_input = `
        <div class="form-group add_answer_btn">
            <input type="hidden" class="question_type" name="question[` + question_nr + `][question_type]" value="` + jQuery("#question_type").val() + `">
            <label for="button">Add new condition (Max 8)</label>
            <button type="button" class="btn btn-default add_answer" value="` + question_nr + `" aria-label="Left Align">+</button>
        </div>`;
            if (question_type == "selection") {
                jQuery(`
        <div class="form-group question_wrap">
            <div class="question_heading">
            <input type="hidden" name="question[` + question_nr + `][question_type]" value="` + question_type + `">
            <input type="hidden" name="question[` + question_nr + `][question_nr]" value="` + question_nr + `">
                <h3>Question </h3>
            </div>
            <button type="button" class="delete">Delete</button>
            <div id="question` + question_nr + `" class="">
                <div class="question">
                    <div class="form-group">
                        <label for="name">Multiple possible answers</label>
                        <input type="radio" name="question[` + question_nr + `][multiple_answers]" value="0" checked>
                        <label>No</label>
                        <input type="radio" name="question[` + question_nr + `][multiple_answers]" value="1">
                        <label>Yes</label><br>
                    </div>

                    <div class="form-group">
                        <label for="name">Title</label>
                        <input type="text" class="form-control question_text" name="question[` + question_nr + `][title]" placeholder="Enter question title...">
                    </div>
                    <div class="form-group">
                        <label for="name">Question</label>
                        <input type="text" class="form-control question_text" name="question[` + question_nr + `][question]" placeholder="Enter question text...">
                    </div>
                </div>
                ` + answer_select + `
            </div>
        </div>
        `).insertBefore(jQuery(this).parent());
            } else {
                var j = 1;

                logic = `<div class="form-group logic_select"><label>Select corresponding result</label>`;
                disqualify_select = `<div class="form-group disqualify_select">
                <label>Select if this answer disqualifies a result</label>`;

                jQuery(".results").each(function() {
                    logic += `<div class="checkbox"><input type="checkbox" name="answers[` + question_nr + `][0][result][` + j + `]"  value="` + j + `">
                    <label for="answers[` + question_nr + `][0][result][` + i + `]"> ` + jQuery(this).find('.result_title').val() + `</label></div>`;
                    disqualify_select += `<div class="checkbox"><input type="checkbox" name="answers[` + question_nr + `][0][disqualify][` + j + `]"  value="` + j + `">
                    <label for="answers[` + question_nr + `][0][disqualify][` + i + `]"> ` + jQuery(this).find('.result_title').val() + `</label></div>`;
                    j++;
                });
                logic += '</div>';
                disqualify_select += '</div>';

                jQuery(`
            <div class="form-group question_wrap">
                <div class="question_heading">
                <input type="hidden" name="question[` + question_nr + `][question_nr]" value="` + question_nr + `">
                    <h3>Question ` + question_nr + `</h3>
                    </a>
                </div>
                <div class="question">
                    <div class="form-group">
                        <label for="name">Title</label>
                        <input type="text" class="form-control question_text" name="question[` + question_nr + `][title]" placeholder="Enter question title...">
                    </div>
                    <div class="form-group">
                        <label for="name">Question</label>
                        <input type="text" class="form-control question_text" name="question[` + question_nr + `][question]" placeholder="Enter question text...">
                    </div>
                </div>

                    ` + answer_input + `
                    <h4>Default condition (if anything other than conditions is written)</h4>
                    <div class="default_answer_wrap">
                        <input type="hidden" name="answers[` + question_nr + `][0][answer_nr]" value="0">
                        <input type="hidden" name="answers[` + question_nr + `][0][number1]" value="0">
                        <input type="hidden" name="answers[` + question_nr + `][0][number2]" value="0">
                        <input type="hidden" name="answers[` + question_nr + `][0][answer_text]" value="default">
                        ` + logic + `
                        ` + disqualify_select + `
                    </div>
        
                </div>
            </div>
                    
            </div>
            `).insertBefore(jQuery(this).parent());
            }
            //Disable after 8 questions (Maximum allowed)
            i++;
        }
    });

    //Dynamically adding quiz results

    jQuery("#add_result").click(function() {
        localStorage.delete = 1;
        var i = jQuery('.results').length + 1;
        while (jQuery('input[name="result[' + i + '][title]"]').length) {
            i++;
        }

        jQuery(`
        <div class="form-group results">
                <h3>Result </h3> <button type="button" class="delete">Delete</button>
            <div id="result` + i + `" class="result">   
                <div class="form-group">
                    <label for="name">Title</label>
                    <input type="text" class="form-control result_title" name="result[` + i + `][title]" placeholder="Enter result title...">
                </div>
                <div class="form-group">
                    <label for="name">Description</label>
                    <input type="text" class="form-control result_description" name="result[` + i + `][description]" placeholder="Enter result text...">
                </div>
                <div class="form-group">
                    <label for="name">Image (optional)</label>
                    <img class="quiz_img_preview" src=""/>
                    <input type="hidden" name="result[` + i + `][image]">
                    <input type="button" class="button-primary img_upload" value="Select a image">
                </div>
                <div class="form-group">
                    <label for="name">Link (optional)</label>
                    <input type="text" class="form-control" name="result[` + i + `][link]" placeholder="Enter result link...">
                </div>
            </div>
        </div>
        `).insertBefore(jQuery(this).parent());
        if (jQuery('.results').length == 20)
            jQuery('#add_result').prop('disabled', true);
    });


    //Dymnamically adding answers   
    jQuery(document.body).on('click', '.add_answer', function() {
        var type = jQuery(this).parent().find('.question_type').val();
        var question_nr = jQuery(this).val();
        var ans_length = (jQuery(this).parent().parent().find('.answer_wrap').length + 1);

        if (ans_length == 8)
            jQuery(this).prop('disabled', true);

        var logic_select = `<div class="form-group logic_select"><label>Select corresponding result</label>`;
        var disqualify_select = `<div class="form-group disqualify_select">
                                        <label>Select if this answer disqualifies a result</label>`;

        var i = 1;
        jQuery(".results").each(function() {
            logic_select += `<div class="checkbox"><input type="checkbox" id="answers[` + question_nr + `][` + ans_length + `][result][` + i + `]" name="answers[` + question_nr + `][` + ans_length + `][result][` + i + `]"  value="` + i + `">
            <label for="answers[` + question_nr + `][` + ans_length + `][result][` + i + `]"> ` + jQuery(this).find('.result_title').val() + `</label></div>`;
            disqualify_select += `<div class="checkbox"><input type="checkbox" id="answers[` + question_nr + `][` + ans_length + `][disqualify][` + i + `]" name="answers[` + question_nr + `][` + ans_length + `][disqualify][` + i + `]"  value="` + i + `">
            <label for="answers[` + question_nr + `][` + ans_length + `][disqualify][` + i + `]"> ` + jQuery(this).find('.result_title').val() + `</label></div>`;
            i++;
        });
        logic_select += '</div>';
        disqualify_select += '</div>';

        //Type of answer selection
        if (type == "selection") {
            jQuery(`
            <h4>Answer ` + ans_length + `</h4>
            <div class="answer_wrap" id="answer` + question_nr + `-` + ans_length + `">
                <div class="form-group">
                    <label for="logic">Answer</label>
                    <input type="hidden" name="answers[` + question_nr + `][` + ans_length + `][answer_nr]" value="` + ans_length + `">
                    <input type="text" class="form-control answer_text" name="answers[` + question_nr + `][` + ans_length + `][answer_text]" placeholder="Enter answer text...">
                </div>
                    <div class="form-group">
                        <label for="name">Answer icon (optional)</label>
                        <img class="quiz_img_preview" src=""/>
                        <input type="hidden" name="answers[` + question_nr + `][` + ans_length + `][image]">
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
                <h4>Condition ` + ans_length + ` </h4>
                <div class="answer_wrap" id="condition` + question_nr + `-` + ans_length + `">
                    <div class="form-group">
                        <label for="logic">If input is: </label>
                        <input type="hidden" name="answers[` + question_nr + `][` + ans_length + `][answer_nr]" value="` + ans_length + `">
                        <input type="` + input + `" class="form-control answer_text" name="answers[` + question_nr + `][` + ans_length + `][answer_text]" placeholder="Enter answer text...">
                        </div>
                    ` + logic_select + disqualify_select + `    
                </div>`).insertBefore(jQuery(this).parent());

            } else {
                var input = 'number';

                jQuery(`
                <h4>Condition ` + ans_length + `</h4>
                <div class="answer_wrap" id="condition` + question_nr + `-` + ans_length + `">
                    <div class="form-group">
                        <label for="logic">If input is between: </label>
                        <input type="hidden" name="answers[` + question_nr + `][` + ans_length + `][answer_nr]" value="` + ans_length + `">
                        <input type="` + input + `" class="form-control answer_text" name="answers[` + question_nr + `][` + ans_length + `][number1]" placeholder="Enter answer number...">
                         and 
                        <input type="` + input + `" class="form-control answer_text" name="answers[` + question_nr + `][` + ans_length + `][number2]" placeholder="Enter answer number...">
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
                new_logic_select += `<input type="checkbox" name="answers[` + question_nr + `][` + ans_length + `][result][` + counter + `]"  value="` + i + `">
                <label for="answers[` + question_nr + `][` + ans_length + `][result][` + i + `]"> ` + jQuery(this).find('.result_title').val() + `</label><br>`;
                new_disqualify_select += `<input type="checkbox" name="answers[` + question_nr + `][` + ans_length + `][disqualify][` + i + `]"  value="` + i + `">
                <label for="answers[` + question_nr + `][` + ans_length + `][disqualify][` + i + `]"> ` + jQuery(this).find('.result_title').val() + `</label><br>`;
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
    jQuery(document.body).on('click', '.delete', function() {
        localStorage.delete = 1;
        jQuery(this).parent().remove();
        if (jQuery('.results').length < 20)
            jQuery('#add_result').prop('disabled', false);
        if (jQuery('.question_wrap').length < 10)
            jQuery('#add_question').prop('disabled', false);


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
            hiddenElement.download = response.data.email + '_result.csv';
            hiddenElement.click();

        } else {
            alert('An error occurred!');
        }
    };
    xhr.send(data);
}


function export_csv_all(id) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', ajax_admin_object.ajax_url, true);
    var data = new FormData();
    data.append('quiz_id', id);
    data.append('action', 'export_csv');
    // Set up a handler for when the request finishes.
    xhr.onload = function() {
        if (xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            data = response.data;

            var header = response.data[0].quiz_data[0];
            var csv = 'ID;Date;Email;';
            Object.values(header).forEach(function(item, index) {
                csv += item.question_title + ';';
            });
            csv += '\n';

            data.forEach(function(row) {
                csv += row.id + ';' + row.createdAt + ';' + row.email + ';';
                row.quiz_data.forEach(function(question) {
                    Object.values(question).forEach(function(answer) {
                        csv += answer.answer + ';';
                    });
                });
                csv += '\n';
            });
            var hiddenElement = document.createElement('a');
            hiddenElement.href = 'data:text/csv;charset=utf-8,' + csv;
            hiddenElement.target = '_blank';
            hiddenElement.download = 'Quiz_' + id + '.csv';
            hiddenElement.click();

        } else {
            alert('An error occurred!');
        }
    };
    xhr.send(data);
}