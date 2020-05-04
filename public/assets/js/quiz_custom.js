jQuery(document).ready(function($) {
    let i = 1;
    //Quiz start
    $("#start_btn").click(function() {
        $(this).parent().hide();
        $('.mask').hide(1000);
        $('.mask2').hide(1000);

        $('.question_wrap').first().fadeIn(1000);
        $('.counter_wrap').fadeIn(1000);
        $('#step' + i).addClass('active', 1000);
    });
    //End quiz start

    //Next and previous button
    $(".next").click(function() {
        $('#question' + i).hide();
        i++;
        $('#question' + i).fadeIn(1000);
        $('#step' + (i - 1)).addClass('done');
        $('#step' + (i - 1)).removeClass('active');
        $('#step' + i).addClass('active');
        if ($(this).hasClass('email')) {}
    });

    $(".prev").click(function() {
        $('#question' + i).hide();
        $('#step' + i).removeClass('active', 1000);
        i--;
        if (i != 0) {
            $('#question' + i).fadeIn(1000);
            $('#step' + i).addClass('active', 1000);
            $('#step' + i).removeClass('done', 1000);
        } else {
            $('.counter_wrap').hide();
            $('.first_page').fadeIn(1000);
            $('.mask').fadeIn(1000);
            $('.mask2').fadeIn(1000);
            i = 1;
        }

    });

    jQuery(document.body).on('click', '#prev_last', function() {
        i--;
        $('#back_buttons').hide();
        $('#step' + i).addClass('active');
        $('#step' + i).removeClass('done');
        $('.counter_wrap').fadeIn(1000);
        $('#question' + i).fadeIn(1000);
        $('.result').hide();
        $('.quiz_wrap').toggleClass('resultpage');
        $('.results_wrap').remove();


    })
    jQuery(document.body).on('click', '#back_to_start', function() {
        i = 1;
        $('#back_buttons').hide();
        $('.first_page').fadeIn(1000);
        $('.mask').fadeIn(1000);
        $('.mask2').fadeIn(1000);
        $('.step').each(function() {
            $(this).removeClass('done');
            $(this).removeClass('active');
        });
        jQuery("input[type=radio]").each(function() {
            $(this).prop('checked', false);

        });
        jQuery("input[type=checkbox]").each(function() {
            $(this).prop('checked', false);

        });
        jQuery("input[type=text]").each(function() {
            $(this).val('');
        });
        jQuery("input[type=number]").each(function() {
            $(this).val('');
        });
        $(".next").each(function() {
            $(this).prop('disabled', true);
        });
        $(".radio_container").each(function() {
            $(this).removeClass('active');

        })

        $('.result').hide();
        $('.quiz_wrap').toggleClass('resultpage');
        $('.results_wrap').remove();

    })

    //End buttons

    //Adding active class to answers
    jQuery("input[type=radio]").click(function() {
        jQuery(this).parent().parent().parent().find(".radio_container").removeClass('active');
        jQuery(this).parent().addClass("active");
        jQuery(this).parent().parent().parent().nextAll(".next").prop("disabled", false);

    });

    jQuery("input[type=checkbox]").click(function() {
        if (jQuery(this).attr("name") == 'email_contact') {

        } else if (jQuery(this).is(":checked")) {
            jQuery(this).parent().addClass("active");
            jQuery(this).parent().parent().parent().nextAll(".next").prop("disabled", false);
        } else {
            jQuery(this).parent().removeClass("active");
            jQuery(this).parent().parent().parent().nextAll(".next").prop("disabled", false);
        }

        if (!jQuery(":checkbox[name='" + jQuery(this).attr("name") + "']").is(":checked")) {
            jQuery(this).parent().parent().parent().nextAll(".next").prop("disabled", true);
        }

    });
    //Email validation
    function isValidEmailAddress(emailAddress) {
        var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
        return pattern.test(emailAddress);
    }

    jQuery("input[type=text]").on('input', (function() {
        if (jQuery(this).hasClass('email')) {
            if (isValidEmailAddress(jQuery(this).val()))
                jQuery(this).nextAll(".next").prop("disabled", false);
            else
                jQuery(this).nextAll(".next").prop("disabled", true);
        } else {
            if (jQuery(this).val() != '')
                jQuery(this).nextAll(".next").prop("disabled", false);
            else
                jQuery(this).nextAll(".next").prop("disabled", true);
        }
    }));

    jQuery("input[type=number]").on('input', (function() {
        if (jQuery(this).val() != '')
            jQuery(this).nextAll(".next").prop("disabled", false);
        else
            jQuery(this).nextAll(".next").prop("disabled", true);

    }));
    //End adding active class

    //Quiz submit

    $("#quiz_form").submit(function(e) {

        e.preventDefault();
        var data = new FormData(this);
        var xhr = new XMLHttpRequest();
        xhr.open('POST', my_ajax_object.ajax_url, true);
        // Set up a handler for when the request finishes.
        xhr.onload = function() {
            if (xhr.status === 200) {
                console.log(xhr.response);
                $('.counter_wrap').hide();
                $('#question' + i).hide();
                $('#lottie').fadeIn(500);
                $('#lottie').fadeOut(1000, function() {
                    jQuery('.quiz_wrap').append(JSON.parse(xhr.responseText));
                    jQuery('.quiz_wrap').append('<p id="prev_last">back</p>');
                    jQuery('.quiz_wrap').toggleClass('resultpage');
                    jQuery('.results_wrap').removeClass('hidden');
                });

            } else {
                alert('An error occurred!');
            }
        };

        xhr.send(data);
    });

    /* 
        $("#quiz_submit").click(function() {
            var value = 0;
            $("input[type=radio]:checked").each(function() {
                value = jQuery(this).val();
                if (value > 0) {
                    if (results[value] != undefined)
                        results[value] += 1;
                    else
                        results[value] = 1;
                } else {
                    value = value * (-1);
                    if (results[value] != undefined)
                        results[value] -= 999;
                    else
                        results[value] = -999;
                }
            });

            //Calculation
            var maxIndex = [];
            var max = -Infinity;

            for (var j = 0; j < results.length; j++) {
                console.log(results[j]);
                if (results[j] === max) {
                    maxIndex.push(j);
                } else if (results[j] > max) {
                    maxIndex = [j];
                    max = results[j];
                }
                //End calculation
            }


            $('#question' + i).hide();
            $('.counter_wrap').hide();
            $('.loader_container').fadeIn(500);
            $('.loader_container').fadeOut("slow", function() {
                maxIndex.forEach(function(item) {
                    $('#result' + item).fadeIn(500);
                });
            });

        }); */

    //End quiz sumbit

    var animationData = { "v": "5.1.3", "fr": 60, "ip": 0, "op": 150, "w": 110, "h": 110, "nm": "9_dark", "ddd": 0, "comps": [], "layers": [{ "ddd": 0, "ind": 2, "ty": 3, "nm": "Null 8", "hd": true, "sr": 1, "ks": { "o": { "a": 0, "k": 0, "ix": 11 }, "r": { "a": 1, "k": [{ "i": { "x": [0.833], "y": [1] }, "o": { "x": [0.333], "y": [0] }, "n": ["0p833_1_0p333_0"], "t": 0, "s": [0], "e": [630] }, { "t": 151 }], "ix": 10 }, "p": { "a": 0, "k": [55, 55, 0], "ix": 2 }, "a": { "a": 0, "k": [0, 0, 0], "ix": 1 }, "s": { "a": 0, "k": [100, 100, 100], "ix": 6 } }, "ao": 0, "ip": 0, "op": 150, "st": 0, "bm": 0 }, { "ddd": 0, "ind": 3, "ty": 4, "nm": "Shape Layer 5", "parent": 2, "sr": 1, "ks": { "o": { "a": 0, "k": 100, "ix": 11 }, "r": { "a": 0, "k": 0, "ix": 10 }, "p": { "a": 1, "k": [{ "i": { "x": 0, "y": 1 }, "o": { "x": 0.926, "y": 0 }, "n": "0_1_0p926_0", "t": 74, "s": [-42.5, 0, 0], "e": [-11.25, 0, 0], "to": [5.20833349227905, 0, 0], "ti": [-5.20833349227905, 0, 0] }, { "t": 134 }], "ix": 2 }, "a": { "a": 0, "k": [0, 0, 0], "ix": 1 }, "s": { "a": 1, "k": [{ "i": { "x": [0.667, 0.667, 0.667], "y": [1, 1, 1] }, "o": { "x": [0.333, 0.333, 0.333], "y": [0, 0, 0] }, "n": ["0p667_1_0p333_0", "0p667_1_0p333_0", "0p667_1_0p333_0"], "t": 74, "s": [0, 0, 100], "e": [130, 130, 100] }, { "t": 93 }], "ix": 6 } }, "ao": 0, "shapes": [{ "d": 1, "ty": "el", "s": { "a": 0, "k": [12, 12], "ix": 2 }, "p": { "a": 0, "k": [0, 0], "ix": 3 }, "nm": "Ellipse Path 1", "mn": "ADBE Vector Shape - Ellipse", "hd": false }, { "ty": "fl", "c": { "a": 0, "k": [0.317647058824, 0.329411764706, 0.388235294118, 1], "ix": 4 }, "o": { "a": 0, "k": 100, "ix": 5 }, "r": 1, "nm": "Fill 1", "mn": "ADBE Vector Graphic - Fill", "hd": false }], "ip": 74, "op": 135, "st": 74, "bm": 0 }, { "ddd": 0, "ind": 4, "ty": 4, "nm": "Shape Layer 4", "parent": 2, "sr": 1, "ks": { "o": { "a": 0, "k": 100, "ix": 11 }, "r": { "a": 0, "k": 0, "ix": 10 }, "p": { "a": 1, "k": [{ "i": { "x": 0, "y": 1 }, "o": { "x": 0.926, "y": 0 }, "n": "0_1_0p926_0", "t": 51, "s": [-42.5, 0, 0], "e": [-8.75, 0, 0], "to": [5.625, 0, 0], "ti": [-5.625, 0, 0] }, { "t": 111 }], "ix": 2 }, "a": { "a": 0, "k": [0, 0, 0], "ix": 1 }, "s": { "a": 1, "k": [{ "i": { "x": [0.667, 0.667, 0.667], "y": [1, 1, 1] }, "o": { "x": [0.333, 0.333, 0.333], "y": [0, 0, 0] }, "n": ["0p667_1_0p333_0", "0p667_1_0p333_0", "0p667_1_0p333_0"], "t": 51, "s": [0, 0, 100], "e": [130, 130, 100] }, { "t": 70 }], "ix": 6 } }, "ao": 0, "shapes": [{ "d": 1, "ty": "el", "s": { "a": 0, "k": [12, 12], "ix": 2 }, "p": { "a": 0, "k": [0, 0], "ix": 3 }, "nm": "Ellipse Path 1", "mn": "ADBE Vector Shape - Ellipse", "hd": false }, { "ty": "fl", "c": { "a": 0, "k": [0.317647058824, 0.329411764706, 0.388235294118, 1], "ix": 4 }, "o": { "a": 0, "k": 100, "ix": 5 }, "r": 1, "nm": "Fill 1", "mn": "ADBE Vector Graphic - Fill", "hd": false }], "ip": 51, "op": 135, "st": 51, "bm": 0 }, { "ddd": 0, "ind": 5, "ty": 4, "nm": "Shape Layer 3", "parent": 2, "sr": 1, "ks": { "o": { "a": 0, "k": 100, "ix": 11 }, "r": { "a": 0, "k": 0, "ix": 10 }, "p": { "a": 1, "k": [{ "i": { "x": 0, "y": 1 }, "o": { "x": 0.926, "y": 0 }, "n": "0_1_0p926_0", "t": 24, "s": [-42.5, 0, 0], "e": [-4.75, 0, 0], "to": [6.29166650772095, 0, 0], "ti": [-6.29166650772095, 0, 0] }, { "t": 84 }], "ix": 2 }, "a": { "a": 0, "k": [0, 0, 0], "ix": 1 }, "s": { "a": 1, "k": [{ "i": { "x": [0.667, 0.667, 0.667], "y": [1, 1, 1] }, "o": { "x": [0.333, 0.333, 0.333], "y": [0, 0, 0] }, "n": ["0p667_1_0p333_0", "0p667_1_0p333_0", "0p667_1_0p333_0"], "t": 24, "s": [0, 0, 100], "e": [130, 130, 100] }, { "t": 45 }], "ix": 6 } }, "ao": 0, "shapes": [{ "d": 1, "ty": "el", "s": { "a": 0, "k": [12, 12], "ix": 2 }, "p": { "a": 0, "k": [0, 0], "ix": 3 }, "nm": "Ellipse Path 1", "mn": "ADBE Vector Shape - Ellipse", "hd": false }, { "ty": "fl", "c": { "a": 0, "k": [0.317647058824, 0.329411764706, 0.388235294118, 1], "ix": 4 }, "o": { "a": 0, "k": 100, "ix": 5 }, "r": 1, "nm": "Fill 1", "mn": "ADBE Vector Graphic - Fill", "hd": false }], "ip": 24, "op": 135, "st": 24, "bm": 0 }, { "ddd": 0, "ind": 6, "ty": 4, "nm": "Shape Layer 2", "parent": 2, "sr": 1, "ks": { "o": { "a": 0, "k": 100, "ix": 11 }, "r": { "a": 0, "k": 0, "ix": 10 }, "p": { "a": 1, "k": [{ "i": { "x": 0, "y": 1 }, "o": { "x": 0.926, "y": 0 }, "n": "0_1_0p926_0", "t": 0, "s": [-42.5, 0, 0], "e": [-3, 0, 0], "to": [6.58333349227905, 0, 0], "ti": [-6.58333349227905, 0, 0] }, { "t": 60 }], "ix": 2 }, "a": { "a": 0, "k": [0, 0, 0], "ix": 1 }, "s": { "a": 1, "k": [{ "i": { "x": [0.667, 0.667, 0.667], "y": [1, 1, 1] }, "o": { "x": [0.333, 0.333, 0.333], "y": [0, 0, 0] }, "n": ["0p667_1_0p333_0", "0p667_1_0p333_0", "0p667_1_0p333_0"], "t": 0, "s": [0, 0, 100], "e": [130, 130, 100] }, { "t": 20 }], "ix": 6 } }, "ao": 0, "shapes": [{ "d": 1, "ty": "el", "s": { "a": 0, "k": [12, 12], "ix": 2 }, "p": { "a": 0, "k": [0, 0], "ix": 3 }, "nm": "Ellipse Path 1", "mn": "ADBE Vector Shape - Ellipse", "hd": false }, { "ty": "fl", "c": { "a": 0, "k": [0.317647058824, 0.329411764706, 0.388235294118, 1], "ix": 4 }, "o": { "a": 0, "k": 100, "ix": 5 }, "r": 1, "nm": "Fill 1", "mn": "ADBE Vector Graphic - Fill", "hd": false }], "ip": 0, "op": 135, "st": 0, "bm": 0 }, { "ddd": 0, "ind": 7, "ty": 4, "nm": "Shape Layer 1", "parent": 2, "sr": 1, "ks": { "o": { "a": 0, "k": 100, "ix": 11 }, "r": { "a": 0, "k": 0, "ix": 10 }, "p": { "a": 0, "k": [0, 0, 0], "ix": 2 }, "a": { "a": 0, "k": [0, 0, 0], "ix": 1 }, "s": { "a": 1, "k": [{ "i": { "x": [0.667, 0.667, 0.667], "y": [1, 1, 1] }, "o": { "x": [0.333, 0.333, 0.333], "y": [0, 0, 0] }, "n": ["0p667_1_0p333_0", "0p667_1_0p333_0", "0p667_1_0p333_0"], "t": 28, "s": [100, 100, 100], "e": [180, 180, 100] }, { "i": { "x": [0.667, 0.667, 0.667], "y": [1, 1, 1] }, "o": { "x": [0.333, 0.333, 0.333], "y": [0, 0, 0] }, "n": ["0p667_1_0p333_0", "0p667_1_0p333_0", "0p667_1_0p333_0"], "t": 40, "s": [180, 180, 100], "e": [160, 160, 100] }, { "i": { "x": [0.667, 0.667, 0.667], "y": [1, 1, 1] }, "o": { "x": [0.333, 0.333, 0.333], "y": [0, 0, 0] }, "n": ["0p667_1_0p333_0", "0p667_1_0p333_0", "0p667_1_0p333_0"], "t": 53, "s": [160, 160, 100], "e": [160, 160, 100] }, { "i": { "x": [0.667, 0.667, 0.667], "y": [1, 1, 1] }, "o": { "x": [0.333, 0.333, 0.333], "y": [0, 0, 0] }, "n": ["0p667_1_0p333_0", "0p667_1_0p333_0", "0p667_1_0p333_0"], "t": 54, "s": [160, 160, 100], "e": [240, 240, 100] }, { "i": { "x": [0.667, 0.667, 0.667], "y": [1, 1, 1] }, "o": { "x": [0.333, 0.333, 0.333], "y": [0, 0, 0] }, "n": ["0p667_1_0p333_0", "0p667_1_0p333_0", "0p667_1_0p333_0"], "t": 66, "s": [240, 240, 100], "e": [220, 220, 100] }, { "i": { "x": [0.667, 0.667, 0.667], "y": [1, 1, 1] }, "o": { "x": [0.333, 0.333, 0.333], "y": [0, 0, 0] }, "n": ["0p667_1_0p333_0", "0p667_1_0p333_0", "0p667_1_0p333_0"], "t": 79, "s": [220, 220, 100], "e": [220, 220, 100] }, { "i": { "x": [0.667, 0.667, 0.667], "y": [1, 1, 1] }, "o": { "x": [0.333, 0.333, 0.333], "y": [0, 0, 0] }, "n": ["0p667_1_0p333_0", "0p667_1_0p333_0", "0p667_1_0p333_0"], "t": 80, "s": [220, 220, 100], "e": [300, 300, 100] }, { "i": { "x": [0.667, 0.667, 0.667], "y": [1, 1, 1] }, "o": { "x": [0.333, 0.333, 0.333], "y": [0, 0, 0] }, "n": ["0p667_1_0p333_0", "0p667_1_0p333_0", "0p667_1_0p333_0"], "t": 92, "s": [300, 300, 100], "e": [280, 280, 100] }, { "i": { "x": [0.667, 0.667, 0.667], "y": [1, 1, 1] }, "o": { "x": [0.333, 0.333, 0.333], "y": [0, 0, 0] }, "n": ["0p667_1_0p333_0", "0p667_1_0p333_0", "0p667_1_0p333_0"], "t": 105, "s": [280, 280, 100], "e": [280, 280, 100] }, { "i": { "x": [0.667, 0.667, 0.667], "y": [1, 1, 1] }, "o": { "x": [0.333, 0.333, 0.333], "y": [0, 0, 0] }, "n": ["0p667_1_0p333_0", "0p667_1_0p333_0", "0p667_1_0p333_0"], "t": 106, "s": [280, 280, 100], "e": [360, 360, 100] }, { "i": { "x": [0.667, 0.667, 0.667], "y": [1, 1, 1] }, "o": { "x": [0.333, 0.333, 0.333], "y": [0, 0, 0] }, "n": ["0p667_1_0p333_0", "0p667_1_0p333_0", "0p667_1_0p333_0"], "t": 116, "s": [360, 360, 100], "e": [340, 340, 100] }, { "i": { "x": [0.667, 0.667, 0.667], "y": [1, 1, 1] }, "o": { "x": [1, 1, 0.333], "y": [0, 0, 0] }, "n": ["0p667_1_1_0", "0p667_1_1_0", "0p667_1_0p333_0"], "t": 126, "s": [340, 340, 100], "e": [340, 340, 100] }, { "i": { "x": [0.031, 0.031, 0.667], "y": [1, 1, 1] }, "o": { "x": [0.801, 0.801, 0.333], "y": [0, 0, 0] }, "n": ["0p031_1_0p801_0", "0p031_1_0p801_0", "0p667_1_0p333_0"], "t": 128, "s": [340, 340, 100], "e": [100, 100, 100] }, { "t": 151 }], "ix": 6 } }, "ao": 0, "shapes": [{ "d": 1, "ty": "el", "s": { "a": 0, "k": [12, 12], "ix": 2 }, "p": { "a": 0, "k": [0, 0], "ix": 3 }, "nm": "Ellipse Path 1", "mn": "ADBE Vector Shape - Ellipse", "hd": false }, { "ty": "fl", "c": { "a": 0, "k": [0.317647058824, 0.329411764706, 0.388235294118, 1], "ix": 4 }, "o": { "a": 0, "k": 100, "ix": 5 }, "r": 1, "nm": "Fill 1", "mn": "ADBE Vector Graphic - Fill", "hd": false }], "ip": 0, "op": 150, "st": 0, "bm": 0 }], "markers": [] };
    var params = {
        container: document.getElementById('lottie'),
        renderer: 'svg',
        loop: true,
        autoplay: true,
        animationData: animationData
    };

    var anim;

    anim = lottie.loadAnimation(params);

});