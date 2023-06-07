<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php //$subcategories = get_subcategories($category->id, $this->categories); ?>
<div class="col-sm-12 col-xs-12">
    <div class="row">
        <section class="webstorysection section-block-4">
            <div class="section-head">
                <h4 class="title" >
                    <a title="<?php echo html_escape($category->name); ?>" href="<?php echo generate_category_url($category); ?>"><?php echo html_escape($category->name); ?></a>
                </h4>
                         </div>
            <div class="section-content">
                 
                        <div class="rowCont">
                            <?php 
                            $category_posts = get_posts_by_category_id($category->id, $this->categories, $category->home_limit);
                            $i = 0;
                            if (!empty($category_posts)):
                                foreach ($category_posts as $post):
                                    /* if ($i < 3): */?>
                                                     <?php $this->load->view("post/_post_item_mid_photo_stories", ["post" => $post,"type" => 'webstory']); ?>
                                                 
                                     <?php /* endif; */
                                    $i++;
                                endforeach;
                            endif; ?>
                    
 

                </div>
 
            </div>


        </section>
 
    </div>
</div>


