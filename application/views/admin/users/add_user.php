<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-lg-6 col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <div class="left">
                    <h3 class="box-title"><?php echo trans("add_user"); ?></h3>
                </div>
                <div class="right">
                    <a href="<?php echo admin_url(); ?>users" class="btn btn-success btn-add-new">
                        <i class="fa fa-bars"></i>
                        <?php echo trans("users"); ?>
                    </a>
                </div>
            </div>

            <!-- form start -->
            <?php echo form_open_multipart('admin_controller/add_user_post'); ?>

            <div class="box-body">
                <!-- include message block -->
                <?php $this->load->view('admin/includes/_messages'); ?>

				  <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12 col-profile">
                           
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 col-profile">
                            <p>
                                <a class="btn btn-success btn-sm btn-file-upload">
                                    <?php echo "Upload Avatar"; ?>
                                    <input name="file" size="40" accept=".png, .jpg, .jpeg" onchange="$('#upload-file-info').html($(this).val().replace(/.*[\/\\]/, ''));" type="file">
                                </a>
                            </p>
                            <p class='label label-info' id="upload-file-info"></p>
                        </div>
                    </div>
                </div>
				
                <div class="form-group">
                    <label><?php echo trans("username"); ?></label>
                    <input type="text" name="username" class="form-control auth-form-input" placeholder="<?php echo trans("username"); ?>" value="<?php echo old("username"); ?>" required>
                </div>
                <div class="form-group">
                    <label><?php echo trans("email"); ?></label>
                    <input type="email" name="email" class="form-control auth-form-input" placeholder="<?php echo trans("email"); ?>" value="<?php echo old("email"); ?>" required>
                </div>
				<div class="form-group">
                    <label><?php echo trans("form_password"); ?></label>
                    <input type="password" name="password" class="form-control auth-form-input" placeholder="<?php echo trans("form_password"); ?>" value="<?php echo old("password"); ?>" required>
                </div>
				<div class="form-group">
                    <label class="control-label"><?php echo trans('about_me'); ?></label>
                    <textarea class="form-control text-area"
                              name="about_me" placeholder="<?php echo trans('about_me'); ?>" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?>></textarea>
                </div>
                
				
                <div class="form-group">
                    <label><?php echo trans("role"); ?></label>
                    <select name="role" class="form-control">
                        <?php foreach ($roles as $role): ?>
                            <option value="<?php echo $role->role; ?>"><?php echo $role->role_name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
				<div class="form-group">
                    <label><?php echo trans('social_accounts'); ?></label>
                    <input type="text" class="form-control form-input" name="facebook_url"
                           placeholder="Facebook <?php echo trans('url'); ?>" value="" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                </div>
<div class="form-group">
                    <input type="text" class="form-control form-input"
                           name="twitter_url" placeholder="Twitter <?php echo trans('url'); ?>"
                           value="" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                </div>

                <div class="form-group">
                    <input type="text" class="form-control form-input" name="instagram_url" placeholder="Instagram <?php echo trans('url'); ?>"
                           value="" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                </div>

                <div class="form-group">
                    <input type="text" class="form-control form-input" name="pinterest_url" placeholder="Pinterest <?php echo trans('url'); ?>"
                           value="" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                </div>

                <div class="form-group">
                    <input type="text" class="form-control form-input" name="linkedin_url" placeholder="LinkedIn <?php echo trans('url'); ?>"
                           value="" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                </div>

                <div class="form-group">
                    <input type="text" class="form-control form-input" name="vk_url"
                           placeholder="VK <?php echo trans('url'); ?>" value="" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control form-input" name="youtube_url"
                           placeholder="Youtube <?php echo trans('url'); ?>" value="" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right"><?php echo trans('add_user'); ?></button>
            </div>
            <!-- /.box-footer -->
            <?php echo form_close(); ?><!-- form end -->
        </div>
        <!-- /.box -->
    </div>
</div>
