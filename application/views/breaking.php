<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<script>
     var widthWindow = window.innerWidth;
  window.googletag = window.googletag || {cmd: []};
  googletag.cmd.push(function() {

    if(widthWindow >= 1024){
    googletag.defineSlot('/22212039110/NWT_Desk_AllUnits/NWT_Desk_Story_Header_Multisize', [ [728, 90]], 'div-gpt-ad-1668489428742-0').addService(googletag.pubads());
    googletag.defineSlot('/22212039110/NWT_Desk_AllUnits/NWT_Desk_Story_Top_728x90_Multisize', [ [728, 90]], 'div-gpt-ad-1668489578337-0').addService(googletag.pubads());    
    googletag.defineSlot('/22212039110/NWT_Desk_AllUnits/NWT_Desk_Story_Middle_728x90_Multisize', [ [728, 90]], 'div-gpt-ad-1668489814538-0').addService(googletag.pubads()); 
  }
    else{
        googletag.defineSlot('/22212039110/NWT_mWeb_AllUnits/NWT_Desk_Story_B_300x250', [[300, 100], [320, 100], 'fluid'], 'div-gpt-ad-1668418707807-0').addService(googletag.pubads());          
        googletag.defineSlot('/22212039110/NWT_mWeb_AllUnits/NWT_Desk_Story_C_300x250', [[300, 250], [336, 280], 'fluid'], 'div-gpt-ad-1668418756498-0').addService(googletag.pubads());
        googletag.defineSlot('/22212039110/NWT_mWeb_AllUnits/NWT_Desk_Story_D_300x250', [[336, 280], 'fluid', [300, 250]], 'div-gpt-ad-1668418792349-0').addService(googletag.pubads());
        }
    googletag.pubads().enableSingleRequest();
    googletag.enableServices();
    
  });
</script>

<!-- /22212039110/NWT_Desk_AllUnits/NWT_Desk_Story_Header_Multisize -->
<div class="adsCont desktopads">
<div id='div-gpt-ad-1668489428742-0' style='min-width: 728px; min-height: 90px;'>
  <script>
    googletag.cmd.push(function() { googletag.display('div-gpt-ad-1668489428742-0'); });
  </script>
</div>
</div>

<div class="adsCont Mobileads">
<!-- /22212039110/NWT_mWeb_AllUnits/NWT_Desk_Story_B_300x250 -->
<div id='div-gpt-ad-1668418707807-0' style='min-width: 300px; min-height: 100px;'>
  <script>
    googletag.cmd.push(function() { googletag.display('div-gpt-ad-1668418707807-0'); });
  </script>
</div>
</div>
<div id="wrapper">
    <div class="container">
        <div class="row">
 
		 
            <div class="PageBody-Inner">
 
 
            <div id="content" class="col-sm-12"> 
                                <h1 class="page-title">Live Updates</h1>

 
			<section class="LiveUpdates section-block-3 commonBlock">
            <div class="section-head">
                
 
                 <div class="row">
                    <div class="col-sm-12">
                    <?php
                    if(!empty($cur_post->url) && !empty($cur_post) ){
                    ?>
                    <h4 class="Live-page-title">
                        <?php echo date("H:i A",strtotime($cur_post->created_at)).": ";?>
                        <a href="<?php echo $cur_post->url; ?>"><?php echo html_escape($cur_post->title); ?></a>
                    </h4>
                    <?php 
                        }else{
                    ?>
                        <h4 class="Live-page-title"><?php if(!empty($cur_post)){ echo date("H:i A",strtotime($cur_post->created_at)).": ".$cur_post->title;}?></h4>
                    <?php
                        }
                    ?>
                         
                    </div>
					<ul class="events">

                    <?php $count = 0; ?>
                    <?php foreach ($posts as $post): ?>
                        <?php 
						if($sel_id != $post->id){
						// if ($count != 0 && $count % 2 == 0): ?>
                             <!--<div class="col-sm-12"></div>-->
                          <?php //endif; ?>
                    <li>
                        <div class="blockList">
                        <time datetime="<?php echo date("H:i A",strtotime($post->created_at));?>:"><?php echo date("H:i A",strtotime($post->created_at));?>:</time>
                        <?php
                        if(!empty($post->url) ){
                        ?> 
                        <h4 class="BHeading"><a href="<?php echo $post->url; ?>"><?php echo html_escape($post->title); ?></a></h4>
                        <?php 
                            }else{
                        ?>
                        <h4 class="BHeading"><?php echo html_escape($post->title); ?></h4>
                        <?php
                            }
                        ?>
                        </div>
                        <div class="live-updates-share post-share-btm">
                            <div class="addthis_inline_share_toolbox_vvw7"></div>
                        </div>
                    </li>
					<li>
                  <?php if ($count == 1): ?>
					<?php //$this->load->view("partials/_ad_spaces", ["ad_space" => "tag_top", "class" => "p-b-30"]); ?>
					<?php endif; ?>
					<?php $count++; ?>
						<?php }
						endforeach; ?>
                    <?php //$this->load->view("partials/_ad_spaces", ["ad_space" => "tag_bottom", "class" => ""]); ?>
                    <!-- <div class="col-sm-12 col-xs-12">
                        <?php //echo $this->pagination->create_links(); ?>
                    </div> -->
                    </li>
 </ul>

					
 
                </div>
					
			</div>
			</section>

            </div>


	
            <!-- <div id="sidebar" class="col-sm-4">
                <?php //$this->load->view('partials/_sidebar'); ?>
            </div> -->
                        </div>
        </div>
    </div>
</div>