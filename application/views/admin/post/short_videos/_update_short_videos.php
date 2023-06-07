<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div id="panel_quiz_<?php echo $result->id; ?>" class="panel panel-default panel-quiz-result" data-result-id="<?php echo $result->id; ?>">
    <div class="panel-heading">
        <h4 class="panel-title" data-toggle="collapse" data-target="#collapse_result_<?php echo $result->id; ?>">
            #<span id="quiz_result_order_<?php echo $result->id; ?>"></span>&nbsp;&nbsp;<span id="quiz_result_title_<?php echo $result->id; ?>"><?php echo html_escape($result->title); ?></span>
        </h4>
        <input type="hidden" name="result_order_<?php echo $result->id; ?>" id="input_quiz_result_order_<?php echo $result->id; ?>" value="">
        <div class="btn-group btn-group-post-list-option" role="group">
            <button type="button" class="del-vin btn btn-default" onclick="delete_short_videos_database('<?php echo $result->id; ?>','<?php echo trans("confirm_result"); ?>');"><i class="fa fa-trash"></i></button>
        </div>
    </div>
    <div id="collapse_result_<?php echo $result->id; ?>" class="panel-collapse collapse <?php echo (empty($result->title) && empty($result->description) && empty($result->image_path)) ? 'in' : ''; ?>">
        <div class="dad panel-body quiz-question">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="control-label"><?php echo "Title"; ?></label>
                        <input type="text" class="form-control input-result-text" data-result-id="<?php echo $result->id; ?>" name="result_title_<?php echo $result->id; ?>" placeholder="<?php echo trans("title"); ?>" value="<?php echo html_escape($result->title); ?>" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="control-label"><?php echo "Video URL"; ?></label>
                        <input type="text" class="form-control input-result-text" data-result-id="<?php echo $result->id; ?>" name="result_video_url_<?php echo $result->id; ?>" placeholder="<?php echo trans("video url"); ?>" value="<?php echo html_escape($result->video_url); ?>" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="list-item-description question-description result-description">
                        
                        <div class="left">
                            <label class="control-label"><?php echo trans("image"); ?></label>
                            <div id="post_list_item_image_container_<?php echo $result->id; ?>">
                                <div class="list-item-image-container">
                                    <?php if (!empty($result->image_path)):
                                        $img_base_url = base_url();
                                        if ($result->image_storage == "aws_s3") {
                                            $img_base_url = $this->aws_base_url;
                                        } ?>
                                        <input type="hidden" name="list_item_image_<?php echo $result->id; ?>" value="<?php echo $result->image_path; ?>">
                                        <input type="hidden" name="list_item_image_storage_<?php echo $result->id; ?>" value="<?php echo $result->image_storage; ?>">
                                        <img src="<?= $img_base_url . $result->image_path; ?>" alt="">
                                        
                                        <a class="btn btn-danger btn-sm btn-delete-selected-file-image btn-delete-selected-list-item-image" data-result-id="<?php echo $result->id; ?>" data-list-item-id="<?php echo $result->id; ?>" data-post-type="short_videos" data-is-update="1">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    <?php else: ?>
                                        <input type="hidden" name="list_item_image_<?php echo $result->id; ?>" value="">
                                        <input type="hidden" name="list_item_image_storage_<?php echo $result->id; ?>" value="">
                                        <a class="btn-select-image" data-toggle="modal" data-target="#file_manager_image" data-post-type="short_videos" data-image-type="list_item" data-list-item-id="<?php echo $result->id; ?>" data-is-update="1">
                                            <div class="btn-select-image-inner">
                                                <i class="icon-images"></i>
                                                <button class="btn"><?php echo trans("select_image"); ?></button>
                                            </div>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="right">
                            <div id="editor_result_<?php echo $result->id; ?>">
                                <label class="control-label"><?php echo trans("description"); ?></label>
                                <textarea class="tinyMCEQuiz form-control" name="result_description_<?php echo $result->id; ?>"><?php echo $result->description; ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
               
            </div>
        </div>
    </div>
</div>