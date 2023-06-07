<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-lg-5 col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans("add_category"); ?></h3>
            </div>
            <!-- /.box-header -->

            <!-- form start -->
            <?php echo form_open('category_controller/add_category_post'); ?>

            <input type="hidden" name="parent_id" value="0">
            <div class="box-body">
                <!-- include message block -->
                <?php $this->load->view('admin/includes/_messages_form'); ?>

                <div class="form-group">
                    <label><?php echo trans("language"); ?></label>
                    <select name="lang_id" class="form-control">
                        <?php foreach ($this->languages as $language): ?>
                            <option value="<?php echo $language->id; ?>" <?php echo ($this->selected_lang->id == $language->id) ? 'selected' : ''; ?>><?php echo $language->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label><?php echo trans('parent_category').' (Max 4 Lebel)'; ?></label>
                    <select id="categories" class="form-control" name="parent_id">
                        <option value=""><?php echo trans('select'); ?></option>
                        <?php foreach ($parent_categories as $item): ?>
                            <option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label><?php echo trans("category_name"); ?></label>
                    <input type="text" class="form-control" name="name" placeholder="<?php echo trans("category_name"); ?>"
                           value="<?php echo old('name'); ?>" maxlength="200" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?> required>
                </div>

                <div class="form-group">
                    <label class="control-label"><?php echo trans("slug"); ?>
                        <small>(<?php echo trans("slug_exp"); ?>)</small>
                    </label>
                    <input type="text" class="form-control" name="name_slug" placeholder="<?php echo trans("slug"); ?>"
                           value="<?php echo old('name_slug'); ?>" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                </div>

                <div class="form-group">
                    <label class="control-label"><?php echo trans('title'); ?> (<?php echo trans('meta_tag'); ?>)</label>
                    <input type="text" class="form-control" name="title"
                           placeholder="<?php echo trans('title'); ?> (<?php echo trans('meta_tag'); ?>)" value="<?php echo old('title'); ?>" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                </div> 
                
                <div class="form-group">
                    <label class="control-label"><?php echo trans('description'); ?> (<?php echo trans('meta_tag'); ?>)</label>
                    <input type="text" class="form-control" name="description"
                           placeholder="<?php echo trans('description'); ?> (<?php echo trans('meta_tag'); ?>)" value="<?php echo old('description'); ?>" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                </div>

                <div class="form-group">
                    <label class="control-label"><?php echo trans('keywords'); ?> (<?php echo trans('meta_tag'); ?>)</label>
                    <input type="text" class="form-control" name="keywords"
                           placeholder="<?php echo trans('keywords'); ?> (<?php echo trans('meta_tag'); ?>)" value="<?php echo old('keywords'); ?>" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                </div>

                <!-- Color Picker -->
                <div class="form-group">
                    <label><?php echo trans('color'); ?></label>
                    <div class="input-group my-colorpicker">
                        <input type="text" class="form-control" name="color" maxlength="200" placeholder="<?php echo trans('color'); ?>" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?> required>
                        <div class="input-group-addon">
                            <i></i>
                        </div>
                    </div><!-- /.input group -->
                </div><!-- /.form group -->

                <div class="form-group">
                    <label><?php echo trans('order'); ?></label>
                    <input type="number" class="form-control" name="category_order" placeholder="<?php echo trans('order'); ?>"
                           value="1" min="1" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?> required>
                </div>
				
				 <div class="form-group">
                    <label>Home Order</label>
                    <input type="number" class="form-control" name="home_order" placeholder="Home Order"
                           value="1" min="1" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?> required>
                </div>
                <div class="form-group">
                    <label>Home Stories Limit (between 1 and 20)</label>
                    <input type="number" class="form-control" name="home_limit" placeholder="Home Limit"
                           value="1" min="1" max="20" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?> required>
                </div>
                <div class="form-group">
                    <label>Home CSS Class Name</label>
                    <input type="text" class="form-control" name="home_css_name" placeholder="CSS Name" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?> >
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-5 col-xs-12">
                            <label><?php echo "Show on CMS"; ?></label>
                        </div>
                        <div class="col-md-3 col-sm-4 col-xs-12 col-option">
                            <input type="radio" id="rb_show_at_cms_1" name="show_at_cms" value="1" class="square-purple" checked>
                            <label for="rb_show_on_menu_1" class="cursor-pointer"><?php echo trans('yes'); ?></label>
                        </div>
                        <div class="col-md-3 col-sm-4 col-xs-12 col-option">
                            <input type="radio" id="rb_show_at_cms_2" name="show_at_cms" value="0" class="square-purple">
                            <label for="rb_show_on_menu_2" class="cursor-pointer"><?php echo trans('no'); ?></label>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-5 col-xs-12">
                            <label><?php echo trans('show_on_menu'); ?></label>
                        </div>
                        <div class="col-md-3 col-sm-4 col-xs-12 col-option">
                            <input type="radio" id="rb_show_on_menu_1" name="show_on_menu" value="1" class="square-purple" checked>
                            <label for="rb_show_on_menu_1" class="cursor-pointer"><?php echo trans('yes'); ?></label>
                        </div>
                        <div class="col-md-3 col-sm-4 col-xs-12 col-option">
                            <input type="radio" id="rb_show_on_menu_2" name="show_on_menu" value="0" class="square-purple">
                            <label for="rb_show_on_menu_2" class="cursor-pointer"><?php echo trans('no'); ?></label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-5 col-xs-12">
                            <label><?php echo trans('show_at_homepage'); ?></label>
                        </div>
                        <div class="col-md-3 col-sm-4 col-xs-12 col-option">
                            <input type="radio" id="rb_show_at_homepage_1" name="show_at_homepage" value="1" class="square-purple" checked>
                            <label for="rb_show_at_homepage_1" class="cursor-pointer"><?php echo trans('yes'); ?></label>
                        </div>
                        <div class="col-md-3 col-sm-4 col-xs-12 col-option">
                            <input type="radio" id="rb_show_at_homepage_2" name="show_at_homepage" value="0" class="square-purple">
                            <label for="rb_show_at_homepage_2" class="cursor-pointer"><?php echo trans('no'); ?></label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo trans('category_block_style'); ?></label>
                    <div class="row m-b-15 m-t-15">
                         <div class="category-block-box">
                            <div class="col-sm-12 text-center m-b-15">
                                <input type="radio" name="block_type" value="block-1" class="square-purple" >
                            </div>
                            <img src="<?php echo base_url(); ?>assets/admin/img/block-1.png" alt="" class="img-responsive cat-block-img">
                        </div>
                        <div class="category-block-box">
                            <div class="col-sm-12 text-center m-b-15">
                                <input type="radio" name="block_type" value="block-2" class="square-purple">
                            </div>
                            <img src="<?php echo base_url(); ?>assets/admin/img/block-2.png" alt="" class="img-responsive cat-block-img">
                        </div>

                        <div class="category-block-box">
                            <div class="col-sm-12 text-center m-b-15">
                                <input type="radio" name="block_type" value="block-3" class="square-purple" checked>
                            </div>
                            <img src="<?php echo base_url(); ?>assets/admin/img/block-3.png" alt="" class="img-responsive cat-block-img">
                        </div>
                         <div class="category-block-box">
                            <div class="col-sm-12 text-center m-b-15">
                                <input type="radio" name="block_type" value="block-4" class="square-purple">
                            </div>
                            <img src="<?php echo base_url(); ?>assets/admin/img/block-4.png" alt="" class="img-responsive cat-block-img">
                        </div>
                      <div class="category-block-box">
                            <div class="col-sm-12 text-center m-b-15">
                                <input type="radio" name="block_type" value="block-5" class="square-purple">
                            </div>
                            <img src="<?php echo base_url(); ?>assets/admin/img/block-5.png" alt="" class="img-responsive cat-block-img">
                        </div>
                    </div>

                </div>


            </div>


            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right"><?php echo trans('add_category'); ?></button>
            </div>
            <!-- /.box-footer -->
            <?php echo form_close(); ?><!-- form end -->
        </div>
        <!-- /.box -->
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css" />
<script>
    $(document).ready(function () {
        $("#categories").select2();
    });
</script>
