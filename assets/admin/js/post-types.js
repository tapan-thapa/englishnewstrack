//append list item
$(document).on('click', '#btn_append_post_list_item', function () {
    var max_item_order = 0;
    $(".input_list_item_order").each(function (index) {
        var val = parseInt($(this).val());
        if (val > max_item_order) {
            max_item_order = val;
        }
    });
    var data = {
        'new_item_order': max_item_order + 1
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "post_controller/get_list_item_html",
        data: data,
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.result == 1) {
                $('#list_items_container').append(obj.html);
            }
        }
    });
});

//add list item database
$(document).on('click', '#btn_add_post_list_item_database', function () {
    var post_id = $(this).attr('data-post-id');
    var data = {
        "post_id": post_id,
        "post_type": post_type
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "post_controller/add_list_item",
        data: data,
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.result == 1) {
                $('#list_items_container').append(obj.html);
            }
        }
    });
});

//update list item title
$(document).on('input change keyup paste', '.input-post-list-item-title', function () {
    var title_id = $(this).attr('data-title-id');
    var text = $(this).val().substring(0, 80);
    if ($(this).val().length > 80) {
        text = text + '...';
    }
    $("#" + title_id).text(text);
});

//delete list item image
$(document).on('click', '.btn-delete-list-item-image', function () {
    var item_id = $(this).attr("data-list-item-id");
    var content = '<input type="hidden" name="list_item_image_id[]" value="0">' +
        '<a class="btn-post-list-item-image" data-toggle="modal" data-target="#file_manager_image" data-image-type="list_item" data-list-item-id="' + item_id + '">' +
        '<i class="fa fa-plus"></i>' +
        '<p class="select-img-text">' + txt_select_image + '</p>' +
        '</a>';
    document.getElementById("post_list_item_image_container_" + item_id).innerHTML = content;
});

//update item order
$(document).on('input change keyup paste', '.input_list_item_order', function () {
    var data_id = $(this).attr("data-list-item-id");
    var val = $(this).val();
    if (Math.floor(val) == val && $.isNumeric(val) && val > 0) {
        $("#list_item_order_" + data_id).text(val);
    } else {
        $(this).val('1');
    }
});

//delete list item
function delete_post_list_item(item_id, message) {
    swal({
        text: message,
        icon: "warning",
        buttons: [sweetalert_cancel, sweetalert_ok],
        dangerMode: true,
    }).then(function (willDelete) {
        if (willDelete) {
            $('#panel_list_item_' + item_id).remove();
        }
    });
}

//delete list item database
function delete_post_list_item_database(item_id, post_type, message) {
    swal({
        text: message,
        icon: "warning",
        buttons: [sweetalert_cancel, sweetalert_ok],
        dangerMode: true,
    }).then(function (willDelete) {
        if (willDelete) {
            var data = {
                'item_id': item_id,
                'post_type': post_type
            };
            data[csfr_token_name] = $.cookie(csfr_cookie_name);
            $.ajax({
                type: "POST",
                url: base_url + "post_controller/delete_post_list_item_post",
                data: data,
                success: function (response) {
                    $('#panel_list_item_' + item_id).remove();
                }
            });
        }
    });
}

//delete selected list item image
$(document).on('click', '.btn-delete-selected-list-item-image', function () {
    var list_item_id = $(this).attr("data-list-item-id");
    var is_update = $(this).attr("data-is-update");
    var data_post_type = $(this).attr("data-post-type");
    var input = '<input type="hidden" name="list_item_image[]" value="">' +
        '<input type="hidden" name="list_item_image_large[]" value="">';
    if (is_update) {
        input = '<input type="hidden" name="list_item_image_' + list_item_id + '" value=""> ' +
            '<input type="hidden" name="list_item_image_large_' + list_item_id + '" value="">';
    }
    var content = '<div class="list-item-image-container">' +
        input +
        '<a class="btn-select-image" data-toggle="modal" data-target="#file_manager_image" data-post-type="' + data_post_type + '" data-image-type="list_item" data-list-item-id="' + list_item_id + '" data-is-update="' + is_update + '">' +
        '<div class="btn-select-image-inner">' +
        '<i class="icon-images"></i>' +
        '<button class="btn">' + txt_select_image + '</button>' +
        '</div>' +
        '</a>' +
        '</div>';
    document.getElementById("post_list_item_image_container_" + list_item_id).innerHTML = content;
});


/*
*------------------------------------------------------------------------------------------
* Quiz
*------------------------------------------------------------------------------------------
*/

//append quiz question
$(document).on('click', '#btn_append_quiz_question', function () {
    var max_question_order = 0;
    $(".input_quiz_question_order").each(function (index) {
        var val = parseInt($(this).val());
        if (val > max_question_order) {
            max_question_order = val;
        }
    });
    var data = {
        "post_type": post_type,
        'new_question_order': max_question_order + 1
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "post_controller/get_quiz_question_html",
        data: data,
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.result == 1) {
                $('#quiz_questions_container').append(obj.html);
            }
        }
    });
});


//add quiz question database
$(document).on('click', '#btn_add_quiz_question_database', function () {
    var post_id = $(this).attr('data-post-id');
    var data = {
        "post_id": post_id,
        "post_type": post_type
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "post_controller/add_quiz_question",
        data: data,
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.result == 1) {
                $('#quiz_questions_container').append(obj.html);
            }
        }
    });
});

//update quiz result orders
function update_quiz_result_orders() {
    $("#quiz_results_container .panel-quiz-result").each(function (index) {
        var result_id = $(this).attr('data-result-id');
        $('#quiz_result_order_' + result_id).text(index + 1);
        $('#input_quiz_result_order_' + result_id).val(index + 1);
    });
};

//update question title
$(document).on('input change keyup paste', '.input-question-text', function () {
    var title_id = $(this).attr('data-question-id');
    var text = $(this).val().substring(0, 80);
    if ($(this).val().length > 80) {
        text = text + '...';
    }
    $("#quiz_question_title_" + title_id).text(text);
});

//update result title
$(document).on('input change keyup paste', '.input-result-text', function () {
    var title_id = $(this).attr('data-result-id');
    var text = $(this).val().substring(0, 80);
    if ($(this).val().length > 80) {
        text = text + '...';
    }
    $("#quiz_result_title_" + title_id).text(text);
    update_answer_result_dropdowns();
    init_tinymce('.tinyMCEQuiz', 200);
});

//select quiz answer format
$(document).on('click', '.btn-group-answer-formats .btn', function () {
    var answer_format = $(this).attr('data-answer-format');
    var question_id = $(this).attr('data-question-id');

    $('.btn-group-answer-formats .btn').removeClass('active');

    $('#panel_quiz_question_' + question_id + ' .quiz-answers').removeClass('quiz-answers-format-text');
    $('#panel_quiz_question_' + question_id + ' .quiz-answers').removeClass('quiz-answers-format-large-image');
    if (answer_format == 'text') {
        $('#panel_quiz_question_' + question_id + ' .quiz-answers').addClass('quiz-answers-format-text');
    } else if (answer_format == 'large_image') {
        $('#panel_quiz_question_' + question_id + ' .quiz-answers').addClass('quiz-answers-format-large-image');
    }

    $('#input_answer_format_' + question_id).val(answer_format);
    $(this).addClass('active');
});

//append quiz answer
$(document).on('click', '#btn_add_quiz_answer', function () {
    var question_id = $(this).attr('data-question-id');
    var data = {
        "post_type": post_type,
        "question_id": question_id
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "post_controller/get_quiz_answer_html",
        data: data,
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.result == 1) {
                $('#quiz_answers_container_question_' + question_id).append(obj.html);
            }
        }
    });
});

//append quiz answer database
$(document).on('click', '#btn_add_quiz_answer_database', function () {
    var question_id = $(this).attr('data-question-id');
    var data = {
        "post_type": post_type,
        "question_id": question_id
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "post_controller/add_quiz_question_answer",
        data: data,
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.result == 1) {
                $('#quiz_answers_container_question_' + question_id).append(obj.html);
            }
        }
    });
});

//update question order
$(document).on('input change keyup paste', '.input_quiz_question_order', function () {
    var data_id = $(this).attr("data-question-id");
    var val = $(this).val();
    if (Math.floor(val) == val && $.isNumeric(val) && val > 0) {
        $("#quiz_question_order_" + data_id).text(val);
    } else {
        $(this).val('1');
    }
});

//delete quiz question
function delete_quiz_question(question_id, message) {
    swal({
        text: message,
        icon: "warning",
        buttons: [sweetalert_cancel, sweetalert_ok],
        dangerMode: true,
    }).then(function (willDelete) {
        if (willDelete) {
            $('#panel_quiz_question_' + question_id).remove();
        }
    });
}

//delete quiz question database
function delete_quiz_question_database(question_id, message) {
    swal({
        text: message,
        icon: "warning",
        buttons: [sweetalert_cancel, sweetalert_ok],
        dangerMode: true,
    }).then(function (willDelete) {
        if (willDelete) {
            var data = {
                'question_id': question_id
            };
            data[csfr_token_name] = $.cookie(csfr_cookie_name);
            $.ajax({
                type: "POST",
                url: base_url + "post_controller/delete_quiz_question",
                data: data,
                success: function (response) {
                    $('#panel_quiz_question_' + question_id).remove();
                }
            });
        }
    });
}

//delete quiz answer
function delete_quiz_answer(answer_id, message) {
    swal({
        text: message,
        icon: "warning",
        buttons: [sweetalert_cancel, sweetalert_ok],
        dangerMode: true,
    }).then(function (willDelete) {
        if (willDelete) {
            $('#quiz_answer_' + answer_id).remove();
        }
    });
}

//delete quiz answer database
function delete_quiz_answer_database(answer_id, message) {
    swal({
        text: message,
        icon: "warning",
        buttons: [sweetalert_cancel, sweetalert_ok],
        dangerMode: true,
    }).then(function (willDelete) {
        if (willDelete) {
            var data = {
                'answer_id': answer_id
            };
            data[csfr_token_name] = $.cookie(csfr_cookie_name);
            $.ajax({
                type: "POST",
                url: base_url + "post_controller/delete_quiz_question_answer",
                data: data,
                success: function (response) {
                    $('#quiz_answer_' + answer_id).remove();
                }
            });
        }
    });
}

//append quiz result
$(document).on('click', '#btn_append_quiz_result', function () {
    var data = {
        "post_type": post_type
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "post_controller/get_quiz_result_html",
        data: data,
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.result == 1) {
                $('#quiz_results_container').append(obj.html);
                update_quiz_result_orders();
            }
        }
    });
});

//append quiz question database
$(document).on('click', '#btn_add_quiz_result_database', function () {
    var post_id = $(this).attr('data-post-id');
    var data = {
        "post_id": post_id,
        "post_type": post_type
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "post_controller/add_quiz_result",
        data: data,
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.result == 1) {
                $('#quiz_results_container').append(obj.html);
                update_quiz_result_orders();
            }
        }
    });
});

//delete quiz result
function delete_quiz_result(result_id, message) {
    swal({
        text: message,
        icon: "warning",
        buttons: [sweetalert_cancel, sweetalert_ok],
        dangerMode: true,
    }).then(function (willDelete) {
        if (willDelete) {
            $('#panel_quiz_' + result_id).remove();
            update_quiz_result_orders();
        }
    });
}

//raj append live blog details
$(document).on('click', '#btn_add_live_history_database', function () {
    var post_id = $(this).attr('data-post-id');
    var data = {
        "post_id": post_id,
        "post_type": post_type
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "post_controller/add_live_history",
        data: data,
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.result == 1) {
                $('#quiz_results_container').append(obj.html);
                update_quiz_result_orders();
            }
        }
    });
});

//raj live history 
$(document).on('click', '#btn_append_live_history', function () {
    var data = {
        "post_type": post_type
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "post_controller/get_live_history_html",
        data: data,
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.result == 1) {
                $('#quiz_results_container').append(obj.html);
                update_quiz_result_orders();
            }
        }
    });
});


//raj delete live blog database
function delete_live_blog_database(result_id, message) {
    swal({
        text: message,
        icon: "warning",
        buttons: [sweetalert_cancel, sweetalert_ok],
        dangerMode: true,
    }).then(function (willDelete) {
        if (willDelete) {
            var data = {
                'result_id': result_id
            };
            data[csfr_token_name] = $.cookie(csfr_cookie_name);
            $.ajax({
                type: "POST",
                url: base_url + "post_controller/delete_live_history",
                data: data,
                success: function (response) {
                    $('#panel_quiz_' + result_id).remove();
                    update_quiz_result_orders();
                }
            });
        }
    });
}

//raj append webstory details
$(document).on('click', '#btn_add_webstory_database', function () {
    var post_id = $(this).attr('data-post-id');
    var data = {
        "post_id": post_id,
        "post_type": post_type
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "post_controller/add_webstory",
        data: data,
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.result == 1) {
                $('#quiz_results_container').append(obj.html);
                update_quiz_result_orders();
            }
        }
    });
});

//raj webstory
$(document).on('click', '#btn_append_webstory', function () {
    var data = {
        "post_type": post_type
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "post_controller/get_webstory_html",
        data: data,
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.result == 1) {
                $('#quiz_results_container').append(obj.html);
                update_quiz_result_orders();
            }
        }
    });
});


//raj delete webstory database
function delete_webstory_database(result_id, message) {
    swal({
        text: message,
        icon: "warning",
        buttons: [sweetalert_cancel, sweetalert_ok],
        dangerMode: true,
    }).then(function (willDelete) {
        if (willDelete) {
            var data = {
                'result_id': result_id
            };
            data[csfr_token_name] = $.cookie(csfr_cookie_name);
            $.ajax({
                type: "POST",
                url: base_url + "post_controller/delete_webstory",
                data: data,
                success: function (response) {
                    $('#panel_quiz_' + result_id).remove();
                    update_quiz_result_orders();
                }
            });
        }
    });
}

/* for short videos */
$(document).on('click', '#btn_add_short_videos_database', function () {
    var post_id = $(this).attr('data-post-id');
    var data = {
        "post_id": post_id,
        "post_type": post_type
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "post_controller/add_short_videos",
        data: data,
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.result == 1) {
                $('#quiz_results_container').append(obj.html);
                update_quiz_result_orders();
            }
        }
    });
});

$(document).on('click', '#btn_append_short_videos', function () {
    var data = {
        "post_type": post_type
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "post_controller/get_short_video_html",
        data: data,
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.result == 1) {
                $('#quiz_results_container').append(obj.html);
                update_quiz_result_orders();
            }
        }
    });
});


//raj delete webstory database
function delete_short_videos_database(result_id, message) {
    swal({
        text: message,
        icon: "warning",
        buttons: [sweetalert_cancel, sweetalert_ok],
        dangerMode: true,
    }).then(function (willDelete) {
        if (willDelete) {
            var data = {
                'result_id': result_id
            };
            data[csfr_token_name] = $.cookie(csfr_cookie_name);
            $.ajax({
                type: "POST",
                url: base_url + "post_controller/delete_short_videos",
                data: data,
                success: function (response) {
                    $('#panel_quiz_' + result_id).remove();
                    update_quiz_result_orders();
                }
            });
        }
    });
}

//delete quiz result database
function delete_quiz_result_database(result_id, message) {
    swal({
        text: message,
        icon: "warning",
        buttons: [sweetalert_cancel, sweetalert_ok],
        dangerMode: true,
    }).then(function (willDelete) {
        if (willDelete) {
            var data = {
                'result_id': result_id
            };
            data[csfr_token_name] = $.cookie(csfr_cookie_name);
            $.ajax({
                type: "POST",
                url: base_url + "post_controller/delete_quiz_result",
                data: data,
                success: function (response) {
                    $('#panel_quiz_' + result_id).remove();
                    update_quiz_result_orders();
                }
            });
        }
    });
}

//update quiz result dropdowns
function update_answer_result_dropdowns() {
    var dropdown_options_array = [];
    $("#quiz_results_container .panel-quiz-result").each(function (index) {
        var result_id = $(this).attr("data-result-id");
        var result_text = $("#panel_quiz_" + result_id + " .input-result-text").val();
        var option = [index + 1, result_text];
        dropdown_options_array.push(option);
    });
    $(".personality-quiz-result-dropdown").each(function (index) {
        var val = $(this).val();
        $(this).find('option').remove();
        $(this).append('<option value="">' + text_select_a_result + '</option>');
        var i;
        for (i = 0; i < dropdown_options_array.length; i++) {
            var order = i + 1;
            if (dropdown_options_array[i][0] == val) {
                $(this).append('<option value="' + order + '" selected>' + order + '. ' + dropdown_options_array[i][1] + '</option>');
            } else {
                $(this).append('<option value="' + order + '">' + order + '. ' + dropdown_options_array[i][1] + '</option>');
            }
        }
    });
}

//delete selected quiz question image
$(document).on('click', '.btn-delete-selected-quiz-question-image', function () {
    var question_id = $(this).attr("data-question-id");
    var is_update = $(this).attr("data-is-update");
    var input = '<input type="hidden" name="question_image[]" value="">';
    if (is_update) {
        input = '<input type="hidden" name="question_image_' + question_id + '" value="">';
    }
    var content = '<div class="quiz-question-image-container">' +
        input +
        '<a class="btn-select-image" data-toggle="modal" data-target="#file_manager_quiz_image" data-quiz-image-type="question" data-question-id="' + question_id + '" data-answer-id="" data-is-update="' + is_update + '">' +
        '<div class="btn-select-image-inner">' +
        '<i class="icon-images"></i>' +
        '<button class="btn">' + txt_select_image + '</button>' +
        '</div>' +
        '</a>' +
        '</div>';
    document.getElementById("quiz_question_image_container_" + question_id).innerHTML = content;
});

//delete selected quiz answer image
$(document).on('click', '.btn-delete-selected-quiz-answer-image', function () {
    var answer_id = $(this).attr("data-answer-id");
    var question_id = $(this).attr("data-question-id");
    var is_update = $(this).attr("data-is-update");
    var input = '<input type="hidden" name="answer_image[]" value="">';
    if (is_update) {
        input = '<input type="hidden" name="answer_image_' + answer_id + '" value="">';
    }
    var content = '<div class="quiz-answer-image-container">' +
        input +
        '<a class="btn-select-image" data-toggle="modal" data-target="#file_manager_quiz_image" data-quiz-image-type="answer" data-question-id="' + question_id + '" data-answer-id="' + answer_id + '" data-is-update="' + is_update + '">' +
        '<div class="btn-select-image-inner">' +
        '<i class="icon-images"></i>' +
        '<button class="btn">' + txt_select_image + '</button>' +
        '</div>' +
        '</a>' +
        '</div>';
    document.getElementById("quiz_answer_image_container_answer_" + answer_id).innerHTML = content;
});

//delete selected quiz result image
$(document).on('click', '.btn-delete-selected-quiz-result-image', function () {
    var result_id = $(this).attr("data-result-id");
    var is_update = $(this).attr("data-is-update");
    var input = '<input type="hidden" name="result_image[]" value="">';
    if (is_update) {
        input = '<input type="hidden" name="result_image_' + result_id + '" value="">';
    }
    var data_post_type = $("input[name=post_type]").val();

    var content = '<div class="list-item-image-container">' +
        input +
        '<a class="btn-select-image" data-toggle="modal" data-target="#file_manager_image" data-post-type="' + data_post_type + '" data-image-type="list_item" data-quiz-image-type="result" data-list-item-id="' + result_id + '" data-result-id="' + result_id + '" data-is-update="' + is_update + '">' +
        '<div class="btn-select-image-inner">' +
        '<i class="icon-images"></i>' +
        '<button class="btn">' + txt_select_image + '</button>' +
        '</div>' +
        '</a>' +
        '</div>';

    if($("#quiz_result_image_container_" + result_id).length){
        document.getElementById("quiz_result_image_container_" + result_id).innerHTML = content;
    }else if($("#post_list_item_image_container_" + result_id).length){
        document.getElementById("post_list_item_image_container_" + result_id).innerHTML = content;
    }
    
});

$(document).ready(function () {
    update_quiz_result_orders();
});

$(document).ajaxStop(function () {
    update_answer_result_dropdowns();
    init_tinymce('.tinyMCE', 500);
    init_tinymce('.tinyMCEQuiz', 200);
});
/* $(document).on('click', '#category_add', function () {
    if($("#maincategory .form-group").length < 4 ){
        var lastIndex = parseInt($(".cat_number").last().text());
        $("#maincategory").append('<div class="form-group">'+$("#maincategory .form-group").first().html()+'</div>');
        $(".cat_number").last().text(lastIndex+1);
    }else{
        alert("You can add max 4 category");
    }
});
$(document).on('click', '#category_remove', function () {
    if($("#maincategory .form-group").length > 1 ){
        $("#maincategory .form-group").last().remove();
    }else{
        alert("you can't delete primary category");
    }
}); */
function matchStart(params, data) {
    // If there are no search terms, return all of the data
    if ($.trim(params.term) === '') {
      return data;
    }
  
    // Skip if there is no 'children' property
    if (typeof data.children === 'undefined') {
      return null;
    }
  
    // `data.children` contains the actual options that we are matching against
    var filteredChildren = [];
    $.each(data.children, function (idx, child) {
      if (child.text.toUpperCase().indexOf(params.term.toUpperCase()) == 0) {
        filteredChildren.push(child);
      }
    });
  
    // If we matched any of the timezone group's children, then set the matched children on the group
    // and return the group object
    if (filteredChildren.length) {
      var modifiedData = $.extend({}, data, true);
      modifiedData.children = filteredChildren;
  
      // You can return modified objects from here
      // This includes matching the `children` how you want in nested data sets
      return modifiedData;
    }
  
    // Return `null` if the term should not be displayed
    return null;
  }
$(document).ready(function() {
    if(typeof $.fn.select2 !== 'undefined') { 
        function matchCustom(params, data) {
            // If there are no search terms, return all of the data
            if ($.trim(params.term) === '') {
              return data;
            }
        
            // Do not display the item if there is no 'text' property
            if (typeof data.text === 'undefined') {
              return null;
            }
        
            // `params.term` should be the term that is used for searching
            // `data.text` is the text that is displayed for the data object
            if (data.text.indexOf(params.term) > -1) {
              var modifiedData = $.extend({}, data, true);
              modifiedData.text += ' (matched)';
        
              // You can return modified objects from here
              // This includes matching the `children` how you want in nested data sets
              return modifiedData;
            }
        
            // Return `null` if the term should not be displayed
            return null;
        }
        $('#categories,#reporter_id,#user_id').select2();
        $('#other_categories').select2({
            maximumSelectionLength: 3
        });
    }
    $("form.featuredValidate").submit(function () {
        var ids = "";
        $("input[id^=postid_]").each(function() {
            ids += this.id.split("_")[1]+",";
        });
        if(ids){
            $("input[name=featured_order_ids]").val(ids);
            return true;
        }else{
            alert("Error, Posts not available for priority");
            return false;
        }
        

        /* var postIds = [];
        var allDataObject = [];
        $("INPUT[id^=featuredorder_]").each(function(){
            var id = this.id.split("_")[1];
            postIds.push($(this).val());
            allDataObject.push({id:parseInt(id),order:parseInt($(this).val())});
        });
        var memberArrData = postIds.sort(); 
        var uniquePostIds = [];
        for (var i = 0; i < memberArrData.length - 1; i++) {
            if (memberArrData[i + 1] == memberArrData[i]) {
                uniquePostIds.push(memberArrData[i]);
            }
        }
        if(uniquePostIds.length==0){
            allDataObject.sort((a, b) => a.order > b.order ? 1 : -1);
            var ids = "";
            for (var i = 0; i < allDataObject.length; i++) {
                ids += allDataObject[i].id+",";
            }
            $(this).find("input[name=featured_order_ids]").val(ids);
            return true;
        }
        swal("","Deuplicate number not allowed","error");
        return false; */
    });
    $(".datatableListing").DataTable({
        searching: false,
        ordering:false,
        paging: false,
        retrieve: true,
        dom: 'frt',
        rowReorder: {
            selector: 'span.drag',
            update: false
        }
    });
});

