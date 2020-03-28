jQuery(document).ready(function() {
    let i = 1;
    var j = new Array();
    //Dynamically adding questions
    jQuery("#add_question").click(function() {
        jQuery(jQuery(this).parent().prev()).append(`
        <div class="form-group question_wrap">
            <div class="question_heading">
                <a class="collapse_link" data-toggle="collapse" data-target="#question` + i + `" aria-expanded="true">
                <h3>Question ` + i + `<span class="glyphicon glyphicon-collapse-down"></span></h3>
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
                <div class="form-group add_answer_btn">
                    <label for="button">Add new answer (Max 5)</label>
                    <button type="button" class="btn btn-default add_answer" value="` + i + `" aria-label="Left Align">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </div>
        `);
        //Disable after 8 questions (Maximum allowed)
        if (i == 8)
            jQuery('#add_question').prop('disabled', true);
        i++;
    });


    //Dymnamically adding answers   
    jQuery(document.body).on('click', '.add_answer', function() {
        let question_nr = jQuery(this).val();
        if (isNaN(j[question_nr]))
            j[question_nr] = 1;
        else
            j[question_nr]++;
        if (j[question_nr] == 5)
            jQuery(this).prop('disabled', true);
        jQuery(`
        <div class="form-group answer_wrap">
        <h3>Answer ` + j[question_nr] + `</h3>
        <label for="answer">Answer</label>
        <input type="text" class="form-control" name="answers[` + question_nr + `][` + j[question_nr] + `]" placeholder="Enter answer text...">
        </div>`).insertBefore(jQuery(this).parent());
        console.log(question_nr);
    });

    //Change glyphicon on collapse
    jQuery(document.body).on('click', '.collapse_link', function() {
        let span = jQuery(this).find("span")
        if (jQuery(this).find("span").hasClass("glyphicon-collapse-up")) {
            span.addClass("glyphicon-collapse-down");
            span.removeClass("glyphicon-collapse-up");
        } else {
            span.addClass("glyphicon-collapse-up");
            span.removeClass("glyphicon-collapse-down");

        }
    });

});