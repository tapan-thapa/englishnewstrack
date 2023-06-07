<!-- Cover page -->
<amp-story standalone title="Director Manmohan Desai Molded Amitabh Bachchan in His Favour" publisher="newstrack"  publisher-logo-src="<?= get_favicon($this->visual_settings); ?>" 
poster-portrait-src="<?= get_favicon($this->visual_settings); ?>">
<!-- comScore UDM pageview tracking -->
<amp-analytics type="comscore">
  <script type="application/json"> {"vars": {"c2": "32441600"}} </script> 
</amp-analytics>
<!-- End comScore example -->
<!--  Google analytics code 1 -->
<amp-analytics type="gtag" data-credentials="include">
  <script type="application/json"> { "vars": {"gtag_id": "UA-82147247-1","config": {"UA-82147247-1": {"groups": "default"}}},"triggers": {"storyProgress": {"on": "story-page-visible","vars": {"event_name": "custom","event_action": "story_progress","event_category": "फोटो स्टोरीज़","event_label": "366","send_to": ["UA-82147247-1"]}},"storyEnd": {"on": "story-last-page-visible","vars": {"event_name": "custom","event_action": "story_complete","event_category": "फोटो स्टोरीज़","send_to": ["UA-82147247-1"]}}},"extraUrlParams": {"cd63":"${storyPageIndex}","cd16":"English"}}</script>
</amp-analytics>

<amp-story-auto-ads>
<script type="application/json">
  {
    "ad-attributes": {
       "type": "doubleclick",
       "data-slot": "/22212039110/NYT_WebStories_AdUnit"
    }
  }
</script>
</amp-story-auto-ads>
  
 <?php 
 if(!empty($webstory_history)){
  $i= 1;
  $length = count($webstory_history);

  foreach ($webstory_history as $key => $webstory) {
      $ampstorypage = "slide-".$webstory->id;
      if($i == 1){
        $ampstorypage = 'cover';
      }
      if($i == $length){
         $ampstorypage = 'last';
      } ?> 
      <amp-story-page id="<?php echo $ampstorypage; ?>" auto-advance-after="15s">
        <amp-story-grid-layer template="fill">
          <amp-img src="<?php echo get_post_image($webstory, "photoslider"); ?>" width="720" height="1280" alt="Amitabh" animate-in="zoom-in" animate-in-duration="150s" layout="responsive"></amp-img>
        </amp-story-grid-layer>
        <amp-story-grid-layer template="vertical" class="bottom overlay-end">
          <div class="z-index-up layoutBox">
            <?php 
            if($i == 1){
            ?>
            <h2 animate-in="fly-in-top"  animate-in-delay="0.3s" animate-in-duration="0.6s">
              <?php echo $webstory->title; ?>
             </h2>
             <?php 
              }else{
             ?>
             <h4 animate-in="fly-in-top"  animate-in-delay="0.3s" animate-in-duration="0.6s">
              <?php echo $webstory->title; ?>
             </h4>
             <?php 
              }
             ?>
             <h3 animate-in="fly-in-top" animate-in-delay="0.3s" animate-in-duration="0.6s"><?php echo $webstory->description; ?></h3>
             <?php 
              if(!empty($webstory->url)){
             ?>
             <div class="story-btn"><a class="i-amphtml-story-page-open-attachment i-amphtml-story-page-open-attachment-outlink" role="button" target="_top" aria-label="Find out More.." href="<?php echo $webstory->url; ?>" active=""><svg class="i-amphtml-story-outlink-page-attachment-arrow" viewBox="0 0 20 8" width="20px" height="8px"><path d="m18 7.7-.7-.2-7.3-4-7.3 4c-.7.4-1.6.2-2-.6-.4-.7-.1-1.6.6-2l8-4.4a2 2 0 0 1 1.5 0l8 4.4c.7.4 1 1.3.6 2-.4.5-.9.8-1.4.8z"></path></svg><div class="i-amphtml-story-outlink-page-attachment-outlink-chip"><svg class="i-amphtml-story-page-open-attachment-link-icon" viewBox="0 0 24 24"><path fill-opacity=".1" d="M12 0c6.6 0 12 5.4 12 12s-5.4 12-12 12S0 18.6 0 12 5.4 0 12 0z"></path><path d="m13.8 14.6.2.5-.2.5-1.5 1.4c-.7.7-1.7 1.1-2.7 1.1A4 4 0 0 1 6.9 17a3.9 3.9 0 0 1-1.1-2.7 4 4 0 0 1 1.1-2.7l1.5-1.5.5-.1.5.2.2.5-.2.5-1.5 1.5c-.5.5-.7 1.1-.7 1.7 0 .6.3 1.3.7 1.7.5.5 1.1.7 1.7.7s1.3-.3 1.7-.7l1.5-1.5c.3-.3.7-.3 1 0zM17 7a3.9 3.9 0 0 0-2.7-1.1A4 4 0 0 0 11.6 7l-1.5 1.5-.1.4.2.5.5.2.5-.2 1.5-1.5c.5-.5 1.1-.7 1.7-.7.6 0 1.3.3 1.7.7.5.5.7 1.1.7 1.7 0 .6-.3 1.3-.7 1.7l-1.5 1.5-.2.5.2.5.5.2.5-.2 1.5-1.5c.7-.7 1.1-1.7 1.1-2.7-.1-1-.5-1.9-1.2-2.6zm-7.9 7.2.2.5.5.2.5-.2 4.5-4.5.2-.5-.2-.5c-.3-.2-.8-.2-1 .1l-4.5 4.5-.2.4z"></path></svg><span class="i-amphtml-story-page-attachment-label">Find out More..</span></div></a></div>
             <?php
              }
             ?>
          </div>
        </amp-story-grid-layer>   
      </amp-story-page>

      <?php 
      $i++;    
      }
    }
  ?> 
  </amp-story>
