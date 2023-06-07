<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="col-sm-12 col-xs-12">
    <div class="row">
        <section class="section-block-4 TrendingWidget"> 
            <div class="section-head">
                <h4 class="title">
                    <a  title="Trending"  href="/trending">
                        Trending
                    </a>
                </h4>
                </div>
            <div class="section-content">
                    <div role="tabpanel" class="tab-pane fade in active" id="all-<?php echo html_escape($category->id); ?>">
                        <div class="row">
                            <?php //$category_posts = get_posts_by_category_id($category->id, $this->categories);
                            $i = 0;
                            if (!empty($trending_posts)):
                                foreach ($trending_posts as $post):
                                    if ($i < 4):?>
                                        <div class="col-sm-12 col-md-3">
                                            <!--Post item small-->
											<div class="post-item-mid<?php echo check_post_img($post, 'class'); ?>">
											<?php if (check_post_img($post)): ?>
											<div class="post-item-image">
												<a  title="<?php echo html_escape($post->title); ?>"  href="<?php echo generate_post_url($post); ?>"<?php post_url_new_tab($this, $post); ?>>
													<?php $this->load->view("post/_post_image", ["post_item" => $post, "type" => "medium"]); ?>
												</a>
											</div>
											<?php endif; ?>
											<h3 class="title">
											<a  title="<?php echo html_escape($post->title); ?>" href="<?php echo generate_post_url($post); ?>"<?php post_url_new_tab($this, $post); ?>>
												<?php echo html_escape(character_limiter($post->title, 255, '...')); ?>
											</a>
											</h3>
											
											</div>
                                        </div>
                                    <?php endif;
                                    $i++;
                                endforeach;
                            endif; ?>
                        </div>
                    </div>
 
            </div>
        </section>
    </div>
</div>