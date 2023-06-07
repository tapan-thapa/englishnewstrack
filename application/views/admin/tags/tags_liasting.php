<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="box">
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
                <?php echo form_open('tags_controller/save_library_tags'); ?>


                <div class="form-group">
                    <label class="control-label">Tags</label>
                    <input type="hidden" name="tag_id" value="<?php echo $edit_id; ?>">
                    <input type="text" id="tag" class="form-control" name="tag" placeholder="Enter Tag" value="<?php echo (isset($edit_tag->tag))?$edit_tag->tag:"";?>" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?> required>
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
                                <th><?php echo "Tags"; ?></th>
                                <th><?php echo "Tags Slug"; ?></th>
                                <th>Time</th>
                                <th style="min-width: 180px;"><?php echo trans('options'); ?></th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php foreach ($posts as $item) :
                            ?>
                                <tr>

                                    <td><?php echo html_escape($item->id); ?></td>
                                    <?php
                                    if (!empty($item->url)) {
                                    ?>
                                        <td><a href="<?php echo $item->url; ?>" target="_blank"><?php echo $item->tag; ?></a></td>
                                    <?php
                                    } else {
                                    ?>
                                        <td><?php echo $item->tag; ?></td>
                                        <td><?php echo $item->tag_slug; ?></td>
                                    <?php
                                    }
                                    ?>
                                    <td><?php echo $item->created_at; ?></td>
                                    <td>
                                        <a href="<?php echo admin_url(); ?>tags-library-listing?edit_id=<?php echo html_escape($item->id); ?>"><i class="fa fa-edit option-icon"></i>Edit</a>
                                        <a href="javascript:void(0)" onclick="delete_item('tags_controller/delete_library_tags','<?php echo html_escape($item->id); ?>','Are you sure you want to delete this tag?');"><i class="fa fa-trash option-icon"></i>Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                        </tbody>
                    </table>
                    <?php if (empty($posts)) : ?>
                        <p class="text-center">
                            <?php echo trans("no_records_found"); ?>
                        </p>
                    <?php endif; ?>
                    <div class="col-sm-12 table-ft">
                        <div class="row">

                            <div class="pull-right">
                                <?php echo $this->pagination->create_links(); ?>
                            </div>


                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div><!-- /.box-body -->
</div>