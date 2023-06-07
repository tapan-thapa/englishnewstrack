<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-sm-12 col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <div class="left">
                    <h3 class="box-title"><?php echo "Add Tags"; ?></h3>
                </div>
                <!-- <div class="right">
                        <a href="<?php //echo admin_url(); 
                                    ?>widgets" class="btn btn-success btn-add-new">
                            <i class="fa fa-bars"></i>
                            <?php //echo trans('widgets'); 
                            ?>
                        </a>
                    </div> -->
            </div><!-- /.box-header -->

            <!-- form start -->
            <?php echo form_open_multipart('tags_controller/add_tags_post'); ?>

            <input type="hidden" name="is_custom" value="1">
            <input type="hidden" name="type" value="custom">

            <div class="box-body">
                <!-- include message block -->
                <?php $this->load->view('admin/includes/_messages'); ?>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label class="control-label"><?php echo trans('tags'); ?></label>
                            <input id="tags_1" type="text" name="tags" class="form-control tags" value="<?php echo html_escape($tags); ?>"/>
                            <small>(<?php echo trans('type_tag'); ?>)</small>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right"><?php echo "Add Tags"; ?></button>
            </div>
            <!-- /.box-footer -->
            <?php echo form_close(); ?><!-- form end -->
        </div>
        <!-- /.box -->
    </div>
</div>

<?php $this->load->view('admin/file-manager/_load_file_manager', ['load_images' => true, 'load_files' => false, 'load_videos' => false, 'load_audios' => false]); ?>