<?php echo '<?xml version="1.0" encoding="UTF-8" ?>' . "\n"; ?>
<?php echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">'. "\n";?>
	<?php 

foreach ($posts as $post): ?>
	<url>
	<loc><![CDATA[<?php echo generate_post_url($post); ?>]]>
	</loc>
	<news:news>
	<news:publication>
	<news:name><![CDATA[ News Drum ]]></news:name>
	<news:language><![CDATA[ en ]]></news:language>
	</news:publication>
	<news:publication_date><![CDATA[ <?php echo date('Y-m-d', strtotime($post->created_at)) . "T" . date('H:i:s', strtotime($post->created_at))."+05:30 "; ?> ]]></news:publication_date>
	<news:title><?php echo convert_to_xml_character(xml_convert($post->title)); ?> </news:title>
	<news:keywords>
	<?php echo convert_to_xml_character(xml_convert($post->title)); ?>
	</news:keywords>
    </news:news>
		</url>
		<?php endforeach; ?>
		</urlset>