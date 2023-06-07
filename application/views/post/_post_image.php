<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php if (!empty($post_item) && !empty($type)): ?>
    <?php if ($post_item->post_type == 'video'): ?>
        <span class="media-icon"><i class="icon-play-circle"></i><em></em></span>
    <?php endif;
    if ($post_item->post_type == 'audio'): ?>
        <span class="media-icon"><i class="icon-music-circle"></i><em></em></span>
    <?php endif; ?>

    <?php if ($type == 'featured_slider'): ?>
        <img  alt="<?php echo html_escape($post->title); ?>"  src="<?= IMG_BASE64_600x460; ?>" alt="bg" class="img-responsive img-bg" width="600" height="460"/>
        <div class="img-container">
            <img  alt="<?php echo html_escape($post->title); ?>"  src="<?= IMG_BASE64_600x460; ?>" data-lazy="<?php echo get_post_image($post_item, "slider"); ?>" alt="<?php echo html_escape($post_item->title); ?>" class="img-cover" width="600" height="460"/>
        </div>
    <?php elseif ($type == 'random_slider'): ?>
        <img  alt="<?php echo html_escape($post->title); ?>" src="<?= IMG_BASE64_360x215; ?>" alt="bg" class="img-responsive img-bg" width="360" height="215"/>
        <div class="img-container">
            <img  alt="<?php echo html_escape($post->title); ?>" src="<?= IMG_BASE64_360x215; ?>" data-lazy="<?php echo get_post_image($post_item, "mid"); ?>" alt="<?php echo html_escape($post_item->title); ?>" class="img-cover" width="360" height="215"/>
        </div>
    <?php else: ?>
        <?php if ($type == 'featured') {
            $img_bg = IMG_BASE64_283x217;
            $image_size = "big";
            //$img_sz = ' width="283" height="217"';
            $img_sz = ' width="800" height="450"';
        }else if ($type == 'webstory') {
            $img_bg = IMG_BASE64_283x217;
            $image_size = "slider";
            $img_sz = ' width="350"';
        }else if ($type == 'featured_mid') {
            $img_bg = IMG_BASE64_283x217;
            $image_size = "mid";
            //$img_sz = ' width="283" height="217"';
            $img_sz = ' width="480" height="270"';
        } elseif ($type == 'big') {
            $img_bg = base_url() . IMG_PATH_BG_LG;
            $image_size = "big";
            //$img_sz = ' width="750" height="422"';
            $img_sz = ' width="800" height="450"';
        } elseif ($type == 'small') {
            $img_bg = IMG_BASE64_1x1;
            $image_size = "small";
            $img_sz = ' width="1" height="1"';
        } else {
            $img_bg = base_url() . IMG_PATH_BG_MD;
            $image_size = "mid";
            $img_sz = ' width="1" height="1"';
        } 
		
		?>

        <?php if (!empty($post_item->image_url) || $post_item->image_mime == 'gif' || $type == 'featured' || $type == 'webstory'): ?>
            <div class="img-container">
			<?php if($type == 'featured'){?>
                <img  alt="<?php echo html_escape($post_item->title); ?>" src="<?php echo get_post_image($post_item, $image_size); ?>" alt="<?php echo html_escape($post_item->title); ?>" class=" img-cover"<?= !empty($img_sz) ? $img_sz : ''; ?>/>
			<?php }elseif($type == 'featured_mid'){?>
                <img  alt="<?php echo html_escape($post_item->title); ?>" src="<?php echo get_post_image($post_item, $image_size); ?>" alt="<?php echo html_escape($post_item->title); ?>" class=" img-cover"<?= !empty($img_sz) ? $img_sz : ''; ?>/>
			<?php }elseif($type == 'webstory'){?>
                <img  alt="<?php echo html_escape($post_item->title); ?>" src="<?php echo get_post_image($post_item, $image_size); ?>" alt="<?php echo html_escape($post_item->title); ?>" class="lazyload"<?= !empty($img_sz) ? $img_sz : ''; ?>/>
			<?php }else{?>
			<img  alt="<?php echo html_escape($post_item->title); ?>" src="<?php echo $img_bg; ?>" data-src="<?php echo get_post_image($post_item, $image_size); ?>" alt="<?php echo html_escape($post_item->title); ?>" class="lazyload img-cover"<?= !empty($img_sz) ? $img_sz : ''; ?>/>
			<?php }?>
            </div>
        <?php else: ?>
            <img  title="<?php echo html_escape($post_item->title); ?>" src="<?php echo $img_bg; ?>" data-src="<?php echo get_post_image($post_item, $image_size); ?>" alt="<?php echo html_escape($post_item->title); ?>" class="lazyload img-responsive img-post"<?= !empty($img_sz) ? $img_sz : ''; ?>/>
        <?php endif; ?>
    <?php endif; ?>
<?php endif; ?>

