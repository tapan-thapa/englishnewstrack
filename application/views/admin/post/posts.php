<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="box">
    <div class="box-header with-border">
        <div class="left">
            <h3 class="box-title"><?php echo $title; ?></h3>
        </div>
        <div class="right">
            <a href="<?php echo admin_url(); ?>post-newslatter" data-confirm="Are you sure you want to send the Newsletter?" class="btn btn-success btn-add-new pull-left">
                <?php echo "Newsletter" ?>
            </a>
            &nbsp;&nbsp;
            <a href="<?php echo admin_url(); ?>post-format" class="btn btn-success btn-add-new pull-right">
                <i class="fa fa-plus"></i>
                <?php echo trans('add_post'); ?>
            </a>
        </div>
    </div><!-- /.box-header -->
    <script>
    $(document).on('click', ':not(form)[data-confirm]', function(e){
    if(!confirm($(this).data('confirm'))){
      e.stopImmediatePropagation();
      e.preventDefault();
        }
    });
    </script>
    <div class="box-body">
        <div class="row">
            <!-- include message block -->
            <div class="col-sm-12">
                <?php $this->load->view('admin/includes/_messages'); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped datatableListing" role="grid">
                        <?php $this->load->view('admin/includes/_filter_posts',['filter_page'=>$list_type,'categoryTree'=>$categoryTree]); ?>
                        <thead>
                        <tr role="row">
                            <th>&nbsp;</th>
                            <?php if (check_user_permission('manage_all_posts')): ?>
                                <!-- <th width="20"><input type="checkbox" class="checkbox-table" id="checkAll"></th> -->
                            <?php endif; ?>
                            <th width="20"><?php echo trans('id'); ?></th>
                            <th><?php echo trans('post'); ?></th>
                            <th><?php echo trans('language'); ?></th>
                            <th><?php echo trans('post_type'); ?></th>
                            <th><?php echo trans('category'); ?></th>
                            <th><?php echo trans('author'); ?></th>
                            <th><?php echo trans('date_added'); ?></th>
                            <th style="min-width: 180px;"><?php echo trans('options'); ?></th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
						//print_r($posts);
						foreach ($posts as $item):
                            $lang = get_language($item->lang_id); 
							 //$category = get_category_id($item->category_id);
							 //pr( $category->name_slug,"============<br>");
							?>
                            <tr>
                                <td><span class="drag"></span>
                                <input type="hidden" id="postid_<?php echo html_escape($item->id); ?>" value="<?php echo html_escape($item->id); ?>">
                                </td>
                                <?php if (check_user_permission('manage_all_posts')): ?>
                                    <!-- <td><input type="checkbox" name="checkbox-table" class="checkbox-table" value="<?php //echo $item->id; ?>"></td> -->
                                <?php endif; ?>
                                <td><?php echo html_escape($item->id); ?></td>
                                <td>
                                    <div class="td-post-item">
                                        <a href="<?php echo site_url().$item->category_slug."/". $item->title_slug."-".$item->id; ?>" target="_blank">
                                            <div class="post-image">
                                                <div class="image">
                                                    <img src="<?php echo IMG_BASE64_1x1; ?>" data-src="<?php echo get_post_image($item, "small"); ?>" alt="" class="lazyload img-responsive"/>
                                                </div>
                                            </div>
                                            <div class="post-title">
                                                <?php echo html_escape($item->title); ?>
                                                <div class="preview">
                                                    <?php if ($item->is_slider): ?>
                                                        <label class="label bg-red label-table"><?php echo trans('slider'); ?></label>
                                                    <?php endif; ?>

                                                    <?php if ($item->is_featured): ?>
                                                        <label class="label bg-olive label-table"><?php echo trans('featured'); ?></label>
                                                    <?php endif; ?>
													
													<?php if ($item->is_special): ?>
                                                        <label class="label bg-olive label-table">Special</label>
                                                    <?php endif; ?>
													
													<?php if ($item->is_trending): ?>
                                                        <label class="label bg-olive label-table">Trending</label>
                                                    <?php endif; ?>

                                                    <?php if ($item->is_recommended): ?>
                                                        <label class="label bg-aqua label-table"><?php echo trans('recommended'); ?></label>
                                                    <?php endif; ?>

                                                    <?php if ($item->is_breaking): ?>
                                                        <label class="label bg-teal label-table"><?php echo trans('breaking'); ?></label>
                                                    <?php endif; ?>

                                                    <?php if ($item->need_auth): ?>
                                                        <label class="label label-warning label-table"><?php echo trans('only_registered'); ?></label>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </td>
                                <td>
                                    <?php
                                    $lang = get_language($item->lang_id);
                                    if (!empty($lang)) {
                                        echo html_escape($lang->name);
                                    }
                                    ?>
                                </td>
                                <td class="td-post-type">
                                    <?php echo trans($item->post_type); ?>
                                </td>
                                <td>
                                    <?php $categories = get_parent_category_tree($item->category_id, $this->categories);
                                    if (!empty($categories)):
                                        foreach ($categories as $category):
                                            if (!empty($category)): ?>
                                                <label class="category-label m-r-5 label-table" style="background-color: <?php echo html_escape($category->color); ?>!important;">
                                                    <?php echo html_escape($category->name); ?>
                                                </label>
                                            <?php endif;
                                        endforeach;
                                    endif; ?>
                                </td>
                                <td>
                                    <?php $author = get_user($item->user_id);
                                    if (!empty($author)): ?>
                                        <a href="<?php echo generate_profile_url($author->slug); ?>" target="_blank" class="table-user-link">
                                            <strong><?php echo html_escape($author->username); ?></strong>
                                        </a>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $item->created_at; ?></td>
                                <td>
                                    <!-- form post options -->
                                    <?php echo form_open('post_controller/post_options_post'); ?>
                                    <input type="hidden" name="id" value="<?php echo html_escape($item->id); ?>">
                                    <input type="hidden" name="post_type" value="<?php echo $item->post_type; ?>">

                                    <div class="dropdown">
                                        <button class="btn bg-purple dropdown-toggle btn-select-option"
                                                type="button"
                                                data-toggle="dropdown"><?php echo trans('select_an_option'); ?>
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu options-dropdown">
                                            <li>
                                                <a href="<?php echo admin_url(); ?>update-post/<?php echo html_escape($item->id); ?>?type=<?php echo html_escape($item->post_type); ?>"><i class="fa fa-edit option-icon"></i><?php echo trans('edit'); ?></a>
                                            </li>
                                            <?php if (check_user_permission('manage_all_posts') && $list_type != 'webstories'): ?>
                                                <?php /*if ($item->is_slider == 1): ?>
                                                    <li>
                                                        <button type="submit" name="option" value="add-remove-from-slider" class="btn-list-button">
                                                            <i class="fa fa-times option-icon"></i><?php echo trans('remove_slider'); ?>
                                                        </button>
                                                    </li>
                                                <?php else: ?>
                                                    <li>
                                                        <button type="submit" name="option" value="add-remove-from-slider" class="btn-list-button">
                                                            <i class="fa fa-plus option-icon"></i><?php echo trans('add_slider'); ?>
                                                        </button>
                                                    </li>
                                                <?php endif; */?>
                                                <?php if ($item->is_featured == 1): ?>
                                                    <li>
                                                        <button type="submit" name="option" value="add-remove-from-featured" class="btn-list-button">
                                                            <i class="fa fa-times option-icon"></i><?php echo trans('remove_featured'); ?>
                                                        </button>
                                                    </li>
                                                <?php else: ?>
                                                    <li>
                                                        <button type="submit" name="option" value="add-remove-from-featured" class="btn-list-button">
                                                            <i class="fa fa-plus option-icon"></i><?php echo trans('add_featured'); ?>
                                                        </button>
                                                    </li>
                                                <?php endif; ?>
												<?php if ($item->is_special == 1): ?>
                                                    <li>
                                                        <button type="submit" name="option" value="add-remove-from-special" class="btn-list-button">
                                                            <i class="fa fa-times option-icon"></i>Remove from Special
                                                        </button>
                                                    </li>
                                                <?php else: ?>
                                                    <li>
                                                        <button type="submit" name="option" value="add-remove-from-special" class="btn-list-button">
                                                            <i class="fa fa-plus option-icon"></i>Add to Special
                                                        </button>
                                                    </li>
                                                <?php endif; ?>
												<?php if ($item->is_trending == 1): ?>
                                                    <li>
                                                        <button type="submit" name="option" value="add-remove-from-trending" class="btn-list-button">
                                                            <i class="fa fa-times option-icon"></i>Remove from Trending
                                                        </button>
                                                    </li>
                                                <?php else: ?>
                                                    <li>
                                                        <button type="submit" name="option" value="add-remove-from-trending" class="btn-list-button">
                                                            <i class="fa fa-plus option-icon"></i>Add to Trending
                                                        </button>
                                                    </li>
                                                <?php endif; ?>
                                                <?php /*if ($item->is_breaking == 1): ?>
                                                    <li>
                                                        <button type="submit" name="option" value="add-remove-from-breaking" class="btn-list-button">
                                                            <i class="fa fa-times option-icon"></i><?php echo trans('remove_breaking'); ?>
                                                        </button>
                                                    </li>
                                                <?php else: ?>
                                                    <li>
                                                        <button type="submit" name="option" value="add-remove-from-breaking" class="btn-list-button">
                                                            <i class="fa fa-plus option-icon"></i><?php echo trans('add_breaking'); ?>
                                                        </button>
                                                    </li>
                                                <?php endif;*/ ?>
                                                <?php if ($item->is_recommended == 1): ?>
                                                    <li>
                                                        <button type="submit" name="option" value="add-remove-from-recommended" class="btn-list-button">
                                                            <i class="fa fa-times option-icon"></i><?php echo trans('remove_recommended'); ?>
                                                        </button>
                                                    </li>
                                                <?php else: ?>
                                                    <li>
                                                        <button type="submit" name="option" value="add-remove-from-recommended" class="btn-list-button">
                                                            <i class="fa fa-plus option-icon"></i><?php echo trans('add_recommended'); ?>
                                                        </button>
                                                    </li>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            <?php
                                            if(isset($user_email) && $user_email == "praveensinghrajpoot12@gmail.com"):
                                            ?>
                                            <li>
                                                <button type="submit" name="option" value="unpublish" class="btn-list-button">
                                                    <i class="fa fa-times option-icon"></i><?php echo "Unpublish Post"; ?>
                                                </button>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0)" onclick="delete_item('post_controller/delete_post','<?php echo $item->id; ?>','<?php echo trans("confirm_post"); ?>','<?php echo $item->post_type; ?>');"><i class="fa fa-trash option-icon"></i><?php echo trans('delete'); ?></a>
                                            </li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>

                                    <?php echo form_close(); ?><!-- form end -->
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        </tbody>
                    </table>
                    <?php if (empty($posts)): ?>
                        <p class="text-center">
                            <?php echo trans("no_records_found"); ?>
                        </p>
                    <?php endif; ?>
                    <div class="col-sm-12 table-ft">
                        <div class="row">

                            <?php if (count($posts) > 0): ?>
                                <div class="form-group text-right">
                                    <?php echo form_open('post_controller/featured_posts_order_post',['class'=>"featuredValidate"]); ?>
                                        <input type="hidden" name="featured_order_ids" value="">
                                        <input type="hidden" name="list_type" value="<?php echo $list_type; ?>">
                                        <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-check"></i>  Save Priority</button>
                                    <?php echo form_close(); ?>
                                </div>
                                <div class="clear"></div>
                                <div class="pull-left bulk-options">
                                    <button class="btn btn-sm btn-danger btn-table-delete" onclick="delete_selected_posts('<?php echo trans("confirm_posts"); ?>');"><i class="fa fa-trash option-icon"></i><?php echo trans('delete'); ?></button>
                                    <?php if ($list_type != 'slider_posts'): ?>
                                        <button class="btn btn-sm btn-default btn-table-delete" onclick="post_bulk_options('add_slider');"><i class="fa fa-plus option-icon"></i><?php echo trans('add_slider'); ?></button>
                                    <?php endif; ?>
                                    <?php if ($list_type != 'featured_posts'): ?>
                                        <button class="btn btn-sm btn-default btn-table-delete" onclick="post_bulk_options('add_featured');"><i class="fa fa-plus option-icon"></i><?php echo trans('add_featured'); ?></button>
                                    <?php endif; ?>
                                    <?php if ($list_type != 'breaking_news'): ?>
                                        <button class="btn btn-sm btn-default btn-table-delete" onclick="post_bulk_options('add_breaking');"><i class="fa fa-plus option-icon"></i><?php echo trans('add_breaking'); ?></button>
                                    <?php endif; ?>
                                    <?php if ($list_type != 'recommended_posts'): ?>
                                        <button class="btn btn-sm btn-default btn-table-delete" onclick="post_bulk_options('add_recommended');"><i class="fa fa-plus option-icon"></i><?php echo trans('add_recommended'); ?></button>
                                    <?php endif; ?>
                                    <button class="btn btn-sm btn-default btn-table-delete" onclick="post_bulk_options('remove_slider');"><i class="fa fa-minus option-icon"></i><?php echo trans('remove_slider'); ?></button>
                                    <button class="btn btn-sm btn-default btn-table-delete" onclick="post_bulk_options('remove_featured');"><i class="fa fa-minus option-icon"></i><?php echo trans('remove_featured'); ?></button>
                                    <button class="btn btn-sm btn-default btn-table-delete" onclick="post_bulk_options('remove_breaking');"><i class="fa fa-minus option-icon"></i><?php echo trans('remove_breaking'); ?></button>
                                    <button class="btn btn-sm btn-default btn-table-delete" onclick="post_bulk_options('remove_recommended');"><i class="fa fa-minus option-icon"></i><?php echo trans('remove_recommended'); ?></button>
                                </div>
                            <?php endif; ?>
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