<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Section: wrapper -->
<div id="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo lang_base_url(); ?>"><?php echo trans("breadcrumb_home"); ?></a>
                    </li>
                    <li class="breadcrumb-item active">Sitemap</li>
                </ol>
            </div>

            <div id="content" class="col-sm-12">
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="page-title">Sitemap</h1>
                    </div>

                    <div class="col-sm-12">
                        <div class="page-content font-text">

                            <div class="rss-item">
                                <div class="left">
                                    <a href="<?php echo lang_base_url(); ?>">&nbsp;&nbsp;Home</a>
                                </div>
                                
                            </div>

                            <?php foreach ($this->categories as $category):
                                if ($category->lang_id == $this->selected_lang->id && $category->parent_id == 0): ?>
                                    <div class="rss-item">
                                        <div class="left">
                                            <a href="<?php echo lang_base_url(); ?><?php echo html_escape($category->name_slug); ?>" target="_blank">&nbsp;&nbsp;<?php echo html_escape($category->name); ?></a>
                                        </div>
                                                                            </div>
                                    <?php $subcategories = get_subcategories($category->id, $this->categories);
                                    if (!empty($subcategories)):
                                        foreach ($subcategories as $subcategory):?>
                                            <div class="rss-item">
                                                <div class="left">
                                                    <a href="<?php echo lang_base_url(); ?><?php echo $subcategory->parent_slug."/". html_escape($subcategory->name_slug); ?>" >&nbsp;&nbsp;<?php echo html_escape($subcategory->name); ?></a>
                                                </div>
                                                
                                            </div>
                                        <?php endforeach;
                                    endif;
                                endif;
                            endforeach; ?>
							
							 <?php if (!empty($this->menu_links)):
                                foreach ($this->menu_links as $item):
                                    if ($item->item_visibility == 1 && $item->item_location == "footer"):?>
                                       <div class="rss-item">
                                                <div class="left">
                                            <a href="<?php echo generate_menu_item_url($item); ?>"><?php echo html_escape($item->item_name); ?> </a>
                                         </div>
                                                
                                            </div>
                                    <?php endif;
                                endforeach;
                            endif; ?>
							
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


</div>
<!-- /.Section: wrapper -->