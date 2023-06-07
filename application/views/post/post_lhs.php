<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div id="content" class="contentElement_<?php echo $post->id; ?>" data-posturl="<?php echo site_url().$post->category_slug."/". $post->title_slug."-".$post->id; ?>">
    <div id="topcontentElement_<?php echo $post->id; ?>"></div>
    <div class="post-content">
        <a title="<?php echo html_escape($post->topic); ?>" href="<?php echo generate_topic_url(str_slug(trim($post->topic))); ?>"><span class="category-label BCatItem"><?php echo html_escape($post->topic); ?></span></a>

        <?php
            if(isset($ajax_load) && $ajax_load == 1){
        ?>
            <h1 class="title"> <?php if($post->post_type == "live_post"){ ?><span class="liveLink">Live |</span> <?php } ?><a href="<?php echo site_url().$post->category_slug."/". $post->title_slug."-".$post->id; ?>"> <?php echo html_escape($post->title); ?> </a></h1>
         <?php
            }else{
        ?>
            <h1 class="title"> <?php if($post->post_type == "live_post"){ ?><span class="liveLink">Live |</span> <?php } ?><?php echo html_escape($post->title); ?></h1>
        <?php
            }
        ?>
        
        <?php if (!empty($post->summary)) : ?>
            <div class="post-summary">
                <h2> <?php echo $post->summary; ?></h2>
            </div>
        <?php endif; ?>
        
        <div class="adsCont Mobileads  postphp">
<!-- /22212039110/NWT_mWeb_AllUnits/NWT_mWeb_Story_Header_Multisize -->
<div id='div-gpt-ad-1668419187969-0' style='min-width: 300px; min-height: 250px;'>
  <script>
    googletag.cmd.push(function() { googletag.display('div-gpt-ad-1668419187969-0'); });
  </script>
</div>
</div>

        <div class="post-meta">
            <?php if ($this->general_settings->show_post_author == 1 && isset($post_user->slug) && isset($post_user->username)) { ?>
                <span class="post-author-meta sp-left">
                    <a title="<?php echo html_escape($post_user->username); ?>" href="<?php echo generate_profile_url($post_user->slug); ?>" class="m-r-0">
                        <img src="<?php echo get_user_avatar($post_user->avatar); ?>" alt="<?php echo html_escape($post_user->username); ?>">
                        <?php echo html_escape($post_user->username); ?>
                    </a>
                </span>
            <?php } ?>

            <div class="post-details-meta-date">
                <?php if ($this->general_settings->show_post_date == 1) : ?>
                    <!-- <span class="sp-left"><?php //echo helper_date_format($post->created_at); ?>&nbsp;-&nbsp;<?php //echo formatted_hour($post->created_at); ?></span> -->
                    <?php if (!empty($post->updated_at) && !empty($post->created_at) && $post->updated_at == $post->created_at) { ?>
                        <span class="sp-left sp-post-update-date"><?php echo trans("publish_time"); ?>:&nbsp;<?php echo helper_date_format($post->created_at); ?>&nbsp;-&nbsp;<?php echo formatted_hour($post->created_at); ?></span>
                    <?php }elseif (!empty($post->updated_at)) { ?>
                        <span class="sp-left sp-post-update-date"><?php echo trans("publish_time"); ?>:&nbsp;<?php echo helper_date_format($post->created_at); ?> | <?php echo trans("updated_time"); ?>:&nbsp;<?php echo helper_date_format($post->updated_at); ?>&nbsp;-&nbsp;<?php echo formatted_hour($post->updated_at); ?></span>
                    <?php } ?>
                <?php endif; ?>
            </div>

            <!--<div class="post-comment-pageviews">
                            <?php if ($this->general_settings->comment_system == 1) : ?>
                                <span class="comment"><i class="icon-comment"></i><?php echo $post->comment_count; ?></span>
                            <?php endif; ?>
                            <?php if ($this->general_settings->show_hits) : ?>
                                <span><i class="icon-eye"></i><?php echo $post->pageviews; ?></span>
                            <?php endif; ?>
                        </div>-->
        </div>

        <?php if ($post->post_type == "video") :
            $this->load->view('post/details/_video', ['post' => $post]);
        elseif ($post->post_type == "audio") :
            $this->load->view('post/details/_audio', ['post' => $post]);
        elseif ($post->post_type == "gallery") :
            $this->load->view('post/details/_gallery', ['post' => $post]);

        elseif ($post->post_type == "live_post") :
            $this->load->view('post/details/_live_post', ['post' => $post]);

        elseif ($post->post_type == "webstory") :
            $this->load->view('post/details/_webstory', ['post' => $post]);

        elseif ($post->post_type == "sorted_list") :
            $this->load->view('post/details/_sorted_list', ['post' => $post]);
        elseif ($post->post_type == "trivia_quiz" || $post->post_type == "personality_quiz") :
            $this->load->view('post/details/_quiz', ['post' => $post]);
        else :
            $this->load->view('post/details/_article', ['post' => $post]);
        endif; ?>
        <?php $this->load->view("partials/_ad_spaces", ["ad_space" => "post_top", "class" => "bn-p-t-20"]); ?>



        <?php
        if ($post->post_type != "webstory"){
        ?>
        <div class="post-share post-share-top">
            <?php
                if($this->selected_lang->id == 2){
            ?>
                <div class="addthis_inline_share_toolbox" style="border-right:0px!important"></div>
            <?php
            }else{
            ?>
            <div class="addthis_inline_share_toolbox" style="border-right:0px!important"></div>
            <div class="google-news-social-icon"><span>Follow us on</span><a title="Follow us on" href="https://news.google.com/publications/CAAqBwgKMIrnjQsw8J6gAw?hl=hi&amp;gl=IN&amp;ceid=IN%3Ahi">
                    <img src="../assets/img/followus.png" width="35" /></a></div>
            <?php
            }
            ?>
            <!--include Social Share -->
            <?php $this->load->view('post/_post_share_box'); ?>
        </div>
        <?php
        }
        ?>

        <div class="post-text">
            <?php
            preg_match_all('/<a .*?>(.*?)<\/a>/', $post->content, $matches);
            //preg_match_all('@https?://(www\.)?newsdrum.in+@i', $post->content, $matches);
            //print_r($matches);
            if (isset($matches[0][0])) {
                preg_match_all('@https?://(www\.)?newsdrum.in+@i', $post->content, $match);
                if (isset($match[0][0])) {
                    //echo "in--------";
                } else {
                    //echo "else";
                    $post->content = str_replace('<a ', '<a rel="nofollow" ', $post->content);
                }
            }
            //echo $post->content; 
            ?>

            <?php if (!empty($post->content)) {

                $content = explode("</p>", $post->content);
                //echo $content[0].'</p>'; 
                $mm = 0;
                $jj = 0;
                foreach ($content as $p) {
                    echo $p . "</p>";
                    if ($mm == 0 && $post->post_type != "live_post" && count($content)>1) {
                    ?>
                         <?php $this->load->view("partials/_ad_spaces", ["ad_space" => "post_bottom", "class" => "bn-p-b"]); ?>
                    <?php
                    }
                    if (($mm == 0 || $mm == 2 || $mm % 2 == 0) && !empty($keyword_Res[$jj])) {
            ?>
                        <div class="ReadMoreWidget">
                            <p><b>Also Read</b></p>
                            <ul class="ReadMoreCont popular-posts">
                                <?php
                                $slug = $keyword_Res[$jj]->title_slug . '-' . $keyword_Res[$jj]->id;
                                ?>
                                <li>
                                    <div class="left">
                                        <?php
                                        if (!empty($keyword_Res[$jj]->image_small)) {
                                        ?>
                                            <div class="left">
                                                <?php
                                                $this->load->view("post/_post_image", ["post_item" => $keyword_Res[$jj], "type" => "small"]);
                                                ?>
                                            </div>
                                        <?php
                                        } else {
                                        ?>
                                            <figure>
                                                <img src="<?php echo base_url() . 'uploads/logo/logo_640e74e924a26.png' ?>" class="img-responsive center-image" style="background: #358cc7;">
                                            </figure>
                                        <?php } ?>
                                    </div>
                                    <a title="<?php echo $keyword_Res[$jj]->title; ?> " href="<?php echo $slug; ?>"><?php echo $keyword_Res[$jj]->title; ?> </a>
                                </li>
                            </ul>
                        </div>
                    <?php
                        $jj++;
                    }
                    $mm++;
                }
                if(!empty($keyword_Res) && is_array($keyword_Res)){
                for ($kk = $jj; $kk < count($keyword_Res); $kk++) {
                    ?>
                    <div class="ReadMoreWidget">
                        <!-- <p><b>Also Read</b></p> -->
                        <ul class="ReadMoreCont popular-posts">
                            <?php
                            $slug = $keyword_Res[$kk]->title_slug . '-' . $keyword_Res[$kk]->id;
                            ?>
                            <li>

                                <?php
                                if (!empty($keyword_Res[$kk]->image_small)) {
                                ?>
                                    <div class="left">
                                        <?php
                                        $this->load->view("post/_post_image", ["post_item" => $keyword_Res[$kk], "type" => "small"]);
                                        ?>
                                    </div>
                                <?php
                                } else {
                                ?>
                                    <figure>
                                        <img src="<?php echo base_url() . 'uploads/logo/logo_640e74e924a26.png' ?>" class="img-responsive center-image" style="background: #358cc7;">
                                    </figure>
                                <?php } ?>


                                <a title="<?php echo $keyword_Res[$kk]->title; ?> " href="<?php echo $slug; ?>"><?php echo $keyword_Res[$kk]->title; ?> </a>
                            </li>
                        </ul>
                    </div>
                <?php
                }
            }

                ?>



            <?php } ?>


        </div>
        
        <?php
        if ($post->post_type == "live_post") :
            $this->load->view('post/details/_live_post_history', ['post' => $post]);
        endif;
        ?>


        <!--Optional Url Button -->
        <?php if (!empty($post->optional_url)) : ?>
            <div class="optional-url-cnt">
                <a href="<?php echo html_escape($post->optional_url); ?>" class="btn btn-md btn-custom" target="_blank" rel="nofollow">
                    <?php echo html_escape($this->settings->optional_url_button_name); ?>&nbsp;&nbsp;&nbsp;<i class="icon-long-arrow-right" aria-hidden="true"></i>
                </a>
            </div>
        <?php endif; ?>

        <!--Optional Url Button -->
        <?php if (!empty($feed) && !empty($post->show_post_url)) : ?>
            <div class="optional-url-cnt">
                <a href="<?php echo $post->post_url; ?>" class="btn btn-md btn-custom" target="_blank" rel="nofollow">
                    <?php echo htmlspecialchars($feed->read_more_button_text); ?>&nbsp;&nbsp;&nbsp;<i class="icon-long-arrow-right" aria-hidden="true"></i>
                </a>
            </div>
        <?php endif; ?>

        <?php $files = get_post_files($post->id);
        if (!empty($files)) : ?>
            <div class="post-files">
                <h2 class="title"><?php echo trans("files"); ?></h2>
                <?php echo form_open('download-file'); ?>
                <?php foreach ($files as $file) : ?>
                    <div class="file">
                        <button type="submit" name="file_id" value="<?php echo $file->id; ?>"><i class="icon-file"></i><?php echo html_escape($file->file_name); ?></button>
                    </div>
                <?php endforeach; ?>
                <?php echo form_close(); ?>
            </div>
        <?php endif; ?>


        <div class="adsCont desktopads">
<!-- /22212039110/NWT_Desk_AllUnits/NWT_Desk_Story_C_300x250 -->
<div id='div-gpt-ad-1668490341675-0' style='min-width: 300px; min-height: 250px;'>
  <script>
    googletag.cmd.push(function() { googletag.display('div-gpt-ad-1668490341675-0'); });
  </script>
</div>
</div>

<div class="adsCont Mobileads">
<!-- /22212039110/NWT_mWeb_AllUnits/NWT_mWeb_Story_C_300x250_Multisize -->
<div id='div-gpt-ad-1668419285992-0' style='min-width: 300px; min-height: 250px;'>
  <script>
    googletag.cmd.push(function() { googletag.display('div-gpt-ad-1668419285992-0'); });
  </script>
</div>
</div>


        <div class="post-tags-box">
            <div class="post-tags">
                <?php if (!empty($post_tags)) : ?>
                    <h2 class="tags-title"><?php echo trans("post_tags"); ?></h2>
                    <ul class="tag-list">
                        <?php foreach ($post_tags as $tag) : ?>
                            <li>
                                <a title="<?php echo html_escape($tag->tag); ?>" href="<?php echo generate_tag_url($tag->tag_slug); ?>">
                                    <?php echo html_escape($tag->tag); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>


    </div>

    <!--include next previous post -->
    <?php //$this->load->view('post/_post_next_prev', ['previous_post' => $previous_post, 'next_post' => $next_post]); 
    ?>

    <?php if ($this->general_settings->emoji_reactions == 1) : ?>
        <div class="col-sm-12 col-xs-12">
            <div class="row">
                <div class="reactions noselect">
                    <h4 class="title-reactions"><?php echo trans("whats_your_reaction"); ?></h4>
                    <div id="reactions_result">
                        <?php $this->load->view('partials/_emoji_reactions'); ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

  
    <!--include about author -->
    <?php
    if ($this->general_settings->show_post_author == 1) : ?>
        <?php //$this->load->view('post/_post_about_author', ['post_user' => $post_user]); 
        ?>
    <?php endif; ?>

    <div id="bottomcontentElement_<?php echo $post->id; ?>"></div>
    <?php if ($this->general_settings->comment_system == 1 || $this->general_settings->facebook_comment_active == 1) : ?>
        <section id="comments_<?php echo $post->id; ?>" class="CommentSection">
            <div class="col-sm-12 col-xs-12">
                <div class="row">
                    <div class="comment-section">
                        <?php if ($this->general_settings->comment_system == 1 || $this->general_settings->facebook_comment_active == 1) : ?>
                            <ul class="nav nav-tabs">
                                <?php if ($this->general_settings->comment_system == 1) : ?>
                                    <li class="active"><a title="<?php echo trans("comments"); ?>" data-toggle="tab" href="#site_comments"><?php echo trans("comments"); ?></a></li>
                                <?php endif; ?>
                                <?php if ($this->general_settings->facebook_comment_active == 1) : ?>
                                    <li class="<?php echo ($this->general_settings->comment_system != 1) ? 'active' : ''; ?>"><a title="<?php echo trans("facebook_comments"); ?>" data-toggle="tab" href="#facebook_comments"><?php echo trans("facebook_comments"); ?></a></li>
                                <?php endif; ?>
                            </ul>

                            <div class="tab-content">
                                <?php if ($this->general_settings->comment_system == 1) : ?>
                                    <div id="site_comments_<?php echo $post->id; ?>" class="tab-pane fade in active">
                                        <!-- include comments -->
                                        <?php $this->load->view('post/_make_comment', ['post' => $post, 'comment_count' => $post->comment_count]); ?>
                                        <div id="comment-result_<?php echo $post->id; ?>">
                                            <?php $this->load->view('post/_comments', ['post' => $post, 'comment_count' => $post->comment_count]); ?>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if ($this->general_settings->facebook_comment_active == 1) : ?>
                                    <div id="facebook_comments_<?php echo $post->id; ?>" class="tab-pane fade <?php echo ($this->general_settings->comment_system != 1) ? 'in active' : ''; ?>">
                                        <div id="div_fb_comments_<?php echo $post->id; ?>" class="fb-comments" data-href="<?php echo current_url(); ?>" data-width="100%" data-numposts="5" data-colorscheme="<?php echo $this->dark_mode == 1 ? 'dark' : 'light'; ?>"></div>
                                        <script>
                                            document.getElementById("div_fb_comments_<?php echo $post->id; ?>").setAttribute("data-href", window.location.href);
                                        </script>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php
        if ($post->post_type != "webstory"){
    ?>

    <div class="post-share post-share-btm">
        <div class="sharethis-inline-share-buttons"></div>
    </div>
    <?php
       }
    ?>
    
</div>