<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
?>

<?php
/*if(empty($type)){
	$type = "webstory";	
}
if (!empty($posts)){
    foreach ($posts as $post){
        $this->load->view('post/details/_video', ['post' => $post]);
    }
}*/ ?>


<link rel="stylesheet" href="https://unpkg.com/swiper@8.0.7/swiper-bundle.min.css">

<!-- Slider main container -->
<div class="swiper-container">
  <!-- Additional required wrapper -->
  <ul class="swiper-wrapper">
    <!-- Slides -->
    <li class="swiper-slide">
      <div>
         
        <video 
               width="100%" height="240" controls  loop>
          <source src="https://ak2.picdn.net/shutterstock/videos/34123252/preview/stock-footage-coworkers-discussing-in-the-future-of-their-company-over-several-charts-and-graphs-business.mp4" type="video/mp4">
          Your browser does not support the video tag.
        </video>
      </div>
    </li>
    <li class="swiper-slide">
      <div>
         
        <video width="100%" height="240" controls  loop>
          <source src="https://ak1.picdn.net/shutterstock/videos/25348301/preview/stock-footage--business-new-customers-sale-and-people-concept-thousands-of-people-formed-qr-code-crowd-flight.webm" type="video/webm">
          Your browser does not support the video tag.
        </video>

      </div>
    </li>
    <li class="swiper-slide">
      <div>
         
        <video width="100%" height="240" controls  loop>
          <source src="https://ak4.picdn.net/shutterstock/videos/17795644/preview/stock-footage-receiving-email-e-mail-sign-on-holographic-screen-the-person-received-the-email-and-opens-it-with.mp4" type="video/mp4">
          Your browser does not support the video tag.
        </video>
      </div>
    </li>
  </ul>
  <!-- If we need pagination -->
  <!-- <div class="swiper-pagination"></div> -->

  <!-- If we need navigation buttons -->
        <div class="swiper-buttons">
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>

  <!-- If we need scrollbar -->
  <!-- <div class="swiper-scrollbar"></div> -->
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://unpkg.com/swiper@8.0.7/swiper-bundle.min.js"></script>

<style>
html, body { position: relative; height: 100%; }
body { background:#093563; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 14px; color:#000; margin: 0; padding: 0; overflow: hidden;}
.swiper-container { max-width:700px; position: relative; height: 100%; margin: auto; }
.swiper-slide { text-align: center; font-size: 18px; display: flex; justify-content: center; align-items: center; height: 100vh;}
.swiper-slide div video{height: 100vh; max-width: 520px; background: #000;}
/* reset list */
ul.swiper-wrapper{list-style-type: none; padding: 0px; margin: 0px;}
.swiper-buttons{position: absolute;transform: translate(0, -50%); right:10px; top: 50%; width: 60px; z-index: 99;}
.swiper-button-next, .swiper-button-prev{width: 60px;height: 60px; border: 2px solid #707070; border-radius: 50%; background-color: #00000076; display: grid; align-items: center; cursor: pointer; line-height: 60px; position: relative; right: auto; left: auto; top: auto; bottom: 0; margin: 10px 0px; text-align: center;}
.swiper-button-prev:after, .swiper-rtl .swiper-button-next:after {content: 'prev'; transform: rotate(90deg); color: #fff; line-height:60px;}
.swiper-button-next:after, .swiper-rtl .swiper-button-prev:after {content: 'next'; transform: rotate(90deg); color: #fff;line-height:60px;}

@media (max-width:700px) {
    .swiper-button-next, .swiper-button-prev{display: none;}
}

        </style>
        <script>
            var swiper = new Swiper('.swiper-container', {
      loop: true,
      
       direction: 'vertical',
      autoplay: false,
       pagination: {
        el: '.swiper-pagination',
        clickable: true
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
      /* ON INIT AUTOPLAY THE FIRST VIDEO */
      on: {
        init: function () {
          console.log('swiper initialized');
          var currentVideo =  $("[data-swiper-slide-index=" + this.realIndex + "]").find("video");
          currentVideo.trigger('play');
        },
      },
    });
    
    /* GET ALL VIDEOS */
    var sliderVideos = $(".swiper-slide video");
    
    /* SWIPER API - Event will be fired after animation to other slide (next or previous) */
    swiper.on('slideChange', function () {
      console.log('slide changed');
      /* stop all videos  */
      sliderVideos.each(function( index ) {
        this.currentTime = 0;
      });
    
      /* SWIPER GET CURRENT AND PREV SLIDE (AND The VIDEO INSIDE) */
      var prevVideo =  $(`[data-swiper-slide-index="${this.previousIndex}]"`).find("video");
      var currentVideo =  $(`[data-swiper-slide-index="${this.realIndex}"]`).find("video");
      prevVideo.trigger('stop');
      currentVideo.trigger('play');
    });
        </script> 

 