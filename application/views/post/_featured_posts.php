<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="section-featured">
        <div class="row">
                <div class="col-md-6 col-xs-12">
                     <!--Include Featured Slider-->
                    <?php if (!empty($featured_posts)): ?>
                        <?php $this->load->view('partials/_featured_slider'); ?>
                    <?php else: ?>
                        <img src="<?= IMG_BASE64_600x460; ?>" alt="bg" class="img-responsive img-bg noselect img-no-slider" style="pointer-events: none"/>
                    <?php endif; ?>
                </div>

                <!-- <div class="col-sm-4 col-xs-12">
                         <section class="ShortVideo section-block-4">
                            <div class="section-head">
                                <h4 class="title" >
                                    <a title="शॉर्ट वीडियो" href="<?php //echo generate_category_url((object)['name_slug'=>'short-videos']); ?>">शॉर्ट वीडियो</a>
                                </h4>
                            </div>
                            <div class="section-content">
                                
                                        <div class="rowCont">
                                            <div class="short-video-slider">
                                            <?php 
                                            /* $category_posts = get_posts_by_category_id(486, $this->categories, $category->home_limit);
                                            $i = 0;
                                            if (!empty($category_posts)):
                                                foreach ($category_posts as $post):
                                                     if ($i < 3): ?>
                                                                    <?php $this->load->view("post/_post_item_mid_short_video", ["post" => $post,"type" => 'video']); ?>
                                                                
                                                    <?php   endif;  
                                                    $i++;
                                                endforeach;
                                            endif; */ ?>
                                            </div>
                                 </div>
                            </div>

                        </section>
                 </div> -->



                 <div class="col-md-6 col-xs-12">
                    <div class="featured-boxes-top">
                        <?php $count = 1;
                        foreach ($featured_posts as $item):
                            if ($count > 1 && $count <= 5): ?>
                                <div class="featured-box box-<?php echo $count; ?>">
                                    <div class="row box-inner">
                                            <div class="col-md-5 col-sm-6 col-xs-5 imgBox">
                                                <a href="<?php echo generate_post_url($item); ?>"<?php post_url_new_tab($this, $item); ?>>
                                                <?php $this->load->view("post/_post_image", ["post_item" => $item, "type" => "featured"]); ?></a> 
                                            </div>

                                          
                                            <div class="col-md-7 col-sm-6 col-xs-7 caption">
                                                <span class="category-label"><a href="<?php echo generate_post_url($item); ?>"> <?php echo html_escape($item->topic); ?></a></span>
                                                <h3 class="title">  
                                                <a href="<?php echo generate_post_url($item); ?>"<?php post_url_new_tab($this, $item); ?>>  <?php if(empty($item->headline)){ echo html_escape($item->title);}else{ echo html_escape($item->headline);} ?></a>
                                                </h3>
											
                                                <!-- <div class="post-share">
                                                    <ul class="share-box">
                                                    <li class="share-li-lg">
                                                        <a href="javascript:void(0)"
                                                        onclick="window.open('https://twitter.com/share?url=<?php echo generate_post_url($item); ?>&amp;text=<?php echo urlencode($item->title); ?>', 'Share This Post', 'width=640,height=450');return false"
                                                        class="social-btn-lg twitter">
                                                            <i class="icon-twitter"><?php //echo trans("twitter"); ?></i>
                                                            <span></span>
                                                        </a>
                                                    </li>
                                                    <li class="li-whatsapp">
                                                    <a href="https://api.whatsapp.com/send?text=<?php echo urlencode($item->title); ?> - <?php echo generate_post_url($item); ?>"
                                                    class="social-btn-sm whatsapp"
                                                    target="_blank">
                                                        <i class="icon-whatsapp"></i>
                                                    </a>
                                                    </li>
                                                </ul> -->
											                                            
                                             
                                                <!-- <p class="post-meta">
                                                    <?php //$this->load->view("post/_post_meta", ["post" => $item]); ?>
                                                </p>       -->
                                        </div> 
                                        
                                    </div>
                                </div>
                            <?php endif;
                            $count++;
                        endforeach; ?>
                     
                    </div>
                </div>  

                    </div>

                    <div class="container"><?php $this->load->view("partials/_ad_spaces", ["ad_space" => "index_top", "class" => ""]); ?></div>   


                    <div class="row">
                    <div class="col-sm-12 col-xs-12">
                    <div class="featured-boxes-bottom">
                    <?php $count = 1;
                        foreach ($featured_posts as $item):
                            if ($count >= 6): ?>
                                <div class="featured-box box-<?php echo $count; ?>">
                                    <div class="box-inner">
                                        <div class="imgBox">
                                         <a  title="<?php echo html_escape($post->title); ?>" href="<?php echo generate_post_url($item); ?>"<?php post_url_new_tab($this, $item); ?>>
                                            <?php $this->load->view("post/_post_image", ["post_item" => $item, "type" => "featured_mid"]); ?>
                                         </a></div>
                                        
                                        <div class="caption">
                                        <span class="category-label"><a  title="<?php echo html_escape($category->name); ?>" href="<?php echo generate_post_url($item); ?>"><?php echo html_escape($item->topic); ?> </a></span>

                                            <h3 class="title">
                                       
                                                <a  title="<?php echo html_escape($post->title); ?>" href="<?php echo generate_post_url($item); ?>"<?php post_url_new_tab($this, $item); ?>>
                                                <?php echo live_Link_show($item); ?><?php  if(empty($item->headline)){ echo html_escape($item->title);}else{ echo html_escape($item->headline);}  ?></a>
                                            </h3>
											
											<!-- <div class="post-share">
											<ul class="share-box">
											
											<li class="share-li-lg">
												<a href="javascript:void(0)"
												   onclick="window.open('https://twitter.com/share?url=<?php echo generate_post_url($item); ?>&amp;text=<?php echo urlencode($item->title); ?>', 'Share This Post', 'width=640,height=450');return false"
												   class="social-btn-lg twitter">
													<i class="icon-twitter"><?php //echo trans("twitter"); ?></i>
													<span></span>
												</a>
											</li>
											 <li class="li-whatsapp">
											<a href="https://api.whatsapp.com/send?text=<?php echo urlencode($item->title); ?> - <?php echo generate_post_url($item); ?>"
											   class="social-btn-sm whatsapp"
											   target="_blank">
												<i class="icon-whatsapp"></i>
											</a>
										</li>
											
											</ul>
											                                            
                                        </div>-->
                                            <!--<p class="post-meta">
                                                <?php //$this->load->view("post/_post_meta", ["post" => $item]); ?>
                                            </p>-->


                                           <!-- <div class="post-share">
                         <?php //$this->load->view('post/_post_share_box'); ?>
                    </div>-->

                                        </div> 
                                    </div>
                                </div>
                            <?php endif;
                            $count++;
                        endforeach; ?>
                        </div>
                    </div>
                    </div>

 </div>