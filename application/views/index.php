<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<script>
     var widthWindow = window.innerWidth;
  window.googletag = window.googletag || {cmd: []};
  googletag.cmd.push(function() {

    if(widthWindow >= 1024){
    googletag.defineSlot('/22212039110/NWT_Desk_AllUnits/NWT_Desk_Home_Header_Multisize', [[980, 90], [728, 90], [950, 90], [960, 90], [970, 90]], 'div-gpt-ad-1668489428742-0').addService(googletag.pubads());
    googletag.defineSlot('/22212039110/NWT_Desk_AllUnits/NWT_Desk_Home_Top_728x90_Multisize', [[970, 90], [980, 90], [728, 90], [950, 90], [960, 90]], 'div-gpt-ad-1668489578337-0').addService(googletag.pubads());    
    googletag.defineSlot('/22212039110/NWT_Desk_AllUnits/NWT_Desk_Home_Bottom_728x90_Multisize', [[980, 90], [728, 90], [950, 90], [960, 90], [970, 90]], 'div-gpt-ad-1668489814538-0').addService(googletag.pubads()); 
    }
    else{
        googletag.defineSlot('/22212039110/NWT_mWeb_AllUnits/NWT_mWeb_Home_Header_Multisize', [[300, 100], [320, 100], 'fluid'], 'div-gpt-ad-1668418707807-0').addService(googletag.pubads());          
        googletag.defineSlot('/22212039110/NWT_mWeb_AllUnits/NWT_mWeb_Home_Top_300x250_Multisize', [[300, 250], [336, 280], 'fluid'], 'div-gpt-ad-1668418756498-0').addService(googletag.pubads());
        googletag.defineSlot('/22212039110/NWT_mWeb_AllUnits/NWT_mWeb_Home_Middle_300x250_Multisize', [[336, 280], 'fluid', [300, 250]], 'div-gpt-ad-1668418792349-0').addService(googletag.pubads());
        googletag.defineSlot('/22212039110/NWT_mWeb_AllUnits/NWT_mWeb_Home_Sticky_320x50_Multisize', [[300, 50], [320, 50]], 'div-gpt-ad-1668418867901-0').addService(googletag.pubads());

    }
    googletag.pubads().enableSingleRequest();
    googletag.enableServices();
    
  });
</script>
<!-- /22212039110/NWT_Desk_AllUnits/NWT_Desk_Home_Header_Multisize -->
<div class="adsCont desktopads">
<div id='div-gpt-ad-1668489428742-0' style='min-width: 728px; min-height: 90px;'>
  <script>
    googletag.cmd.push(function() { googletag.display('div-gpt-ad-1668489428742-0'); });
  </script>
</div>
</div>

<div class="adsCont Mobileads">
<!-- /22212039110/NWT_mWeb_AllUnits/NWT_mWeb_Home_Header_Multisize -->
<div id='div-gpt-ad-1668418707807-0' style='min-width: 300px; min-height: 100px;'>
  <script>
    googletag.cmd.push(function() { googletag.display('div-gpt-ad-1668418707807-0'); });
  </script>
</div>
</div>

<div class="container">
    <?php $this->load->view('post/_news_ticker', $breaking_news); ?>
</div>
<div class="container">
    <?php $this->load->view('post/_trending_tags.php',['tag_type'=>'trending','trending_tags'=>$trending_tags,'tag_lable'=>'TRENDING TAGS :']); ?>
</div>
<h1 class="title-index"><?php echo html_escape($home_title); ?></h1>
<div class="featuredTopMain m-b-20 home">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-xs-12 TopP1bg">
                <?php
                if ($this->general_settings->show_featured_section == 1) : ?>

                    <?php $this->load->view('post/_featured_posts', $featured_posts);
                    $ex_ids = array();
                    foreach ($featured_posts as $fp) {
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

            <!-- <div id="sidebar" class="col-sm-4 col-xs-12">
                <?php //$this->load->view('partials/home_breaking_sidebar', $breaking_news); ?>
                <?php
               
               // $this->load->view('partials/_category_block_type_sidebar_4', $special_posts);
                ?>
            </div> -->

        </div>
    </div>

</div>
 
<!-- <div class="TrendingComponent">
    <div class="container">
        <?php
        // foreach ($this->categories as $category):
        //if($category->name_slug=='trending'){
            //$this->load->view('partials/_category_block_trending', $trending_posts);
        // } 
        //endforeach; 
        ?>
    </div>
</div> -->


<div class="container"><?php $this->load->view("partials/_ad_spaces", ["ad_space" => "index_bottom", "class" => "bn-p-b"]); ?></div>   


<?php
    $homeWidgetCategory = $this->categories;
    $key_values = array_column($homeWidgetCategory, 'home_order'); 
    array_multisort($key_values, SORT_ASC, $homeWidgetCategory);
    //echo "<pre>";
    //print_r($this->categories);die;
    $categoryLoopArr = [];
    $groupSectionArr = [];
    $cnt = 0;
    if (!empty($homeWidgetCategory)){
        foreach ($homeWidgetCategory as $category){
            if ($category->show_at_homepage == 1){
                $index = $groupSectionArr[$category->home_css_name];
                if($index===0 || $index>0){
                    //echo "--222---";
                    $categoryLoopArr[$index][] = $category;    
                }else{
                    //echo "--333---";
                    $categoryLoopArr[$cnt][] = $category;
                    if(!empty($category->home_css_name)){
                        $groupSectionArr[$category->home_css_name] = $cnt;
                    }
                    $cnt++;
                }
                /* if ($category->block_type == "block-1") {
                    echo '<div class="container news '.$category->home_css_name.'">';
                    $this->load->view('partials/_category_block_type_1', ['category' => $category]);
                    echo '</div>';
                }elseif($category->block_type == "block-2") {
                    echo '<div class="container news '.$category->home_css_name.'">';
                    $this->load->view('partials/_category_block_type_2', ['category' => $category]);
                    echo '</div>';
                }elseif($category->block_type == "block-3") {
                    echo '<div class="container news '.$category->home_css_name.'">';
                    $this->load->view('partials/_category_block_type_3', ['category' => $category]);
                    echo '</div>';
                }elseif($category->block_type == "block-4") {
                    echo '<div class="container news '.$category->home_css_name.'">';
                    if($category->id == 366){
                        $this->load->view('partials/_category_block_type_4_webstory', ['category' => $category]);
                    }else{
                        $this->load->view('partials/_category_block_type_4', ['category' => $category]);
                    }
                    echo '</div>';
                }elseif($category->block_type == "block-5") {
                    echo '<div class="container news '.$category->home_css_name.'">';
                    $this->load->view('partials/_category_block_type_5', ['category' => $category]);
                    echo '</div>';
                } */
            }
        }
        foreach ($categoryLoopArr as $loop1){
            $totalWidget = count($loop1);
            if($totalWidget){
                $firstCat = $loop1[0];
                $widgetClass = "col-sm-12";
                if($totalWidget>=4){
                    $widgetClass = "col-md-3";
                }elseif($totalWidget==3){
                    $widgetClass = "col-md-4";
                }elseif($totalWidget==2){
                    $widgetClass = "col-md-8";
                }
                echo '<div class="container news '.$firstCat->home_css_name.'">';
                $blockCSS = (count($loop1)>1)?"row":"";
                echo '<div class="MainBoxCont">';
                if(!empty($blockCSS)){
                    echo '<div class="'.$blockCSS.'">';
                }
                $loopCounter = 0;
                 foreach ($loop1 as $category){
                    if($loopCounter==1 && $widgetClass == 'col-md-8'){
                        $widgetClass = "col-md-4";
                    }
                    if ($category->block_type == "block-1") {
                        if($category->id == 208 || $category->id == 319 ){
                            $this->load->view('partials/_category_block_type_single_col_layout_vertical', ['category' => $category,'widgetClass'=>$widgetClass]);

                         }else{
                            $this->load->view('partials/_category_block_type_single_col_layout', ['category' => $category,'widgetClass'=>$widgetClass]);
                             //$this->load->view('partials/_category_block_type_1', ['category' => $category,'widgetClass'=>$widgetClass]);

                        }
                    
                    }elseif($category->block_type == "block-2") {
                        $this->load->view('partials/_category_block_type_2', ['category' => $category,'widgetClass'=>$widgetClass]);
                    }elseif($category->block_type == "block-3") {
                        $this->load->view('partials/_category_block_type_3', ['category' => $category,'widgetClass'=>$widgetClass]);
                    }elseif($category->block_type == "block-4") {
                        if($category->id == 366 || $category->id == 488){
                            $this->load->view('partials/_category_block_type_4_webstory', ['category' => $category,'widgetClass'=>$widgetClass]);

                        }elseif($category->id == 213 || $category->id == 217 || $category->id == 333) {
                            $this->load->view('partials/_category_block_type_4_slider', ['category' => $category,'widgetClass'=>$widgetClass]);
                        }else{
                            $this->load->view('partials/_category_block_type_4', ['category' => $category,'widgetClass'=>$widgetClass]);
                        }
                    }elseif($category->block_type == "block-5") {
                        $this->load->view('partials/_category_block_type_5', ['category' => $category,'widgetClass'=>$widgetClass]);
                    }
                    $loopCounter++;
                }
                if(!empty($blockCSS)){
                    echo '</div>';
                }
                 echo '</div>';
                 echo '</div>';
            }
        }
    }
    //print_r($categoryLoopArr);
?>
<!-- <div class="container news">
    <?php     //news category data
    //$cats = get_category_id(1);
    //$this->load->view('partials/_category_block_type_3', ['category' => $cats, 'ex_ids' => $ex_ids]); ?>
</div>

<div class="TrendingComponent Gallary">
    <div class="container">
        <?php
        //$cats = get_category_id(42);
        //$this->load->view('partials/_category_block_gallery', ['category' => $cats]);
        ?>
    </div>
</div>
  

<div class="container analysis">
    <?php    //analysis category data
    //$cats = get_category_id(2);
    //$this->load->view('partials/_category_block_type_3', ['category' => $cats]); ?>
</div>

<div class="TrendingComponent">
    <div class="container">
        <?php    //opinion category data
       // $cats = get_category_id(3);
        //$this->load->view('partials/_category_block_opinion', ['category' => $cats]);
        //$this->load->view('partials/_category_block_type_3', ['category' => $cats]);
        ?>
    </div>
</div>

<div class="container lifestyle">
    <?php    //lifestyle category data
    //$cats = get_category_id(5);
    //$this->load->view('partials/_category_block_type_3', ['category' => $cats]); ?>
</div> -->

<div class="photo_gallery">
    <?php //$this->load->view('partials/photo_gallery'); ?>
</div>


<!-- <div class="container culture">
    <?php    //culture category data
    //$cats = get_category_id(4);
   // $this->load->view('partials/_category_block_type_3', ['category' => $cats]); ?>
</div> -->

<!-- webstory -->
<div class="photo_gallery">
    <?php $this->load->view('partials/web_stories'); ?>
</div>

<!--<div class="container"><?php //$this->load->view("partials/_ad_spaces", ["ad_space" => "index_bottom", "class" => "bn-p-b"]); ?></div>


 <div class="videos_section"><?php //$this->load->view('partials/videos_section'); ?></div> -->

<div class="container most_viewed_post">
    <?php $this->load->view('partials/most_viewed_post'); ?>
</div>

<!-- <div class="container most_viewed_post_tabs">
    <?php //$this->load->view('partials/_sidebar_widget_popular_posts', ['widget' => $widget]); 
    ?>
</div> -->


<div class="FooterSticky FooterAd">
<!-- /22212039110/NWT_mWeb_AllUnits/NWT_mWeb_Home_Sticky_320x50_Multisize -->
    <div id='div-gpt-ad-1668418867901-0' style='min-width: 300px; min-height: 50px;'>
    <script>
        googletag.cmd.push(function() { googletag.display('div-gpt-ad-1668418867901-0'); });
    </script>
    </div>
</div>