<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html amp lang="<?php echo ($this->selected_lang->id == 2)?"en":"hi";?>">
<head>
<meta charset="utf-8">
<title><?php echo $post->title; ?></title>
<link rel="shortcut icon" href="<?= get_favicon($this->visual_settings); ?>" type="image/x-icon"/>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="index, follow" />
<meta name="theme-color" content="#0c8cc7" />
<meta name="description" content="<?= addslashes(xss_clean($description)); ?>"/>
<meta name="keywords" content="<?php  if (isset($post_type)){ if(!empty($og_tags)){ foreach ($og_tags as $tag): echo xss_clean($tag->tag).","; endforeach;}else{ echo xss_clean($keywords); } }else{ echo xss_clean($keywords); } ?>"/>
<meta name="author" content="<?= xss_clean($this->settings->application_name); ?>"/>

<style amp-boilerplate>
body{-webkit-animation: -amp-start 8s steps(1, end) 0s 1 normal both; -moz-animation: -amp-start 8s steps(1, end) 0s 1 normal both; -ms-animation: -amp-start 8s steps(1, end) 0s 1 normal both; animation: -amp-start 8s steps(1, end) 0s 1 normal both}
@-webkit-keyframes -amp-start {from{visibility:hidden} to {visibility: visible}}
@-moz-keyframes -amp-start{from {visibility:hidden} to {visibility: visible}}
@-ms-keyframes -amp-start{from {visibility:hidden} to {visibility: visible}}
@-o-keyframes -amp-start{from {visibility:hidden} to {visibility: visible}}
@keyframes -amp-start{from {visibility:hidden} to {visibility: visible}}
</style>
<noscript>
<style amp-boilerplate>body {-webkit-animation: none;-moz-animation: none;-ms-animation: none;animation: none}</style>
</noscript>
<script async src="https://cdn.ampproject.org/v0.js"></script>
<script async custom-element="amp-ad" src="https://cdn.ampproject.org/v0/amp-ad-0.1.js"></script>
<script async custom-element="amp-story" src="https://cdn.ampproject.org/v0/amp-story-1.0.js"></script>
<script async custom-element="amp-video" src="https://cdn.ampproject.org/v0/amp-video-0.1.js"></script>
<script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<script async custom-element="amp-story-auto-ads" src="https://cdn.ampproject.org/v0/amp-story-auto-ads-0.1.js"></script>
<!-- <amp-ad width="1" height="1" type="doubleclick" data-slot="/22212039110/NYT_WebStories_AdUnit"> </amp-ad> -->

<style amp-custom>
  amp-story{font-family: 'Poppins', sans-serif;} 
.layoutBox{position: absolute; bottom: 0; padding:0px 0 15px; color: #fff;}
.layoutBox h4{font-size: 20px; text-shadow: 0 0 20px #000; background: #00000042; padding:0 12px; line-height:36px;}
.layoutBox h2{font-size: 20px; text-shadow: 0 0 20px #000; background: #00000042; padding:0 12px; line-height:36px;}
.layoutBox h3{text-align: center;}
.layoutBox h3 p{display: inline-block; background: #5c5c5c; padding: 5px 10px; border-radius: 20px; font-size: 10px;}
.story-btn{text-align: center; margin-top: 25px;}
 </style>
 <link rel="canonical" href="<?php echo site_url().$post->category_slug."/". $post->title_slug."-".$post->id; ?>">
 <?php
 $schemaGallery = [];
 foreach ($webstory_history as $key => $webstory) {
  $schemaGallery[] = [
    "@type"=>"ImageObject",
    "name"=>$webstory->title,
    "contentUrl"=>site_url().$post->category_slug."/". $post->title_slug."-".$post->id,
    "url"=>get_post_image($webstory, "photoslider"),
    "width"=>"900",
    "height"=>"1200"
  ];
}
 ?>
 <?php $this->load->view('partials/_json_ld',["schemaGallery"=>$schemaGallery]); ?> 
<script data-rh="true" type="application/ld+json">{
		"@context" : "https://schema.org",
		"@type": "MediaGallery",
		"description":"",
		  	"mainEntityOfPage": {  
		  	"@type": "ImageGallery",
				"associatedMedia": <?php echo json_encode($schemaGallery,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES); ?>
			} 
		}</script>
    
</head>
<body>