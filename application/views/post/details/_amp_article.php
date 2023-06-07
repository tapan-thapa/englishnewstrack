<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="post-image">
    <div class="post-image-inner">
        <?php 
		     if (!empty($post->image_default) || !empty($post->image_url)):?>
 				 <amp-img src="<?php echo get_post_image($post, "default"); ?>" class="articleImg" height= "220" width = "340"></amp-img>
                <?php if (!empty($post->image_description)): ?>
                    <figcaption class="img-description"><?php echo html_escape($post->image_description); ?></figcaption>
                <?php endif; ?>
            <?php endif;
    ?>
    </div>
</div>