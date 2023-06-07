<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<rss version="2.0"
     xmlns:dc="http://purl.org/dc/elements/1.1/"
     xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
     xmlns:admin="http://webns.net/mvcb/"
     xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
     xmlns:content="http://purl.org/rss/1.0/modules/content/">
<channel>
    <title><?php echo convert_to_xml_character(xml_convert($feed_name)); ?></title>
    <link><?php echo $feed_url; ?></link>
    <description><?php echo convert_to_xml_character(xml_convert($page_description)); ?></description>
    <dc:language><?php echo $page_language; ?></dc:language>
    <dc:creator><?php echo $creator_email; ?></dc:creator>
    <dc:rights><?php echo convert_to_xml_character(xml_convert($this->settings->copyright)); ?></dc:rights>
<?php foreach ($posts as $post): ?>

    <item>
        <title><?php echo convert_to_xml_character(xml_convert($post->title)); ?></title>
        <description><![CDATA[ <?php echo html_escape($post->summary); ?> ]]></description>
        <image><![CDATA[ <?php echo get_post_image($post, "big"); ?> ]]></image>
        <content:encoded><![CDATA[ <?php echo html_escape($post->content); ?> ]]></content:encoded>
        <link><?php echo generate_post_url($post); ?></link>
        <guid isPermaLink="true"><?php echo generate_post_url($post); ?></guid>
        <category><![CDATA[ <?php echo $post->category_name; ?> ]]></category>
        <dc:creator><?php echo convert_to_xml_character($post->author_username); ?></dc:creator>
        <pubDate><?php echo date('r', strtotime($post->created_at)); ?></pubDate>
    </item>
<?php endforeach; ?>
    </channel>
</rss>