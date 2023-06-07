<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="row">
    <div class="col-sm-12">
        <label class="control-label control-label-content">Short Videos</label>
        <div id="quiz_results_container" class="panel-group post-list-items quiz-questions">
            <input type="hidden" name="content" value="">
            <?php if (!empty($short_videos)):
                foreach ($short_videos as $video):
                    $this->load->view("admin/post/short_videos/_update_short_videos", ['result' => $video, 'post_type' => $post_type]);
                endforeach;
            else:
                $this->load->view("admin/post/short_videos/_add_short_videos");
            endif; ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 text-center">
        <?php if (!empty($post)): ?>
            <button type="button" id="btn_add_short_videos_database" data-post-id="<?php echo $post->id; ?>" class="btn btn-md btn-success btn-add-post-item"><i class="fa fa-plus"></i><?php echo "Add Short Videos"; ?></button>
        <?php else: ?>
            <button type="button" id="btn_append_short_videos" class="btn btn-md btn-success btn-add-post-item"><i class="fa fa-plus"></i><?php echo "Add Short Videos"; ?></button>
        <?php endif; ?>
    </div>
</div>
