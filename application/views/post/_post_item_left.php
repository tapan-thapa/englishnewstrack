<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
 
     <div class="post-item-small<?php echo check_post_img($post, 'class'); ?>">
        <?php if (check_post_img($post)): ?>
                <div class="left">
                    <a  title="<?php echo html_escape($post->title); ?>" href="<?php echo generate_post_url($post); ?>"<?php post_url_new_tab($this, $post); ?>>
                        <?php $this->load->view("post/_post_image", ["post_item" => $post, "type" => "small"]); ?>
                    </a>
                </div> 
            <?php endif; ?>
                <div class="right">
                    <?php if(isset($post->topic)){?>
                    <a title="<?php echo html_escape($post->topic); ?>" href="<?php echo generate_topic_url(str_slug(trim($post->topic))); ?>">
                        <span class="category-label"><?php echo html_escape($post->topic); ?></span>
                    </a>
                    <?php }?>
                        <h3 class="title">
                            <a  title="<?php echo html_escape($post->title); ?>" href="<?php echo generate_post_url($post); ?>"<?php post_url_new_tab($this, $post); ?>>
                                <?php if(empty($post->headline)){ echo html_escape($post->title);}else{ echo html_escape($post->headline);}  ?>
                            </a>
                        </h3>
						 <div class="post-share">
					 <!--include Social Share -->
						<?php $this->load->view('post/_post_share_box'); ?>
						</div>		
                </div>
 
<!--<div class="post-item-small<?php echo check_post_img($post, 'class'); ?>">
<?php if (check_post_img($post)): ?>
        <div class="left">
            <a href="<?php echo generate_post_url($post); ?>"<?php post_url_new_tab($this, $post); ?>>
                <?php $this->load->view("post/_post_image", ["post_item" => $post, "type" => "small"]); ?>
            </a>
        </div>
    <?php endif; ?>
    <div class="right">
	<?php if(isset($post->topic)){?>
	<a href="<?php echo generate_topic_url(str_slug(trim($post->topic))); ?>">
		<span class="category-label"><?php echo html_escape($post->topic); ?></span>
	</a>
	<?php }?>
        <h3 class="title">
            <a href="<?php echo generate_post_url($post); ?>"<?php post_url_new_tab($this, $post); ?>>
                <?php if(empty($post->headline)){ echo html_escape($post->title);}else{ echo html_escape($post->headline);}  ?>
            </a>
        </h3>
    </div>
</div>-->
 </div>