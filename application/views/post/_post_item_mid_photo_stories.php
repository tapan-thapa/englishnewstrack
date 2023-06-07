<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
if(empty($type)){
	$type = "medium";	
}
?>

<!--Post item small-->
<div class="post-item-mid<?php echo check_post_img($post, 'class'); ?>">
    <?php if (check_post_img($post)): ?>
        <div class="imgBox">
        <div class="iconofwebstoires"><span class="webstories-icon"></span></div>
            <a title="<?php echo html_escape($post->title); ?>"  href="<?php echo generate_post_url($post); ?>"<?php post_url_new_tab($this, $post); ?> target="_blank">
                <?php $this->load->view("post/_post_image", ["post_item" => $post, "type" => $type]); ?>
            </a>
        </div>
    <?php endif; ?>
    <h3 class="title">
        <a  title="<?php echo html_escape($post->title); ?>" href="<?php echo generate_post_url($post); ?>"<?php post_url_new_tab($this, $post); ?> target="_blank">
            <?php echo html_escape($post->title); ?>
        </a>
    </h3>
    <!--<p class="post-meta">
        <?php //$this->load->view("post/_post_meta", ["post" => $post]); ?>
    </p>-->
</div>
