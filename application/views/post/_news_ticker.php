<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
    <!--News Ticker-->
<?php if ($this->general_settings->show_newsticker == 1 && count($breaking_news) > 0): ?>
    <div class="news-ticker-cnt">
             <div class="left">
                <span class="news-ticker-title font-second"><a title=" <?php echo html_escape($breaking_news[0]->title); ?>" href="<?= lang_base_url(); ?>live-updates"><?php echo trans("breaking_news"); ?></a></span>
            </div>
            <div class="right">
                <div class="news-ticker">
                    <ul class="newsticker_new">
                        <?php 
						//foreach ($breaking_news as $post): ?>
                            <li>
                                    <?php
                                        if(!empty($breaking_news[0]->url) ){
                                    ?>
                                        <a href="<?php echo $breaking_news[0]->url; ?>">
                                            <?php echo html_escape($breaking_news[0]->title); ?>
                                        </a>
                                    <?php 
                                        }else{
                                    ?>
                                         <a title=" <?php echo html_escape($breaking_news[0]->title); ?>" href="<?= lang_base_url(); ?>live-updates">
                                            <?php echo html_escape($breaking_news[0]->title); ?>
                                        </a>
                                    <?php
                                        }
                                    ?>
                                
                            </li>
                        <?php //endforeach; ?>
                    </ul>
                </div>
            </div>
            <!--<div class="news-ticker-btn-cnt">
                <a href="javascript:void(0)" id="btn_newsticker_prev" class="bnt-news-ticker news-prev"><span class="icon-arrow-left"></span></a>
                <a href="javascript:void(0)" id="btn_newsticker_next" class="bnt-news-ticker news-next"><span class="icon-arrow-right"></span></a>
            </div>-->
     </div>
<?php else: ?>
    <div class="col-sm-12 news-ticker-sep"></div>
<?php endif; ?>