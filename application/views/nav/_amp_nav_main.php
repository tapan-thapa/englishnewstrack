<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $menu_limit = $this->general_settings->menu_limit; ?>
<nav class="navbar navbar-default main-menu megamenu">
         <div class="navbar-collapse">
            <div class="row">
                <ul class="nav navbar-nav">
                    
                    <?php
                    $total_item = 0;
                    $i = 1;
                    if (!empty($this->menu_links)):
                        foreach ($this->menu_links as $item):
                            if ($item->item_visibility == 1 && $item->item_location == "main" && $item->item_parent_id == "0"):
                                if ($i < $menu_limit):
                                     if ($item->item_type == "category") {
                                        $category = get_category($item->item_id, $this->categories);
                                           ?>
										<li class="dropdown megamenu-fw mega-li-<?php echo $category->id; ?> <?php echo (uri_string() == html_escape($category->name_slug)) ? 'active' : ''; ?>">
										<a href="<?php echo generate_category_url($category); ?>" class="dropdown-toggle disabled" data-toggle="dropdown" role="button" aria-expanded="false"><?php echo html_escape($category->name); ?>
        </a></li>
								<?php
                                    } 
                                    $i++;
                                endif;
                                $total_item++;
                            endif;
                        endforeach;
                    endif; ?>
                    
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="li-search">
                        <a class="search-icon"><i class="icon-search"></i></a>
                        <div class="search-form">
						
						<form action="/search" method="get" id="search_validate" target="_top">
                           
                           
                            <input type="text" name="q" maxlength="300" pattern=".*\S+.*" class="form-control form-input" placeholder="<?= trans("placeholder_search"); ?>" <?= ($this->rtl == true) ? 'dir="rtl"' : ''; ?> required>
                            <button class="btn btn-default"><i class="icon-search"></i></button>
                             </form>
                        </div>
                    </li>
                </ul>
            </div>
        </div><!-- /.navbar-collapse -->
 </nav>