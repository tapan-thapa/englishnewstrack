<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
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
        googletag.defineSlot('/22212039110/NWT_mWeb_AllUnits/NWT_mWeb_Section_Sticky_320x50_Multisize', [[300, 50], [320, 50]], 'div-gpt-ad-1668419121407-0').addService(googletag.pubads());
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
                    <li class="breadcrumb-item">
                        <a href="<?php echo lang_base_url(); ?>"><?php echo trans("breadcrumb_home"); ?></a>
                    </li>
                    <?php $categories = get_parent_category_tree($category->id, $this->categories);
                    $i = 1;
                    if (!empty($categories)):
                        foreach ($categories as $item):
                            if($item->name_slug == 'state' && count($categories)>1){
                                $i++;
                                continue;
                            } 
                            if ($i < count($categories)): 
                                $breadcrumb[] = $item->name_slug;
                            ?>
                                <li class="breadcrumb-item active">
                                    <a href="<?php echo generate_category_url_new($breadcrumb); ?>"><?php echo html_escape($item->name); ?></a>
                                </li>
                            <?php else: ?>
                                <li class="breadcrumb-item">
                                    <span><?php echo html_escape($item->name); ?></span>
                                </li>
                            <?php endif;
                            $i++;
                        endforeach;
                    endif; ?>
                </ol>
            </div>

            <div class="PageBody-Inner listingpage">
            <div id="content" class="col-sm-12">
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="page-title"><?php echo html_escape($category->name); ?></h1>
                    </div>
                    <div class="listingPostCont">
                            <?php $count = 0; ?>
                            <?php foreach ($posts as $post): 
                            
                            
                            ?>
                                <?php if ($count != 0 && $count % 3 == 0): ?>
                                    <div class="col-sm-12"></div>
                                <?php endif; ?>
                                <?php $this->load->view("post/_post_item_list", ["post" => $post]); ?>
                                <?php if ($count == 2): ?>
                                    <div class="ads adscont"><?php $this->load->view("partials/_ad_spaces", ["ad_space" => "category_top", "class" => "p-b-30"]); ?></div>
                                <?php endif; ?>
                                <?php $count++; ?>
                            <?php endforeach; ?>
                        </div>
                    <div class="ads adscont"><?php $this->load->view("partials/_ad_spaces", ["ad_space" => "category_bottom", "class" => ""]); ?></div>
                    <div class="col-sm-12 col-xs-12">
                        <div class="PagCont text-center">
                        <?php echo $this->pagination->create_links(); ?>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
                        </div>



    <div class="FooterSticky FooterAd">
    <!-- /22212039110/NWT_mWeb_AllUnits/NWT_mWeb_Section_Sticky_320x50_Multisize -->
    <div id='div-gpt-ad-1668419121407-0' style='min-width: 300px; min-height: 50px;'>
    <script>
        googletag.cmd.push(function() { googletag.display('div-gpt-ad-1668419121407-0'); });
    </script>
    </div>
    </div>