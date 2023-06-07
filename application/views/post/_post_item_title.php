<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!--Post item small-->
<div class="post-item-small<?php echo check_post_img($post, 'class'); ?>">
        <div class="left">
        <h3 class="title">
            <a  title="<?php echo html_escape($post->title); ?>" href="<?php echo generate_post_url($post); ?>"<?php post_url_new_tab($this, $post); ?>>
                <?php echo html_escape($post->title); ?>
            </a>
        </h3>
         </div>
</div>