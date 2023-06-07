<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <div class="pull-left">
                    <h3 class="box-title"><?php echo trans('categories'); ?></h3>
                </div>
            </div><!-- /.box-header -->

            <div class="box-body">
                <div class="row">
                    <!-- include message block -->
                    <div class="col-sm-12">
                        <?php $this->load->view('admin/includes/_messages'); ?>
                    </div>
                </div>
                <?php
                if($parentListBar){ 
                ?>
                <div class="row m-b-sm">
                    <!-- include message block -->
                    <dl class="col-sm-12">
                    <a href="<?php echo admin_url(); ?>categories">Categories</a> -> 
                    <?php echo $parentListBar; ?>
                    </dl>
                </div>
                <?php
                }
                ?>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped dataTable" id="cs_datatable_lang" role="grid"
                                   aria-describedby="example1_info">
                                <thead>
                                <tr role="row">
                                    <th width="20"><?php echo trans('id'); ?></th>
                                    <th><?php echo trans('category_name'); ?></th>
                                    <th><?php echo trans('language'); ?></th>
                                    <th><?php echo trans('order'); ?></th>
									 <th>Home Order</th>
                                    <th><?php echo trans('color'); ?></th>
                                    <th class="max-width-120"><?php echo trans('options'); ?></th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php foreach ($categories as $item): 
                                     $lang = get_language($item->lang_id); 
                                     $lang_name = "";
                                     if (!empty($lang)) {
                                        $lang_name = html_escape($lang->name);
                                    }  
                                    ?>
                                    <tr>
                                        <td><?php echo html_escape($item->id); ?></td>
                                        <td><a href="<?php echo admin_url(); ?>categories/<?php echo html_escape($item->id); ?>?select_lang=<?php echo $lang_name; ?>"><?php echo html_escape($item->name); ?></a></td>
                                        <td>
                                            <?php
                                            echo $lang_name;
                                            ?>
                                        </td>
                                        <td><?php echo html_escape($item->category_order); ?></td>
										  <td><?php echo html_escape($item->home_order); ?></td>
                                        <td>
                                            <div style="width: 60px; height: 30px; background-color:<?php echo html_escape($item->color); ?> ;"></div>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn bg-purple dropdown-toggle btn-select-option"
                                                        type="button"
                                                        data-toggle="dropdown"><?php echo trans('select_an_option'); ?>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu options-dropdown">
                                                    <li>
                                                        <a href="<?php echo admin_url(); ?>update-category/<?php echo html_escape($item->id); ?>"><i class="fa fa-edit option-icon"></i><?php echo trans('edit'); ?></a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:void(0)" onclick="delete_item('category_controller/delete_category_post','<?php echo $item->id; ?>','<?php echo trans("confirm_category"); ?>');"><i class="fa fa-trash option-icon"></i><?php echo trans('delete'); ?></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>

                                <?php endforeach; ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div><!-- /.box-body -->
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css" />
<script>
    $(document).ready(function () {
        $("#categories").select2();
    });
</script>
