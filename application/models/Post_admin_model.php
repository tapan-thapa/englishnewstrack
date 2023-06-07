<?php defined('BASEPATH') or exit('No direct script access allowed');

class Post_admin_model extends CI_Model
{
    public $otherCategoryArr = [];
    //input values
    public function input_values($atype='',$status='')
    {
        $data = array(
            'reporter_id' => $this->input->post('reporter_id', true),
            'lang_id' => $this->input->post('lang_id', true),
			'topic' => trim($this->input->post('topic', false)),
			//'headline' => trim($this->input->post('headline', false)),
            'headline' => trim($this->input->post('title', false)),
            //'title' => trim(remove_forbidden_characters($this->input->post('title', false))),
            'title' => trim($this->input->post('title', false)),
			
           // 'title_slug' => $this->input->post('title_slug', true),
            'summary' => $this->input->post('summary', false),
			'tag_description'=> $this->input->post('tag_description', false),
            'category_id' => $this->input->post('category_id', true),
            'content' => $this->input->post('content', false),
            'optional_url' => $this->input->post('optional_url', true),
            'need_auth' => $this->input->post('need_auth', true),
            'is_slider' => $this->input->post('is_slider', true),
            'is_featured' => $this->input->post('is_featured', true),
            'is_recommended' => $this->input->post('is_recommended', true),
            'is_breaking' => $this->input->post('is_breaking', true),
            'is_live' => $this->input->post('is_live', true),
            'visibility' => $this->input->post('visibility', true),
            'show_right_column' => $this->input->post('show_right_column', true),
            'keywords' => $this->input->post('keywords', true),
            'image_description' => $this->input->post('image_description', true),
        );
		
		if($atype!='edit'){
			$data['title_slug'] = $this->input->post('title_slug', true);
		}elseif($atype=='edit' && $status!=1){
            $data['title_slug'] = $this->input->post('title_slug', true);
        }
        return $data;
    }

    //add post
    public function add_post($post_type)
    {
        $data = $this->set_data($post_type);

        if($data['post_type'] == 'webstory' || $data['post_type'] == 'short_videos'){
            $sqlArticle = "SELECT id FROM posts_".$data['post_type']." WHERE  title_slug = '".$data['title_slug']."'";
        }else{
            $sqlArticle = "SELECT id FROM posts WHERE  title_slug = '".$data['title_slug']."'";
        }
        $queryArt = $this->db->query($sqlArticle);
        $checkArticleObj = $queryArt->row();
        if(!empty($checkArticleObj)){
            return ["res"=>false,"data"=>$data,"message"=>"Post Already exists with this english title"];
        }
        $is_scheduled = $this->input->post('scheduled_post', true);
        $date_published = $this->input->post('date_published', true);
		$user_id = $this->input->post('user_id', true);
		if(empty($user_id)){
			$data['user_id'] = user()->id;
		}else{
			$data["user_id"] = $this->input->post('user_id', true);
		}

        $sql = "SELECT * FROM users WHERE users.id = ".$data['user_id'];
        $query = $this->db->query($sql);
        $user = $query->row();
        $data['author_username'] = $user->username;
        $data['author_slug'] = $user->slug;

        $data['is_scheduled'] = 0;
        if ($is_scheduled) {
            $data["is_scheduled"] = 1;
        }

        if (!empty($date_published)) {
            $data["created_at"] = $date_published;
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
        }
        $data['updated_at'] = $data['created_at'];
        $data['show_post_url'] = 0;
        $data["post_type"] = $post_type;

        if($this->input->post('post_select_key') != null){
            $data["post_select_key"] = json_encode($this->input->post('post_select_key', true));
        }
        
        $data['status'] = $this->input->post('status', true);

        //add post image
        $data["image_big"] = "";
        $data["image_default"] = "";
        $data["image_slider"] = "";
        $data["image_mid"] = "";
        $data["image_small"] = "";
        $data["image_mime"] = "jpg";
        $data["image_url"] = "";
        $data["image_storage"] = "local";
        $post_image_id = $this->input->post('post_image_id', true);
        if (!empty($post_image_id)) {
            $image = $this->file_model->get_image($post_image_id);
            if (!empty($image)) {
                $data["image_big"] = $image->image_big;
                $data["image_default"] = $image->image_default;
                $data["image_slider"] = $image->image_slider;
                $data["image_mid"] = $image->image_mid;
                $data["image_small"] = $image->image_small;
                $data["image_mime"] = $image->image_mime;
                if ($image->storage == "aws_s3") {
                    $data["image_storage"] = "aws_s3";
                }
            }
        }
        if (!empty($this->input->post('image_url', true))) {
            $data["image_url"] = $this->input->post('image_url', true);
        }
		
		$data['pri_category_id'] = $this->input->post('category_id', true);
        $data['cat_url'] = '/'.$data['category_slug'].'/';

        /* Set Category data */
        $this->set_cat_data($data);
        $data['cat_ids'] = json_encode($this->otherCategoryArr['cat_ids']);

        if($data['post_type'] == 'webstory' || $data['post_type'] == 'short_videos'){
            $res = $this->db->insert('posts_'.$data['post_type'], $data);
        }else{
            $res = $this->db->insert('posts', $data);
        }
        
        /* $post_id = $this->db->insert_id();
        $this->db->where('posts.id',$post_id);
        $query = $this->db->get('posts');
        $tblData = $query->row();
        if(!empty($tblData->id)){
            $this->db->where('posts.id',clean_number($post_id));
            $this->db->update('posts',['title_slug'=>$data['title_slug'].'-'.$post_id]);
        } */
        return ["res"=>$res,"data"=>$data];
    }

    //update post
    public function update_post($id, $post_type,$status="")
    {
        $data = $this->set_data($post_type,'edit',$status);
        $data["created_at"] = $this->input->post('date_published', true);
        $data["user_id"] = $this->input->post('user_id', true);

        if($this->input->post('post_select_key') != null){
            $data["post_select_key"] = json_encode($this->input->post('post_select_key', true));
        }

        //$data["user_id"] = $this->input->post('user_id', true);
        $user_id = $this->input->post('user_id', true);
        if(empty($user_id)){
            $data['user_id'] = user()->id;
        }else{
            $data["user_id"] = $this->input->post('user_id', true);
        }
        
        $sql = "SELECT * FROM users WHERE users.id = ".$data['user_id'];
        $query = $this->db->query($sql);
        $user = $query->row();
        $data['author_username'] = $user->username;
        $data['author_slug'] = $user->slug;

        $data['is_scheduled'] = $this->input->post('scheduled_post', true);
        if (empty($data['is_scheduled'])) {
            $data['is_scheduled'] = 0;
        }

        $publish = $this->input->post('publish', true);
        if (!empty($publish) && $publish == 1) {
            $data["status"] = 1;
        }elseif($data['is_scheduled'] == 1){
            $data["status"] = 1;
        }

        //update post image
        $post_image_id = $this->input->post('post_image_id', true);
        if (!empty($post_image_id)) {
            $image = $this->file_model->get_image($post_image_id);
            if (!empty($image)) {
                $data["image_big"] = $image->image_big;
                $data["image_default"] = $image->image_default;
                $data["image_slider"] = $image->image_slider;
                $data["image_mid"] = $image->image_mid;
                $data["image_small"] = $image->image_small;
                $data["image_mime"] = $image->image_mime;
                $data["image_url"] = "";
                $data["image_storage"] = "local";
                if ($image->storage == "aws_s3") {
                    $data["image_storage"] = "aws_s3";
                }
            }
        }
        if (!empty($this->input->post('image_url', true))) {
            $data["image_url"] = $this->input->post('image_url', true);
            $data["image_big"] = "";
            $data["image_default"] = "";
            $data["image_slider"] = "";
            $data["image_mid"] = "";
            $data["image_small"] = "";
            $data["image_mime"] = "jpg";
            $data["image_storage"] = "";
        }
        $data['updated_at'] = date('Y-m-d H:i:s');
        if($status!=1 && $data['is_scheduled']!=1){
            $data["created_at"] = $data['updated_at'];
        }elseif($data['is_scheduled']==1){
            $data['updated_at'] = $data["created_at"];
        }
        $data['pri_category_id'] = $this->input->post('category_id', true);
        $data['cat_url'] = '/'.$data['category_slug'].'/';
        /* Set Category data */
        $this->set_cat_data($data);
        $data['cat_ids'] = json_encode($this->otherCategoryArr['cat_ids']);

        $this->db->where('id', clean_number($id));
        if($post_type == 'webstory' || $post_type == 'short_videos'){
            $res = $this->db->update('posts_'.$post_type, $data);
        }else{
            $res = $this->db->update('posts', $data);
        }
        /* Used for cache purged */
        $this->load->model('cache_purge_model');
        $postData = $data;
        $postData["id"] = $id; 
        if(empty($data['title_slug'])){
            $postData['title_slug'] = $this->input->post('title_slug', true);
        }
        $this->cache_purge_model->cache_purge_article(["is_edit"=>1,"lang_id"=>$this->selected_lang->id,"postData"=>$postData]);
        /* END Used for cache purged */

        return $res;

        
    }
    function set_cat_data($postTableData){
        
        $categoryArr = [];
        $catids = [];
        $category_id =$this->input->post('category_id', true);
        if(empty($category_id)){
            $this->otherCategoryArr['posts_cat'] = $categoryArr;
            $this->otherCategoryArr['cat_ids'] = $catids;
            return $categoryArr;
        }
        $categoryArr[] = array(
            //'post_id' => $post_id,
            'lang_id' => $postTableData['lang_id'],
            'cat_id' => $category_id,
            'cat_type' => 1,
            'is_deleted'=>0,
            'updated_at'=>(isset($postTableData['updated_at']))?$postTableData['updated_at']:( isset($postTableData['created_at'])?$postTableData['created_at']:date('Y-m-d H:i:s'))
        );
        $catids[] = (int)$category_id;
        $category_ids = $this->input->post('cat_ids', true);
        if(is_array($category_ids) && count($category_ids)){
            foreach ($category_ids as $key => $value) {
                if(!in_array($value,$catids)){
                    $categoryArr[] = array(
                        //'post_id' => $post_id,
                        'lang_id' => $postTableData['lang_id'],
                        'cat_id' => $value,
                        'cat_type' => 0,
                        'is_deleted'=>0,
                        'updated_at'=>$postTableData['updated_at']
                    );
                    $catids[] = (int)$value;
                }
            }
        }
        $this->otherCategoryArr['posts_cat'] = $categoryArr;
        $this->otherCategoryArr['cat_ids'] = $catids;
        return $categoryArr;
    }
    function add_post_cat($post_id,$post_type){
        $table = "posts_cat";
        if($post_type == 'webstory'){
            $table = $table."_".$post_type;
        }
        $catArr = $this->otherCategoryArr['posts_cat'];
        foreach ($catArr as $key => $value) {
            $value['post_id'] = $post_id;
            $this->db->insert($table, $value);
        }
        return true;    
    }
    function update_post_cat($post_id,$post_type){

        $table = "posts_cat";
        if($post_type == 'webstory'){
            $table = $table."_".$post_type;
        }
        $queryArray = [];
        $query = $this->db->query("SELECT GROUP_CONCAT(cat_id) as cat_ids FROM $table WHERE post_id = $post_id");
        $catObj = $query->row();
        $cat_ids_old = [];
        if(!empty($catObj->cat_ids)){
            $cat_ids_old = explode(",",$catObj->cat_ids);
        }
        $catArr = $this->otherCategoryArr['posts_cat'];
        $keepCatIds = [];
        foreach ($catArr as $key => $value) {
            $value['post_id'] = $post_id;
            $keepCatIds[] = $value['cat_id'];
            if(in_array($value['cat_id'],$cat_ids_old)){
                $this->db->where('cat_id', clean_number($value['cat_id']));
                $this->db->where('post_id', clean_number($post_id));
                $this->db->update($table, $value);
            }else{
                $this->db->insert($table, $value);
            }
        }
        $new_list = array_diff($cat_ids_old,$keepCatIds);
        //pr($new_list);die;
        if(is_array($new_list) && count($new_list)){
            $this->db->where_in('cat_id',$new_list);
            $this->db->where('post_id', clean_number($post_id));
            $this->db->update($table, ['is_deleted'=>1]);
        }
        return true;    
    }

    //set post data
    public function set_data($post_type,$atype='',$status="")
    {
		$data = $this->input_values($atype,$status);
        if (!isset($data['is_featured'])) {
            $data['is_featured'] = 0;
        }
        if (!isset($data['is_breaking'])) {
            $data['is_breaking'] = 0;
        }
        if (!isset($data['is_slider'])) {
            $data['is_slider'] = 0;
        }
        if (!isset($data['is_recommended'])) {
            $data['is_recommended'] = 0;
        }
        if (!isset($data['need_auth'])) {
            $data['need_auth'] = 0;
        }
        if (!isset($data['show_right_column'])) {
            $data['show_right_column'] = 0;
        }
        if (!isset($data['is_live'])) {
            $data['is_live'] = 0;
        }
        if(isset($data['category_id']))
        {
            $article_cat_url = $this->category_model->makeArticleCatURL($data['category_id']);
            if(count($article_cat_url)<=0){
                die("Something went wrong");
            }

            $sql = "SELECT name,name_slug,color FROM categories WHERE categories.id = ".$data['category_id'];
            $query = $this->db->query($sql);
            $cat = $query->row();
            $data['category_name'] = $cat->name;
            $data['category_slug'] = implode("/",$article_cat_url);
            $data['category_color'] = $cat->color;
        }
        //$data['subcategory_id'] = $data['category_id'];
        //set category
        /* $subcategory_id = $this->input->post('subcategory_id', true);
        if (!empty($subcategory_id)) {

            $data['category_id'] = $subcategory_id;
        } */

        $data['show_item_numbers'] = 0;
        if (!empty($this->input->post('show_item_numbers', true))) {
            $data['show_item_numbers'] = 1;
        }
		if($atype != 'edit'){
			if (empty($data["title_slug"])) {
				//slug for title
				$data["title_slug"] = str_slug(strtolower($data["topic"])." ".strtolower($data["title"]));
			} else {
				$data["title_slug"] = editFilename(strtolower($data["title_slug"]), true);
			}
		}elseif($atype == 'edit' && $status!=1){
            $data["title_slug"] = editFilename(strtolower($data["title_slug"]), true);
        }
        
        if ($post_type == "video") {
            $data["video_url"] = $this->input->post('video_url', true);
            $data["video_embed_code"] = $this->input->post('video_embed_code', true);
            $data['video_path'] = $this->input->post('video_path', true);
            $data['video_storage'] = $this->input->post('video_storage', true);
        }
        return $data;
    }

    //update slug
    public function update_slug($id)
    {
        $post = $this->get_post($id);
        if (!empty($post)) {
            if (empty($post->title_slug) || $post->title_slug == "-") {
                $data = array(
                    'title_slug' => $post->id
                );
                $this->db->where('id', $post->id);
                $this->db->update('posts', $data);
            } else {
                if ($this->check_slug_exists($post->title_slug, $post->id) == true) {
                    $data = array(
                        'title_slug' => $post->title_slug . "-" . $post->id
                    );
                    $this->db->where('id', $post->id);
                    $this->db->update('posts', $data);
                }
            }
        }
        return false;
    }

    //check slug exists
    public function check_slug_exists($slug, $id)
    {
        $sql = "SELECT * FROM posts WHERE title_slug = ? AND id != ?";
        $query = $this->db->query($sql, array(clean_str($slug), clean_number($id)));
        if (!empty($query->row())) {
            return true;
        }
        return false;
    }

    //check post exists
    public function check_post_exists($title, $title_hash)
    {
        $sql = "SELECT * FROM posts WHERE title = ? OR title_hash = ?";
        $query = $this->db->query($sql, array(clean_str($title), clean_str($title_hash)));
        if (!empty($query->row())) {
            return true;
        }
        return false;
    }

    //generate CSV object
    public function generate_csv_object($file_path)
    {
        $array = array();
        $fields = array();
        $txt_name = uniqid() . '.txt';
        $i = 0;
        $handle = fopen($file_path, "r");
        if ($handle) {
            while (($row = fgetcsv($handle)) !== false) {
                if (empty($fields)) {
                    $fields = $row;
                    continue;
                }
                foreach ($row as $k => $value) {
                    $array[$i][$fields[$k]] = $value;
                }
                $i++;
            }
            if (!feof($handle)) {
                return false;
            }
            fclose($handle);

            if (!empty($array)) {
                $txt_file = fopen(FCPATH . "uploads/tmp/" . $txt_name, "w");
                fwrite($txt_file, serialize($array));
                fclose($txt_file);
                $csv_object = new stdClass();
                $csv_object->number_of_items = count($array);
                $csv_object->txt_file_name = $txt_name;
                @unlink($file_path);
                return $csv_object;
            }
        }
        return false;
    }

    //import csv item
    public function import_csv_item($txt_file_name, $index)
    {
        $file_path = FCPATH . 'uploads/tmp/' . $txt_file_name;
        $file = fopen($file_path, 'r');
        $content = fread($file, filesize($file_path));
        $array = @unserialize($content);
        if (!empty($array)) {
            $this->load->model('upload_model');
            $i = 1;
            foreach ($array as $item) {
                if ($i == $index) {
                    $data = array();
                    $data['lang_id'] = get_csv_value($item, 'lang_id', 'int');
                    $data['title'] = get_csv_value($item, 'title');
                    $data['title_slug'] = get_csv_value($item, 'title_slug') ? get_csv_value($item, 'title_slug') : str_slug($data['title']);
                    $data['title_hash'] = "";
                    $data['keywords'] = get_csv_value($item, 'keywords');
                    $data['summary'] = get_csv_value($item, 'summary');
                    $data['content'] = get_csv_value($item, 'content');
                    $data['category_id'] = get_csv_value($item, 'category_id', 'int');
                    $data['image_big'] = "";
                    $data['image_default'] = "";
                    $data['image_slider'] = "";
                    $data['image_mid'] = "";
                    $data['image_small'] = "";
                    $data['image_mime'] = "jpg";
                    $data['optional_url'] = "";
                    $data['pageviews'] = 0;
                    $data['need_auth'] = 0;
                    $data['is_slider'] = 0;
                    $data['slider_order'] = 0;
                    $data['is_featured'] = 0;
                    $data['featured_order'] = 0;
                    $data['is_recommended'] = 0;
                    $data['is_breaking'] = 0;
                    $data['is_scheduled'] = 0;
                    $data['visibility'] = 0;
                    $data['show_right_column'] = 1;
                    $data['post_type'] = get_csv_value($item, 'post_type') ? get_csv_value($item, 'post_type') : 'article';
                    $data['video_path'] = "";
                    $data['image_url'] = "";
                    $data['video_url'] = "";
                    $data['video_embed_code'] = get_csv_value($item, 'video_embed_code');
                    $data['user_id'] = $this->auth_user->id;
                    $data['status'] = get_csv_value($item, 'status', 'int');
                    $data['feed_id'] = 0;
                    $data['post_url'] = "";
                    $data['show_post_url'] = 0;
                    $data['image_description'] = get_csv_value($item, 'image_description');
                    $data['show_item_numbers'] = 0;
                    $data['created_at'] = date('Y-m-d H:i:s');
                    //download image
                    $img_url = get_csv_value($item, 'image_url');
                    if (!empty($img_url)) {
                        @unlink(FCPATH . "uploads/tmp/temp.jpg");
                        $ext = pathinfo($img_url, PATHINFO_EXTENSION);
                        if ($ext == 'gif') {
                            $gif_name = "img_" . generate_unique_id() . ".gif";
                            $save_to = FCPATH . "uploads/tmp/" . $gif_name;
                            @copy($img_url, $save_to);
                            if (!empty($save_to) && file_exists($save_to)) {
                                $gif_path = $this->upload_model->post_gif_image_upload($gif_name);
                                $data_image = [
                                    'image_big' => $gif_path,
                                    'image_default' => $gif_path,
                                    'image_slider' => $gif_path,
                                    'image_mid' => $gif_path,
                                    'image_small' => $gif_path,
                                    'image_mime' => 'gif',
                                    'file_name' => $data['title_slug'],
                                    'user_id' => $this->auth_user->id
                                ];
                            }
                            @unlink($save_to);
                        } else {
                            try {
                                $save_to = FCPATH . "uploads/tmp/temp.jpg";
                                @copy($img_url, $save_to);
                                if (!empty($save_to) && file_exists($save_to)) {
                                    $data_image = [
                                        'image_big' => $this->upload_model->post_big_image_upload($save_to),
                                        'image_default' => $this->upload_model->post_default_image_upload($save_to),
                                        'image_slider' => $this->upload_model->post_slider_image_upload($save_to),
                                        'image_mid' => $this->upload_model->post_mid_image_upload($save_to),
                                        'image_small' => $this->upload_model->post_small_image_upload($save_to),
                                        'image_mime' => 'jpg',
                                        'file_name' => $data['title_slug'],
                                        'user_id' => $this->auth_user->id
                                    ];
                                }
                            } catch (\Exception $e) {
                            }
                        }
                        //add image to database
                        if (!empty($data_image)) {
                            if ($this->db->insert('images', $data_image)) {
                                $data['image_big'] = $data_image['image_big'];
                                $data['image_default'] = $data_image['image_default'];
                                $data['image_slider'] = $data_image['image_slider'];
                                $data['image_mid'] = $data_image['image_mid'];
                                $data['image_small'] = $data_image['image_small'];
                                $data['image_mime'] = $data_image['image_mime'];
                            }
                        }
                        @unlink($save_to);
                    }

                    //check visibility
                    if (check_user_permission('manage_all_posts') || $this->general_settings->approve_updated_user_posts != 1) {
                        $data['visibility'] = 1;
                    }

                    if ($this->db->insert('posts', $data)) {
                        $last_id = $this->db->insert_id();
                        //update slug
                        $this->update_slug($last_id);
                        //add tags
                        $tags = get_csv_value($item, 'tags');
                        if (!empty($tags)) {
                            $this->tag_model->add_post_tags($last_id, $tags);
                        }
                    }
                    return $data['title'];
                }
                $i++;
            }
        }
    }

    //get post
    public function get_post($id,$table='posts')
    {
        $sql = "SELECT * FROM ".$table." WHERE id =  ?";
        $query = $this->db->query($sql, array(clean_number($id)));
        return $query->row();
    }

    //get posts count
    public function get_posts_count()
    {
        $sql = "SELECT COUNT(id) AS count FROM posts WHERE is_scheduled = 0 AND status = 1 AND visibility = 1";
        $query = $this->db->query($sql);
        if (!check_user_permission('manage_all_posts')) {
            $sql = "SELECT COUNT(id) AS count FROM posts WHERE is_scheduled = 0 AND status = 1 AND visibility = 1 AND user_id = ?";
            $query = $this->db->query($sql, array(clean_number($this->auth_user->id)));
        }
        return $query->row()->count;
    }

    //get pending posts count
    public function get_pending_posts_count()
    {
        $sql = "SELECT COUNT(id) AS count FROM posts WHERE is_scheduled = 0 AND status = 1 AND visibility = 0";
        $query = $this->db->query($sql);
        if (!check_user_permission('manage_all_posts')) {
            $sql = "SELECT COUNT(id) AS count FROM posts WHERE is_scheduled = 0 AND status = 1 AND visibility = 0 AND user_id = ?";
            $query = $this->db->query($sql, array(clean_number($this->auth_user->id)));
        }
        return $query->row()->count;
    }

    //get drafts count
    public function get_drafts_count()
    {
        $sql = "SELECT COUNT(id) AS count FROM posts WHERE status = 0";
        $query = $this->db->query($sql);
        if (!check_user_permission('manage_all_posts')) {
            $sql = "SELECT COUNT(id) AS count FROM posts WHERE status = 0 AND user_id = ?";
            $query = $this->db->query($sql, array(clean_number($this->auth_user->id)));
        }
        return $query->row()->count;
    }

    //get scheduled posts count
    public function get_scheduled_posts_count()
    {
        $sql = "SELECT COUNT(id) AS count FROM posts WHERE status = 1 AND is_scheduled = 1";
        $query = $this->db->query($sql);
        if (!check_user_permission('manage_all_posts')) {
            $sql = "SELECT COUNT(id) AS count FROM posts WHERE status = 1 AND is_scheduled = 1 AND user_id = ?";
            $query = $this->db->query($sql, array(clean_number($this->auth_user->id)));
        }
        return $query->row()->count;
    }

    //filter by values
    public function filter_posts($table='posts')
    {
        $lang_id = $this->input->get('lang_id', true);
        $post_type = $this->input->get('post_type', true);
        $user = $this->input->get('user', true);
        $sdate = $this->input->get('sdate', true);
        $edate = $this->input->get('edate', true);
        /* $category = $this->input->get('category', true);
        $subcategory = $this->input->get('subcategory', true); */
        $q = trim($this->input->get('q', true));
        /* if (!empty($subcategory)) {
            $category = $subcategory;
        } */
        $user_id = null;
        if (check_user_permission('manage_all_posts')) {
            if (!empty($user)) {
                $user_id = $user;
            }
        } else {
            $user_id = $this->auth_user->id;
        }

        if (!empty($user_id)) {
            $this->db->where($table.'.user_id', clean_number($user_id));
        }
        if (!empty($lang_id)) {
            $this->db->where($table.'.lang_id', clean_number($lang_id));
        }else{
            $this->db->where($table.'.lang_id', clean_number($this->selected_lang->id)); 
        }
        if (!empty($post_type)) {
            $this->db->where($table.'.post_type', clean_str(str_replace("webstories","webstory",$post_type)));
        }
        if(!empty($sdate) && !empty($edate)){
            $edate = $edate." 23:59:59";
            $this->db->where($table.'.updated_at >= ', date("Y-m-d H:i:s",strtotime($sdate)));
            $this->db->where($table.'.updated_at <= ', date("Y-m-d H:i:s",strtotime($edate)));
        }
        /* if (!empty($category)) {
            $category_ids = generate_ids_string(get_category_tree($category, $this->categories));
            if (!empty($category_ids)) {
                $this->db->where($table.'.category_id IN (' . $category_ids . ')');
            }
        } */
        if (!empty($q)) {
            $this->db->like($table.'.title', clean_str($q));
        }
    }

    //filter by list
    public function filter_posts_list($list)
    {
        if (!empty($list)) {
            if ($list == "slider_posts") {
                $this->db->where('posts.is_slider', 1);
            }
            if ($list == "featured_posts") {
                $this->db->where('posts.is_featured', 1);
            }
            if ($list == "breaking_news") {
                $this->db->where('posts.is_breaking', 1);
            }
            if ($list == "recommended_posts") {
                $this->db->where('posts.is_recommended', 1);
            }
			if ($list == "special_posts") {
                $this->db->where('posts.is_special', 1);
            }
			if ($list == "trending_posts") {
                $this->db->where('posts.is_trending', 1);
            }
            if ($list == "live_news") {
                $this->db->where('posts.is_live', 1);
            }
            if ($list == "webstories") {
                $this->db->where('posts_webstory.post_type', 'webstory');
            }
            if ($list == "short_videos") {
                $this->db->where('posts.post_type', $list);
            }
        }
    }
    //index add for filter
    public function filter_posts_table_index($list)
    {
        $indexArr = [];
        if (!empty($list)) {
            if ($list == "slider_posts") {
                $indexArr[] = "idx_is_slider";
            }
            if ($list == "featured_posts") {
                $indexArr[] = "idx_is_featured";
            }
            if ($list == "breaking_news") {
                $indexArr[] = "idx_is_breaking";
            }
            if ($list == "recommended_posts") {
                $indexArr[] = "idx_is_recommended";
            }
			if ($list == "special_posts") {
                $indexArr[] = "is_special";
            }
			if ($list == "webstories") {
                $indexArr[] = "post_type";
            }
            if ($list == "live_news") {
                $indexArr[] = "idx_is_live";
            }
        }
        $indexArr[] = "UIDX_COMP3";
        return "USE INDEX(".implode(",",$indexArr).")";
    }
    public function post_table($list){

        if (!empty($list)) {
            if ($list == "webstories") {
                return 'posts_webstory';
            }
        }
        return 'posts';
    }
    //get paginated posts
    public function get_paginated_posts($per_page, $offset, $list)
    {
        $table = $this->post_table($list);
        $this->filter_posts($table);
        $this->filter_posts_list($list);
        
        $this->db->where($table.'.visibility', 1);
        $this->db->where($table.'.status', 1);
        $this->db->where($table.'.is_scheduled', 0);
        if ($list == "featured_posts") {
            $this->db->order_by($table.'.featured_order', 'ASC');
        }
        
        $this->db->order_by($table.'.updated_at', 'DESC');
        $this->db->limit($per_page, $offset);

        $category = $this->input->get('category', true);
        if($category){
            $posts_cat = ($table=='posts')?'posts_cat':'posts_cat_webstory';
            $this->db->select('DISTINCT('.$table.'.id), '.$table.'.*');
            $this->db->where_in('c.cat_id', explode(",",$category));
            $this->db->where_in('c.is_deleted',0);
            $query = $this->db->get($table .' '.$this->filter_posts_table_index($list).' INNER JOIN '.$posts_cat.' as c ON posts.id=c.post_id');

        }else{
             $query = $this->db->get($table .' '.$this->filter_posts_table_index($list));
        }
        return $query->result();
    }

    //get paginated posts count
    public function get_paginated_posts_count($list)
    {
        $table = $this->post_table($list);
        $category = $this->input->get('category', true);
        if($category){
            $posts_cat = ($table=='posts')?'posts_cat c':'posts_cat_webstory c';
            $this->db->select('COUNT(DISTINCT(post_id)) as count');
            $this->db->where_in('c.cat_id', explode(",",$category));
            $this->db->where_in('c.is_deleted',0);
            $query = $this->db->get($posts_cat);
        }else{
            $this->filter_posts($table);
            $this->filter_posts_list($list);
            $this->db->select('COUNT('.$table.'.id) as count');
            $this->db->where($table.'.visibility', 1);
            $this->db->where($table.'.status', 1);
            $this->db->where($table.'.is_scheduled', 0);
            $query = $this->db->get($table .' '.$this->filter_posts_table_index($list));
        }
        return $query->row()->count;
    }

    //get paginated pending posts
    public function get_paginated_pending_posts($per_page, $offset)
    {
        $post_type = $this->input->get('post_type', true);
        $table = $this->post_table($post_type);
        $this->filter_posts($table);
        $this->db->where($table.'.visibility', 0);
        $this->db->where($table.'.status', 1);
        $this->db->where($table.'.is_scheduled', 0);
        $this->db->order_by($table.'.updated_at', 'DESC');
        $this->db->limit($per_page, $offset);
        $category = $this->input->get('category', true);
        if($category){
            $posts_cat = ($table=='posts')?'posts_cat':'posts_cat_webstory';
            $this->db->select('DISTINCT('.$table.'.id), '.$table.'.*');
            $this->db->where_in('c.cat_id', explode(",",$category));
            $this->db->where_in('c.is_deleted',0);
            $query = $this->db->get($table .' '.$this->filter_posts_table_index($post_type).' INNER JOIN '.$posts_cat.' as c ON posts.id=c.post_id');

        }else{
             $query = $this->db->get($table .' '.$this->filter_posts_table_index($post_type));
        }
        return $query->result();
    }

    //get paginated pending posts count
    public function get_paginated_pending_posts_count()
    {
        $post_type = $this->input->get('post_type', true);
        $table = $this->post_table($post_type);
        $this->filter_posts($table);
        $this->db->select('COUNT('.$table.'.id) as count');
        $this->db->where($table.'.visibility', 0);
        $this->db->where($table.'.status', 1);
        $this->db->where($table.'.is_scheduled', 0);
        $query = $this->db->get($table .' '.$this->filter_posts_table_index($post_type));
        return $query->row()->count;
    }

    //get paginated scheduled posts
    public function get_paginated_scheduled_posts($per_page, $offset)
    {
        $post_type = $this->input->get('post_type', true);
        $table = $this->post_table($post_type);
        $this->filter_posts($table);
        $this->db->where($table.'.status', 1);
        $this->db->where($table.'.is_scheduled', 1);
        $this->db->order_by($table.'.updated_at', 'DESC');
        $this->db->limit($per_page, $offset);

        $category = $this->input->get('category', true);
        if($category){
            $posts_cat = ($table=='posts')?'posts_cat':'posts_cat_webstory';
            $this->db->select('DISTINCT('.$table.'.id), '.$table.'.*');
            $this->db->where_in('c.cat_id', explode(",",$category));
            $this->db->where_in('c.is_deleted',0);
            $query = $this->db->get($table .' '.$this->filter_posts_table_index($post_type).' INNER JOIN '.$posts_cat.' as c ON posts.id=c.post_id');

        }else{
             $query = $this->db->get($table .' '.$this->filter_posts_table_index($post_type));
        }

        //$query = $this->db->get($table .' '.$this->filter_posts_table_index($post_type));
        return $query->result();
    }

    //get paginated scheduled posts count
    public function get_paginated_scheduled_posts_count()
    {
        $post_type = $this->input->get('post_type', true);
        $table = $this->post_table($post_type);
        $this->filter_posts($table);
        $this->db->select('COUNT('.$table.'.id) as count');
        $this->db->where($table.'.status', 1);
        $this->db->where($table.'.is_scheduled', 1);
        $query = $this->db->get($table .' '.$this->filter_posts_table_index($post_type));
        return $query->row()->count;
    }

    //get paginated drafts
    public function get_paginated_drafts($per_page, $offset)
    {
        $post_type = $this->input->get('post_type', true);
        $table = $this->post_table($post_type);
        $this->filter_posts($table);
        
        $this->db->where($table.'.status !=', 1);
        $this->db->order_by($table.'.updated_at', 'DESC');
        $this->db->limit($per_page, $offset);

        $category = $this->input->get('category', true);
        if($category){
            $posts_cat = ($table=='posts')?'posts_cat':'posts_cat_webstory';
            $this->db->select('DISTINCT('.$table.'.id), '.$table.'.*');
            $this->db->where_in('c.cat_id', explode(",",$category));
            $this->db->where_in('c.is_deleted',0);
            $query = $this->db->get($table .' '.$this->filter_posts_table_index($post_type).' INNER JOIN '.$posts_cat.' as c ON posts.id=c.post_id');

        }else{
             $query = $this->db->get($table .' '.$this->filter_posts_table_index($post_type));
        }
        return $query->result();
    }

    //get paginated drafts count
    public function get_paginated_drafts_count()
    {
        $post_type = $this->input->get('post_type', true);
        $table = $this->post_table($post_type);
        $this->filter_posts($table);
        $this->db->select('COUNT('.$table.'.id) as count');
        $this->db->where($table.'.status !=', 1);
        $query = $this->db->get($table .' '.$this->filter_posts_table_index($post_type));
        return $query->row()->count;
    }

    //get feed posts count
    public function get_feed_posts_count($feed_id)
    {
        $this->filter_posts();
        $this->db->select('COUNT(posts.id) as count');
        $this->db->where('feed_id', clean_number($feed_id));
        $this->db->where('posts.visibility', 1);
        $this->db->where('posts.status', 1);
        $this->db->where('posts.is_scheduled', 0);
        $query = $this->db->get('posts');
        return $query->row()->count;
    }

    //get posts by feed id
    public function get_posts_by_feed_id($feed_id)
    {
        $sql = "SELECT * FROM posts WHERE feed_id = ?";
        $query = $this->db->query($sql, array(clean_number($feed_id)));
        return $query->result();
    }

    //add or remove post from slider
    public function post_add_remove_slider($id)
    {
        //get post
        $post = $this->get_post($id);
        if (!empty($post)) {
            $result = "";
            if ($post->is_slider == 1) {
                //remove from slider
                $data = array(
                    'is_slider' => 0,
                );
                $result = "removed";
            } else {
                //add to slider
                $data = array(
                    'is_slider' => 1,
                );
                $result = "added";
            }
            $this->db->where('id', $post->id);
            $this->db->update('posts', $data);
            return $result;
        }
    }

    //add or remove post from featured
    public function post_add_remove_featured($id)
    {
        //get post
        $post = $this->get_post($id);
        if (!empty($post)) {
            $result = "";
            if ($post->is_featured == 1) {
                //remove from featured
                $data = array(
                    'is_featured' => 0,
                );
                $result = "removed";
            } else {
                //add to featured
                $data = array(
                    'is_featured' => 1,
                );
                $result = "added";
            }
            $this->db->where('id', $post->id);
            $this->db->update('posts', $data);
            return $result;
        }
    }
	
	 //add or remove post from trending
    public function post_add_remove_trending($id)
    {
        //get post
        $post = $this->get_post($id);
        if (!empty($post)) {
            $result = "";
            if ($post->is_trending == 1) {
                //remove from trending
                $data = array(
                    'is_trending' => 0,
                );
                $result = "removed";
            } else {
                //add to trending
                $data = array(
                    'is_trending' => 1,
                );
                $result = "added";
            }
            $this->db->where('id', $post->id);
            $this->db->update('posts', $data);
            return $result;
        }
    }
	 //add or remove post from special
    public function post_add_remove_special($id)
    {
        //get post
        $post = $this->get_post($id);
        if (!empty($post)) {
            $result = "";
            if ($post->is_special == 1) {
                //remove from featured
                $data = array(
                    'is_special' => 0,
                );
                $result = "removed";
            } else {
                //add to featured
                $data = array(
                    'is_special' => 1,
                );
                $result = "added";
            }
            $this->db->where('id', $post->id);
            $this->db->update('posts', $data);
            return $result;
        }
    }

    //add or remove post from breaking
    public function post_add_remove_breaking($id)
    {
        //get post
        $post = $this->get_post($id);
        if (!empty($post)) {
            $result = "";
            if ($post->is_breaking == 1) {
                //remove from breaking
                $data = array(
                    'is_breaking' => 0,
                );
                $result = "removed";
            } else {
                //add to breaking
                $data = array(
                    'is_breaking' => 1,
					'breaking_datetime'=>date("Y-m-d H:i:s")
                );
                $result = "added";
            }

            $this->db->where('id', $post->id);
            $this->db->update('posts', $data);
            return $result;
        }
    }

    //approve post
    public function approve_post($id)
    {
        $data = array(
            'visibility' => 1,
        );
        $this->db->where('id', clean_number($id));
        return $this->db->update('posts', $data);
    }

    //publish post
    public function publish_post($id)
    {
        $data = array(
            'is_scheduled' => 0,
            'created_at' => date('Y-m-d H:i:s')
        );
        $this->db->where('id', clean_number($id));
        return $this->db->update('posts', $data);
    }
    //publish post
    public function unpublish_post($id,$table="posts")
    {
        $data = array(
            'status' => 2
        );
        $this->db->where('id', clean_number($id));
        return $this->db->update($table, $data);
    }
    

    //check scheduled posts
    public function check_scheduled_posts()
    {
        $date = date('Y-m-d H:i:s');
        $sql = "SELECT * FROM posts WHERE is_scheduled = 1";
        $query = $this->db->query($sql);
        $posts = $query->result();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                if ($post->created_at <= $date) {
                    $data = array(
                        'is_scheduled' => 0,
                    );
                    $this->db->where('id', $post->id);
                    $this->db->update('posts', $data);
                }
            }
            reset_cache_data_on_change();

            echo "All scheduled posts have been checked.";
        } else {
            echo "There are no scheduled posts.";
        }
    }
	
	
	 public function delete_bnews_p($id){
		$this->db->where('id', $id);
        return $this->db->delete('breaking_news');
	 }	
			
	  //get bnews paginated posts count
    public function get_paginated_bnews_count()
    {
		$this->db->select('COUNT(breaking_news.id) as count');
        $this->db->where("lang_id",$this->selected_lang->id);
        $query = $this->db->get('breaking_news');
        return $query->row()->count;
    }

	//get b news paginated posts
    public function get_paginated_bnews($per_page, $offset)
    {
        $this->db->order_by('breaking_news.created_at', 'DESC');
        $this->db->where("lang_id",$this->selected_lang->id);
        $this->db->limit($per_page, $offset);
        $query = $this->db->get('breaking_news');
        return $query->result();
    }
	
	//get b news by id
    public function get_brnews_by_id($id)
    {
        $sql = "SELECT * FROM breaking_news WHERE id = ?";
        $query = $this->db->query($sql, array(clean_number($id)));
        return $query->row();
    }
	
	//b news
    public function breaking_news_save($title,$url)
    {
        $data = array(
            'title' => $title,
            'url' => $url,
            'lang_id' => $this->selected_lang->id,
            'created_at' => date('Y-m-d H:i:s')
        );
        
        return $this->db->insert('breaking_news', $data);
    }
	
	//edit b news
    public function breaking_news_update($title,$edit_id,$url)
    {
        $data = array(
            'title' => trim($title),
            'url' => $url
        );
        $this->db->where('id', clean_number($edit_id));
        return $this->db->update('breaking_news', $data);
    }
	
	
    //publish draft
    public function publish_draft($id,$table="posts")
    {
        $data = array(
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s')
        );
        $this->db->where('id', clean_number($id));
        return $this->db->update($table, $data);
    }

    //add or remove post from recommended
    public function post_add_remove_recommended($id)
    {
        //get post
        $post = $this->get_post($id);
        if (!empty($post)) {
            $result = "";
            if ($post->is_recommended == 1) {
                //remove from recommended
                $data = array(
                    'is_recommended' => 0,
                );
                $result = "removed";
            } else {
                //add to recommended
                $data = array(
                    'is_recommended' => 1,
                );
                $result = "added";
            }
            $this->db->where('id', $post->id);
            $this->db->update('posts', $data);
            return $result;
        }
    }

    //save feaured post order
    public function save_featured_post_order($id, $order, $list_type = "featured_posts")
    {
        //get post
        $post = $this->get_post($id);
        if (!empty($post)) {
            if($list_type == "featured_posts"){
                $data = array('featured_order' => clean_number($order));
            }elseif($list_type == "slider_posts"){
                $data = array('slider_order' => clean_number($order));
            }elseif($list_type == "special_posts"){
                $data = array('special_order' => clean_number($order));
            }elseif($list_type == "trending_posts"){
                $data = array('trending_order' => clean_number($order));
            }
            $this->db->where('id', $post->id);
            $this->db->update('posts', $data);
        }
    }

	//save special post order
    public function save_special_post_order($id, $order)
    {
        //get post
        $post = $this->get_post($id);
        if (!empty($post)) {
            $data = array(
                'special_order' => clean_number($order),
            );
            $this->db->where('id', $post->id);
            $this->db->update('posts', $data);
        }
    }
	//save trending post order
    public function save_trending_post_order($id, $order)
    {
        //get post
        $post = $this->get_post($id);
        if (!empty($post)) {
            $data = array(
                'trending_order' => clean_number($order),
            );
            $this->db->where('id', $post->id);
            $this->db->update('posts', $data);
        }
    }
    //save home slider post order
    public function save_home_slider_post_order($id, $order)
    {
        //get post
        $post = $this->get_post($id);
        if (!empty($post)) {
            $data = array(
                'slider_order' => clean_number($order),
            );
            $this->db->where('id', $post->id);
            $this->db->update('posts', $data);
        }
    }

    //post bulk options
    public function post_bulk_options($operation, $post_ids)
    {
        $data = array();
        if ($operation == 'add_slider') {
            $data['is_slider'] = 1;
        } elseif ($operation == 'remove_slider') {
            $data['is_slider'] = 0;
        } elseif ($operation == 'add_featured') {
            $data['is_featured'] = 1;
        } elseif ($operation == 'remove_featured') {
            $data['is_featured'] = 0;
        } elseif ($operation == 'add_breaking') {
            $data['is_breaking'] = 1;
        } elseif ($operation == 'remove_breaking') {
            $data['is_breaking'] = 0;
        } elseif ($operation == 'add_recommended') {
            $data['is_recommended'] = 1;
        } elseif ($operation == 'remove_recommended') {
            $data['is_recommended'] = 0;
        } elseif ($operation == 'publish_scheduled') {
            $data['is_scheduled'] = 0;
            $data['created_at'] = date('Y-m-d H:i:s');
        } elseif ($operation == 'approve') {
            $data['visibility'] = 1;
        } elseif ($operation == 'publish_draft') {
            $data['status'] = 1;
            $data['created_at'] = date('Y-m-d H:i:s');
        }
        if (!empty($post_ids)) {
            foreach ($post_ids as $id) {
                $post = $this->get_post($id);
                if (!empty($post)) {
                    $this->db->where('id', $id);
                    $this->db->update('posts', $data);
                }
            }
        }
    }

    //delete post
    public function delete_post($id,$table="posts")
    {
        $post = $this->get_post($id,$table);
        if (!empty($post)) {
            if (!check_post_ownership($post->user_id)) {
                return false;
            }
            if ($post->post_type != "webstory") {
                //delete additional images
                $this->post_file_model->delete_post_additional_images($post->id);
                //delete audios
                $this->post_file_model->delete_post_audios($post->id);

                //delete post tags
                $this->tag_model->delete_post_tags($post->id);

            }
            //delete gallery post items
            if ($post->post_type == "gallery") {
                $this->post_item_model->delete_post_list_items($post->id, 'gallery');
            }
            if ($post->post_type == "sorted_list") {
                $this->post_item_model->delete_post_list_items($post->id, 'sorted_list');
            }
            //delete quiz questions
            if ($post->post_type == "trivia_quiz" || $post->post_type == "personality_quiz") {
                $this->quiz_model->delete_quiz_questions($post->id);
                $this->quiz_model->delete_quiz_results($post->id);
            }

            

            $this->db->where('id', $post->id);
            return $this->db->delete($table);
        }
        return false;
    }

    //delete multi post
    public function delete_multi_posts($post_ids)
    {
        if (!empty($post_ids)) {
            foreach ($post_ids as $id) {
                $this->delete_post($id);
            }
        }
    }

    //get sitemap post category_id
    public function get_post_sitemap($hours)
    {

     $sql = $this->db->select('p.id,p.title,p.title_slug,p.created_at,c.name')
     ->from('posts as p')
     ->where('(p.created_at > NOW() - INTERVAL '.$hours.' HOUR)')
     ->join('categories as c', 'p.category_id = c.id', 'LEFT')
     ->order_by('p.id DESC')
     ->get();
     return $sql->result();

    // $sql = "SELECT posts.id,posts.title,posts.title_slug,posts.created_at, categories.name FROM posts inner join categories on posts.category_id = categories.id  where posts.created_at > NOW() -  INTERVAL ? HOUR ORDER BY id DESC";
    // $query = $this->db->query($sql,array(clean_number($hours)));
    // return $query->result();
    }

    public function post_search_byTerm($search)
    {
        $sql = "SELECT id,title FROM posts WHERE MATCH(posts.title) AGAINST ('".$search."' IN BOOLEAN MODE) and lang_id=? order by id DESC limit 10";
        $query = $this->db->query($sql,[clean_number($this->selected_lang->id)]);
        return $query->result();
    }
}