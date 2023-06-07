<div class="row">
	<div class="col-sm-12">			
		<div class="sidebar-widget widget-popular-posts Livewidget">
			<div class="widget-head">
			<h4 class="titlebg text-center">Live Updates</h4>
			</div>
 			 <div class="tab-content">
                 <ul class="popular-posts">
                    <?php
					$c = 0;					
                    if (!empty($breaking_news)):
                        foreach ($breaking_news as $post):
						
						if($page_type == 'home'){
							if($c>0){
						?>
                            <li>
							<a title="<?php echo html_escape($post->title); ?>" href="https://www.newsdrum.in/live-updates?id=<?php echo $post->id;?>"><?php echo html_escape($post->title); ?> </a>
									<div class="timestamp"><?php echo date("H:i A",strtotime($post->created_at));?></div>
 								 
                            </li>
							<?php } $c++; }else{
								
								//if($c < 5){
								?>
								 <li>
							<a title="<?php echo html_escape($post->title); ?>" href="https://www.newsdrum.in/live-updates?id=<?php echo $post->id;?>"><?php echo html_escape($post->title); ?> </a>
									<div class="timestamp"><?php echo date("H:i A",strtotime($post->created_at));?></div>
                            </li>
								<?php
								
							//}
							$c++; } endforeach;
                    endif; ?>
                </ul>
 			</div>
		</div>
	</div>
</div>   