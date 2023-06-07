<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php //$subcategories = get_subcategories($category->id, $this->categories); ?>
<div class="container">
<div class="<?php echo (isset($widgetClass))?$widgetClass:"col-sm-12"; ?> col-xs-12">
    <div class="row">
        <section class="sectionSlider section-block-4">
            <div class="section-head">
                <h4 class="title" >
                    <a title="<?php echo html_escape($category->name); ?>" href="<?php echo generate_category_url($category); ?>"><?php echo html_escape($category->name); ?></a>
                </h4>
                <div class="v-all"><a title="VIEW ALL" href="<?php echo generate_category_url($category); ?>" class="v-all-click"><span>VIEW ALL</span><i class="fa fa-angle-right"></i></a></div>
             
                <!--Include subcategories-->
                <?php //$this->load->view('partials/_block_subcategories', ['category' => $category, 'subcategories' => $subcategories]); ?>
            </div>
            <div class="section-content">
                 
            <div class="slick-slider">
                            <?php 
                            $category_posts = get_posts_by_category_id($category->id, $this->categories, $category->home_limit);
                            $i = 0;
                            if (!empty($category_posts)):
                                foreach ($category_posts as $post):
                                    /* if ($i < 3): */?>
                                              <div class="listItemCont">
                                                    <?php $this->load->view("post/_post_item_mid", ["post" => $post]); ?>
                                                </div>
                                     <?php /* endif; */
                                    $i++;
                                endforeach;
                            endif; ?>
                    

                 

                </div>
                <!-- <div class="viewall text-center"><a href="/special-stories">View All <i class="icon-arrow-slider-right"></i></a></div> -->

            </div>


        </section>
    </div>
</div>
                </div>