<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="box">
    <div class="row">
        <div class="col-sm-12 form-header">
            <a href="<?php echo admin_url(); ?>add-quick-links" class="btn btn-success btn-add-new pull-right">
                <i class="fa fa-bars"></i>
                <?php echo "Quick Links"; ?>
            </a>
        </div>
    </div>
    <div class="box-header with-border">
        <div class="left">
            <h3 class="box-title"><?php echo $title; ?></h3>
        </div>

    </div><!-- /.box-header -->

    <div class="box-body">
        <div class="row">
            <!-- include message block -->
            <div class="col-sm-12">
                <?php $this->load->view('admin/includes/_messages'); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <?php echo form_open('tags_controller/add_quick_links_post'); ?>
                <input type="hidden" name="hd_edit_id" value="<?php echo $edit_id; ?>">

                <div class="form-group">
                    <input type="hidden" name="hd_edit_id" value="<?php echo $edit_id; ?>">
                    <label class="control-label">Title</label>
                    <input type="text" id="tag" class="form-control" name="tag" placeholder="Enter Title" value="<?php if (isset($edit_tags->tag)) {
                                                                                                                        echo $edit_tags->tag;
                                                                                                                    } ?>" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                </div>
                <div class="form-group">
                    <label class="control-label">Link</label>
                    <input type="text" id="tag_slug" class="form-control" name="tag_slug" placeholder="Enter Title" value="<?php if (isset($edit_tags->tag_slug)) {
                                                                                                                                echo $edit_tags->tag_slug;
                                                                                                                            } ?>" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                </div>

                <div class="form-group">
                    <input type="submit" name="save" value="Save">
                </div>
                <?php echo form_close(); ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" role="grid">

                        <thead>
                            <tr role="row">
                                <?php if (check_user_permission('manage_all_posts')) : ?>

                                <?php endif; ?>
                                <th width="20"><?php echo trans('id'); ?></th>
                                <th><?php echo trans('title'); ?></th>
                                <th>Link</th>
                                <th style="min-width: 180px;"><?php echo trans('options'); ?></th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php foreach ($tags as $item) :
                            ?>
                                <tr>

                                    <td><?php echo html_escape($item->id); ?></td>
                                    <td><?php echo $item->tag; ?></td>
                                    <td><?php echo $item->tag_slug; ?></td>
                                    <td>
                                        <a href="<?php echo admin_url(); ?>add-quick-links?edit_id=<?php echo html_escape($item->id); ?>"><i class="fa fa-edit option-icon"></i>Edit</a>
                                        <a href="javascript:void(0);" onclick="delete_item('tags_controller/delete_quick_link','<?php echo html_escape($item->id); ?>','Are you sure you want to delete this quick link?');"><i class="fa fa-trash option-icon"></i>Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                        </tbody>
                    </table>
                    <?php if (empty($tags)) : ?>
                        <p class="text-center">
                            <?php echo trans("no_records_found"); ?>
                        </p>
                    <?php endif; ?>
                    <div class="col-sm-12 table-ft">
                        <div class="row">

                            <div class="pull-right">
                                <?php //echo $this->pagination->create_links(); 
                                ?>
                            </div>


                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div><!-- /.box-body -->
</div>