<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php //$subcategories = get_subcategories($category->id, $this->categories); 
?>
<div class="<?php echo (isset($widgetClass))?$widgetClass:"col-sm-12"; ?> col-xs-12">
    <div class="row">
        <section class="section section-block-3 commonBlock">
            <div class="section-head">
                <h4 class="title">
                    <a title="<?php echo html_escape($category->name); ?>" href="<?php echo generate_category_url($category); ?>"><?php echo html_escape($category->name); ?></a>
                </h4>
                <div class="v-all"><a href="<?php echo generate_category_url($category); ?>" class="v-all-click"><span>VIEW ALL</span><i class="fa fa-angle-right"></i></a></div>
            </div>
            <div class="section-content">
                <div class="row">
                    <div class="col-sm-4 col-xs-12">
                        <?php
                        $category_posts = get_posts_by_category_id($category->id, $this->categories, $category->home_limit);
                        $c = 0;
                        $otherArticleArr = [];
                        if (is_array($category_posts) && count($category_posts)) {
                            foreach ($category_posts as $post) {
                                if (!in_array($post->id, $ex_ids)) {
                                    if ($c <= 1) {
                                        $this->load->view("post/_post_item", ["post" => $post]);
                                    } else {
                                        $otherArticleArr[] = $post;
                                    }
                                    $c++;
                                }
                            }
                        }
                        ?>
                    </div>
                    <div class="col-sm-8 col-xs-12">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12 postsectionsec">
                                <?php
                                    foreach ($otherArticleArr as $post) {
                                        $this->load->view("post/_post_item_small", ["post" => $post]);
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>
</div>