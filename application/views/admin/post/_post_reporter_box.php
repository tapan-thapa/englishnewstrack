<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="box">
    <div class="box-header with-border">
        <div class="left">
            <h3 class="box-title"><?php echo trans('reporter'); ?></h3>
        </div>
    </div><!-- /.box-header -->
    <div class="box-body">
        <div class="form-group m-0">
            <label><?php echo trans("reporter"); ?></label>
            <select name="reporter_id" id="reporter_id" class="form-control">
                <option value='0'>Select Reporter</option>
                <?php foreach ($users as $user) : ?>
                    <option value="<?php echo $user->id; ?>" <?php if (isset($post->reporter_id)) {
                                                                    echo ($post->reporter_id == $user->id) ? 'selected' : '';
                                                                } ?>><?php echo $user->username; ?>&nbsp;(<?php echo $user->role; ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</div>