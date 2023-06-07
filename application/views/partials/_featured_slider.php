<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!--Featured Slider-->
<div class="slider-container" <?= $this->rtl == true ? 'dir="rtl"' : ''; ?>>
         <?php $count = 0;
        foreach ($featured_posts as $post):
            if ($count < 1):?>
                      
                       <div class="row">

                     <div class="col-md-12 col-sm-12 col-xs-12"><a  title="<?php echo html_escape($post->title); ?>"  href="<?php echo generate_post_url($post); ?>" class="img-link"<?php post_url_new_tab($this, $post); ?>>
                        <?php $this->load->view("post/_post_image", ["post_item" => $post, "type" => "featured"]); ?>
                    </a></div>
                    <div class="col-md-12 col-sm-12 col-xs-12 contentBox">
                    
                     <div class="caption">
                        <h2 class="title">
                        <a title="<?php  if(empty($post->headline)){ echo html_escape($post->title);}else{ echo html_escape($post->headline);}  ?>" href="<?php echo generate_post_url($post); ?>" class="img-link"<?php post_url_new_tab($this, $post); ?>><?php echo live_Link_show($post); ?><?php  if(empty($post->headline)){ echo html_escape($post->title);}else{ echo html_escape($post->headline);}  ?></a>
                        </h2>
						<!-- <div class="post-share">
                        <?php //$this->load->view('post/_post_share_box', ["post" => $post]); ?>
                        </div> -->
                        <div class="description">
                    <?php echo character_limiter($post->summary, 430, '...'); ?>
                         </div> 

                         <div class="HomePage share_toolbox">
                         <div class="sharethis-inline-share-buttons"></div>
                         <!-- <div class="addthis_inline_share_toolbox_vvw7_9ye0"></div> -->
                         </div>
                         
                        <!--<p class="post-meta">
                            <?php //$this->load->view("post/_post_meta", ["post" => $post]); ?>
                        </p>-->
                    </div></div>
             <?php endif;
            $count++;
        endforeach; ?>
    </div>
    <!--<div id="featured-slider-nav" class="featured-slider-nav">
        <button class="prev"><i class="icon-arrow-slider-left"></i></button>
        <button class="next"><i class="icon-arrow-slider-right"></i></button>
    </div>-->
</div>
