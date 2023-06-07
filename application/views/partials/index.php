<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

  <div class="container">
        <div class="row">
            <?php $this->load->view('post/_news_ticker', $breaking_news); ?>
            </div>
        </div>
<h1 class="title-index"><?php echo html_escape($home_title); ?></h1>
<div class="featuredTopMain m-b-20 home">
    <div class="container">
        <div class="row">
                 <div class="col-md-8 col-xs-12 TopP1bg">
                    <?php
                    if ($this->general_settings->show_featured_section == 1): ?>

                        <?php $this->load->view('post/_featured_posts', $featured_posts);
						$ex_ids = array();
							foreach($featured_posts as $fp){
								$ex_ids[] = $fp->id;
							}
						?> 
                    <?php endif; ?>

                      <?php 
                        //foreach ($this->categories as $category):
                        //if($category->name_slug=='trending'){
                            //$this->load->view('partials/_category_block_trending', ['category' => $category]);
                        //} 
                       // endforeach; 
                        ?>
			    </div>
 
                <div id="sidebar" class="col-sm-4 col-xs-12">		      
                    <?php $this->load->view('partials/home_breaking_sidebar', $breaking_news); ?>
                    
                        <?php
                        foreach ($this->categories as $category):
                        if($category->name_slug=='special-stories'){
                            $this->load->view('partials/_category_block_type_4', ['category' => $category]); 
                        }
                        endforeach; 
                        ?>
                </div>
	</div>
</div>

</div>

            <div class="TrendingComponent">
                    <div class="container">
                        <?php 
                        foreach ($this->categories as $category):
                        if($category->name_slug=='trending'){
                            $this->load->view('partials/_category_block_trending', ['category' => $category]);
                            } 
                        endforeach; 
                        ?>
                     </div>
            </div>

            
            <div class="container"><?php $this->load->view("partials/_ad_spaces", ["ad_space" => "header", "class" => ""]); ?></div>


            <div class="container news">
            <?php 	//news category data
				$cats = get_category_id(1);
				$this->load->view('partials/_category_block_type_3', ['category' => $cats,'ex_ids'=>$ex_ids]);?>
            </div>

            
            <div class="container analysis">
            <?php	//analysis category data
				$cats = get_category_id(2);
				$this->load->view('partials/_category_block_type_3', ['category' => $cats]);?>
            </div>

            <div class="TrendingComponent">
                <div class="container">
                        <?php	//opinion category data
                                $cats = get_category_id(3);
                                $this->load->view('partials/_category_block_trending', ['category' => $cats]);
                                //$this->load->view('partials/_category_block_type_3', ['category' => $cats]);?>
                </div>
            </div>

            <div class="container lifestyle">
            <?php	//lifestyle category data
				$cats = get_category_id(5);
				$this->load->view('partials/_category_block_type_3', ['category' => $cats]);?>
            </div>

            <div class="photo_gallery">
            <?php $this->load->view('partials/photo_gallery');?>  
            </div>

            
            <div class="container culture">
            <?php	//culture category data
				$cats = get_category_id(4);
				$this->load->view('partials/_category_block_type_3', ['category' => $cats]);?>
            </div>

            
            <div class="container"><?php $this->load->view("partials/_ad_spaces", ["ad_space" => "index_bottom", "class" => "bn-p-b"]); ?></div>

            
            <!--<div class="videos_section">
            <?php //$this->load->view('partials/videos_section');?>
            </div>-->

            <div class="container">
            <?php $this->load->view('partials/most_viewed_post');?>
            </div>

    