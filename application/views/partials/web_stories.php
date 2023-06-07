<?php $category_posts = get_posts_by_category_id(44, $this->categories);
if (!empty($category_posts)) : 
 ?>
<div class="Videos-popular-posts mb-4 slider-container" >
    <div class="container">
 			<h4 class="video-title">Webstories</h4>
 			<div class="widget-body">
                        <div class="row carousel4img">
                    <?php 
                    if (!empty($category_posts)):
                        foreach ($category_posts as $post): ?>
                               <div class="col-sm-3 featured-slider-item">	
                                <?php $this->load->view("post/_web_story_post", ["post" => $post]); ?>
                                </div>
                        <?php endforeach;
                    endif; ?>
                </div>
            </div>
            <div id="featured-slider-nav" class="featured-slider-nav">
        <button class="prev"><i class="icon-arrow-slider-left"></i></button>
        <button class="next"><i class="icon-arrow-slider-right"></i></button>
    </div>
        </div>
                </div>
<?php  endif; ?>