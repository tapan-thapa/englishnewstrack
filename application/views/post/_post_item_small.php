<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
 <!--Post item small-->
<div class="post-item-small<?php echo check_post_img($post, 'class'); ?>">
                 <div class="left">
                    <a title="<?php echo html_escape($post->title); ?>"  href="<?php echo generate_post_url($post); ?>"<?php post_url_new_tab($this, $post); ?>>
                        <?php $this->load->view("post/_post_image", ["post_item" => $post, "type" => "medium"]); ?>
                    </a>
                </div>
 
            <div class="right">
                <?php if(isset($post->topic)){?>
                <a  title="<?php echo html_escape($post->topic); ?>" href="<?php echo generate_post_url($post); ?>">
                    <span class="category-label"><?php echo html_escape($post->topic); ?></span>
                </a>
                <?php }?>
                    <h3 class="title">
                        <a title="<?php echo html_escape($post->title); ?>"  href="<?php echo generate_post_url($post); ?>"<?php post_url_new_tab($this, $post); ?>>
                            <?php if(empty($post->headline)){ echo html_escape($post->title);}else{ echo html_escape($post->headline);}  ?>
                        </a>
                    </h3>
					 <div class="post-share">
					 <!--include Social Share -->
						<?php $this->load->view('post/_post_share_box'); ?>
						</div>		
                    <!--<p class="small-post-meta">
                        <?php //$this->load->view("post/_post_meta", ["post" => $post]); ?>
                    </p>-->
            </div>
        </div>
 