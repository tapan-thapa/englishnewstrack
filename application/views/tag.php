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
            <div class="col-sm-12 page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo lang_base_url(); ?>"><?php echo trans("breadcrumb_home"); ?></a></li>
                    <li class="breadcrumb-item active"><?php echo html_escape($tag->tag); ?></li>
                </ol>
            </div>
            <div class="PageBody-Inner">
            <div id="content" class="col-sm-12">
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="page-title"><span> <?php echo trans("tag"); ?>:</span>&nbsp;<strong><?php echo html_escape($tag->tag); ?></strong></h1>
                    </div>
                    <?php $count = 0; ?>
                    <?php foreach ($posts as $post): ?>
                        <?php if ($count != 0 && $count % 3 == 0): ?>
                            <div class="col-sm-12"></div>
                        <?php endif; ?>
                        <?php $this->load->view("post/_post_item_list", ["post" => $post, "show_label" => true]); ?>
                        <?php if ($count == 2): ?>
                            <div class="ads adscont"> <?php $this->load->view("partials/_ad_spaces", ["ad_space" => "tag_top", "class" => "p-b-30"]); ?></div>
                        <?php endif; ?>
                        <?php $count++; ?>
                    <?php endforeach; ?>
                    <div class="ads adscont"><?php $this->load->view("partials/_ad_spaces", ["ad_space" => "tag_bottom", "class" => ""]); ?></div>
                    <div class="col-sm-12 col-xs-12">
                    <div class="PagCont text-center">
                        <?php echo $this->pagination->create_links(); ?>
                        </div>
                    </div>
                </div>
            </div>
                                <!-- <div id="sidebar" class="col-sm-4">
                                    <?php //$this->load->view('partials/_sidebar'); ?>

                                    <?php //$this->load->view('partials/home_breaking_sidebar', $breaking_news); ?>
                                        
                                    
                                </div> -->
                        </div>
        </div>
    </div>
</div>