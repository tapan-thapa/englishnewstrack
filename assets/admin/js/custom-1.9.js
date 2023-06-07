//update token
$("form").submit(function () {
    $("input[name='" + csfr_token_name + "']").val($.cookie(csfr_cookie_name));
});

//datatable
$(document).ready(function () {
    $('#cs_datatable').DataTable({
        "order": [[0, "desc"]],
        "aLengthMenu": [[15, 30, 60, 100], [15, 30, 60, 100, "All"]]
    });
});

$(function () {
    $('#tags_1').tagsInput({
        width: 'auto',
        height: 'auto',
        'defaultText': '',
        onChange: function(){
            var input = $(this).siblings('.tagsinput');
            var maxLen = 3; // e.g.
            if(input.children('span.tag').length >= maxLen){
                input.children('div').hide();
            }
            else{
                input.children('div').show();
            }
        }
    });
    $('#tags_search').tagsInput({
        width: 'auto',
        height: 'auto',
        'defaultText': 'Search',
        unique: true,
        onChange: function(elem, elem_tags){
            if(elem_tags){
                var searchTag = elem_tags.split("(").pop();
                let position = searchTag.includes(")");
                if(!position){
                    $(this).siblings('.tagsinput').children('span.tag:last').remove();
                }
            }
            var input = $(this).siblings('.tagsinput');
            var maxLen = 3; // e.g.
            if(input.children('span.tag').length >= maxLen){
                input.children('div').hide();
            }else{
                input.children('div').show();
            }
        },
        autocomplete_url: '',
        autocomplete: {
            source: function (request, response) {
                var data = {
                    "term": request.term
                };
                data[csfr_token_name] = $.cookie(csfr_cookie_name);
                $.ajax({
                  url: base_url+'tags_controller/get_serach_library_tags',
                  data: data,
                  dataType: "json",
                  type: "POST",
                  success: function (data) {
                    response(data);
                  }
                });
            }
        }
    });
    $('#input_allowed_file_extensions').tagsInput({
        width: '100%',
        height: '100px',
        'defaultText': '',
    });
});

$('input[type="checkbox"].square-purple, input[type="radio"].square-purple').iCheck({
    checkboxClass: 'icheckbox_square-purple',
    radioClass: 'iradio_square-purple',
    increaseArea: '20%' // optional
});

//color picker with addon
$(".my-colorpicker").colorpicker();

function get_sub_categories(val) {
    var data = {
        "parent_id": val
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);

    $.ajax({
        type: "POST",
        url: base_url + "category_controller/get_sub_categories",
        data: data,
        success: function (response) {
            $('#subcategories').children('option:not(:first)').remove();
            $("#subcategories").append(response);
        }
    });
}

function get_parent_categories_by_lang(val) {
    var data = {
        "lang_id": val
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);

    $.ajax({
        type: "POST",
        url: base_url + "category_controller/get_parent_categories_by_lang",
        data: data,
        success: function (response) {
            $('#categories').children('option:not(:first)').remove();
            $('#subcategories').children('option:not(:first)').remove();
            $("#categories").append(response);
        }
    });
}

function get_menu_links_by_lang(val) {
    var data = {
        "lang_id": val
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);

    $.ajax({
        type: "POST",
        url: base_url + "admin_controller/get_menu_links_by_lang",
        data: data,
        success: function (response) {
            $('#parent_links').children('option:not(:first)').remove();
            $("#parent_links").append(response);
        }
    });
}

//datetimepicker

$(document).ready(function () {
    //Enablabling linked pickers
    $('#filter_start_date,#filter_end_date').datetimepicker({
        format: 'YYYY-MM-DD',
        //minDate: new Date(2016, 01 - 1, 01),
        //maxDate: 'now',
    });
    //Setting the range of dates
    $("#filter_start_date").on("dp.change", function (e) {
        $('#filter_end_date').data("DateTimePicker").minDate(e.date);
    });
    $("#filter_end_date").on("dp.change", function (e) {
        $('#filter_start_date').data("DateTimePicker").maxDate(e.date);
    });

})

$(function () {
    $('#datetimepicker').datetimepicker({
        format: 'YYYY-MM-DD HH:mm:ss'
    });
});

$('#cb_scheduled').on('ifChecked', function () {
    $("#date_published_content").show();
    $("#input_date_published").prop('required', true);
});
$('#cb_scheduled').on('ifUnchecked', function () {
    $("#date_published_content").hide();
    $("#input_date_published").prop('required', false);
});

/*
*
* Video Upload Functions
*
* */

$("#video_embed_code").on("input change keyup paste", function () {
    var embed_code = $("#video_embed_code").val();
    $("#video_preview").attr('src', embed_code);
    if ($("#video_embed_code").val() == '') {
        $("#video_embed_preview").attr('src', '');
        $("#video_embed_preview").hide();
    }
});


function get_video_from_url() {
    var url = $("#video_url").val();
    if (url) {
        var data = {
            "url": url,
        };
        data[csfr_token_name] = $.cookie(csfr_cookie_name);
        $.ajax({
            type: "POST",
            url: base_url + "post_controller/get_video_from_url",
            data: data,
            success: function (response) {
                var obj = JSON.parse(response);
                if (obj.video_embed_code) {
                    $("#video_embed_code").val(obj.video_embed_code);
                    $("#video_embed_preview").attr('src', obj.video_embed_code);
                    $("#video_embed_preview").show();
                }
                if (obj.video_thumbnail) {
                    $("#video_thumbnail_url").val(obj.video_thumbnail);
                    var image = '<div class="post-select-image-container">' +
                        '<img src="' + obj.video_thumbnail + '" alt="">' +
                        '<a id="btn_delete_post_main_image" class="btn btn-danger btn-sm btn-delete-selected-file-image">' +
                        '<i class="fa fa-times"></i> ' +
                        '</a>' +
                        '</div>';
                    document.getElementById("post_select_image_container").innerHTML = image;
                }
            }
        });
    }
}

$("#video_thumbnail_url").on("input change keyup paste", function () {
    var url = $("#video_thumbnail_url").val();
    var image = '<div class="post-select-image-container">' +
        '<img src="' + url + '" alt="">' +
        '<a id="btn_delete_post_main_image" class="btn btn-danger btn-sm btn-delete-selected-file-image">' +
        '<i class="fa fa-times"></i> ' +
        '</a>' +
        '</div>';
    document.getElementById("post_select_image_container").innerHTML = image;
    $('input[name="post_image_id"]').val('');
});

//reset file input
function reset_file_input(id) {
    $(id).val('');
    $(id + "_label").html('');
    $(id + "_button").hide();
}

//reset preview image
function reset_preview_image(id) {
    $(id).val('');
    $(id + "_image").remove();
    $(id + "_button").hide();
}

//delete post video
function delete_post_video(post_id) {
    var data = {
        "post_id": post_id,
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "post_controller/delete_post_video",
        data: data,
        success: function (response) {
            document.getElementById("post_selected_video").innerHTML = " ";
            $(".btn-delete-post-video").hide();
        }
    });
}


//check all checkboxes
$("#checkAll").click(function () {
    $('input:checkbox').not(this).prop('checked', this.checked);
});

//show hide delete button
$('.checkbox-table').click(function () {
    if ($(".checkbox-table").is(':checked')) {
        $(".btn-table-delete").show();
    } else {
        $(".btn-table-delete").hide();
    }
});

//delete selected posts
function delete_selected_posts(message) {
    swal({
        text: message,
        icon: "warning",
        buttons: [sweetalert_cancel, sweetalert_ok],
        dangerMode: true,
    }).then(function (willDelete) {
        if (willDelete) {
            var post_ids = [];

            $("input[name='checkbox-table']:checked").each(function () {
                post_ids.push(this.value);
            });
            var data = {
                'post_ids': post_ids,
            };
            data[csfr_token_name] = $.cookie(csfr_cookie_name);
            $.ajax({
                type: "POST",
                url: base_url + "post_controller/delete_selected_posts",
                data: data,
                success: function (response) {
                    location.reload();
                }
            });
        }
    });
};

//post bulk options
function post_bulk_options(operation) {
    var post_ids = [];

    $("input[name='checkbox-table']:checked").each(function () {
        post_ids.push(this.value);
    });
    var data = {
        'operation': operation,
        'post_ids': post_ids,
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "post_controller/post_bulk_options",
        data: data,
        success: function (response) {
            location.reload();
        }
    });
};

//approve selected comments
function approve_selected_comments() {
    var comment_ids = [];
    $("input[name='checkbox-table']:checked").each(function () {
        comment_ids.push(this.value);
    });
    var data = {
        'comment_ids': comment_ids,
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "admin_controller/approve_selected_comments",
        data: data,
        success: function (response) {
            location.reload();
        }
    });
};

//delete selected comments
function delete_selected_comments(message) {
    swal({
        text: message,
        icon: "warning",
        buttons: [sweetalert_cancel, sweetalert_ok],
        dangerMode: true,
    }).then(function (willDelete) {
        if (willDelete) {
            var comment_ids = [];
            $("input[name='checkbox-table']:checked").each(function () {
                comment_ids.push(this.value);
            });
            var data = {
                'comment_ids': comment_ids,
            };
            data[csfr_token_name] = $.cookie(csfr_cookie_name);
            $.ajax({
                type: "POST",
                url: base_url + "admin_controller/delete_selected_comments",
                data: data,
                success: function (response) {
                    location.reload();
                }
            });
        }
    });
};

//delete selected contact messages
function delete_selected_contact_messages(message) {
    swal({
        text: message,
        icon: "warning",
        buttons: [sweetalert_cancel, sweetalert_ok],
        dangerMode: true,
    }).then(function (willDelete) {
        if (willDelete) {
            var messages_ids = [];
            $("input[name='checkbox-table']:checked").each(function () {
                messages_ids.push(this.value);
            });
            var data = {
                'messages_ids': messages_ids,
            };
            data[csfr_token_name] = $.cookie(csfr_cookie_name);
            $.ajax({
                type: "POST",
                url: base_url + "admin_controller/delete_selected_contact_messages",
                data: data,
                success: function (response) {
                    location.reload();
                }
            });
        }
    });
};

//delete post main image
$(document).on('click', '#btn_delete_post_main_image', function () {
    var post_type = $("input[name=post_type]").val();
    post_type = (typeof post_type!="undefined")?post_type:"";
    
    var content = '<a class="btn-select-image" data-toggle="modal" data-post-type="'+post_type+'" data-target="#file_manager_image" data-image-type="main">' +
        '<div class="btn-select-image-inner">' +
        '<i class="icon-images"></i>' +
        '<button class="btn">' + txt_select_image + '</button>' +
        '</div>' +
        '</a>';
    document.getElementById("post_select_image_container").innerHTML = content;
    $("#post_image_id").val('');
    $("#video_thumbnail_url").val('');
});

//delete post main image database
$(document).on('click', '#btn_delete_post_main_image_database', function () {
    var post_type = $("input[name=post_type]").val();
    post_type = (typeof post_type!="undefined")?post_type:"";
    var data = {
        "post_id": $(this).attr("data-post-id"),
        "post_type":post_type
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "post_controller/delete_post_main_image",
        data: data,
        success: function (response) {
            var content = '<a class="btn-select-image" data-toggle="modal" data-post-type="'+post_type+'" data-target="#file_manager_image" data-image-type="main">' +
                '<div class="btn-select-image-inner">' +
                '<i class="icon-images"></i>' +
                '<button class="btn">' + txt_select_image + '</button>' +
                '</div>' +
                '</a>';
            document.getElementById("post_select_image_container").innerHTML = content;
            $("#post_image_id").val('');

            $("#video_thumbnail_url").val('');
        }
    });


});

//delete additional image
$(document).on('click', '.btn-delete-additional-image', function () {
    var item_id = $(this).attr("data-value");
    $('.additional-item-' + item_id).remove();
});

//delete additional image from database
$(document).on('click', '.btn-delete-additional-image-database', function () {
    var item_id = $(this).attr("data-value");
    $('.additional-item-' + item_id).remove();
    var data = {
        "file_id": item_id
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "post_controller/delete_post_additional_image",
        data: data,
        success: function (response) {
        }
    });
});

//delete selected file
$(document).on('click', '.btn-delete-selected-file', function () {
    var item_id = $(this).attr("data-value");
    $('#file_' + item_id).remove();
});

//delete selected file from database
$(document).on('click', '.btn-delete-selected-file-database', function () {
    var item_id = $(this).attr("data-value");
    $('#post_selected_file_' + item_id).remove();
    var data = {
        "id": item_id
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "post_controller/delete_post_file",
        data: data,
        success: function (response) {
        }
    });
});

//delete selected audio
$(document).on('click', '.btn-delete-selected-audio', function () {
    var item_id = $(this).attr("data-value");
    $('#audio_' + item_id).remove();
});

//delete selected audio from database
$(document).on('click', '.btn-delete-selected-audio-database', function () {
    var post_audio_id = $(this).attr("data-value");
    $('#post_selected_audio_' + post_audio_id).remove();
    var data = {
        'post_audio_id': post_audio_id
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "post_controller/delete_post_audio",
        data: data,
        success: function (response) {
        }
    });
});

$('.increase-count').each(function () {
    $(this).prop('Counter', 0).animate({
        Counter: $(this).text()
    }, {
        duration: 1000,
        easing: 'swing',
        step: function (now) {
            $(this).text(Math.ceil(now));
        }
    });
});

//confirm user email
function confirm_user_email(id) {
    var data = {
        'id': id,
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "admin_controller/confirm_user_email",
        data: data,
        success: function (response) {
            location.reload();
        }
    });
};

//delete item
function delete_item(url, id, message,post_type="") {
    swal({
        text: message,
        icon: "warning",
        buttons: [sweetalert_cancel, sweetalert_ok],
        dangerMode: true,
    }).then(function (willDelete) {
        if (willDelete) {
            var data = {
                'id': id,
                'post_type':post_type,
            };
            data[csfr_token_name] = $.cookie(csfr_cookie_name);
            $.ajax({
                type: "POST",
                url: base_url + url,
                data: data,
                success: function (response) {
                    location.reload();
                }
            });
        }
    });
};

//enable or disable reward system
function enable_disable_reward_system(id) {
    var data = {
        'id': id
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "reward_controller/enable_disable_reward_system",
        data: data,
        success: function (response) {
            location.reload();
        }
    });
}

//ban user
function ban_user(id, message, option) {
    swal({
        text: message,
        icon: "warning",
        buttons: [sweetalert_cancel, sweetalert_ok],
        dangerMode: true,
    }).then(function (willDelete) {
        if (willDelete) {
            var data = {
                'id': id,
                'option': option
            };
            data[csfr_token_name] = $.cookie(csfr_cookie_name);
            $.ajax({
                type: "POST",
                url: base_url + "admin_controller/ban_user_post",
                data: data,
                success: function (response) {
                    location.reload();
                }
            });
        }
    });
};

function get_albums_by_lang(val) {
    var data = {
        "lang_id": val
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);

    $.ajax({
        type: "POST",
        url: base_url + "gallery_controller/gallery_albums_by_lang",
        data: data,
        success: function (response) {
            $('#albums').children('option:not(:first)').remove();
            $("#albums").append(response);
        }
    });
}

function get_categories_by_albums(val) {
    var data = {
        "category_id": val
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);

    $.ajax({
        type: "POST",
        url: base_url + "category_controller/gallery_categories_by_album",
        data: data,
        success: function (response) {
            $('#categories').children('option:not(:first)').remove();
            $("#categories").append(response);
        }
    });
}

function set_as_album_cover(val) {
    var data = {
        "image_id": val
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);

    $.ajax({
        type: "POST",
        url: base_url + "gallery_controller/set_as_album_cover",
        data: data,
        success: function (response) {
            location.reload();
        }
    });
}

function delete_rss_feed_image(feed_id) {
    var data = {
        "feed_id": feed_id
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);

    $.ajax({
        type: "POST",
        url: base_url + "rss_controller/delete_feed_image",
        data: data,
        success: function (response) {
            location.reload();
        }
    });
}

/*
*-------------------------------------------------------------------------------------------------
* Post Options Functions
*-------------------------------------------------------------------------------------------------
*/

function select_post_item_image(file_id) {
    var data = {
        "file_id": file_id
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "file_controller/select_image_file",
        data: data,
        success: function (response) {
            var item_tab_id = $("#post_item_image_button_id").val();
            $("#selected_image_file_" + item_tab_id).attr('src', response);
            $("#selected_image_id_" + item_tab_id).val(file_id);
        }
    });
}

function set_gallery_item_box_collapsed(id) {
    var data = {
        "id": id
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "post_controller/set_gallery_item_box_collapsed",
        data: data,
        success: function (response) {
        }
    });
}

function set_ordered_list_item_box_collapsed(id) {
    var data = {
        "id": id
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "post_controller/set_ordered_list_item_box_collapsed",
        data: data,
        success: function (response) {
        }
    });
}

//delete gallery post item
function delete_gallery_post_item(item_id, message) {
    swal({
        text: message,
        icon: "warning",
        buttons: [sweetalert_cancel, sweetalert_ok],
        dangerMode: true,
    }).then(function (willDelete) {
        if (willDelete) {
            var data = {
                'item_id': item_id,
            };
            data[csfr_token_name] = $.cookie(csfr_cookie_name);
            $.ajax({
                type: "POST",
                url: base_url + "post_controller/delete_gallery_post_item_post",
                data: data,
                success: function (response) {
                    location.reload();
                }
            });
        }
    });
};

//delete ordered list item
function delete_ordered_list_item(item_id, message) {
    swal({
        text: message,
        icon: "warning",
        buttons: [sweetalert_cancel, sweetalert_ok],
        dangerMode: true,
    }).then(function (willDelete) {
        if (willDelete) {
            var data = {
                'item_id': item_id,
            };
            data[csfr_token_name] = $.cookie(csfr_cookie_name);
            $.ajax({
                type: "POST",
                url: base_url + "post_controller/delete_ordered_list_item_post",
                data: data,
                success: function (response) {
                    location.reload();
                }
            });
        }
    });
};

$('.price-input').keypress(function (event) {
    var $this = $(this);
    if ((event.which != 46 || $this.val().indexOf('.') != -1) &&
        ((event.which < 48 || event.which > 57) &&
            (event.which != 0 && event.which != 8))) {
        event.preventDefault();
    }
    var text = $(this).val();
    if ((text.indexOf('.') != -1) &&
        (text.substring(text.indexOf('.')).length > 2) &&
        (event.which != 0 && event.which != 8) &&
        ($(this)[0].selectionStart >= text.length - 2)) {
        event.preventDefault();
    }
});

//upload product image update page
$(document).on('change', '#Multifileupload', function () {
    var MultifileUpload = document.getElementById("Multifileupload");
    if (typeof (FileReader) != "undefined") {
        var MultidvPreview = document.getElementById("MultidvPreview");
        MultidvPreview.innerHTML = "";
        var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.jpg|.jpeg|.gif|.png|.bmp)$/;
        for (var i = 0; i < MultifileUpload.files.length; i++) {
            var file = MultifileUpload.files[i];
            var reader = new FileReader();
            reader.onload = function (e) {
                var img = document.createElement("IMG");
                img.height = "100";
                img.width = "100";
                img.src = e.target.result;
                img.id = "Multifileupload_image";
                MultidvPreview.appendChild(img);
                $("#Multifileupload_button").show();
            }
            reader.readAsDataURL(file);
        }
    } else {
        alert("This browser does not support HTML5 FileReader.");
    }
});

$(document).ajaxStop(function () {

    $('input[type="checkbox"].square-purple, input[type="radio"].square-purple').iCheck({
        checkboxClass: 'icheckbox_square-purple',
        radioClass: 'iradio_square-purple',
        increaseArea: '20%' // optional
    });

    $('#cb_scheduled').on('ifChecked', function () {
        $("#date_published_content").show();
        $("#input_date_published").prop('required', true);
    });
    $('#cb_scheduled').on('ifUnchecked', function () {
        $("#date_published_content").hide();
        $("#input_date_published").prop('required', false);
    });

});
function count_word(string)
{
    if(string==""){
        return 0;
    }else{
        r1 = new RegExp('[\u3000-\u4DFF]','g');
        r2 = new RegExp('[\u4E00-\u9FFF]','g');
        r3 = new RegExp('[\u0E00-\u0E7F]','g');
        string = string.replace(r1,' {PNK} ');
        string = string.replace(r2,' {CJK} ');
        string = string.replace(r3,' {THI} ');
        //string = string.replace(/(<([^>]+)>)/ig,”") ;
        //string = string.replace(/(\(|\)|\*|\||\+|\”|\’|_|;|:|,|\.|\-|\?)/ig," ") ;
        string = string.replaceAll(',', ', ');
        string = string.replace(/\s+/ig," ");
        //string = string.replace(/_+/ig," ");
        //string = string.replace(new RegExp('<p class="iframe-media-block"></p>', 'g'), "");
        string = string.replace(/&nbsp /gi, ' ').replace(/  +/g, ' ').replace(/&nbsp;/gi, ' ').replace(/&nbsp/gi, ' ');
        string = string.toString();
        string = string.replace( /(<([^>]+)>)/ig, '');
        string = $.trim(string);
        string = string.replace(/  +/g, ' ');
        if(string==""){
            return 0;
        }else{
            var a = string.split(" ");
            
            var count = 0;
            var pnkCounter = 0;
            var thiCounter = 0;
            let wordLen = a.length;
            for (var i=0;i<wordLen;i++){
                if (a[i]=='{PNK}'){
                    pnkCounter++;
                }else if(a[i]=='{THI}'){
                    thiCounter++;
                }else if (a[i].length>0){
                    count++;
                }
            }
            count += Math.ceil(pnkCounter/3) + Math.ceil(thiCounter/4); 
            return count;
            //return a.length;
        }
    }
}
function checkInputWordLimit(inputId,eventType){ 
    let dataArrWord = $("#"+inputId).attr("word-json");
    dataArrWord     = JSON.parse(dataArrWord); 
    let data_word   = dataArrWord.word;       
    if(eventType != null && eventType.handleObj.type=="paste"){
        wordString = $("#"+inputId).val()+eventType.originalEvent.clipboardData.getData('text/plain');
    }
    else{
        wordString = $("#"+inputId).val();
    }
    totalWord = count_word(wordString); 
    wordString = wordString.replace(/\s+/ig," ");
    /* if(totalWord >data_word){
        totalWord =data_word;
        wordString= wordString.trim().split(" ").splice(0,data_word).join(" ");
        wordString +=" ";
        $("#"+inputId).val(wordString);
        if(dataArrWord.highlight !=undefined && eventType.handleObj.type=="paste"){
            $("#"+inputId).highlightWithinTextarea({
                highlight:[]
            });
        }
        if(eventType != null){
            eventType.preventDefault();
        }
    } */
    if(dataArrWord.show==1){
        $("#"+dataArrWord.showID).html(totalWord);
    }
    if(dataArrWord.totalcount !=undefined){
        $("#"+dataArrWord.totalcount).val(totalWord);
    }
    if(dataArrWord.callfun !=undefined){
        dataArrWord.callfun;
    }
    return false;
   
}
$(document).on('keyup keypress blur change paste',".inputwordcount", function (event) {
    checkInputWordLimit(this.id,event);    
});
$(document).ready(function () {
    setTimeout(() => {
        $(".inputwordcount:not([readonly])").each(function () {
            checkInputWordLimit(this.id,null);
        });
    }, 1000);
});




