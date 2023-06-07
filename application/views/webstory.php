<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div id="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?php echo lang_base_url(); ?>"><?php echo trans("breadcrumb_home"); ?></a>
                    </li>
                    <li class="breadcrumb-item">
                        <span><?php echo html_escape($category->name); ?></span>
                    </li>
                </ol>
            </div> 

            <div class="PageBody-Inner webstory PhotoStories">
                <div id="content" class="col-sm-12">
                    <div class="row"> 
                        <div class="col-sm-12">
                            <h1 class="page-title"><?php echo html_escape($category->name); ?></h1>
                        </div>
                        <?php $count = 0; ?>
                        <?php foreach ($posts as $post) :
                                if ($count != 0 && $count % 4 == 0) : ?>
                                <div class="col-sm-12"></div>
                            <?php endif; ?>
                            <?php $this->load->view("post/_post_item_list_webstory", ["post" => $post]); ?>
                            <?php if ($count == 3) : ?>
                                <div class="ads adscont"><?php $this->load->view("partials/_ad_spaces", ["ad_space" => "category_top", "class" => "p-b-30"]); ?></div>
                            <?php endif; ?>
                            <?php $count++; ?>
                        <?php endforeach; ?>
                        <div class="ads adscont"><?php $this->load->view("partials/_ad_spaces", ["ad_space" => "category_bottom", "class" => ""]); ?></div>
                        <div class="col-sm-12 col-xs-12">
                            <div class="PagCont text-center">
                                <?php echo $this->pagination->create_links(); ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>