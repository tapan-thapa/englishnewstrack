<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="row">
    <div class="col-sm-12">
        <label class="control-label control-label-content">Live Post </label>
        <div id="quiz_results_container" class="panel-group post-list-items quiz-questions">
            <!-- <input type="hidden" name="content" value=""> -->
            <?php if (!empty($live_history)):
                foreach ($live_history as $live_historys):
                    $this->load->view("admin/post/live/_update_live_history", ['result' => $live_historys, 'post_type' => $post_type]);
                endforeach;
            else:
                $this->load->view("admin/post/live/_add_live_history");
            endif; ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 text-center">
        <?php if (!empty($post)): ?>
            <button type="button" id="btn_add_live_history_database" data-post-id="<?php echo $post->id; ?>" class="btn btn-md btn-success btn-add-post-item"><i class="fa fa-plus"></i><?php echo "Add post History"; ?></button>
        <?php else: ?>
            <button type="button" id="btn_append_live_history" class="btn btn-md btn-success btn-add-post-item"><i class="fa fa-plus"></i><?php echo "Add post History"; ?></button>
        <?php endif; ?>
    </div>
</div>
