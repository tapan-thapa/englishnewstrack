<?php if (count($most_viewed_posts) > 0): ?>
	<div class="sidebar-widget widget-popular-posts MostViewed">
	<div class="widget-head">
        <h2 class="titlebg">Most Viewed</h2>
 			</div>
			 <div id="last_posts_content" class="section-content ">
				<ul class="MostViewedList">
					<?php foreach ($most_viewed_posts as $post):  ?>
						<li> 
						<div class="post-item-small">
							<div>
								<h3 class="title"><a title="<?php if(empty($post->headline)){ echo $post->title;}else{ echo $post->headline;}?>" href="<?php echo generate_post_url($post); ?>"><?php if(empty($post->headline)){ echo $post->title;}else{ echo $post->headline;}?></a></h3>
							</div>
					</div>
					</li>
					<?php  endforeach;?>
				</ul>
				</div>
</div>
<?php endif; ?>