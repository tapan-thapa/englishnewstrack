<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html amp lang="<?php echo ($this->selected_lang->id == 2)?"en":"hi";?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
    <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
    <?php if(isset($page_name) && ($page_name=='topic' || $page_name=='list')){?><title><?php echo xss_clean($title);?> </title>
	<?php }else{ ?><title><?= xss_clean($title); ?> : <?php if (isset($post_type)): echo "newstrack";  else: echo xss_clean($this->settings->site_title); endif; ?></title><?php }?>
    <link rel="canonical" href="<?php echo str_replace("amp/","", current_full_url()); ?>">
	<meta name="robots" content="index, follow" />
    <meta name="theme-color" content="#0c8cc7" />
	<?php if (isset($post_type)): ?>
	<meta name="robots" content="max-image-preview:large" />
	<?php endif; ?>
    <meta name="description" content="<?= addslashes(xss_clean($description)); ?>"/>
    <meta name="keywords" content="<?= xss_clean($keywords); ?>"/>
    <meta name="author" content="<?= xss_clean($this->settings->application_name); ?>"/>
    <meta property="og:locale" content="en_US"/>
    <meta property="og:site_name" content="<?= xss_clean($this->settings->application_name); ?>"/>
<?php if (isset($post_type)): ?>
    <meta property="og:type" content="<?= $og_type; ?>"/>
    <meta property="og:title" content="<?php if(!empty($post->topic)){ echo html_escape($post->topic); if(!empty($post->headline)){ echo ": ".$post->headline;} }else{ echo $og_title; } ?>"/>
    <meta property="og:description" content="<?= addslashes(xss_clean($description)); ?>"/>
    <meta property="og:url" content="<?= $og_url; ?>"/>
    <meta property="og:image" content="<?= $og_image; ?>"/>
    <meta property="og:image:width" content="<?= $og_width; ?>"/>
    <meta property="og:image:height" content="<?= $og_height; ?>"/>
    <meta property="article:author" content="<?= $og_author; ?>"/>
    <meta property="fb:app_id" content="<?= $this->general_settings->facebook_app_id; ?>"/>
<?php foreach ($og_tags as $tag): ?>
    <meta property="article:tag" content="<?= xss_clean($tag->tag); ?>"/>
<?php endforeach; ?>
    <meta property="article:published_time" content="<?= $og_published_time; ?>"/>
    <meta property="article:modified_time" content="<?= $og_modified_time; ?>"/>
    <meta name="twitter:card" content="summary_large_image"/>
    <meta name="twitter:site" content="@<?= xss_clean($this->settings->application_name); ?>"/>
    <meta name="twitter:creator" content="@<?= xss_clean($og_creator); ?>"/>
    <meta name="twitter:title" content="<?php if(!empty($post->topic)){ echo html_escape($post->topic); if(!empty($post->headline)){ echo ": ".$post->headline;} }else{ echo $og_title; } ?>"/>
    <meta name="twitter:description" content="<?= addslashes(xss_clean($description)); ?>"/>
    <meta name="twitter:image" content="<?= $og_image; ?>"/>
<?php else: ?>
    <meta property="og:image" content="https://alpha.newstrack.com/uploads/logo/logo_63ea7576d62d4.png"/>
    <meta property="og:image:width" content="210"/>
    <meta property="og:image:height" content="90"/>
    <meta property="og:type" content="website"/>
    <meta property="og:title" content="<?= xss_clean($title); ?> - <?= xss_clean($this->settings->site_title); ?>"/>
    <meta property="og:description" content="<?= addslashes(xss_clean($description)); ?>"/>
    <meta property="og:url" content="<?= current_full_url(); ?>"/>
    <meta property="fb:app_id" content="<?= $this->general_settings->facebook_app_id; ?>"/>
    <meta name="twitter:card" content="summary_large_image"/>
    <meta name="twitter:site" content="@<?= xss_clean($this->settings->application_name); ?>"/>
    <meta name="twitter:title" content="<?= xss_clean($title); ?> - <?= xss_clean($this->settings->site_title); ?>"/>
    <meta name="twitter:description" content="<?= xss_clean($description); ?>"/>
<?php endif; ?>
        <script async src="https://cdn.ampproject.org/v0.js"></script>
        <script async custom-element="amp-ad" src="https://cdn.ampproject.org/v0/amp-ad-0.1.js"></script>
        <script async custom-element="amp-sticky-ad" src="https://cdn.ampproject.org/v0/amp-sticky-ad-1.0.js"></script>
        <script async custom-element="amp-form" src="https://cdn.ampproject.org/v0/amp-form-0.1.js"></script>
        <script async custom-element="amp-iframe" src="https://cdn.ampproject.org/v0/amp-iframe-0.1.js"></script>
		<script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script> 
		<script async custom-element="amp-twitter" src="https://cdn.ampproject.org/v0/amp-twitter-0.1.js"></script> 
		<script async custom-element="amp-instagram" src="https://cdn.ampproject.org/v0/amp-instagram-0.1.js"></script>
		<script async custom-element="amp-youtube" src="https://cdn.ampproject.org/v0/amp-youtube-0.1.js"></script> 
		<script async custom-element="amp-facebook" src="https://cdn.ampproject.org/v0/amp-facebook-0.1.js"></script> 
    
        <link rel="shortcut icon" type="image/png" href="<?= get_favicon($this->visual_settings); ?>"/>

    <link rel="apple-touch-icon" href="<?= get_favicon($this->visual_settings); ?>">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&display=swap&subset=devanagari,latin-ext" rel="stylesheet">
    <style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>

    <?php $this->load->view('partials/_amp_css_js_header'); ?>
    <?php $this->load->view('partials/_json_ld'); ?>        
</head>
<body>
 <amp-analytics type="googleanalytics" id="analytics1">
    <script type="application/json">
      {
      "vars": {"account": "UA-82147247-1"},
      "extraUrlParams": {"dimension1": "Newstrack"},
      "triggers": {"trackPageview": {"on": "visible", "request": "pageview"}}
      }
    </script>
	
  </amp-analytics>
  <amp-analytics type="comscore">
    <script type="application/json"> {"vars": {"c2": "32441600"}} </script> 
</amp-analytics>
<header id="header">

    <div class="MenuFixed">
            <div class="topHeader"> 
                 <a title="<?php echo ($this->selected_lang->id == 2)?"Newstrack English":"Newstrack Hindi";?>" href="<?= lang_base_url(); ?>" class="logoimg">
					<amp-img src="<?= $this->dark_mode == 1 ? get_logo_footer($this->visual_settings) : get_logo($this->visual_settings); ?>" height= "35" width = "100"></amp-img>
                </a>        
                <div class="col-sm-10 col-xs-12"><?php $this->load->view('nav/_amp_nav_main'); ?></div>
            </div>
            <!--/.top-bar-->
 			
            <?php //$tags = all_tags();
            $cat_id = 1;$cur_name_slug = '';
            if(isset($category) && !empty($category)){
                $cur_name_slug = $category->name_slug;
                if($category->parent_id == 0){
                $cat_id = $category->id;
                }else{
                    $cat_id = $category->parent_id;
                }
            }
            
            $subcategories = get_subcategories($cat_id, $this->categories);
            //print_r($this->categories);
            ?>
        <div class="SubMenuCont">
            <div class="container">
                <div class="scroll">
                    <ul class="SubMenu">
                    <?php foreach ($subcategories as $subcategory):  if ($subcategory->show_on_menu == 1):
                    ?>
                    <li><a title="<?php echo ucwords($subcategory->name);?>" href="<?php echo generate_category_url($subcategory); ?>" class="<?php echo ($cur_name_slug == html_escape($subcategory->name_slug)) ? 'active' : ''; ?>"><?php echo ucwords($subcategory->name);?></a></li>
                    <?php endif;
                    endforeach; ?>
                    </ul>
                </div>
            </div>
         </div>
    </div>
    </div>

</header>
<!-- <div id="overlay_bg" class="overlay-bg"></div> -->
<div class="ads ampads">
<amp-ad width="336" height="280"
data-loading-strategy="prefer-viewability-over-views"
type="doubleclick" data-slot="/22212039110/NWT_AMP_AllUnits/NWT_AMP_Story_Header_Multisize"
data-multi-size="300x250"
data-enable-refresh="30">
</amp-ad> 
                </div>