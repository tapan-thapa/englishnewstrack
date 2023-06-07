<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="<?php echo ($this->selected_lang->id == 2)?"eng":"hi";?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php if(isset($page_name) && ($page_name=='topic' || $page_name=='list' || $page_name=='home')){?><title><?php echo xss_clean($title);?> </title>
	<?php }else{ ?><title><?= xss_clean($title); ?> : <?php if (isset($post_type)): echo "newstrack";  else: echo xss_clean($this->settings->site_title); endif; ?></title><?php }?>
    <?php if(isset($page_name) && $page_name == 'e_404'){ ?>
        <meta name="robots" content="noindex, follow"> 
    <?php }else{?>
       <meta name="robots" content="index, follow" />
    <?php } ?>
    <meta name="theme-color" content="#0c8cc7" />
	<?php if (isset($post_type)): ?>
	<meta name="robots" content="max-image-preview:large" />
	<?php endif; ?>
	
    <meta name="description" content="<?= addslashes(xss_clean($description)); ?>"/>
    <meta name="keywords" content="<?php  if (isset($post_type)){ if(!empty($og_tags)){ foreach ($og_tags as $tag): echo xss_clean($tag->tag).","; endforeach;}else{ echo xss_clean($keywords); } }else{ echo xss_clean($keywords); } ?>"/>
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
	
	<link rel="amphtml" href="<?php echo str_replace('newstrack.com/','newstrack.com/amp/', $og_url); ?>"/>
	
<?php else: ?>
    <meta property="og:image" content="<?= get_favicon($this->visual_settings); ?>"/>
    <meta property="og:image:width" content="210"/>
    <meta property="og:image:height" content="90"/>
    <meta property="og:type" content="website"/>
    <?php if(isset($page_name) && ($page_name=='list' || $page_name=='home')){?>
        <meta property="og:title" content="<?php echo xss_clean($title);?>"/>
        <meta name="twitter:title" content="<?= xss_clean($title); ?>"/>
    <?php
    }else{
    ?>
    <meta property="og:title" content="<?= xss_clean($title); ?> - <?= xss_clean($this->settings->site_title); ?>"/>
    <meta name="twitter:title" content="<?= xss_clean($title); ?> - <?= xss_clean($this->settings->site_title); ?>"/>
    <?php
    }
    ?>
    <meta property="og:description" content="<?= addslashes(xss_clean($description)); ?>"/>
    <meta property="og:url" content="<?= current_full_url(); ?>"/>
    <meta property="fb:app_id" content="<?= $this->general_settings->facebook_app_id; ?>"/>
    <meta name="twitter:card" content="summary_large_image"/>
    <meta name="twitter:site" content="@<?= xss_clean($this->settings->application_name); ?>"/>
    <meta name="twitter:description" content="<?= xss_clean($description); ?>"/>
<?php endif; ?>
<?php if ($this->general_settings->pwa_status == 1): ?>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="<?= xss_clean($this->settings->application_name); ?>">
    <meta name="msapplication-TileImage" content="<?= base_url(); ?>assets/img/pwa/144x144.png">
    <meta name="msapplication-TileColor" content="#2F3BA2">
    <link rel="manifest" href="<?= base_url(); ?>manifest.json">
    <link rel="apple-touch-icon" href="<?= base_url(); ?>assets/img/pwa/144x144.png">
<?php endif; ?>
    <link rel="shortcut icon" type="image/png" href="<?= get_favicon($this->visual_settings); ?>"/>
    <link rel="canonical" href="<?= current_full_url(); ?>"/>
     <link href="<?= base_url(); ?>assets/vendor/font-icons/css/font-icon.min.css" rel="stylesheet"/>
    <?= !empty($this->fonts->primary_font_url) ? $this->fonts->primary_font_url : ''; ?>
    <?= !empty($this->fonts->secondary_font_url) ? $this->fonts->secondary_font_url : ''; ?>
    <?= !empty($this->fonts->tertiary_font_url) ? $this->fonts->tertiary_font_url : ''; ?>
    <link href="<?= base_url(); ?>assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="<?= base_url(); ?>assets/css/style-1.9.css?v=1010" rel="stylesheet"/>
    <link href="<?= base_url(); ?>assets/css/plugins-1.9.css" rel="stylesheet"/>
<?php if ($this->dark_mode == 1) : ?>
    <link href="<?= base_url(); ?>assets/css/dark-1.9.min.css" rel="stylesheet"/>
<?php endif; ?>
    <script>var rtl = false;</script>
<?php if ($this->selected_lang->text_direction == "rtl"): ?>
    <link href="<?= base_url(); ?>assets/css/rtl-1.9.min.css" rel="stylesheet"/>
    <script>var rtl = true;</script>
<?php endif; ?>
    <?php $this->load->view('partials/_css_js_header'); ?>
    <?= $this->general_settings->custom_css_codes; ?>
    <?= $this->general_settings->adsense_activation_code; ?>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <?php $this->load->view('partials/_json_ld'); ?>
    <!-- <script id="mcjs">!function(c,h,i,m,p){m=c.createElement(h),p=c.getElementsByTagName(h)[0],m.async=1,m.src=i,p.parentNode.insertBefore(m,p)}(document,"script","https://chimpstatic.com/mcjs-connected/js/users/048f2bc627d41a9bdee649a2c/1965ed16adadd9f5ae3ad90e6.js");</script> 
    <script id="mcjs">!function(c,h,i,m,p){m=c.createElement(h),p=c.getElementsByTagName(h)[0],m.async=1,m.src=i,p.parentNode.insertBefore(m,p)}(document,"script","https://chimpstatic.com/mcjs-connected/js/users/bfd58161cc6d8c0a447a64e9c/1e3fb6ba8cdf7ae8cf37adbad.js");</script>-->
</head>
<body>
<!-- Load Facebook SDK for JavaScript -->
<div id="fb-root"></div>
<script>(function(d, s, id) {
var js, fjs = d.getElementsByTagName(s)[0];
if (d.getElementById(id)) return;
js = d.createElement(s); js.id = id;
js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0";
fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>


<header id="header">
    <?php //$this->load->view('nav/_nav_top'); ?>

    <div class="MenuFixed">
        <div class="logo-banner" id="<?php echo $this->dark_mode; ?>">
        <div class="container">
            <div class="row">
            <div class="col-sm-12">
                <div class="row">
            <div class="col-md-6 col-lg-9"> 

            <div class="hamburger-menu">
          <div class="bar"></div>	
      </div>

                <a title="Logo" href="<?= lang_base_url(); ?>" class="Headerlogo"> 
                    <img src="<?= $this->dark_mode == 1 ? get_logo_footer($this->visual_settings) : get_logo($this->visual_settings); ?>" class="logo custom" width="100%"  alt="<?php echo ($this->selected_lang->id == 2)?"Newstrack English":"Newstrack Hindi";?>" title="<?php echo ($this->selected_lang->id == 2)?"Newstrack English":"Newstrack Hindi";?>">
                </a>
                <?php $this->load->view('nav/_nav_main'); ?></div>

                <div class="col-md-6 col-lg-3">
                    <ul class="nav navbar-nav navbar-right"> 
                    <li class="li-img">
                                <a title="Apna Bharat" href="https://apnabharat.org/" target="_blank" class="t-anchor-wrap"><span class="lang-txt"><img width="100%" alt="apnabharat.org" src="/assets/img/apnabharatlogo.jpg"></span></a>
                        </li>
                        <li class="li-language">
                                <?php
                                    if($this->selected_lang->id == 1){
                                ?>
                                <a title="EN" class="t-anchor-wrap switch-lang-header " href="https://english.newstrack.com"><span class="lang-txt">EN</span></a>
                                <?php
                                    }elseif($this->selected_lang->id == 2){
                                ?>
                                <a title="HI" class="t-anchor-wrap switch-lang-header " href="https://newstrack.com"><span class="lang-txt">HI</span></a>
                                <?php 
                                }
                                ?>
                             </li>
                            <li class="li-search">
                                <a title="Search" class="search-icon"><i class="icon-search"></i></a>
                                <div class="search-form">
                                    <?= form_open(generate_url('search'), ['method' => 'get', 'id' => 'search_validate']); ?>
                                    <input type="text" name="q" maxlength="300" pattern=".*\S+.*" class="form-control form-input" placeholder="<?= trans("placeholder_search"); ?>" <?= ($this->rtl == true) ? 'dir="rtl"' : ''; ?> required>
                                    <button class="btn btn-default"><i class="icon-search"></i></button>
                                    <?= form_close(); ?>
                                </div>
                            </li>
                        </ul>
                    </div>


                </div>
            </div>
        </div><!--/.container-->

    </div>

    </div><!--/.top-bar-->
                            							
    <?php //$tags = all_tags();
	$cat_id = 1;$cur_name_slug = '';
	if(isset($categoryParent) && !empty($categoryParent)){
		$cur_name_slug = $categoryParent->name_slug;
		if($categoryParent->parent_id == 0){
		    $cat_id = $categoryParent->id;
		}else{
			$cat_id = $categoryParent->parent_id;
		}
	}
    //echo "<br>=====".$cat_level;
    //echo "<br>=====".$parentURL;
	//echo "=====".$cat_id;
    //pr($categoryParent);
	$subcategories = get_subcategories($cat_id, $this->categories);
    //pr($subcategories);
    ?>
        <div class="SubMenuCont">
            <div class="container">
                <div class="scroll">
                <ul class="SubMenu">
                <?php foreach ($subcategories as $subcategory):  if ($subcategory->show_on_menu == 1):
                if(!empty($subcategory->parent_slug) && $subcategory->parent_slug != 'state'){
                    $breadcrumb = [$subcategory->parent_slug,$subcategory->name_slug];
                }else{
                    $breadcrumb = [$subcategory->name_slug];
                }
                ?>
                <li><a title="<?php echo ucwords($subcategory->name);?>" href="<?php echo generate_category_url_new($breadcrumb); ?>" class="<?php echo ($cur_name_slug == html_escape($subcategory->name_slug)) ? 'active' : ''; ?>"><?php echo ucwords($subcategory->name);?></a></li>
                <?php endif;
                endforeach; ?>
            
                </ul>
                </div>
            </div>
        </div>

    <?php
    if(isset($categoryChild) && is_object($categoryChild)){
        //pr($categoryChild);die;
        //echo $menu_cat_child_name;
        $subSubcategories = get_subcategories($categoryChild->parent_id, $this->categories);
         ?>
            <div class="SubMenuCont">
                <div class="container">
                    <div class="scroll">
                    <ul class="SubMenu">
                    <?php foreach ($subSubcategories as $subcategory):  if ($subcategory->show_on_menu == 1):
                        if(!empty($menu_cat_child)){
                            $breadcrumb = [$menu_cat_child,$subcategory->name_slug];
                        }else{
                            $breadcrumb = [$subcategory->name_slug];
                        }
                    ?>
                    <li><a title="<?php echo ucwords($subcategory->name);?>" href="<?php echo generate_category_url_new($breadcrumb); ?>" class="<?php echo ($menu_cat_child_name == html_escape($subcategory->name_slug)) ? 'active' : ''; ?>"><?php echo ucwords($subcategory->name);?></a></li>
                    <?php endif;
                    endforeach; ?>
                
                    </ul>
                    </div>
                </div>
            </div>
         <?php

    } 

    ?>    


    </div>

</header>
 
<div id="overlay_bg" class="overlay-bg"></div>
<div class="Topad container"> 
    <div class="">
        <?php $this->load->view("partials/_ad_spaces", ["ad_space" => "header"]); ?>
    </div>
</div>

<!--<div class="mobile-nav-search">
    <div class="search-form">
        <?= form_open(generate_url('search'), ['method' => 'get']); ?>
        <input type="text" name="q" maxlength="300" pattern=".*\S+.*"
               class="form-control form-input"
               placeholder="<?= trans("placeholder_search"); ?>" required>
        <button class="btn btn-default"><i class="icon-search"></i></button>
        <?= form_close(); ?>
    </div>
</div>-->
<?php //$this->load->view('nav/_nav_mobile'); ?>
<?php $this->load->view('partials/_modals'); ?>


