 	<div class="sidebar-widget widget-popular-posts MostViewed">
	<div class="widget-head">
        <h2 class="titlebg">Top Stories</h2>
 			</div>
			 <div id="last_posts_content" class="section-content ">
			 <ul class="popular-posts">
                    <?php //print_r($featured_posts);
					//$popular_posts = get_latest_posts($this->selected_lang->id, 8);
					$count=1;
                    if (!empty($featured_posts)):
                        foreach ($featured_posts as $post): 
						if($count<=8){
						?>
                            <li>
                                <?php $this->load->view("post/_post_item_title", ["post" => $post]); ?>
                            </li>
                        <?php  }$count++; endforeach;
                    endif; ?>
                </ul>
				</div>
</div> 