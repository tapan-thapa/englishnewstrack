<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!--Post row item-->
<div class="col-sm-12 col-xs-12 m-b-10">
    <div class="row">
        <div class="post-item-horizontal<?php echo check_post_img($post, 'class'); ?>">
         
            <?php if (check_post_img($post)): ?>
                <div class="col-sm-3 col-xs-12 item-image m-b-10">
                    <div class="post-item-image">
                        <a title="<?php echo html_escape($post->topic); ?>" href="<?php echo generate_post_url($post); ?>"<?php post_url_new_tab($this, $post); ?>>
                            <?php $this->load->view("post/_post_image", ["post_item" => $post, "type" => "medium"]); ?>
                        </a>
                    </div>
                </div>
            <?php endif; ?>
            <div class="col-sm-9 col-xs-12 item-content">
                <ul class="topic-list">
                    <li><a title="<?php echo html_escape($post->topic); ?>" href="<?php echo generate_post_url($post); ?>"><span class="category-label BCatItem"><?php echo html_escape($post->topic); ?></span></a></li>
                    <!--<li> <?php //if (isset($show_label)): ?>
                <a href="<?php //echo generate_category_url_by_id($post->pri_category_id); ?>">
                    <span class="category-label category-label-horizontal"><?php //echo html_escape($post->category_name); ?></span>
                </a>
            <?php //endif; ?></li>-->
                </ul>
            

           
                <h3 class="title">
                    <a title="<?php if(empty($post->headline)){ echo html_escape($post->title);}else{ echo html_escape($post->headline);} ?>" href="<?php echo generate_post_url($post); ?>"<?php post_url_new_tab($this, $post); ?>>
                        <?php if(empty($post->headline)){ echo html_escape($post->title);}else{ echo html_escape($post->headline);} ?>
                    </a>
                </h3>
                <div class="timestamp"><?php echo date("M d, Y - H:i A",strtotime($post->created_at));?></div>
                <div class="post-share">
                        <!--include Social Share -->
                        <?php $this->load->view('post/_post_share_box'); ?>
                    </div>

                <!--<p class="description">
                    <?php //echo character_limiter(strip_tags($post->summary), 130, '...'); ?>
                </p>-->
            </div>
        </div>
    </div>
</div>