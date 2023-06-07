<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php 
    $social_array = get_social_links_array($this->settings);
    $i = 0; 
?>
<?php
if($page_type == "home"){
?>
<script type="application/ld+json">[{
        "@context": "https://schema.org",
        "@type": "Organization",
        "url": "<?= base_url(); ?>",
        "logo": {"@type": "ImageObject","width": 190,"height": 60,"url": "<?= get_logo($this->visual_settings); ?>"}<?= !empty($social_array) ? ',' : ''; ?>

<?php if (!empty($social_array) && item_count($social_array)): ?>
        "sameAs": [<?php foreach ($social_array as $item):if (isset($item['url'])): ?><?= $i != 0 ? ',' : ''; ?>"<?= $item['url']; ?>"<?php endif;
        $i++;endforeach; ?>]
<?php endif; ?>
    },
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "url": "<?= base_url(); ?>",
        "potentialAction": {
            "@type": "SearchAction",
            "target": "<?= base_url(); ?>search?q={search_term_string}",
            "query-input": "required name=search_term_string"
        }
    }]
    </script>
    <?php
}else{
    ?>
<script type="application/ld+json">[{
        "@context": "https://schema.org",
        "@type": "Organization",
        "url": "<?= base_url(); ?>",
        "logo": {"@type": "ImageObject","width": 190,"height": 60,"url": "<?= get_logo($this->visual_settings); ?>"}<?= !empty($social_array) ? ',' : ''; ?>

<?php if (!empty($social_array) && item_count($social_array)): ?>
        "sameAs": [<?php foreach ($social_array as $item):if (isset($item['url'])): ?><?= $i != 0 ? ',' : ''; ?>"<?= $item['url']; ?>"<?php endif;
        $i++;endforeach; ?>]
<?php endif; ?>
    }]
    </script>
<?php } ?>
<script type="application/ld+json">[{
    "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": "<?= current_url(); ?>"
        }
    }]
</script>
<?php if (!empty($post)):
    $date_modified = $post->updated_at;
    if (empty($date_modified)) {
        $date_modified = $post->created_at;
    }
if ($post->post_type == "video"):?>
<script type="application/ld+json">     
    <script type="application/ld+json">   {
        "@context" : "https://schema.org",
        "@type" : "VideoObject",
        "contentUrl" : "<?= $post->video_url; ?>",
        "url" : "<?= generate_post_url($post); ?>",
        "datePublished" : "<?= date(DATE_ISO8601, strtotime($post->created_at)); ?>",
        "dateModified" : "<?= date(DATE_ISO8601, strtotime($date_modified)); ?>",
        "keywords" : "<?= $post->keywords; ?>",
        "name" : "<?= html_escape($post->title); ?> | News Track in Hindi",
        "headline":"<?= html_escape($post->title); ?> | News Track in Hindi",
        "description" : "<?= html_escape($post->summary); ?>",
        "thumbnailUrl" : "<?= get_post_image($post, "big"); ?>",
        "uploadDate":  "<?= date(DATE_ISO8601, strtotime($post->created_at)); ?>"
    }
    </script>
<?php /* else: */ endif; ?>
<?php 
$newsArticle_ImagePath = get_post_image($post, "big");
$newsArticle_image_width = 1280;
$newsArticle_image_height = 720;
$newsArticleLogo = '{
    "@context" : "https://schema.org",
    "@type" : "ImageObject",
    "contentUrl" : "https://newstrack.com/uploads/logo/logo_640e74e924a26.png",
    "height": "60",
    "width" : "600",
    "name"  : "Newstrack - Logo",
    "url" : "https://newstrack.com/images/logo.png"
}';
$newsArticleLogoObj = json_decode($newsArticleLogo);
if ($post->post_type == "webstory"){
    
    $newsArticle_image_width = 900;
    $newsArticle_image_height = 1200;

    $newsArticleLogoObj->height = "96";
    $newsArticleLogoObj->width =  "96";
    unset($newsArticleLogoObj->url);
    $newsArticle_ImagePath = (!empty($schemaGallery[0]["url"]))?$schemaGallery[0]["url"]:$newsArticle_ImagePath;
}

?>
    <script type="application/ld+json">
    {
        "@context" : "https://schema.org",
        "@type" : "NewsArticle",
        "name" : "Newstrack", "author" : {"@type" : "Person","name" : "<?= $post->author_username; ?>","url" : "<?= generate_profile_url(str_replace(" ","-",$post->author_username)); ?>","jobTitle" : "Editor","image" : "<?php (!empty($post->author_image))?$ci->aws_base_url.$post->author_image:""; ?>","sameAs" : ["https://www.facebook.com/shreedhar.agnihotri"]},
        "datePublished" : "<?= date(DATE_ISO8601, strtotime($post->created_at)); ?>",
        "dateModified" : "<?= date(DATE_ISO8601, strtotime($date_modified)); ?>",
        "keywords" : "<?= $post->keywords; ?>",
        "inLanguage" : "<?php echo ($this->selected_lang->id == 2)?"eng":"hi";?>",
        "headline" : "<?= html_escape(strip_tags(preg_replace( "/\r|\n/", "", $post->title ))); ?> | News...",
        "copyrightHolder" : "Newstrack",
        "contentLocation" : "",
        "image" : {
        "@context" : "https://schema.org",
        "@type" : "ImageObject",
        "contentUrl" : "<?= $newsArticle_ImagePath; ?>",
        "height": <?= $newsArticle_image_height; ?>,
        "width" : <?= $newsArticle_image_width; ?>,
        "url" : "<?= $newsArticle_ImagePath; ?>"
        }, 
        "articleSection" : "<?= $post->category_name; ?>",
        "articleBody" : "<?= html_escape(strip_tags($post->content)); ?>",
        "description" : "<?= html_escape($post->summary); ?>",
        "url" : "<?= generate_post_url($post); ?>",
        "publisher" : {
        "@type" : "Organization",
        "name" : "Newstrack",
        "url"  : "https://<?php echo $_SERVER['HTTP_HOST'];?>",
        "sameAs" : [],
        "logo" : <?php echo json_encode($newsArticleLogoObj,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES); ?>
        }
    }
    </script>
    <script type="application/ld+json">   {
    "@context" : "https://schema.org",
    "@type" : "WebPage",
    "name" : "<?= html_escape($post->title); ?> | News Track in Hindi",
    "description" : "<?= html_escape($post->summary); ?>",
    "url" : "<?= generate_post_url($post); ?>",
    "author" : {
      "@type" : "Person",
      "name" : "Newstrack"
     },
    "publisher" : {
      "@type" : "Organization",
       "name" : "Newstrack",
       "url"  : "https://newstrack.com",
       "sameAs" : [],
       "logo" : {
          "@context" : "https://schema.org",
          "@type" : "ImageObject",
          "contentUrl" : "https://newstrack.com/uploads/logo/logo_640e74e924a26.png",
          "height": "226",
          "width" : "535",
          "name"  : "Newstrack - Logo",
          "url" : "https://newstrack.com/uploads/logo/logo_640e74e924a26.png"
      }
     }
   }</script>
    <?php /* endif; */ ?>
<?php endif; ?>
<?php if (!empty($category)):
    if (!empty($parent_category)):?>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BreadcrumbList",
        "itemListElement": [{
            "@type": "ListItem",
            "position": 1,
            "name": "<?= html_escape($parent_category->name); ?>",
            "item": "<?= generate_category_url($parent_category); ?>"
        },
        {
            "@type": "ListItem",
            "position": 2,
            "name": "<?= html_escape($category->name); ?>",
            "item": "<?= generate_category_url($category); ?>"
        }]
    }
    </script>
    <?php else: ?>
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "BreadcrumbList",
            "itemListElement": [{
                "@type": "ListItem",
                "position": 1,
                "name": "<?= html_escape($category->name); ?>",
                "item": "<?= generate_category_url($category); ?>"
            }]
        }
    </script>
    <?php endif; ?>
<?php endif; ?>
<?php if (isset($post_type)): ?>

 <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BreadcrumbList",
        "itemListElement": [{
			"@type" : "ListItem",
            "position" : 1,
            "item" : "<?php echo base_url(); ?>",
            "name" : "Home"
        },
		{
			"@type" : "ListItem",
            "position" : 2,
            "item" : "<?php echo generate_category_url_by_id($post->category_id); ?>",
            "name" : "<?php echo $post->category_name;?>"
        },
        {
            "@type": "ListItem",
            "position": 3,
            "name": "<?php echo htmlspecialchars($og_title); ?>",
            "item": "<?= $og_url; ?>"
        }]
    }
    </script>
<?php endif; ?>
