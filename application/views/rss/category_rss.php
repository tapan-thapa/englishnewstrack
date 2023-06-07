<?php echo '<?xml version="1.0" encoding="UTF-8" ?>' . "\n"; ?>
<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:sy="http://purl.org/rss/1.0/modules/syndication/" xmlns:slash="http://purl.org/rss/1.0/modules/slash/" xmlns:media="http://search.yahoo.com/mrss/">
<channel>
    <title><?php echo xml_convert($feed_name); ?></title>
    <link><?php echo $base_url; ?></link>
	<atom:link href="<?php echo $feed_url; ?>" rel="self" type="application/rss+xml" />
    <description><?php echo convert_to_xml_character(xml_convert($page_description)); ?></description>
    <dc:language><?php echo $page_language; ?></dc:language>
	<sy:updatePeriod>hourly</sy:updatePeriod>
	<sy:updateFrequency>1</sy:updateFrequency>
    
<?php 

foreach ($posts as $post): ?>

    <item>
        <title><![CDATA[<?php echo convert_to_xml_character(xml_convert($post->title)); ?>]]></title>
        <link><![CDATA[<?php echo generate_post_url($post); ?>]]></link>
		<pubDate><![CDATA[ <?php echo date('r', strtotime($post->created_at)); ?>]]></pubDate>
		<?php if(isset($category_name)){?><category><?php echo $category_name;?></category><?php }?>
		
        <guid><?php echo generate_post_url($post); ?></guid>
        <description><![CDATA[ <?php echo convert_to_xml_character(xml_convert($post->content)); ?> ]]></description>
<?php
if (!empty($post->image_url)):
$image_path = str_replace('https://', 'http://', $post->image_url); ?>
        <enclosure url="<?php echo $image_path; ?>" length="49398" type="image/jpeg"/>
<?php else:
$image_path = base_url() . $post->image_big;
if (!empty($image_path)) {
$file_size = @filesize(FCPATH . $post->image_big);
}
$image_path = str_replace('https://', 'http://', $image_path);
if(empty($file_size) || $file_size<1){
    $file_size=49398;
}
if (!empty($image_path)):?>

	<media:content url='<?php echo $image_path; ?>' type='image/jpg' expression='full' width='538' height='190'>
	<media:description type='plain'><![CDATA[ <?php echo $post->title;?> ]]></media:description>
	<media:credit role='author' scheme='urn:ebu'>News Drum</media:credit>
	</media:content>
	
        
<?php endif;
endif; ?>
        <author>info@newsdrum.in (<?php echo convert_to_xml_character($post->author_username); ?>)</author>
        <media:thumbnail url="<?php echo $image_path; ?>" width="220"/>
    </item>
<?php endforeach; ?>
    </channel>
</rss>