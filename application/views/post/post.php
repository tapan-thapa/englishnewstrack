<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<style>
 li div.left{width:auto!important}   
</style>
<script>
     var widthWindow = window.innerWidth;
  window.googletag = window.googletag || {cmd: []};
  googletag.cmd.push(function() {

    if(widthWindow >= 1024){
    googletag.defineSlot('/22212039110/NWT_Desk_AllUnits/NWT_Desk_Story_Header_Multisize', [ [728, 90]], 'div-gpt-ad-1668489428742-0').addService(googletag.pubads());
    googletag.defineSlot('/22212039110/NWT_Desk_AllUnits/NWT_Desk_Story_A_300x250', [300, 250], 'div-gpt-ad-1668490282621-0').addService(googletag.pubads());   
    googletag.defineSlot('/22212039110/NWT_Desk_AllUnits/NWT_Desk_Story_B_300x250', [300, 250], 'div-gpt-ad-1668490311804-0').addService(googletag.pubads());
    googletag.defineSlot('/22212039110/NWT_Desk_AllUnits/NWT_Desk_Story_C_300x250', [300, 250], 'div-gpt-ad-1668490341675-0').addService(googletag.pubads()); 
    }
    else{
        googletag.defineSlot('/22212039110/NWT_mWeb_AllUnits/NWT_mWeb_Story_Header_Multisize', [[336, 280], [300, 250], 'fluid'], 'div-gpt-ad-1668419187969-0').addService(googletag.pubads());
        googletag.defineSlot('/22212039110/NWT_mWeb_AllUnits/NWT_mWeb_Story_A_300x250_Multisize', [[336, 280], 'fluid', [300, 250]], 'div-gpt-ad-1668419218248-0').addService(googletag.pubads());        googletag.defineSlot('/22212039110/NWT_mWeb_AllUnits/NWT_Desk_Story_B_300x250', [[300, 100], [320, 100], 'fluid'], 'div-gpt-ad-1668418707807-0').addService(googletag.pubads());          
        googletag.defineSlot('/22212039110/NWT_mWeb_AllUnits/NWT_mWeb_Story_B_300x250_Multisize', [[300, 250], 'fluid', [336, 280]], 'div-gpt-ad-1668419252990-0').addService(googletag.pubads());
        googletag.defineSlot('/22212039110/NWT_mWeb_AllUnits/NWT_mWeb_Story_C_300x250_Multisize', [[336, 280], [300, 250], 'fluid'], 'div-gpt-ad-1668419285992-0').addService(googletag.pubads());
        googletag.defineSlot('/22212039110/NWT_mWeb_AllUnits/NWT_mWeb_Story_Sticky_320x50_Multisize', [[300, 50], [320, 50]], 'div-gpt-ad-1668419372004-0').addService(googletag.pubads());   
    }
    googletag.pubads().enableSingleRequest();
    googletag.enableServices();
    
  });
</script>
<!-- /22212039110/NWT_Desk_AllUnits/NWT_Desk_Story_Header_Multisize -->
<div class="adsCont desktopads postphp">
<div id='div-gpt-ad-1668489428742-0' style='min-width: 728px; min-height: 90px;'>
  <script>
    googletag.cmd.push(function() { googletag.display('div-gpt-ad-1668489428742-0'); });
  </script>
</div>
</div>



<!-- Section: wrapper -->
<div id="wrapper" class="PostWrapper PostPage">
    <div class="container">
        <div class="">
            <!-- breadcrumb -->
            <div class="col-sm-12 page-breadcrumb" style="display:block;">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a title="<?php echo trans("breadcrumb_home"); ?>" href="<?php echo lang_base_url(); ?>"><?php echo trans("breadcrumb_home"); ?></a>
                    </li>
                    <?php $categories = get_parent_category_tree($post->category_id, $this->categories);
                    if (!empty($categories)) :
                        foreach ($categories as $item) :
                            if (!empty($item)) :
                                if ($item->name_slug == 'state' && count($categories) > 1) continue;
                                $breadcrumb[] = $item->name_slug;
                    ?>
                                <li class="breadcrumb-item active">
                                    <a title="<?php echo html_escape($item->name); ?>" href="<?php echo generate_category_url_new($breadcrumb); ?>"><?php echo html_escape($item->name); ?></a>
                                </li>
                    <?php endif;
                        endforeach;
                    endif; ?>
                    <li class="breadcrumb-item active"> <?php echo html_escape(character_limiter($post->title, 160, '...')); 
                                                            ?></li>
                </ol>
            </div>
            <div class="PageBody-Inner ArticleBody">
                <div class="postContent col-md-8 col-xs-12">
                    <?php $this->load->view('post/post_lhs',['post'=>$post]); ?>
                    <div class="nextPostPlaceholder"></div>
                </div>


                <?php //if ($post->show_right_column == 1): 
                ?>
                <div id="sidebar" class="col-md-4 col-xs-12 articleSidebar">
                    <div class="sidebar-widget widget-popular-posts MostViewed"><?php $this->load->view('post/_trending_tags', ['tag_type' => 'quick_links', 'trending_tags' => $trending_tags, 'tag_lable' => 'Quick Links:']); ?></div>
                <div class="ads adscont"><?php $this->load->view("partials/_ad_spaces", ["ad_space" => "sidebar_top", "class" => "p-b-30"]); ?></div>

                    <?php $this->load->view('partials/most_viewed_post'); ?>

                    <!--include sidebar -->
                    <?php //$this->load->view('partials/home_breaking_sidebar', $breaking_news); 
                    ?>


                    <?php $this->load->view('partials/top_most_post'); ?>

                    <div class="ads adscont"> <?php $this->load->view("partials/_ad_spaces", ["ad_space" => "sidebar_bottom", "class" => ""]); ?></div>

                </div>


                <?php //endif; 
                ?>

            </div>



            <?php 
                #we will not using this related widget now, now stopped by if condi 
                if ($post->post_type=="NO_NEED_THIS_SECTION" && !empty($related_posts) && $post->post_type != "live_post" && $post->post_type != "webstory") { ?>
                <section class="section section-related-posts commonCont ArticleWidget">
                    <h4 class="title"><?php echo trans("related_posts"); ?></h4>

                    <div class="PageBody-Inner">
                        <div class="relatedWidget">
                            <div class="row">
                                <?php //$i = 0; 
                                ?>
                                <?php 
                                $nextLoadArr = [];
                                foreach ($related_posts as $item) :
                                    $nextLoadArr[] = $item->id;
                                    if (!in_array($item->id, [$post->id])) :
                                ?>

                                        <?php //if ($i > 0 && $i % 3 == 0): 
                                        ?>
                                        <!--<div class="col-sm-12"></div>-->
                                        <?php //endif; 
                                        ?>
                                        <div class="col-sm-3 col-xs-12">
                                            <?php $this->load->view("post/_post_item_mid", ["post" => $item]); ?>
                                        </div>

                                        <?php //$i++; 
                                        ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                            <!-- <div id="featured-slider-nav" class="featured-slider-nav">
                                <button class="prev"><i class="icon-arrow-slider-left"></i></button>
                                <button class="next"><i class="icon-arrow-slider-right"></i></button>
                            </div> -->
                        </div>
                    </div>
                </section>
            <?php } ?>



        </div>
    </div>

</div>
<!-- /.Section: wrapper -->

<?php if ($this->general_settings->facebook_comment_active) {
    echo $this->general_settings->facebook_comment;
} ?>

<?php if (!empty($post->feed_id)) : ?>
    <style>
        .post-text img {
            display: none !important;
        }

        .post-content .post-summary {
            display: none;
        }
    </style>
<?php endif; ?>
<script>
    window.infiniteScrollUrls = <?php echo json_encode($next_posts_ids); ?>;
</script>

<div class="FooterSticky FooterAd">
  <!-- /22212039110/NWT_mWeb_AllUnits/NWT_mWeb_Story_Sticky_320x50_Multisize -->
    <div id='div-gpt-ad-1668419372004-0' style='min-width: 300px; min-height: 50px;'>
    <script>
        googletag.cmd.push(function() { googletag.display('div-gpt-ad-1668419372004-0'); });
    </script>
    </div>
    </div>