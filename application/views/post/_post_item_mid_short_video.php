<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
if(empty($type)){
	$type = "medium";	
}
?>

<!--Post item small-->
<div class="post-item-mid<?php echo check_post_img($post, 'class'); ?>">
    <?php if (check_post_img($post)): ?>
        <h3 class="Toptitle">
        <a  title="<?php echo html_escape($post->title); ?>" href="<?php echo generate_post_url($post); ?>"<?php post_url_new_tab($this, $post); ?>>
            <?php echo html_escape($post->title); ?>
        </a>
    </h3>
        <div class="imgBox">
             <a  title="<?php echo html_escape($post->title); ?>" href="<?php echo generate_post_url($post); ?>"<?php post_url_new_tab($this, $post); ?>>
                <?php $this->load->view("post/_post_image", ["post_item" => $post, "type" => $type]); ?>
            </a>
        </div>
    <?php endif; ?>
    <h3 class="title">
        <a  title="<?php echo html_escape($post->title); ?>" href="<?php echo generate_post_url($post); ?>"<?php post_url_new_tab($this, $post); ?>>
            <?php echo html_escape($post->title); ?>
        </a>
    </h3>
  
</div>
