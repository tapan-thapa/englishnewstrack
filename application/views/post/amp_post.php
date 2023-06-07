<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Section: wrapper -->
<div id="wrapper" class="PostWrapper "> 
    <div class="container">
        <div class="row">
            <!-- breadcrumb -->
            <div class="col-sm-12 page-breadcrumb">
                <ol class="breadcrumb">
                    <!-- <li class="breadcrumb-item">
                        <a href="<?php //echo lang_base_url(); ?>"><?php //echo trans("breadcrumb_home"); ?></a>
                    </li> -->
                    <?php /* $categories = get_parent_category_tree($post->category_id, $this->categories);
                    if (!empty($categories)):
                        foreach ($categories as $item):
                            if (!empty($item)):?>
                                <li class="breadcrumb-item active">
                                    <a href="<?php echo generate_category_url($item); ?>"><?php echo html_escape($item->name); ?></a>
                                </li>
                            <?php endif;
                        endforeach;
                    endif; */ ?>
                    </ol>
            </div>
            <div class="PageBody-Inner ArticleBody">
                <div id="content" class="postContent col-sm-8">
                <div class="post-content">
                    
					<!-- <a href="<?php //echo generate_topic_url(str_slug(trim($post->topic))); ?>"><span class="category-label BCatItem"><?php //echo html_escape($post->topic); ?></span></a> -->
				
                    <h1 class="title"><?php echo html_escape($post->title); ?></h1>
                    <?php if (!empty($post->summary)): ?>
                        <div class="post-summary">                          
                                <h2><?php echo $post->summary; ?></h2>  
                            </div>
                    <?php endif; ?>
                   
                    <div class="ads ampads">
<amp-ad width="336" height="280"
data-loading-strategy="prefer-viewability-over-views"
type="doubleclick" data-slot="/22212039110/NWT_AMP_AllUnits/NWT_AMP_Story_A_300x250_Multisize"
data-multi-size="300x250"
data-enable-refresh="30">
</amp-ad>

                    <?php $this->load->view('post/details/_amp_article', ['post' => $post]);?>
                   <div class="post-meta">
                        <?php if ($this->general_settings->show_post_author == 1): ?>
                            <span class="post-author-meta sp-left">
                                <a title="<?php echo html_escape($post_user->username); ?>" href="<?php echo generate_profile_url($post_user->slug); ?>" class="imgsmall m-r-0">
									<amp-img src="<?php echo get_user_avatar($post_user->avatar); ?>" height= "50" width = "50"></amp-img>
                                    <span><?php echo html_escape($post_user->username); ?></span>
                                </a>
                            </span>
                        <?php endif; ?>

                        <div class="post-details-meta-date">
                            <?php if ($this->general_settings->show_post_date == 1): ?>
                                <span class="sp-left"><?php echo helper_date_format($post->created_at); ?>&nbsp;-&nbsp;<?php echo formatted_hour($post->created_at); ?></span>
                                <?php if (!empty($post->updated_at)): ?>
                                    <span class="sp-left sp-post-update-date"><?php echo trans("updated"); ?>:&nbsp;<?php echo helper_date_format($post->updated_at); ?>&nbsp;-&nbsp;<?php echo formatted_hour($post->updated_at); ?></span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>

                        
                    </div>

                    <div class="ads ampads">
<amp-ad width="336" height="280"
data-loading-strategy="prefer-viewability-over-views"
type="doubleclick" data-slot="/22212039110/NWT_AMP_AllUnits/NWT_AMP_Story_B_300x250_Multisize"
data-multi-size="300x250"
data-enable-refresh="30">
</amp-ad>

                    <div class="post-text">
                        <?php 
						preg_match_all('/<a .*?>(.*?)<\/a>/',$post->content,$matches);
						//preg_match_all('@https?://(www\.)?newsdrum.in+@i', $post->content, $matches);
						//echo "<pre>";//print_r($matches);
						if (isset($matches[0][0])) {
							preg_match_all('@https?://(www\.)?newsdrum.in+@i', $post->content, $match);
							if(isset($match[0][0])){
								//echo "in--------";
							}else{
								//echo "else";
								 $post->content = str_replace('<a ', '<a rel="nofollow" ', $post->content);
							}
						}
						$content = $post->content;
						$content = replaceInstagram($content);
						
						$find_tw = '~https://twitter.(.+)~';
						$output_array_data = array();
						preg_match_all($find_tw, $content, $output_array_data);
                       // echo "<pre>";print_r($content);die;
                        //$tw_content = $content;
						if (!empty($output_array_data[0])){
							
							//foreach ($output_array_data[0] as $key => $value) {
								
							  $content = replaceTwitter($content);
							//}
						}
                       // die;
						//$content = $tw_content;
						//$content = replaceTwitter($content);
						
                        preg_match_all('/<img[^>]+>/i', $content, $imgTags);
                        for ($i = 0; $i < count($imgTags[0]); $i++) {
                            if(strpos($imgTags[0][$i], 'newstrack.com/') !== false){
                                $content = str_replace($imgTags[0][$i],"", $content);
                            }else{
                                preg_match('/src="([^"]+)/i',$imgTags[0][$i], $imgage);
                                $imgTagHTML  =  '<amp-img width="414" height="232" src="'.str_ireplace( 'src="', '',  $imgage[0]).'"></amp-img>';
                                $content = str_replace($imgTags[0][$i],$imgTagHTML, $content);
                            }
                        }
                        //$content = preg_replace('/(<img[^>]+>(?:<\/img>)?)/i', '$1</amp-img>', $content);
						//$content = str_replace('<img', '<amp-img width="414"  height="232"', $content);
						preg_match_all('@https?://(www\.)?youtube.com/.[^\s.,"\']+@i', $content, $matches);
						if (isset($matches[0][0])) {
							$content = str_replace('<iframe', '<amp-iframe sandbox="allow-scripts allow-same-origin allow-popups"', $content);
							$content = str_replace('</iframe>', '</amp-iframe>', $content);
						}
						$content = str_replace('<iframe', '<amp-iframe', $content);
						$content = str_replace('</iframe>', '</amp-iframe>', $content);
						$content = str_replace('marginwidth="0"', '', $content);
						$content = str_replace('marginheight="0"', '', $content);
						$content = str_replace('allowtransparency="true"', '', $content);
						$content = str_replace('allowTransparency="true"', '', $content);
						$content = str_replace('allowfullscreen="true"', '', $content);
						$content = str_replace('allowFullScreen="true"', '', $content);
						$content = str_replace('target="_parent"', '', $content);
						$content = str_replace('width="100%"', 'width="320px"', $content);
						$content = str_replace('width="95%"', 'width="320px"', $content);
                        $content = str_replace('width="95%"', 'width="320px"', $content);
                        $content = str_replace(['contenteditable="false"','contenteditable="true"'], '', $content);
                        $content = preg_replace('/(<font[^>]*>)|(<\/font>)/', '', $content);
                        $content = preg_replace('/(<blockquote[^>]*>)|(<\/blockquote>)/', '', $content);
                        $content = preg_replace('/(<script[^>]*>)|(<\/script>)/', '', $content);
                        $content = str_replace('has-title="true"', '', $content);
						echo $content; ?>
                    </div>

                    <div class="post-tags">
                        <?php if (!empty($post_tags)): ?>
                            <h2 class="tags-title"><?php echo trans("post_tags"); ?></h2>
                            <ul class="tag-list">
                                <?php foreach ($post_tags as $tag) : ?>
                                    <li>
                                        <a title="<?php echo html_escape($tag->tag); ?>" href="<?php echo generate_tag_url($tag->tag_slug); ?>">
                                            <?php echo html_escape($tag->tag); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>

                </div>


                <div class="ads ampads">
<amp-ad width="336" height="280"
data-loading-strategy="prefer-viewability-over-views"
type="doubleclick" data-slot="/22212039110/NWT_AMP_AllUnits/NWT_AMP_Story_C_300x250_Multisize"
data-multi-size="300x250"
data-enable-refresh="30">
</amp-ad>
 
 
            </div>
            <!-- <div class="col-sm-4 sidebar">
                
            <?php 	
                    //$this->load->view('partials/home_breaking_sidebar', $breaking_news); 
					//$this->load->view('partials/_amp_special_stories', $special_posts); 
			?>
            </div>  -->

        </div>    
    </div>
            </div>

</div>
<!-- /.Section: wrapper -->