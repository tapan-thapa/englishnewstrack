<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="row">
    <div class="col-sm-12">
        <label class="control-label control-label-content">Webstories</label>
        <div id="quiz_results_container" class="panel-group post-list-items quiz-questions">
            <input type="hidden" name="content" value="">
            <?php if (!empty($webstories)):
                foreach ($webstories as $webstory):
                    $this->load->view("admin/post/webstory/_update_webstory", ['result' => $webstory, 'post_type' => $post_type]);
                endforeach;
            else:
                $this->load->view("admin/post/webstory/_add_webstory");
            endif; ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 text-center">
        <?php if (!empty($post)): ?>
            <button type="button" id="btn_add_webstory_database" data-post-id="<?php echo $post->id; ?>" class="btn btn-md btn-success btn-add-post-item"><i class="fa fa-plus"></i><?php echo "Add WebStory"; ?></button>
        <?php else: ?>
            <button type="button" id="btn_append_webstory" class="btn btn-md btn-success btn-add-post-item"><i class="fa fa-plus"></i><?php echo "Add WebStory"; ?></button>
        <?php endif; ?>
    </div>
</div>
