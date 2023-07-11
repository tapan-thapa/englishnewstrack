<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0"
     xmlns:dc="http://purl.org/dc/elements/1.1/"
     xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
     xmlns:admin="http://webns.net/mvcb/"
     xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
     xmlns:content="http://purl.org/rss/1.0/modules/content/"
     xmlns:media="http://search.yahoo.com/mrss/"
     xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title><?php echo convert_to_xml_character(xml_convert($feed_name)); ?></title>
        <link><?php echo $feed_url; ?></link>
        <atom:link href="<?php echo current_url(); ?>" rel="self" type="application/rss+xml"></atom:link>
        <description><?php echo convert_to_xml_character(xml_convert($page_description)); ?></description>
        <language><?php echo $page_language; ?></language>
        <generator><?php echo $generator; ?></generator>
        <copyright><?php echo convert_to_xml_character(xml_convert($this->settings->copyright)); ?></copyright>
        <image>
        <url>https://english.newstrack.com/uploads/logo/logo_64208921bf114.jpg</url>
        <title>Newstrack English</title>
        <link><?php echo $generator; ?></link>
        </image>
        <?php foreach ($posts as $post): ?>

            <item>
                <title><![CDATA[ <?= $post->title; ?> ]]></title>
                <description><![CDATA[ <?= strip_tags($post->summary); ?> ]]></description>
                <image><?php echo get_post_image($post, "big"); ?></image>
                <?php if ($post->post_type == 'video'): ?>
                    <?php
                    $video_base_url = "";
                    if (!empty($post->video_path)) {
                        $video_base_url = lang_base_url();
                        if ($post->video_storage == "aws_s3") {
                            $video_base_url = $this->aws_base_url . $post->video_path;
                        }
                    } elseif (strpos($post->video_url, 'www.facebook.com') !== false) {
                        $video_base_url = $post->video_url;
                    } elseif (strpos($post->video_url, 'www.youtube.com') !== false) {
                        $video_base_url = getYoutubeEmbedUrl($post->video_url);
                    } elseif (!empty($post->video_embed_code)) {
                        $video_base_url = $post->video_embed_code;
                    }
                    ?>
                    <?php if (!empty($video_base_url)): ?>
                        <media:content type="video/mp4" url="<?= $video_base_url ?>" lang="hi"></media:content>
                    <?php endif; ?>
                <?php endif; ?>
                <atom:link href="<?php echo generate_post_url($post); ?>"></atom:link>
                <link><?php echo generate_post_url($post); ?></link>
                <guid isPermaLink="true"><?php echo generate_post_url($post); ?></guid>
                <author><?php echo convert_to_xml_character($post->author_username); ?></author>
                <pubDate><?php echo date('l, F j, Y h:i A', strtotime($post->created_at)); ?></pubDate>
                <copyright><?php echo convert_to_xml_character(xml_convert($this->settings->copyright)); ?></copyright>
                <language><?php echo $page_language; ?></language>
            </item>
        <?php endforeach; ?>
    </channel>
</rss>