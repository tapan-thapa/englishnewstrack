<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
    <div class="row">
        <div class="col-sm-12">
            <!-- form start -->
            <?php echo form_open_multipart('post_controller/add_post_post'); ?>
            <input type="hidden" name="post_type" value="webstory">
            <div class="row">
                <div class="col-sm-12 form-header">
                    <h1 class="form-title"><?php echo $title; ?></h1>
                    <a href="<?php echo admin_url(); ?>posts" class="btn btn-success btn-add-new pull-right">
                        <i class="fa fa-bars"></i>
                        <?php echo trans('posts'); ?>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-post">
                        <div class="form-post-left">
                            <div class="row">
                                <div class="col-sm-12">
                                    <?php $this->load->view("admin/post/_form_add_webstory_left"); ?>
                                </div>
                            </div>
                            <?php $this->load->view("admin/post/webstory/_webstory"); ?>
                            <?php // $this->load->view("admin/post/quiz/_questions"); ?>
                        </div>
                        <div class="form-post-right">
                            <div class="row">
                                <div class="col-sm-12">
                                    <?php $this->load->view('admin/post/_upload_image_box',['post_type' => $post_type]); ?>
                                </div>
                                <div class="col-sm-12">
                                <?php $this->load->view('admin/post/_post_reporter_box'); ?>
								</div>
                                <div class="col-sm-12">
                                    <?php $this->load->view('admin/post/_categories_v2_box'); ?>
                                </div>
                                <div class="col-sm-12">
                                    <?php $this->load->view('admin/post/_publish_box', ['post_type' => "live_post"]); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo form_close(); ?><!-- form end -->
        </div>
    </div>

<?php $this->load->view('admin/file-manager/_load_file_manager', ['load_images' => true, 'load_quiz_images' => true, 'load_files' => false, 'load_videos' => false, 'load_audios' => false]); ?>