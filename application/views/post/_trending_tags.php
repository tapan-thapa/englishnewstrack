<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!--News Ticker-->
<div class="post-tags-box">
        <div class="post-tags">
            <h2 class="tags-title"><?php echo $tag_lable; ?></h2>
                    <ul class="tag-list">
                    <?php foreach ($trending_tags as $tag) : ?>
                        <li>
                            <?php if($tag_type=='quick_links'){ ?>
                                <a title="<?php echo $tag->tag; ?>" href="<?php echo $tag->tag_slug ?>" class="tags-chip"><?php echo $tag->tag; ?></a>
                            <?php }else{ ?>
                                <a  title="<?php echo $tag->tag; ?>" href="<?php echo generate_tag_url($tag->tag_slug); ?>" class="tags-chip"><?php echo $tag->tag; ?></a>
                            <?php } ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
        </div>


            <div class="addthis_inline_follow_toolbox"></div> 


                

    </div>          
 