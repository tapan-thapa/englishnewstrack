<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<label class="control-label control-label-content"><?php echo trans('content'); ?></label>
<div id="main_editor">
    <div class="row">
        <div class="col-sm-6 editor-buttons">
            <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#file_manager_image" data-image-type="editor"><i class="fa fa-image"></i>&nbsp;&nbsp;&nbsp;<?php echo trans("add_image"); ?></button>
        </div>
        <div class="col-sm-6">
        <div class="wordCount">Words : <span id="content_tinymce_word">0</span></div>
        </div>
    </div>
    <?php if (!empty($post)): ?>
        <textarea class="tinyMCE form-control" name="content" id="content_tinymce"><?php echo $post->content; ?></textarea>
    <?php else: ?>
        <textarea class="tinyMCE form-control" name="content" id="content_tinymce"><?php echo old('content'); ?></textarea>
    <?php endif; ?>
</div>