<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!--Post item small-->
<div class="video-item-small<?php echo check_post_img($post, 'class'); ?>">
    <div class="VideoPostImg"><div class="videoIcon">
        <a title="<?php post_url_new_tab($this, $post); ?>" href="<?php echo generate_post_url($post); ?>">
        <?php post_url_new_tab($this, $post); ?>
        <!-- <i class="icon-play-circle"></i> -->
    </a></div>
	<?php if (check_post_img($post)): ?>
    <a  title="<?php echo html_escape($post->title); ?>"  target="_blank" href="<?php echo generate_post_url($post); ?>"<?php post_url_new_tab($this, $post); ?>>
                <?php $this->load->view("post/_post_image", ["post_item" => $post, "type" => "small"]); ?>
            </a>
			 <?php endif; ?>
    </div>

    <div class="left">

		<?php if(isset($post->category_id)){?>
	<a title="<?php echo html_escape($post->category_name); ?>" href="<?php echo generate_category_url_by_id($post->category_id); ?>">
		<span class="category-label"><?php echo html_escape($post->category_name); ?></span>
	</a>
	<?php }?>
             <a  title="<?php echo html_escape($post->title); ?>"  href="<?php echo generate_post_url($post); ?>"<?php post_url_new_tab($this, $post); ?>>
                <b><?php echo html_escape(character_limiter($post->title, 85, '...')); ?></b>
            </a>
          
        </div>

</div>