<?php defined('BASEPATH') or exit('No direct script access allowed');

class Category_model extends CI_Model
{
    public $article_cat_url = [];
    //input values
    public function input_values()
    {
        $data = array(
            'lang_id' => $this->input->post('lang_id', true),
            'name' => $this->input->post('name', true),
            'name_slug' => $this->input->post('name_slug', true),
            'parent_id' => (int)$this->input->post('parent_id', true),
            'title' => $this->input->post('title', true),
            'description' => $this->input->post('description', true),
            'keywords' => $this->input->post('keywords', true),
            'color' => $this->input->post('color', true),
            'category_order' => $this->input->post('category_order', true),
            'home_order' => $this->input->post('home_order', true),
            'show_at_cms' => (int)$this->input->post('show_at_cms', true),
            'show_at_homepage' => $this->input->post('show_at_homepage', true),
            'home_limit' => $this->input->post('home_limit', true),
            'home_css_name' => $this->input->post('home_css_name', true),
            'show_on_menu' => $this->input->post('show_on_menu', true),
            'block_type' => $this->input->post('block_type', true),
        );
        return $data;
    }

    //add category
    public function add_category()
    {
        $data = $this->input_values();
        if (empty($data["name_slug"])) {
            //slug for title
            $data["name_slug"] = str_slug($data["name"]);
        } else {
            $data["name_slug"] = remove_special_characters($data["name_slug"], true);
        }
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->db->insert('categories', $data);
    }

    //add subcategory
    public function add_subcategory()
    {
        $data = $this->input_values();

        $category = $this->get_category($data["parent_id"]);
        if ($category) {
            $data["color"] = $category->color;
        } else {
            $data["color"] = "#0a0a0a";
        }

        if (empty($data["name_slug"])) {
            //slug for title
            $data["name_slug"] = str_slug($data["name"]);
        } else {
            $data["name_slug"] = remove_special_characters($data["name_slug"], true);
        }
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->db->insert('categories', $data);
    }

    //update slug
    public function update_slug($id)
    {
        $category = $this->get_category($id);
        if (!empty($category)) {
            if (empty($category->name_slug) || $category->name_slug == "-") {
                $data = array(
                    'name_slug' => $category->id
                );
                $this->db->where('id', $category->id);
                $this->db->update('categories', $data);
            } else {
                if ($this->check_slug_exists($category->name_slug, $category->id) == true) {
                    $data = array(
                        'name_slug' => $category->name_slug . "-" . $category->id
                    );
                    $this->db->where('id', $id);
                    $this->db->update('categories', $data);
                }
            }
        }
    }

    //check slug
    public function check_slug_exists($slug, $id)
    {
        $sql = "SELECT * FROM categories WHERE categories.name_slug = ? AND categories.id != ? and lang_id=?";
        $query = $this->db->query($sql, array(clean_str($slug), clean_number($id), clean_number($this->selected_lang->id)));
        if (!empty($query->row())) {
            return true;
        }
        return false;
    }

    //get category
    public function get_category($id)
    {
        $sql = "SELECT categories.*, (SELECT name_slug FROM categories AS tbl_categories WHERE tbl_categories.id = categories.parent_id) as parent_slug FROM categories WHERE categories.id =  ?";
        $query = $this->db->query($sql, array(clean_number($id)));
        return $query->row();
    }

    //get category by slug
    public function get_category_by_slug($slug,$lang_id = 1)
    {
        $sql = "SELECT categories.*, (SELECT name_slug FROM categories AS tbl_categories WHERE tbl_categories.id = categories.parent_id) as parent_slug FROM categories WHERE lang_id=? AND categories.name_slug =  ?";
        $query = $this->db->query($sql, array(clean_number($lang_id),clean_str($slug)));
        return $query->row();
    }

    //get category by slug
    /* public function get_parent_cats_by_slug($slug)
    {
        $sql = "WITH RECURSIVE parents AS ( SELECT * FROM categories WHERE name_slug = ? UNION ALL SELECT c.* FROM categories c JOIN parents p ON p.parent_id = c.id) SELECT * FROM parents";
        $query = $this->db->query($sql, array(clean_str($slug)));
        $rows = $query->row();
        if(is_object($rows)){
            foreach($rows as $val){
                
            }
        }
    } */

    //get parent categories
    public function get_parent_categories()
    {
        $query = $this->db->query("SELECT * FROM categories WHERE categories.parent_id = 0 ORDER BY created_at DESC");
        return $query->result();
    }

    //get parent categories by lang
    public function get_parent_categories_by_lang($lang_id)
    {
        return $this->db->where('parent_id', 0)->where('lang_id', clean_number($lang_id))->order_by('name')->get('categories')->result();
    }

    //get categories
    public function get_categories()
    {
        $query = $this->db->query("SELECT categories.*, (SELECT name_slug FROM categories AS tbl_categories WHERE tbl_categories.id = categories.parent_id) as parent_slug FROM categories ORDER BY category_order");
        return $query->result();
    }

    //get categories by lang
    public function get_categories_by_lang($lang_id)
    {
        $sql = "SELECT categories.*, (SELECT name_slug FROM categories AS tbl_categories WHERE tbl_categories.id = categories.parent_id) as parent_slug FROM categories WHERE categories.lang_id =  ? ORDER BY category_order";
        $query = $this->db->query($sql, array(clean_number($lang_id)));
        return $query->result();
    }

    //get subcategories
    public function get_subcategories()
    {
        $query = $this->db->query("SELECT * FROM categories WHERE categories.parent_id != 0");
        return $query->result();
    }

    //get subcategories by lang
    public function get_subcategories_by_lang($lang_id)
    {
        $sql = "SELECT * FROM categories WHERE categories.parent_id != 0 AND categories.lang_id =  ?";
        $query = $this->db->query($sql, array(clean_number($lang_id)));
        return $query->result();
    }

    //get subcategories by parent id
    public function get_subcategories_by_parent_id($parent_id)
    {
        return $this->db->where('parent_id', clean_number($parent_id))->order_by('name')->get('categories')->result();
    }

    //get category count
    public function get_category_count()
    {
        $sql = "SELECT COUNT(categories.id) AS count FROM categories";
        $query = $this->db->query($sql);
        return $query->row()->count;
    }

    //update category
    public function update_category($id)
    {
        $id = clean_number($id);
        $data = $this->input_values();
        if (empty($data["name_slug"])) {
            //slug for title
            $data["name_slug"] = str_slug($data["name"]);
        } else {
            $data["name_slug"] = remove_special_characters($data["name_slug"], true);
        }

        $category = $this->get_category($id);
        //check if parent
        if ($category->parent_id == 0) {
            $this->update_subcategories_color($id, $data["color"]);
        } else {
            $parent = $this->get_category($data["parent_id"]);
            if ($parent) {
                $data["color"] = $parent->color;
            } else {
                $data["color"] = "#0a0a0a";
            }
        }
        //pr($data);die;
        $this->db->where('id', $id);
        return $this->db->update('categories', $data);
    }

    //update subcategory color
    public function update_subcategories_color($parent_id, $color)
    {
        $categories = $this->get_subcategories_by_parent_id($parent_id);
        if (!empty($categories)) {
            foreach ($categories as $item) {
                $data = array(
                    'color' => $color,
                );
                $this->db->where('parent_id', $parent_id);
                return $this->db->update('categories', $data);
            }
        }
    }

    //delete category
    public function delete_category($id)
    {
        $category = $this->get_category($id);
        if (!empty($category)) {
            $this->db->where('id', $category->id);
            return $this->db->delete('categories');
        } else {
            return false;
        }
    }
    public function get_all_categories($lang_id)
    {
        return $this->db->where('lang_id', clean_number($lang_id))->order_by('name')->get('categories')->result();
    }
    //get parent categories
    public function getParentCategoriesById($parent_id = 0)
    {
        $query = $this->db->query("SELECT * FROM categories WHERE categories.parent_id = $parent_id ORDER BY created_at DESC");
        return $query->result();
    }
    public function getParentListBarById($parent_id = 0)
    {
        $query = $this->db->query("WITH RECURSIVE parents AS
        ( SELECT id, name, parent_id
          FROM categories
          WHERE id = $parent_id
          UNION ALL
          SELECT c.id, c.name, c.parent_id
          FROM categories c
          JOIN parents p
          ON p.parent_id = c.id
        ) SELECT * FROM parents;");
        $data = $query->result();
        $bar = [];
        if(is_array($data) && count($data)){
            krsort($data);
            foreach ($data as $key => $value) {
                $bar[] = '<a href="'.admin_url().'categories/'.html_escape($value->id).'">'.$value->name.'</a>';
            } 
        }
         
        return implode(" -> ",$bar);
    }
    public function categoryTree($lang_id,$flag=""){
        if($flag == "all"){
            $parent_categories = $this->db->where('lang_id', clean_number($lang_id))->order_by('name_slug')->get('categories')->result();
        }else{
            $parent_categories = $this->db->where('lang_id', clean_number($lang_id))->where('show_at_cms',1)->order_by('name_slug')->get('categories')->result();
        }
        
        $items = [];
        foreach($parent_categories as $key=>$val){
            $items[$val->id] =  array('id'=>$val->id,'parent_id'=>$val->parent_id,'name'=>$val->name);
        }
        $childs = [];
        foreach ($items as &$item) {
            $childs[$item['parent_id']][$item['id']] = &$item;
        }
        unset($item);
        foreach ($items as &$item) {
            if (isset($childs[$item['id']])) {
                $item['childs'] = $childs[$item['id']];
            }
        }
        $list = $childs[0];
        $finalList = [];
        foreach ($list as $value) {
            $finalList[] = ['name'=>$value['name'],'id'=>$value['id']];
            if(isset($value['childs'])){
                foreach ($value['childs'] as $value2) {
                    $finalList[] = ['name'=>$value['name'].'->'.$value2['name'],'id'=>$value2['id']];
                    if(isset($value2['childs'])){
                        foreach ($value2['childs'] as $value3) {
                            $finalList[] = ['name'=>$value['name'].'->'.$value2['name'].'->'.$value3['name'],'id'=>$value3['id']];
                            /* if(isset($value3['childs'])){
                                foreach ($value3['childs'] as $value4) {
                                    $finalList[] = ['name'=>$value['name'].'->'.$value2['name'].'->'.$value3['name'].'->'.$value4['name'],'id'=>$value4['id']];
                                }
                            } */
                        }
                    }
                }
            }
        }
        return $finalList;
    }
    public function getLastLevelCategory($lang_id){
        $parent_categories = $this->get_all_categories($lang_id);
        $items = [];
        foreach($parent_categories as $key=>$val){
            $items[$val->id] =  array('id'=>$val->id,'parent_id'=>$val->parent_id,'name'=>$val->name);
        }
        $childs = [];
        foreach ($items as &$item) {
            $childs[$item['parent_id']][$item['id']] = &$item;
        }
        unset($item);
        foreach ($items as &$item) {
            if (isset($childs[$item['id']])) {
                $item['childs'] = $childs[$item['id']];
            }
        }
        $list = $childs[0];
        $finalList = [];
        foreach ($list as $value) {
            if(isset($value['childs'])){
                foreach ($value['childs'] as $value2) {
                    if(isset($value2['childs'])){
                        foreach ($value2['childs'] as $value3) {
                            if(isset($value3['childs'])){
                                foreach ($value3['childs'] as $value4) {
                                    $finalList[] = ['name'=>$value['name'].'->'.$value2['name'].'->'.$value3['name'].'->'.$value4['name'],'id'=>$value4['id']];
                                }
                            }else{
                                $finalList[] = ['name'=>$value['name'].'->'.$value2['name'].'->'.$value3['name'],'id'=>$value3['id']];
                            }
                        }
                    }else{
                        $finalList[] = ['name'=>$value['name'].'->'.$value2['name'],'id'=>$value2['id']];
                    }
                }
            }else{
                $finalList[] = ['name'=>$value['name'],'id'=>$value['id']];
            }
        }
        return $finalList;
    }
    public function makeArticleCatURL($id){
        $sql = "SELECT name,name_slug,parent_id,color FROM categories WHERE name_slug<>'state' and categories.id = ".$id;
        $query = $this->db->query($sql);
        $catObj = $query->row();
        if(!empty($catObj->parent_id)){
            $this->article_cat_url[] = $catObj->name_slug; 
            $this->makeArticleCatURL($catObj->parent_id);
        }elseif(!empty($catObj->name_slug)){
            $this->article_cat_url[] = $catObj->name_slug;
        }
        return array_reverse($this->article_cat_url);
    }
}
