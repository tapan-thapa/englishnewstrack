<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
 
<?php if(isset($post->created_at)){ ?>
<!-- <div class="gal_date_section">
    <div class="left">
        <span class="date_gal"><?php
       
            //echo helper_date_format($post->created_at); 
            //echo "&nbsp;&nbsp; &nbsp;";
           // echo date("h:i A",strtotime($post->created_at)) ;
        
        ?>
        </span>
    </div>
    <div class="right">
                <div class="galerry-share">
                    <?php //$this->load->view('post/_post_share_box'); ?>
                </div>    
    </div>
</div> -->
<?php } ?>

<div class="post-image">
    
    <div class="post-image-inner">
        <?php 
            if (!empty($post->image_default) || !empty($post->image_url)):?>
                <img src="<?php echo get_post_image($post, "big"); ?>" class="img-responsive center-image" alt="<?php echo html_escape($post->title); ?>" style="width:100%"/>
                <?php if (!empty($post->image_description)): ?>
                    <figcaption class="img-description"><?php echo html_escape($post->image_description); ?></figcaption>
                <?php endif; ?>
            <?php endif;?>
            
    </div>
    
</div>