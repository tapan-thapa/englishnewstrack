<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="col-sm-12 col-xs-12">
    <div class="row">
        <section class="OtherNews section-block-4">
            <div class="section-head">
                <h4 class="titlebg">  
                    <a title="Special" href="/special-stories">
                       Special
                    </a>
                </h4>
                </div>
            <div class="OtherNews-content">
                        <div class="rowCont">
                            <?php 
                            $i = 0;
							 $img_bg = IMG_BASE64_1x1;
							$image_size = "small";
							$img_sz = ' width="1" height="1"';
                            if (!empty($special_posts)):
                                foreach ($special_posts as $post):
                                    if ($i < 3):?>
                                             <!--Post item small-->
									<div class="post-item-mid<?php echo check_post_img($post, 'class'); ?>">
									<?php if (check_post_img($post)): ?>
										<div class="imgBox">
											<a title="<?php echo html_escape($post->title); ?>" href="<?php echo generate_post_url($post); ?>"<?php post_url_new_tab($this, $post); ?>>
<div class="img-container">											
											<amp-img src="<?php echo get_post_image($post, $image_size);  ?>" alt="<?php echo html_escape($post->title); ?>" width="140" height="98" /></amp-img>
											
										</div>	
											</a>
										</div>
									<?php endif; ?>
									<h3 class="title">
										<a title="<?php echo html_escape($post->title); ?>" href="<?php echo generate_post_url($post); ?>"<?php post_url_new_tab($this, $post); ?>>
											<?php echo html_escape($post->title); ?>
										</a>
									</h3>
									</div>
                                     <?php endif;
                                    $i++;
                                endforeach;
                            endif; ?>
                </div>
                <div class="viewall text-center"><a title="View All" href="/special-stories">View All <i class="icon-arrow-slider-right"></i></a></div>
            </div>
        </section>
    </div>
</div>