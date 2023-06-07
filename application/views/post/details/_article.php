<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<style type="text/css">
    .slick-arrow.slick-disabled {
      display: none !important;
    }
</style>
<div class="post-image">
    <div class="post-image-inner">
        <?php if (count($post_images) > 0) : ?>
            <div class="show-on-page-load">
                <div id="post-detail-slider" class="post-detail-slider" data-count="1">
                    <div class="post-detail-slider-item">
                        <img src="<?= get_post_image($post, 'default'); ?>" class="img-responsive center-image" alt="<?php echo html_escape($post->title); ?>"/>
                        <figcaption class="img-description"><?php echo html_escape($post->image_description); ?></figcaption>
                    </div>
                    <!--List  random slider posts-->
                    <?php $mm = 2 ; foreach ($post_images as $image):
                        $img_base_url = base_url();
                        if ($image->storage == "aws_s3") {
                            $img_base_url = $this->aws_base_url;
                        } ?>
                        <!-- slider item -->
                        <div class="post-detail-slider-item gal_img_count" data-count="<?=$mm?>">
                            <img src="<?= $img_base_url . html_escape($image->image_default); ?>" class="img-responsive center-image" alt="<?php echo html_escape($post->title); ?>"/>
                        </div>
                    <?php $mm++;endforeach; ?>
                </div>
                <div id="post-detail-slider-nav" class="slider-nav post-detail-slider-nav">
                    <button class="gal prev"><i class="icon-arrow-left"></i></button>
                    <button class="gal next"><i class="icon-arrow-right"></i></button>
                </div>
            </div>
        <?php else:
            if (!empty($post->image_default) || !empty($post->image_url)):?>
                <img src="<?php echo get_post_image($post, "default"); ?>" class="img-responsive center-image" alt="<?php echo html_escape($post->title); ?>"/>
                <?php if (!empty($post->image_description)): ?>
                    <figcaption class="img-description"><?php echo html_escape($post->image_description); ?></figcaption>
                <?php endif; ?>
            <?php endif;
        endif; ?>
    </div>
</div>