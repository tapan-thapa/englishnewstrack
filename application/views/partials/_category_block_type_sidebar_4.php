<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="col-sm-12 col-xs-12">
    <div class="row">
        <section class="OtherNews section-block-4">
            <div class="section-head">
                <h4 class="titlebg"><a title="Special" href="/special-stories">Special</a></h4>
            </div>
            <div class="OtherNews-content">

                <div class="rowCont">
                    <?php //$category_posts = get_posts_by_category_id($category->id, $this->categories);
                    $i = 0;
                    if (!empty($special_posts)) :
                        foreach ($special_posts as $post) :
                            if ($i < 3) : ?>
                                <?php $this->load->view("post/_post_item_mid", ["post" => $post, "type" => "featured"]); ?>
                    <?php endif;
                            $i++;
                        endforeach;
                    endif; ?>
                </div>
                <div class="viewall text-center"><a title="View All" href="/special-stories">View All <i class="icon-arrow-slider-right"></i></a></div>
            </div>
        </section>
    </div>
</div>