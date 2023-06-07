<div class="row">
	<div class="col-sm-12">			
		<div class="sidebar-widget widget-popular-posts MustWatch">
			<div class="widget-head">
			<h4 class="title text-center">Must Watch</h4>
			</div>
			<div class="widget-body">
			 <div class="tab-content">
				<div id="tab_must_watch_posts" class="tab-pane fade in active">
                <ul class="popular-posts">
                    <?php $popular_v_posts = get_popular_video_posts($this->selected_lang->id);
                    if (!empty($popular_v_posts)):
                        foreach ($popular_v_posts as $post): ?>
                            <li>
                                <?php $this->load->view("post/_post_item_must_watch", ["post" => $post]); ?>
                            </li>
                        <?php endforeach;
                    endif; ?>
                </ul>
            </div>
        </div>
			</div>
		</div>
	</div>
</div>   