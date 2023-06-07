<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css" />
<div class="box">
    <div class="box-header with-border">
        <div class="left">
            <h3 class="box-title"><?php echo trans('category'); ?></h3>
        </div>
    </div><!-- /.box-header -->
    <div class="box-body">
        <div class="form-group">
            <label><?php echo trans("language"); ?></label>
            <select name="lang_id" class="form-control" onchange="get_parent_categories_by_lang(this.value);">
                <?php foreach ($this->languages as $language) : ?>
                    <option value="<?php echo $language->id; ?>" <?php echo ($this->selected_lang->id == $language->id) ? 'selected' : ''; ?>><?php echo $language->name; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div id="maincategory">
            <div class="form-group">
                <label class="control-label"><?php echo "Primary ".trans('category'); ?></label>
                <select id="categories" name="category_id" class="form-control" required>
                    <option value=""><?php echo trans('select_category'); ?></option>
                    <?php foreach ($parent_categories as $item) : ?>
                        <?php if ($item['id'] == old('category_id') || (isset($parent_category_id) && $item['id'] == $parent_category_id)) : ?>
                            <option value="<?php echo html_escape($item['id']); ?>" selected><?php echo html_escape($item['name']); ?></option>
                        <?php else : ?>
                            <option value="<?php echo html_escape($item['id']); ?>"><?php echo html_escape($item['name']); ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div id="maincategory">
            <div class="form-group">
                <label class="control-label"><?php echo "Secondary Categories"; ?></label>
                <!-- <input type="hidden" name="cat_ids_old" value="<?php //echo implode(",",$cat_ids); ?>"> -->
                <select id="other_categories" name="cat_ids[]" class="form-control" multiple>
                    <!-- <option value=""><?php //echo trans('select_category'); ?></option> -->
                    <?php foreach ($parent_categories as $item) : ?>
                        <?php if ($item['id'] != $parent_category_id && is_array($cat_ids) && in_array($item['id'],$cat_ids)) : ?>
                            <option value="<?php echo html_escape($item['id']); ?>" selected><?php echo html_escape($item['name']); ?></option>
                        <?php elseif ($item['id'] != $parent_category_id && is_array(old('cat_ids')) && in_array($item['id'], old('cat_ids'))) : ?>
                            <option value="<?php echo html_escape($item['id']); ?>" selected><?php echo html_escape($item['name']); ?></option>
                        <?php else : ?>
                            <option value="<?php echo html_escape($item['id']); ?>"><?php echo html_escape($item['name']); ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <!-- <button id="category_add" type="button" class="btn">Add More Category</button>
        <button id="category_remove" type="button" class="btn">Remove Category</button> -->
    </div>
</div>