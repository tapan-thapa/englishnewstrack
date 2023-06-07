<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!--Post row item-->
<div class="post-item<?php echo check_post_img($post, 'class'); ?> PostCommon">
  
     <?php if (check_post_img($post)): ?>
        <div class="ImgCont"> 
            <a title="<?php echo html_escape($post->title); ?>" href="<?php echo generate_post_url($post); ?>"<?php post_url_new_tab($this, $post); ?>>
                <?php $this->load->view("post/_post_image", ["post_item" => $post, "type" => "medium"]); ?>
            </a>
        </div>
    <?php endif; ?>


    <?php if (isset($show_label)): ?>
        <a title="<?php echo html_escape($post->category_name); ?>" href="<?php echo generate_category_url_by_id($post->category_id); ?>">
            <span class="category-label"><?php echo html_escape($post->category_name); ?></span>
        </a>
    <?php endif; ?>

     <!-- <a href="<?php echo generate_post_url($post); ?>"><span class="category-label BCatItem"><?php echo html_escape($post->topic); ?></span></a> -->

    <h3 class="title">
        <a title="<?php echo html_escape($post->title); ?>" href="<?php echo generate_post_url($post); ?>"<?php post_url_new_tab($this, $post); ?>>
            <?php if(empty($post->headline)){ echo html_escape($post->title);}else{ echo html_escape($post->headline);}  ?>
        </a>
    </h3>
	 <!--<div class="post-share">
	 include Social Share 
        <?php //$this->load->view('post/_post_share_box'); ?>
		</div>	-->			
    <!--<p class="post-meta">
        <?php //$this->load->view("post/_post_meta", ["post" => $post]); ?>
    </p>
    <div class="description"> 
        <?php //echo character_limiter($post->summary, 120, '...'); ?>
    </div> -->
 </div>
