<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php //$subcategories = get_subcategories($category->id, $this->categories); ?>
<div class="col-sm-12 col-xs-12">
    <div class="rowCont">
        <section class="sectionSlider"> 
            <div class="section-head"> 
                <h4 class="title">
                    <a title="<?php echo html_escape($category->name); ?>" href="<?php echo generate_category_url($category); ?>">
                        <?php echo html_escape($category->name); ?>
                    </a>
                </h4>
                <div class="v-all"><a title="View All" href="<?php echo generate_category_url($category); ?>" class="v-all-click"><span>VIEW ALL</span><i class="fa fa-angle-right"></i></a></div>
               </div>
            <div class="section-content">
                <div class="tab-content pull-left">
                    <div role="tabpanel" class="tab-pane fade in active" id="all-<?php echo html_escape($category->id); ?>">
                        <div class="row">
                            <?php $category_posts = get_posts_by_category_id($category->id, $this->categories, $category->home_limit);
                            $i = 0;
                            if (!empty($category_posts)):
                                foreach ($category_posts as $post):
                                    /* if ($i < 4): */
                                        if ($i < 1): ?>
                                            <div class="col-sm-12 col-xs-12">
                                                <div class="post-item-video-big<?php echo check_post_img($post, 'class'); ?>">
                                                    <?php if (check_post_img($post)): ?>
                                                        <div class="post-item-image">
                                                            <a title="<?php echo html_escape($post->title); ?>" href="<?php echo generate_post_url($post); ?>"<?php post_url_new_tab($this, $post); ?>>
                                                                <?php $this->load->view("post/_post_image", ["post_item" => $post, "type" => "big"]); ?>
                                                                <div class="overlay"></div>
                                                            </a>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="caption caption-video-image">
                                                        <a title="<?php echo html_escape($category->name); ?>" href="<?php echo generate_category_url($category); ?>">
                                                            <span class="category-label" style="background-color: <?php echo html_escape($category->color); ?>"><?php echo html_escape($category->name); ?></span>
                                                        </a>
                                                        <h3 class="title">
                                                            <a  title="<?php echo html_escape($post->title); ?>"  href="<?php echo generate_post_url($post); ?>"<?php post_url_new_tab($this, $post); ?>>
                                                                <?php echo html_escape(character_limiter($post->title, 55, '...')); ?>
                                                            </a>
                                                        </h3>
                                                        <p class="small-post-meta">
                                                            <?php $this->load->view("post/_post_meta", ["post" => $post]); ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <div class="col-sm-4 col-xs-12">
                                                <?php $this->load->view("post/_post_item_mid", ["post" => $post, "type" => "featured_mid"]); ?>
                                            </div>
                                        <?php endif;
                                    /* endif; */
                                    $i++;
                                endforeach;
                            endif; ?>
                        </div>
                    </div>

                    <?php if (!empty($subcategories)):
                        foreach ($subcategories as $subcategory): ?>
                            <div role="tabpanel" class="tab-pane fade in " id="<?php echo html_escape($subcategory->name_slug); ?>-<?php echo html_escape($subcategory->id); ?>">
                                <div class="row">
                                    <?php $category_posts = get_posts_by_category_id($subcategory->id, $this->categories, $category->home_limit);
                                    $i = 0;
                                    if (!empty($category_posts)):
                                        foreach ($category_posts as $post):
                                            if ($i < 4):
                                                if ($i < 1): ?>
                                                    <div class="col-sm-12 col-xs-12">
                                                        <div class="post-item-video-big<?php echo check_post_img($post, 'class'); ?>">
                                                            <?php if (check_post_img($post)): ?>
                                                                <div class="post-item-image">
                                                                    <a title="<?php echo html_escape($post->title); ?>" href="<?php echo generate_post_url($post); ?>"<?php post_url_new_tab($this, $post); ?>>
                                                                        <?php $this->load->view("post/_post_image", ["post_item" => $post, "type" => "big"]); ?>
                                                                        <div class="overlay"></div>
                                                                    </a>
                                                                </div>
                                                            <?php endif; ?>
                                                            <div class="caption caption-video-image">
                                                                <a title="<?php echo html_escape($subcategory->name); ?>" href="<?php echo generate_category_url($subcategory); ?>">
                                                                    <span class="category-label" style="background-color: <?php echo html_escape($category->color); ?>"><?php echo html_escape($subcategory->name); ?></span>
                                                                </a>
                                                                <h3 class="title">
                                                                    <a  title="<?php echo html_escape($post->title); ?>" href="<?php echo generate_post_url($post); ?>"<?php post_url_new_tab($this, $post); ?>>
                                                                        <?php echo html_escape(character_limiter($post->title, 55, '...')); ?>
                                                                    </a>
                                                                </h3>
                                                                <p class="small-post-meta">
                                                                    <?php $this->load->view("post/_post_meta", ["post" => $post]); ?>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="col-sm-4 col-xs-12">
                                                        <?php $this->load->view("post/_post_item_mid", ["post" => $post]); ?>
                                                    </div>
                                                <?php endif;
                                            endif;
                                            $i++;
                                        endforeach;
                                    endif; ?>
                                </div>
                            </div>
                        <?php endforeach;
                    endif; ?>
                </div>
            </div>
        </section>
    </div>
</div>