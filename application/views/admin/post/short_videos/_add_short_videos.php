<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php $result_unique_id = "result_" . uniqid(); ?>
<div id="panel_quiz_<?php echo $result_unique_id; ?>" class="panel panel-default panel-quiz-result" data-result-id="<?php echo $result_unique_id; ?>">
    <div class="panel-heading">
        <h4 class="panel-title" data-toggle="collapse" data-target="#collapse_<?php echo $result_unique_id; ?>">
            #<span id="quiz_result_order_<?php echo $result_unique_id; ?>"></span>&nbsp;&nbsp;<span id="quiz_result_title_<?php echo $result_unique_id; ?>"></span>
        </h4>
        <input type="hidden" name="result_order[]" id="input_quiz_result_order_<?php echo $result_unique_id; ?>" value="">
        <div class="btn-group btn-group-post-list-option" role="group">
            <button type="button" class="btn btn-default" onclick="delete_quiz_result('<?php echo $result_unique_id; ?>','<?php echo trans("confirm_result"); ?>');"><i class="fa fa-trash"></i></button>
        </div>
    </div>
    <div id="collapse_<?php echo $result_unique_id; ?>" class="panel-collapse collapse in">
        <div class="panel-body quiz-question">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="control-label"><?php echo "Title" ?></label>
                        <input type="text" class="form-control input-result-text" id="input_result_text_<?php echo $result_unique_id; ?>" data-result-id="<?php echo $result_unique_id; ?>" name="result_title[]" placeholder="<?php echo "title"; ?>" value="" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="control-label"><?php echo "Video URL" ?></label>
                        <input type="text" class="form-control input-result-text" id="input_result_url_<?php echo $result_unique_id; ?>" data-result-id="<?php echo $result_unique_id; ?>" name="result_video_url[]" placeholder="<?php echo "video url"; ?>" value="" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="list-item-description question-description result-description">
                    <div class="left">
                            <label class="control-label"><?php echo trans("image"); ?></label>
                            <div id="post_list_item_image_container_<?php echo $result_unique_id; ?>">
                                <!-- <div class="quiz-question-image-container">
                                    <input type="hidden" name="result_image[]" value="">
                                    <input type="hidden" name="result_image_storage[]" value="">
                                    <a class="btn-select-image" data-toggle="modal" data-target="#file_manager_image" data-quiz-image-type="list_item" data-result-id="<?php echo $result_unique_id; ?>">
                                        <div class="btn-select-image-inner">
                                            <i class="icon-images"></i>
                                            <button class="btn"><?php echo trans("select_image"); ?></button>
                                        </div>
                                    </a>
                                </div> -->

                                <div class="list-item-image-container">
                                        <input type="hidden" name="list_item_image[]" value="">
                                        <input type="hidden" name="list_item_image_large[]" value="">
                                        <input type="hidden" name="list_item_image_storage[]" value="">
                                        <a class='btn-select-image' data-toggle="modal" data-target="#file_manager_image"  data-post-type="short_videos" data-image-type="list_item" data-list-item-id="<?php echo $result_unique_id; ?>" data-is-update="0">
                                            <div class="btn-select-image-inner">
                                                <i class="icon-images"></i>
                                                <button class="btn"><?php echo trans("select_image"); ?></button>
                                            </div>
                                        </a>
                                    </div>
                            </div>
                        </div>
                        <div class="right">
                            <div id="editor_<?php echo $result_unique_id; ?>">
                                <label class="control-label"><?php echo trans("description"); ?></label>
                                <textarea class="tinyMCEQuiz form-control" name="result_description[]"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>