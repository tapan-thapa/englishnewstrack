<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<style>
 .PostSliderCont{clear:both; margin-bottom: 30px; position: relative;}
 .PostSliderCont .featured-slider-nav{ top:350px;margin: inherit;}
 .PostSliderCont .icon-arrow-slider-right,.PostSliderCont .icon-arrow-slider-left{background: #555; padding:5px;}
 .featured-slider-nav .prev{left: 0;}
 .featured-slider-nav .next {right:0;}
 .post-summary{    display: none !important;}
 .post-meta, .post-share{display: none !important;}
 .GalleryTitle{font-size: 24px !important}
 .galkey_2, .galkey_3, .galkey_4, .galkey_5, .galkey_6, .galkey_7, .galkey_8, .galkey_9{
    display: none !important;
 }
 .gal_date_section .left{width: 40%; float: left; margin-bottom: 15px;}
 .gal_date_section .right{    margin-bottom: 15px; float: right}
 .PostSliderCont .featured-slider-nav{top:205px !important;}
 #st-1.st-has-labels .st-btn.st-remove-label > span {
    display: initial;
}
#st-1.st-has-labels .st-btn {
    min-width: unset;
}
.st-btn.st-last.st-remove-label .st-label {
    padding: 0px 10px !important;
}
.slick-arrow.slick-disabled {
  display: none !important;
}
 @media (max-width:592px) {
    div#featured-slider-nav {
    top: 132px !important;
    }
    .PostSliderCont .featured-slider-nav{ top:185px;margin: inherit;}
    .post-content .title { font-size:26px; line-height:32px;}
    .GalleryTitle{min-height:64px;}
}

</style> 

<?php if (!empty($gallery_post_item)):
    if ($gallery_post_total_item_count > 0): ?>
<div class="gal_date_section">
    <div class="left">
        <span class="date_gal"><?php
        if(isset($gallery_post_slider_data[0]->created_at)){
            echo helper_date_format($gallery_post_slider_data[0]->created_at); 
            echo "&nbsp;&nbsp; &nbsp;";
            echo date("h:i A",strtotime($gallery_post_slider_data[0]->created_at)) ;
        }
        ?>
        </span>
    </div>
    <div class="right">
                <div class="galerry-share">
                    <?php $this->load->view('post/_post_share_box'); ?>
                </div>
        <!-- <?php if ($this->general_settings->show_post_author == 1): ?>
            <span class="post-author-meta sp-left">
                <a href="<?php echo generate_profile_url($post_user->slug); ?>" class="m-r-0">
                    <img src="<?php echo get_user_avatar($post_user->avatar); ?>" alt="<?php echo html_escape($post_user->username); ?>">
                    <?php echo "<b>". html_escape($post_user->username). "</b>" ?>
                </a>
            </span>
        <?php endif; ?>
        &nbsp; -->
        
    </div>
</div>

<div class="PostSliderCont gallery-post-item">
<div id="featured-slider1" class="PostSlider">
    <?php 
    $counter = 0;
    foreach($gallery_post_slider_data as $key => $items){
       
                if($items->image !=''){
                    $img_base_url = base_url();
                    if ($gallery_post_item->storage == "aws_s3") {
                        $img_base_url = $this->aws_base_url;
                    }
            }
            $counter++;

        ?>
    <div>
       
        <div class="post-item-mid gal_img_count" data-count="<?php echo $counter; ?>">
            <img src="<?= $img_base_url . $items->image; ?>" alt="<?php echo html_escape($gallery_post_item->title); ?>" class="img-responsive" data-count="<?php echo $key; ?>" /> 
        </div>
        <div class="post-text"><p><?php echo $items->content; ?></p> </div>
    </div>

    <?php } ?>

 </div> 
  <div id="featured-slider-nav" class="featured-slider-nav">
        <button class="gal prev"><i class="icon-arrow-slider-left"></i></button>
        <button class="gal next"><i class="icon-arrow-slider-right"></i></button>
    </div> 
 </div> 

  <?php endif;
endif; ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $("#featured-slider1").slick({
                autoplay: false,
                slidesToShow: 1,
                slidesToScroll: 1,
                infinite: false,
                speed: 200,
                cssEase: "linear",
                prevArrow: $("#featured-slider-nav .prev"),
                nextArrow: $("#featured-slider-nav .next"),
        });
    });


    // var flag = false;
    // var fl2 = false;
    // $(".gal.next").on("click", function(){
        
    //     //var val = $('.slick-slide.slick-active').index();
    //     var val = $('.slick-slide.slick-active').data("slick-index");
    //     var len = $('.gal_img_count:last').data("count");
    //     //$('button.gal.prev.slick-arrow').show();
    //     console.log(val)
    //     $('#featured-slider1').slick('slickGoTo', val+1);
    //     if(val){
    //         flag = true;
    //          var c = val + 2;             
    //          if(len >= c){
    //             var url= window.location.pathname + '?image='+ c;
    //             window.history.pushState('', 'NewsDrum', url);
    //             callGa(c);
    //             }
            
    //     }else{
    //         if(flag != true){
    //             var url= window.location.pathname + '?image='+ 2;
    //             window.history.pushState('', 'NewsDrum', url);
    //             callGa(2);
    //         }else{                
    //             var url= window.location.pathname + '?image='+ 2;
    //             window.history.pushState('', 'NewsDrum', url);
    //             callGa(2);
    //         }
    //     }

        
    // });

    // $(".gal.prev").on("click", function(){
    //     //var val = $(this).index();
    //     var val = $('.slick-slide.slick-active').data("slick-index");
    //     var len = $('.gal_img_count:last').data("count");
    //     //$('button.gal.next.slick-arrow').show();
    //     $('#featured-slider1').slick('slickGoTo', val-1);
    //     if(val){
    //          if(len >= val){
    //             if(val == 1){
    //                  var url= window.location.pathname + '?image='+ val;
    //                  window.history.pushState('', 'NewsDrum', url);
    //                  callGa(val);
                     
    //             }else{
    //             var url= window.location.pathname + '?image='+ val;
    //             window.history.pushState('', 'NewsDrum', url);
    //             callGa(val);
    //             }
    //         }
    //     }else{
    //        var url= window.location.pathname + '?image='+ len;
    //        window.history.pushState('', 'NewsDrum', url);
    //        callGa(len);  
    //     }

    // });

    // function callGa(c){
    //     gtag('event', 'image View', {'event_category' : 'galleries','event_label' : 'image='+ c});
    // }
</script>