jQuery(document).ready(function() {
    let i = 1;
    let r = 1;
    var j = new Array();
    //Dynamically adding questions
    jQuery("#add_question").click(function() {
        if (jQuery(".results").html() == undefined) {
            alert("Please add at least one quiz result before adding questions!");
        } else {

            let question_type = jQuery(this).parent().find('#question_type').val();
            var answer_select = `
        <div class="form-group add_answer_btn">
            <label for="button">Add new answer (Max 5)</label>
            <button type="button" class="btn btn-default add_answer" value="` + i + `" aria-label="Left Align">+</button>
        </div>`;
            var answer_input = `
        <div class="form-group add_answer_btn">
            <label for="button">Add new condition (Max 5)</label>
            <button type="button" class="btn btn-default add_answer" value="` + i + `" aria-label="Left Align">+</button>
        </div>`;
            if (question_type == "selection") {
                jQuery(`
        <div class="form-group question_wrap">
            <div class="question_heading">
            <input type="hidden" name="question[` + i + `][question_type]" value="` + question_type + `">
            <input type="hidden" name="question[` + i + `][question_nr]" value="` + i + `">
            <input type="hidden" class="question_type" name="question[` + i + `][question_type]" value="` + jQuery("#question_type").val() + `">
                <h3>Question ` + i + ` <span class="toggle" onclick="toggle_function('question` + i + `')">(Toggle) </span></h3>
                </a>
            </div>
            <div id="question` + i + `" class="collapse">
                <div class="form-group">
                    <label for="name">Multiple possible answers</label>
                    <input type="radio" name="question[` + i + `][multiple_answers]" value="0" checked>
                    <label>No</label>
                    <input type="radio" name="question[` + i + `][multiple_answers]" value="1">
                    <label>Yes</label><br>
                </div>

                <div class="form-group">
                    <label for="name">Title</label>
                    <input type="text" class="form-control" name="question[` + i + `][title]" placeholder="Enter question title...">
                </div>
                <div class="form-group">
                    <label for="name">Question</label>
                    <input type="text" class="form-control" name="question[` + i + `][question]" placeholder="Enter question text...">
                </div>
                ` + answer_select + `
            </div>
        </div>
        `).insertBefore(jQuery(this).parent());
            } else {
                var logic = '';
                var disqualify_select = '';
                let j = 1;
                jQuery(".results").each(function() {
                    logic += `<option value="` + j + `">` + jQuery(this).find('.result_title').val() + `</option>`;
                    disqualify_select += `<option value="` + j + `">` + jQuery(this).find('.result_title').val() + `</option>`;
                    j++;
                });

                jQuery(`
            <div class="form-group question_wrap">
                <div class="question_heading">
                <input type="hidden" name="question[` + i + `][question_nr]" value="` + i + `">
                <input type="hidden" class="question_type" name="question[` + i + `][question_type]" value="` + jQuery("#question_type").val() + `">
                    <h3>Question ` + i + ` <span class="toggle" onclick="toggle_function('question` + i + `')">(Toggle) </span></h3>
                    </a>
                </div>
                <div id="question` + i + `" class="collapse">  
                    <div class="form-group">
                        <label for="name">Title</label>
                        <input type="text" class="form-control" name="question[` + i + `][title]" placeholder="Enter question title...">
                    </div>
                    <div class="form-group">
                        <label for="name">Question</label>
                        <input type="text" class="form-control" name="question[` + i + `][question]" placeholder="Enter question text...">
                    </div>
                    ` + answer_input + `
                    <div class="answer_wrap">
                    <h4>Default condition (if anything other than conditions is written)</h4>
                        <div class="form-group">
                        <label for="logic">Select corresponding result</label>
                        <input type="hidden" name="answers[` + i + `][0][answer_nr]" value="0">
                        <input type="hidden" name="answers[` + i + `][0][number1]" value="0">
                        <input type="hidden" name="answers[` + i + `][0][number2]" value="0">
                        <input type="hidden" name="answers[` + i + `][0][answer_text]" value="default">
                        <select class="form-control logic_select" name="answers[` + i + `][0][result]">
                        <option value="0">No</option>
                        ` + logic + `
                        </select>
                         Or 
                        <label for="logic">Select if this answer disqualifies a result</label>
                        <select class="form-control logic_select" name="answers[` + i + `][0][disqualify]">
                        <option value="0">No</option>
                        ` + disqualify_select + `
                        </select>
                        </div>
        
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
        <div class="form-group question_wrap results">
            <div class="question_heading">
                <h3>Result ` + r + ` <span class="toggle" onclick="toggle_function('result` + r + `')">(Toggle) </span></h3>
                </a>
            </div>
            <div id="result` + r + `" class="collapse">   
                <div class="form-group">
                    <label for="name">Title</label>
                    <input type="text" class="form-control result_title" name="result[` + r + `][title]" placeholder="Enter result title...">
                </div>
                <div class="form-group">
                    <label for="name">Description</label>
                    <input type="text" class="form-control" name="result[` + r + `][description]" placeholder="Enter result text...">
                </div>
                <div class="form-group">
                    <label for="name">Image (optional)</label>
                    <input type="file" name="result[` + r + `][image]" accept="image/*">
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
        let type = jQuery(this).parent().parent().parent().find('.question_type').val();
        let question_nr = jQuery(this).val();
        if (isNaN(j[question_nr]))
            j[question_nr] = 1;
        else
            j[question_nr]++;
        if (j[question_nr] == 5)
            jQuery(this).prop('disabled', true);

        let logic_select = `
        <label for="logic">Select corresponding result</label>
        <select class="form-control logic_select corresponding" name="answers[` + question_nr + `][` + j[question_nr] + `][result]">
        <option value="0">No</option>`;
        disqualify_select = ` Or 
        <label for="logic">Select if this answer disqualifies a result</label>
        <select class="form-control logic_select disqualifying" name="answers[` + question_nr + `][` + j[question_nr] + `][disqualify]">
        <option value="0">No</option>`;

        let i = 1;
        jQuery(".results").each(function() {
            logic_select += `<option value="` + i + `">` + jQuery(this).find('.result_title').val() + `</option>`;
            disqualify_select += `<option value="` + i + `">` + jQuery(this).find('.result_title').val() + `</option>`;
            i++;
        });
        logic_select += '</select>';
        disqualify_select += '</select>';

        //Type of answer selection
        if (type == "selection") {
            jQuery(`
            <h4>Answer ` + j[question_nr] + ` <span class="toggle" onclick="toggle_function('answer` + question_nr + `-` + j[question_nr] + `')">(Toggle) </span></h4>
            <div class="answer_wrap" id="answer` + question_nr + `-` + j[question_nr] + `">
                <div class="form-group">
                    <label for="logic">Answer</label>
                    <input type="hidden" name="answers[` + question_nr + `][` + j[question_nr] + `][answer_nr]" value="` + j[question_nr] + `">
                    <input type="text" class="form-control" name="answers[` + question_nr + `][` + j[question_nr] + `][answer_text]" placeholder="Enter answer text...">
                    <br>
                    <label for="name">Answer icon (optional)</label>
                    <input type="file" name="answers[` + question_nr + `][` + j[question_nr] + `][image]" accept="image/*">
                </div>
                <div class="form-group">
                ` + logic_select + disqualify_select + `
                </div>

                </div>
            </div>`).insertBefore(jQuery(this).parent());
        }
        //Type of answer text input
        else {
            if (type == "text") {
                var input = 'text';
                jQuery(`
                <h4>Condition ` + j[question_nr] + ` <span class="toggle" onclick="toggle_function('condition` + question_nr + `-` + j[question_nr] + `')">(Toggle) </span></h4>
                <div class="answer_wrap" id="condition` + question_nr + `-` + j[question_nr] + `">
                    <div class="form-group">
                        <label for="logic">If input is: </label>
                        <input type="hidden" name="answers[` + question_nr + `][` + j[question_nr] + `][answer_nr]" value="` + j[question_nr] + `">
                        <input type="` + input + `" class="form-control" name="answers[` + question_nr + `][` + j[question_nr] + `][answer_text]" placeholder="Enter answer text...">
                    </div>
                    <div class="form-group">
                    ` + logic_select + disqualify_select + `
                    </div>
    
                    </div>
                </div>`).insertBefore(jQuery(this).parent());

            } else {
                var input = 'number';

                jQuery(`
                <h4>Condition ` + j[question_nr] + ` <span class="toggle" onclick="toggle_function('condition` + question_nr + `-` + j[question_nr] + `')">(Toggle) </span></h4>
                <div class="answer_wrap" id="condition` + question_nr + `-` + j[question_nr] + `">
                    <div class="form-group">
                        <label for="logic">If input is between: </label>
                        <input type="hidden" name="answers[` + question_nr + `][` + j[question_nr] + `][answer_nr]" value="` + j[question_nr] + `">
                        <input type="` + input + `" class="form-control" name="answers[` + question_nr + `][` + j[question_nr] + `][number1]" placeholder="Enter answer number...">
                         and 
                        <input type="` + input + `" class="form-control" name="answers[` + question_nr + `][` + j[question_nr] + `][number2]" placeholder="Enter answer number...">
                    </div>
                    <div class="form-group">
                    ` + logic_select + disqualify_select + `
                    </div>
    
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

    //Adding new results to select options
    jQuery(document.body).on('change', '.result_title', function() {
        var counter = 1;
        var new_select = `
        <option value="0">No</option>`;

        jQuery('.logic_select').html('');
        jQuery(".result_title").each(function() {
            new_select += `<option value="` + counter + `">` + jQuery(this).val() + `</option>`;
            counter++;
        });
        jQuery('.logic_select').html(new_select);

    });

    //Disabling corresponding/disqualifying answer
    jQuery(document.body).on('change', '.corresponding', function() {
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
//Toggling results, questions and answers
function toggle_function(id) {
    jQuery("#" + id).toggle();
}