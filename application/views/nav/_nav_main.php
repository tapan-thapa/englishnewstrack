<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php if (!function_exists('user_session')) {
    exit();
}
$menu_limit = $this->general_settings->menu_limit; ?>
<nav class="navbar navbar-default main-menu megamenu menuhide">
         <div class="navbar-collapse">
            <div class="row">
                <ul class="nav navbar-nav">
                    <?php if ($this->general_settings->show_home_link == 1): ?>
                         <li class="<?= (uri_string() == 'index' || uri_string() == "") ? 'active' : ''; ?>">
                            <a title="<?= trans("home"); ?>" href="<?= lang_base_url(); ?>">
                                <?= trans("home"); ?>
                            </a>
                        </li> 
                    <?php endif; ?>
                    <?php
                    $total_item = 0;
                    $i = 1;
                    if (!empty($this->menu_links)):
                        foreach ($this->menu_links as $item):
                            if ($item->item_visibility == 1 && $item->item_location == "main" && $item->item_parent_id == "0"):
                                if ($i < $menu_limit):
                                    $sub_links = get_sub_menu_links($this->menu_links, $item->item_id, $item->item_type);
                                    if ($item->item_type == "category") {
                                        if (!empty($sub_links)) {
                                            $this->load->view('nav/_megamenu_multicategory', ['category_id' => $item->item_id]);
                                        } else {
                                            $this->load->view('nav/_megamenu_singlecategory', ['category_id' => $item->item_id]);
                                        }
                                    } else {
                                        if (!empty($sub_links)): ?>
                                            <li class="dropdown <?= (uri_string() == $item->item_slug) ? 'active' : ''; (uri_string() == 'index' || uri_string() == "") ? 'active' : '';?> ">
                                                <a title="<?= html_escape($item->item_name); ?>" class="dropdown-toggle disabled no-after" data-toggle="dropdown" href="<?= generate_menu_item_url($item); ?>">
                                                    <?= html_escape($item->item_name); ?>
                                                    <span class="caret"></span>
                                                </a>
                                                <ul class="dropdown-menu dropdown-more dropdown-top">
                                                    <?php foreach ($sub_links as $sub_item): ?>
                                                        <?php if ($sub_item->item_visibility == 1): ?>
                                                            <li>
                                                                <a title="<?= html_escape($sub_item->item_name); ?>" role="menuitem" href="<?= generate_menu_item_url($sub_item); ?>">
                                                                    <?= html_escape($sub_item->item_name); ?>
                                                                </a>
                                                            </li>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </li>
                                        <?php else: ?>
                                            <li class="<?= (uri_string() == $item->item_slug) ? 'active' : ''; ?>">
                                                <a title="<?= html_escape($item->item_name); ?>" href="<?= generate_menu_item_url($item); ?>">
                                                    <?= html_escape($item->item_name); ?>
                                                </a>
                                            </li>
                                        <?php endif;
                                    }
                                    $i++;
                                endif;
                                $total_item++;
                            endif;
                        endforeach;
                    endif; ?>
                    <?php if ($total_item >= $menu_limit): ?>
                        <li class="dropdown relative">
                            <a class="dropdown-toggle dropdown-more-icon" data-toggle="dropdown" href="#">
                                <i class="icon-ellipsis-h"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-more dropdown-top">
                                <?php $i = 1;
                                if (!empty($this->menu_links)):
                                    foreach ($this->menu_links as $item):
                                        if ($item->item_visibility == 1 && $item->item_location == "main" && $item->item_parent_id == "0"):
                                            if ($i >= $menu_limit):
                                                $sub_links = get_sub_menu_links($this->menu_links, $item->item_id, $item->item_type);
                                                if (!empty($sub_links)): ?>
                                                    <li class="dropdown-more-item">
                                                        <a title="<?= html_escape($item->item_name); ?>" class="dropdown-toggle disabled" data-toggle="dropdown" href="<?= generate_menu_item_url($item); ?>">
                                                            <?= html_escape($item->item_name); ?> <span class="icon-arrow-right"></span>
                                                        </a>
                                                        <ul class="dropdown-menu dropdown-sub">
                                                            <?php foreach ($sub_links as $sub_item): ?>
                                                                <?php if ($sub_item->item_visibility == 1): ?>
                                                                    <li>
                                                                        <a title="<?= html_escape($sub_item->item_name); ?>" role="menuitem" href="<?= generate_menu_item_url($sub_item); ?>">
                                                                            <?= html_escape($sub_item->item_name); ?>
                                                                        </a>
                                                                    </li>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    </li>
                                                <?php else: ?>
                                                    <li>
                                                        <a title="<?= html_escape($item->item_name); ?>" href="<?= generate_menu_item_url($item); ?>">
                                                            <?= html_escape($item->item_name); ?>
                                                        </a>
                                                    </li>
                                                <?php endif;
                                            endif;
                                            $i++;
                                        endif;
                                    endforeach;
                                endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
                
            </div>
        </div><!-- /.navbar-collapse -->
 </nav>