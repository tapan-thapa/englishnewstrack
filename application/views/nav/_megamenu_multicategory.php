<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $category = get_category($category_id, $this->categories);
if (!empty($category)):
    //$category_posts = get_posts_by_category_id($category_id, $this->categories); 
    ?>
    <li class="dropdown megamenu-fw mega-li-<?php echo $category->id; ?> <?php echo (uri_string() == html_escape($category->name_slug)) ? 'active' : ''; echo (uri_string() == '' && $category_id == 1) ? ' active' : '';  ?>">
        <a title="<?php echo html_escape($category->name); ?>" href="<?php if($category_id != 1){ echo generate_category_url($category);}else{ echo "/";} ?>" class="dropdown-toggle disabled" data-toggle="dropdown" role="button" aria-expanded="false"><?php echo html_escape($category->name); ?> <!--<span class="caret"></span>--></a>
    </li>
<?php endif; ?>