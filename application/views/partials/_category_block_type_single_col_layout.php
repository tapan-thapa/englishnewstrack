<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php //$subcategories = get_subcategories($category->id, $this->categories); ?>
<div class="<?php echo (isset($widgetClass))?$widgetClass:"col-sm-12"; ?> col-xs-12">

          <section class="section-block-1 single_col_layout">
            <div class="section-head">
                <h4 class="title">
                    <a title="<?php echo html_escape($category->name); ?>" href="<?php echo generate_category_url($category); ?>">
                        <?php echo html_escape($category->name); ?>
                    </a>
                </h4>
                <div class="v-all"><a title="VIEW ALL" href="<?php echo generate_category_url($category); ?>" class="v-all-click"><span>VIEW ALL</span><i class="fa fa-angle-right"></i></a></div>
                <!--Include subcategories-->
                <?php //$this->load->view('partials/_block_subcategories', ['category' => $category, 'subcategories' => $subcategories]); ?>
            </div><!--End section head-->
            <div class="section-content singleRow">
                             <?php $category_posts = get_posts_by_category_id($category->id, $this->categories, $category->home_limit);
                            if (!empty($category_posts)):
                                $i = 0;
                                foreach ($category_posts as $post):
                                    if ($i < 10): ?>
                                         <?php $this->load->view("post/_post_item_small", ["post" => $post]); ?>
                                     <?php endif;
                                    $i++;
                                endforeach;
                            endif; ?>
   
            </div>
        </section>
                        </div>
 