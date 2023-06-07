<?php defined('BASEPATH') or exit('No direct script access allowed');

class Newstrackrss_model extends CI_Model
{
    public function set_data($feed_item,$tblData)
    {

        //$userData = $this->getData('users',['id','username','slug'],'username',$feed_item->author);
        $string = basename($feed_item->url);
        $title_slug   = implode('-', explode('-', $string, -1));
        $caption = '';
        if(isset($feed_item->media[0]->caption)){
            $caption = strip_tags($feed_item->media[0]->caption);
        }
        $urlArr = explode("/",$feed_item->url);
        unset($urlArr[(count($urlArr)-1)]);
        unset($urlArr[0]);
        unset($urlArr[1]);
        unset($urlArr[2]);
        //$category_slug = end($urlArr);
        $category_slug = implode("/",$urlArr);
        $cat_url = '/'.implode("/",$urlArr).'/';
        //204 will enable for home

        /* Category Logic */
        $is_featured = $is_trending = 0;
        $is_featured = (isset($tblData->is_featured) && $tblData->is_featured==1)?1:0;
        $maincategory = $feed_item->maincategory;
        $maincat_name = $feed_item->maincat_name;
        $category_color = "#000000";
        if(is_array($feed_item->categories)){
            /* if(in_array(204,$feed_item->categories)){
                $is_featured = 1;
            } */
            if(in_array(214,$feed_item->categories)){
                $is_trending = 1;
            }
            /* $child_cat_id = end($feed_item->categories);
            if($child_cat_id == 204){
                $child_cat_id = prev($feed_item->categories);
            } */
            $catData = $this->getData('categories',['id','name','name_slug','color'],'id',$maincategory);
            if(isset($catData->id)){
                $maincategory = $catData->id;
                $maincat_name = $catData->name;
                $category_color = $catData->color;
            }
        }
        /* End Category Logic */

        $lang_id = 1;
        if(strpos($feed_item->url, 'english.newstrack.com') !== false){
            $lang_id = 2;
        }elseif(strpos($feed_item->url, 'apnabharat.org') !== false){
            $lang_id = 3;
        }

        $tableData = array(
            'id'=>(int)$feed_item->newsId,
            'reporter_id' => 0,
            'updated_at' => date("Y-m-d H:i:s", strtotime($feed_item->date_news)),
            'created_at' => date("Y-m-d H:i:s", strtotime($feed_item->date_created)),
            'lang_id' => (isset($tblData->lang_id))?$tblData->lang_id:$lang_id,
            'topic' => '',
            'headline' => $feed_item->heading,
            'title' => $feed_item->heading,
            'title_slug' => trim($title_slug),
            'summary' => (string)$feed_item->description,
            'tag_description' => (string)$feed_item->description,
            'user_id' => $feed_item->authorId,
            'author_username' => $feed_item->authorName,
            'author_slug' => str_replace(" ","-",strtolower($feed_item->authorName)),
            'category_id' => $maincategory,
            'pri_category_id' => $maincategory,
            'category_name' => $maincat_name,
            'category_slug' => $category_slug,
            'category_color' => $category_color,
            'cat_url'=>$cat_url,
            'content' => $this->uploadContentImage($feed_item->story),
            'optional_url' => '',
            'need_auth' => 0,
            'is_slider' => 0,
            'is_featured' => $is_featured,
            'is_trending' => $is_trending,
            'is_recommended' => 0,
            'is_breaking' => 0,
            'is_live' => 0,
            'show_right_column' => 0,
            'keywords' => $feed_item->keywords,
            'image_description' => $caption,
            'post_type' => (isset($feed_item->videoUrl) && isset($feed_item->yt_videoId))?"video":"article",
            'status' => 1,
            'visibility' => 1,
            'cat_ids' => json_encode($feed_item->categories),
            'video_url'=> (isset($feed_item->videoUrl) && isset($feed_item->yt_videoId))?$feed_item->videoUrl:'',
        );
        
        $imgTableData = [];
        $imgTableData["image_big"] = "";
        $imgTableData["image_default"] = "";
        $imgTableData["image_slider"] = "";
        $imgTableData["image_mid"] = "";
        $imgTableData["image_small"] = "";
        $imgTableData["image_mime"] = "jpg";
        $imgTableData["image_url"] = "";
        $imgTableData["image_storage"] = $this->general_settings->storage;

        if (!isset($tblData->id) && !empty($feed_item->media[0]->url)) {
            if(!empty($feed_item->mediaId)){
                $imageData = $this->uploadImage($feed_item->mediaId);
            }else{
                $imageData = $this->uploadImage($feed_item->media[0]->url);
            }
            if ($imageData) {
                $tableData = array_merge($tableData, $imageData);
            }else{
                $tableData = array_merge($tableData, $imgTableData);
            }
        }elseif (!empty($feed_item->media[0]->url) && isset($tblData->id)) {
            $resImageExists = $this->imageExists($feed_item->media[0]->url);
            if(!$resImageExists){
                if(!empty($feed_item->mediaId)){
                    $imageData = $this->uploadImage($feed_item->mediaId);
                }else{
                    $imageData = $this->uploadImage($feed_item->media[0]->url);
                }
                if ($imageData) {
                    $tableData = array_merge($tableData, $imageData);
                }else{
                    $tableData = array_merge($tableData, $imgTableData);
                }
            }
        }elseif(!isset($tblData->id) && !empty($feed_item->mediaId)){
            $imageData = $this->uploadImage($feed_item->mediaId);
            if ($imageData) {
                $tableData = array_merge($tableData, $imageData);
            }else{
                $tableData = array_merge($tableData, $imgTableData);
            }
        }elseif(!empty($feed_item->mediaId) && isset($tblData->id)){
            $resImageExists = $this->imageExists($feed_item->mediaId);
            if(!$resImageExists){
                $imageData = $this->uploadImage($feed_item->mediaId);
                if ($imageData) {
                    $tableData = array_merge($tableData, $imageData);
                }else{
                    $tableData = array_merge($tableData, $imgTableData);
                }
            }
        }else{
            $tableData = array_merge($tableData, $imgTableData);
        }
        return $tableData;
    }
    function set_cat_data($data){
        
        $categoryArr[] = array(
            //'post_id' => $post_id,
            'lang_id' => $data['lang_id'],
            'cat_id' => $data['pri_category_id'],
            'cat_type' => 1,
            'is_deleted'=>0,
            'updated_at'=> $data['updated_at'],
        );
        if(is_array(json_decode($data['cat_ids']))){
            foreach (json_decode($data['cat_ids']) as $key => $value) {
                if($value == $data['pri_category_id']) continue;
                $categoryArr[] = array(
                    //'post_id' => $post_id,
                    'lang_id' => $data['lang_id'],
                    'cat_id' => $value,
                    'cat_type' => 0,
                    'is_deleted'=>0,
                    'updated_at'=> $data['updated_at'],
                );
            }
        }
        //pr($categoryArr);
        return $categoryArr;
    }
    function add_post_cat($post_id,$catArr,$post_type=""){
        $table = "posts_cat";
        if($post_type == 'webstory'){
            $table = $table."_".$post_type;
        }
        foreach ($catArr as $key => $value) {
            $value['post_id'] = $post_id;
            $this->db->insert($table, $value);
        }
        return true;    
    }
    function update_post_cat($post_id,$catArr,$post_type=""){

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
    public function checkOldPostExists($feed_item){
        
        $string = basename($feed_item->url);
        $title_slug   = implode('-', explode('-', $string, -1));
        $this->db->where('posts.id',$feed_item->newsId);
        $this->db->or_where('posts.title_slug',$title_slug);
        $query = $this->db->get('posts');
        $tblData = $query->result();
        $postResArr = ["slno_post"=>false,"title_slug_post"=>false,"matchedSlug"=>false];
        foreach ($tblData as $key => $value) {
            if($value->id == $feed_item->newsId){
                $postResArr["slno_post"] = $value;
            }
            if($value->title_slug == $title_slug){
                $postResArr["title_slug_post"] = $value;
                $postResArr["matchedSlug"] = true;
            }
        }
        return $postResArr;
    }
    public function add_post($feed_item)
    {

       /*  $postResArr = $this->checkOldPostExists($feed_item);
        if($postResArr["slno_post"] && !$postResArr["matchedSlug"]){
            $tblData = (object)[];
        }elseif($postResArr["slno_post"] && $postResArr["matchedSlug"]){
            $tblData = $postResArr["slno_post"];
        } */
       /*  pr($tblData);
        pr($postResArr);
        pr($this->db->queries);
        die; */
        $this->db->where('posts.id',$feed_item->newsId);
        $query = $this->db->get('posts');
        $tblData = $query->row();

        $data = $this->set_data($feed_item,$tblData);
        
        $this->load->model('tag_model');
        if(isset($tblData->id)){
            $this->db->where('posts.id',$feed_item->newsId);
            $this->db->update('posts', $data);
            $this->updatePostTags($feed_item->newsId,$feed_item->tags);
            $this->update_post_cat($feed_item->newsId,$this->set_cat_data($data));
            return "UPDATED";
        }else{
            /* if($postResArr["slno_post"]){
                unset($data["id"]);
            } */
            $this->db->insert('posts', $data);
            /* $feed_item->newsId = $this->db->insert_id();
            $data["id"] = $feed_item->newsId; */
            $this->tag_model->add_post_tags($feed_item->newsId, $feed_item->tags);
            $this->add_post_cat($feed_item->newsId,$this->set_cat_data($data));
            /* if($postResArr["slno_post"]){
                return "UPDATED_NEW";
            } */
            return "NEW";
        }
        /* if(!isset($tblData->id)){
            $data = $this->set_data($feed_item);
            return $this->db->insert('posts', $data);
        }else{
            return false;
        } */
        
    }
    function set_photo_story_data($feed_item)
    {

        $userData = $this->getData('users',['id','username','slug'],'id',$feed_item->authorId);
        $string = basename($feed_item->url);
        $title_slug   = implode('-', explode('-', $string, -1));
        $caption = '';
        $urlArr = explode("/",$feed_item->url);
        //unset($urlArr[(count($urlArr)-1)]);
        unset($urlArr[0]);
        //unset($urlArr[1]);
        unset($urlArr[2]);
        //print_r($urlArr);die;
        //$category_slug = end($urlArr);
        $category_slug = implode("/",$urlArr);
        $cat_url = '/'.implode("/",$urlArr).'/';
        //204 will enable for home

        /* Category Logic */
        $is_featured = $is_trending = 0;
        $maincategory = '366';
        $maincat_name = 'Photo Stories';
        $category_color = "#000000";
        
        /* End Category Logic */
        $lang_id = 2;
        $maincategory = '488';
        if(strpos($feed_item->imageUrl, 'english.newstrack.com') !== false){
            $lang_id = 2;
            $maincategory = '488';
        }elseif(strpos($feed_item->imageUrl, 'apnabharat.org') !== false){
            $lang_id = 3;
        }

        $tableData = array(
            'id'=>(int)$feed_item->uid,
            'reporter_id' => 0,
            'updated_at' => date("Y-m-d H:i:s", strtotime($feed_item->date_news)),
            'created_at' => date("Y-m-d H:i:s", strtotime($feed_item->date_news)),
            'lang_id' => $lang_id,
            'topic' => '',
            'headline' => $feed_item->heading,
            'title' => $feed_item->heading,
            'title_slug' => trim($title_slug),
            'summary' => '',
            'tag_description' => '',
            'user_id' => $feed_item->authorId,
            'author_username' => (isset($userData->authorName))??'',
            'author_slug' => (isset($userData->slug))??'',
            'category_id' => $maincategory,
            'pri_category_id' => $maincategory,
            'category_name' => $maincat_name,
            'category_slug' => $category_slug,
            'category_color' => $category_color,
            'cat_url'=>$cat_url,
            'content' => '',
            'optional_url' => '',
            'need_auth' => 0,
            'is_slider' => 0,
            'is_featured' => $is_featured,
            'is_trending' => $is_trending,
            'is_recommended' => 0,
            'is_breaking' => 0,
            'is_live' => 0,
            'show_right_column' => 0,
            'keywords' => '',
            'image_description' => '',
            'post_type' => "webstory",
            'status' => 1,
            'visibility' => 1,
            'cat_ids' => json_encode([(int)$maincategory]),
        );

        //print_r($feed_item->slides);
        if(isset($feed_item->slides) && is_array($feed_item->slides)){
            foreach($feed_item->slides as $key=>$photo){
                //print_r($photo);
                $image_path = "";
                if(!empty($photo->slide_attributes[0]->value)){
                    $image_url = str_replace(".webp122",".jpg",$photo->slide_attributes[0]->value);
                    $imageData = $this->uploadImage($image_url,'webstory');
                    if(isset($imageData['image_slider'])){
                        $image_path = $imageData['image_slider'];
                        if ($imageData) {
                            if($key<=0){
                                $tableData = array_merge($tableData, $imageData);
                            }
                        }
                    }else{
                        continue;
                    }
                }
                $data = array(
                    'id' => (int)$photo->uid,
                    'post_id' => (int)$feed_item->uid,
                    'title' => !empty($photo->slide_attributes[3]->value) ? $photo->slide_attributes[3]->value : '',
                    'image_path' => $image_path,
                    'image_storage' => 'aws_s3',
                    'description' => !empty($photo->slide_attributes[5]->value) ? $photo->slide_attributes[5]->value : '',
                    'history_order' => $key+1,
                    'created_at' => date("Y-m-d H:i:s", strtotime($feed_item->date_news)),
                    'updated_at' => date("Y-m-d H:i:s", strtotime($feed_item->date_news))
                );
                $tableData['webstories_items'][] = $data;
            }
        }
        //print_r($tableData);
        //die;
        /* $imgTableData = [];
        $imgTableData["image_big"] = "";
        $imgTableData["image_default"] = "";
        $imgTableData["image_slider"] = "";
        $imgTableData["image_mid"] = "";
        $imgTableData["image_small"] = "";
        $imgTableData["image_mime"] = "jpg";
        $imgTableData["image_url"] = "";
        $imgTableData["image_storage"] = "local";

        if (!isset($tblData->id) && !empty($feed_item->media[0]->url)) {
            if(!empty($feed_item->mediaId)){
                $imageData = $this->uploadImage($feed_item->mediaId);
            }else{
                $imageData = $this->uploadImage($feed_item->media[0]->url);
            }
            if ($imageData) {
                $tableData = array_merge($tableData, $imageData);
            }else{
                $tableData = array_merge($tableData, $imgTableData);
            }
        }elseif (!empty($feed_item->media[0]->url) && isset($tblData->id)) {
            $resImageExists = $this->imageExists($feed_item->media[0]->url);
            if(!$resImageExists){
                if(!empty($feed_item->mediaId)){
                    $imageData = $this->uploadImage($feed_item->mediaId);
                }else{
                    $imageData = $this->uploadImage($feed_item->media[0]->url);
                }
                if ($imageData) {
                    $tableData = array_merge($tableData, $imageData);
                }else{
                    $tableData = array_merge($tableData, $imgTableData);
                }
            }
        }else{
            $tableData = array_merge($tableData, $imgTableData);
        } */
        return $tableData;
    }
    function add_photo_story($item){

        $this->db->where('posts_webstory.id',$item->uid);
        $query = $this->db->get('posts_webstory');
        $tblData = $query->row();
        if(!isset($tblData->id) && empty($tblData->id) && $item->uid){
            $data = $this->set_photo_story_data($item);
            $webstories_items = $data['webstories_items'];
            if(isset($data['webstories_items'])){
                unset($data['webstories_items']); 
            }
            $this->db->insert('posts_webstory', $data);
            if(is_array($webstories_items) && count($webstories_items)){
                foreach($webstories_items as $webstory){
                    $this->db->insert('webstories_items', $webstory);
                }
            }
            $this->update_post_cat($item->uid,$this->set_cat_data($data),$data['post_type']);
            return "NEW";
        }else{
            return "UPDATED";
        }
        
    }
    function getData($table, $field = '*', $where, $author)
    {
        if (is_array($field)) {
            $sql = "select " . implode(',', $field) . " from $table where $where='" . $author . "' limit 1";
        } else {
            $sql = "select $field from $table where $where='" . $author . "' limit 1";
        }
        $query = $this->db->query($sql);
        return  $query->row();
    }
    function getUrlMimeType($buffer)
    {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        return $finfo->buffer($buffer);
    }
    function uploadImageFromURL($img_url)
    {

        $buffer = file_get_contents($img_url);
        $im = imagecreatefromstring($buffer);
        $fileSize = strlen($buffer);
        $img_url_array = explode("upload/", $img_url);

        $config['upload_path'] = './uploads/tmp/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        //$config['file_name'] = 'img_' . generate_unique_id();
        $config['file_name'] = basename($img_url);
        $config['file_ext'] = pathinfo($img_url, PATHINFO_EXTENSION);
        $config['DOOC_ROOT'] = str_replace('/application/models', '', __DIR__);

        $example = [
            'file_name' => $config['file_name'],
            'file_type' => $this->getUrlMimeType($buffer),
            'file_path' => $config['DOOC_ROOT'] . '/uploads/tmp/',
            'full_path' => $config['DOOC_ROOT'] . '/uploads/tmp/' . $config['file_name'],
            'raw_name' => str_replace('.' . $config['file_ext'], '', $config['file_name']),
            'orig_name' => $config['file_name'],
            'client_name' => $config['file_name'],
            'file_ext' => '.' . $config['file_ext'],
            'file_size' => $fileSize,
            'is_image' => '1',
            'image_width' => imagesx($im),
            'image_height' => imagesy($im),
            'image_type' => $config['file_ext'],
            'image_size_str' => 'width="' . imagesx($im) . '" height="' . imagesy($im) . '"',
            'img_url' => $img_url,
            'folder' => 'uploads/images/' . substr(end($img_url_array), 0, 8),
        ];
        return $example;
    }
    public function add_category($catData){
        
        $this->db->where('categories.id', $catData['id']);
        $query = $this->db->get('categories');
        $data = $query->row();
        if(!isset($data->id)){
            $this->db->insert('categories', $catData);
        }elseif(!empty($catData['id'])){
            $this->db->where('categories.id', $catData['id']);
            $this->db->update('categories', ["title"=>$catData["title"],"description"=>$catData["description"],"keywords"=>$catData["keywords"]]);
        }
    }
    private function uploadImage($url,$actionType=''){

        $url = str_replace(".webp",".jpg",$url);
        $url = str_replace("/500x300_","/",$url);
        $url = str_replace("//newstrack.com","//api.newstrack.com",$url);
        if(strpos($url, '//newstrack.com/') !== false){
            return false;
        }
        $urlArr = explode(",",$url);
        $url = $urlArr[0];
        $ext = strtolower(pathinfo(basename($url), PATHINFO_EXTENSION));
        if (!in_array($ext,['gif','jpg','jpeg','png','webp']))
        {
            return false;
        }
        $this->load->model('upload_model');
        $temp_data = $this->uploadImageFromURL($url);
        if (!empty($temp_data) && copy($temp_data['img_url'], $temp_data['full_path'])) {
            $temp_path = $temp_data['full_path'];
            if ($temp_data['image_type'] == 'gif') {
                $gif_path = $this->upload_model->post_gif_image_upload($temp_data['file_name']);
                $data["image_big"] = $gif_path;
                $data["image_default"] = $gif_path;
                $data["image_slider"] = $gif_path;
                $data["image_mid"] = $gif_path;
                $data["image_small"] = $gif_path;
                $data["image_mime"] = 'gif';
                $data["file_name"] = @$temp_data['client_name'];
            } else {
                if($actionType == 'webstory'){
                    $data['image_type'] = 'webstory';
                    $data["image_slider"] = $this->upload_model->post_slider_webstory_image_upload($temp_path);
                    $data["image_big"] = $data["image_slider"];
                    $data["image_default"] = $data["image_slider"];
                    //$data["image_slider"] = $data["image_slider"];
                    $data["image_mid"] = $data["image_slider"];
                    $data["image_small"] = $data["image_slider"];
                }else{
                    $data["image_big"] = $this->upload_model->post_big_image_upload($temp_path);
                    $data["image_default"] = $this->upload_model->post_default_image_upload($temp_path);
                    $data["image_slider"] = $this->upload_model->post_slider_image_upload($temp_path);
                    $data["image_mid"] = $this->upload_model->post_mid_image_upload($temp_path);
                    $data["image_small"] = $this->upload_model->post_small_image_upload($temp_path);
                }
                $data["image_mime"] = 'jpg';
                $data["file_name"] = @$temp_data['client_name'];
            }
            $data["user_id"] = -1;
            $data["storage"] = $this->general_settings->storage;
            @$this->db->close();
            @$this->db->initialize();
            $this->db->insert('images', $data);
            $this->upload_model->delete_temp_image($temp_path);
            //move to s3
            if ($data["storage"] == "aws_s3" && $actionType == 'webstory') {
                $this->load->model("aws_model");
                $this->aws_model->upload_file($data["image_big"]);
                if ($temp_data['image_type'] != 'gif') {
                    $this->aws_model->upload_file($data["image_slider"]);
                }
            }elseif($data["storage"] == "aws_s3"){
                $this->load->model("aws_model");
                $this->aws_model->upload_file($data["image_big"]);
                if ($temp_data['image_type'] != 'gif') {
                    $this->aws_model->upload_file($data["image_default"]);
                    $this->aws_model->upload_file($data["image_slider"]);
                    $this->aws_model->upload_file($data["image_mid"]);
                    $this->aws_model->upload_file($data["image_small"]);
                }
            }
            //$data["image_url"] = $temp_data['img_url'];
            $data["image_storage"] = $data['storage'];
            unset($data['file_name']);
            unset($data['storage']);
            unset($data['user_id']);
            unset($data['image_type']);
            return $data;
        }
        return false;
    }
    private function imageExists($url){
        $this->db->where('images.file_name', basename($url));
        $query = $this->db->get('images');
        $data = $query->row();
        if(isset($data->image_default)){
           return $data->image_default;
        }
        return false;
    }
    private function uploadContentImage($content){
        $data = [];
        $host = $_SERVER['HTTP_HOST'];
        if($host == 'localhost:8888'){
            $host = "http://".$host.'/bitbucket-newsdrum/newsdrum/'; 
        }else{
            $host = "https://".$host.'/';
        }
        $host = "https://newstrack.sgp1.cdn.digitaloceanspaces.com/";

        $source = $destination = [];
        preg_match_all('/<(img|source)[^>]+src="([^"]+)"/', $content, $match);
        if(is_array($match) && count($match)==3 && is_array($match[2])){
            foreach ($match[2] as $key => $value) {
                $resImageExists = $this->imageExists($value);
                if($resImageExists){
                    $source[] = $value;
                    $destination[] = $host.$resImageExists;
                }else{
                    $imageData = $this->uploadImage($value);
                    if(!empty($imageData['image_default'])){
                        $source[] = $value;
                        $destination[] = $host.$imageData['image_default'];
                    }
                }
            }
        }
        $content = str_replace($source,$destination,$content);
        return $content;
    }
    public function updateSpecialFlag($storyIdsArr,$flag)
    {
        $lang_id = $this->input->get('lang_id', TRUE);
        $queryArr = [];
        $query = $this->db->query("select group_concat(id) as ids FROM posts WHERE $flag=1 and lang_id=".$lang_id);
        $dataObj =  $query->row();
        $queryArr[] = $this->db->last_query();
        if(isset($dataObj->ids) && count($storyIdsArr)){
            //$publish_date = date("Y-m-d 04:00:00", strtotime("-7 day"));
            //$this->db->where('posts.created_at >= ',$publish_date);
            $this->db->where_in('posts.id',explode(",",$dataObj->ids));
            $this->db->where('posts.'.$flag,1);
            $this->db->update('posts', [$flag=>0]);
            $queryArr[] = $this->db->last_query();
        }
        if(count($storyIdsArr)){
            $this->db->where_in('posts.id',$storyIdsArr);
            //$this->db->where('posts.created_at >= ',$publish_date);
            $this->db->update('posts', [$flag=>1]);
            $queryArr[] = $this->db->last_query();
            return ["status"=>"UPDATED - OLD:".count(explode(",",$dataObj->ids)).', NEW:'.count($storyIdsArr),"data"=>$queryArr];
        }
        
        return ["status"=>"NO DATA","data"=>$queryArr];
    }
    //update post tags
    public function updatePostTags($post_id,$currentTags)
    {
        //delete old tags
        $this->load->model('tag_model');
        $tags = $this->tag_model->get_post_tags($post_id);
        if (!empty($tags)) {
            foreach ($tags as $tag) {
                //delete
                $this->db->where('id', $tag->id);
                $this->db->delete('tags');
            }
        }
        //add new tags
        //echo $currentTags;die;
        $tags_array = explode(",", $currentTags);
        if (!empty($tags_array)) {
            foreach ($tags_array as $tag) {
                $tag = trim($tag);
                if (strlen($tag) > 1) {
                    $data = array(
                        'post_id' => clean_number($post_id),
                        'tag' => trim($tag),
                        'tag_slug' => str_slug(trim($tag))
                    );

                    if (empty($data["tag_slug"]) || $data["tag_slug"] == "-") {
                        $data["tag_slug"] = "tag-" . uniqid();
                    }
                    //insert tag
                    $this->db->insert('tags', $data);
                }
            }
        }
    }
    public function last_id_backup_post($cron_type,$maxArr = []){
        //return 360067;
        $data = $this->db->select('news_id')->from('posts_backup')->where('cron_type',$cron_type)->order_by('news_id',"desc")->limit(1)->get()->row();
        //echo $this->db->last_query();
        if(isset($data->news_id)){
            return $data->news_id+1;
        }
        return $maxArr[$cron_type]-49999;
    }
    public function makecopyfolder_BK($dateDir,$folder){

        $target_folder_arr = explode("/",$dateDir);
        $year = $target_folder_arr[0];
        $month = $target_folder_arr[1];
        $day = $target_folder_arr[2];

        $root_directory = "/mnt/volume_blr1_01/newstrack-alpha/htdocs/alpha.newstrack.com/";
        #$root_directory = FCPATH;
        
        $directory_year = $root_directory.$folder."/" . $year . "/";
        $directory_month = $root_directory.$folder."/" . $year . "/" . $month . "/";
        $directory_day = $root_directory.$folder."/" . $year . "/" . $month . "/". $day . "/";

        //echo "Creating Directory - ".$directory_day;
        //If the directory doesn't already exists.
        if (!is_dir($directory_day)) {
            //Create directory.
            mkdir($directory_day, 0755, true);
        }
        //add index.html if does not exist
        if (!file_exists($directory_year . "index.html")) {
            copy(BASEPATH . "core/index.html", $directory_year . "index.html");
        }
        if (!file_exists($directory_month . "index.html")) {
            copy(BASEPATH . "core/index.html", $directory_month . "index.html");
        }
        if (!file_exists($directory_day . "index.html")) {
            copy(BASEPATH . "core/index.html", $directory_day . "index.html");
        }
        return $directory_day;

    }
    public function makecopyfolder($directory){
        $host = $_SERVER['HTTP_HOST'];
        /* if($host == 'localhost:8888'){
            $root_directory = FCPATH;
        }else{
            $root_directory = "/mnt/volume_blr1_01/newstrack-alpha/htdocs/alpha.newstrack.com/";
        } */
        $root_directory = FCPATH;
        $directory_day = $root_directory.$directory;
        if (!is_dir($directory_day)) {
            mkdir($directory_day, 0755, true);
        }
        return $directory_day;
    }
    public function imageCopy($url,$webp = ""){
        if($webp != 'webp'){
            $url = str_replace(".webp",".jpg",$url);
        }
        if(strpos($url, '//newstrack.com/') !== false){
            return false;
        }
        $url = str_replace("//newstrack.com","//api.newstrack.com",$url);
        $url_path = explode("/h-upload/",$url)[1];
        $directory = 'uploads/images/'.str_replace(basename($url_path),'',$url_path);
        $directory_final = $this->makecopyfolder($directory);
        $directory_final_with_img = $directory_final . basename($url);
        $dataArr = [];
        $dataArr['upload_from'] = $url;
        $dataArr['upload_to'] = $directory_final_with_img;
        copy($url,$directory_final_with_img);
        if($this->general_settings->storage == 'aws_s3'){
            $this->load->model("aws_model");
            $this->aws_model->upload_file($directory.basename($url));
            $dataArr['upload_to_s3'] = $directory.basename($url);
        }
        pr($dataArr);
    }
    public function videoCopy($date,$url,$image_url){
        
        $directory = "uploads/videos/".date("Y/m/d",strtotime($date));
        $directory_final = $this->makecopyfolder($directory);
        $directory_final_with_video = $directory_final . str_replace(['.gif','.jpg','.jpeg','.png','.webp'],'.mp4',basename($image_url));
        $dataArr = [];
        $dataArr['video_upload_from'] = $url;
        $dataArr['video_upload_from'] = $directory_final_with_video;
        copy($url,$directory_final_with_video);
        if($this->general_settings->storage == 'aws_s3'){
            $this->load->model("aws_model");
            $this->aws_model->upload_file($directory.basename($url));
            $dataArr['upload_to_s3'] = $directory.basename($url);
        }
        pr($dataArr);
    }
    public function add_backup_log($data){
        return $this->db->insert('post_backup_log', $data);
    }
    public function add_backup_post($itemObj,$i,$cron_type){
        
        try {
            if(is_array($itemObj) && count($itemObj)){
                $item = $itemObj[0];
                if (!empty($item->media[0]->url) && strpos($item->media[0]->url, 'h-upload') !== false ) {
                    if(!empty($item->mediaId)){
                        $imageData = $this->imageCopy($item->mediaId);
                    }else{
                        $imageData = $this->imageCopy($item->media[0]->url);
                    }
                }elseif(!empty($item->mediaId)){
                    $imageData = $this->imageCopy($item->mediaId);
                }
                $has_video = 0;
                //&& strpos($item->videoUrl, 'www.youtube.com') === false
                if (isset($item->videoUrl) && !empty($item->videoUrl)) {
                    $has_video = 1;
                    //$this->videoCopy($item->date_created,$item->videoUrl,$item->mediaId);
                }
                if($item->story){
                    $this->uploadBackupContentImage($item->story);
                }
                $lang_id = 1;
                if(strpos($item->url, 'english.newstrack.com') !== false){
                    $lang_id = 2;
                }elseif(strpos($item->url, 'apnabharat.org') !== false){
                    $lang_id = 3;
                }
                $data = [
                    "news_id"=>$item->newsId,
                    "lang_id"=>$lang_id,
                    "created_at"=>$item->date_created,
                    "updated_at"=>$item->date_news,
                    "has_data"=>1,
                    "has_video"=>$has_video,
                    "cron_type"=>$cron_type,
                    "news_json"=>json_encode($itemObj)
                ];
                return $this->db->insert('posts_backup', $data);
            }else{
             
                return false;

               /*  $data = [
                    "news_id"=>$i,
                    "lang_id"=>0,
                    "has_data"=>0,
                    "has_video"=>0,
                    "cron_type"=>$cron_type,
                    "created_at"=>date("Y-m-d H:i:s"),
                    "updated_at"=>date("Y-m-d H:i:s"),
                    "news_json"=>'{}'
                ];
                return $this->db->insert('posts_backup', $data); */
            }
        }catch (\Exception $e) {
            echo $e->getMessage();
        }
        return false;
    }
    private function uploadBackupContentImage($content){
        $source = $destination = [];
        preg_match_all('/<(img|source)[^>]+src="([^"]+)"/', $content, $match);
        if(is_array($match) && count($match)==3 && is_array($match[2])){
            foreach ($match[2] as $key => $value) {
                if ($value && strpos($value, 'h-upload') !== false ) {
                    $this->imageCopy($value);
                }
            }
        }
    }
    public function last_id_backup_photo($cron_type,$maxArr = []){
        //return 360067;
        $data = $this->db->select('news_id')->from('posts_backup_photo')->where('cron_type',$cron_type)->order_by('news_id',"desc")->limit(1)->get()->row();
        //echo $this->db->last_query();
        if(isset($data->news_id)){
            return $data->news_id+1;
        }
        return $maxArr[$cron_type]-4999;
    }
    public function add_backup_log_photo($data){
        return $this->db->insert('post_backup_log_photo', $data);
    }
    public function add_backup_photo($itemObj,$i,$cron_type){
        
        try {
            if(is_array($itemObj) && count($itemObj)){
                $item = $itemObj[0];
                if(isset($item->slides) && is_array($item->slides)){
                    foreach($item->slides as $key=>$photo){
                        if(!empty($photo->slide_attributes[0]->value) && strpos($photo->slide_attributes[0]->value, 'h-upload') !== false){
                            //$this->imageCopy($photo->slide_attributes[0]->value);
                        }
                    }
                }
                if (!empty($item->imageUrl) && strpos($item->imageUrl, 'h-upload') !== false ) {
                    //$this->imageCopy($item->imageUrl);
                }
                $lang_id = 1;
                if(strpos($item->imageUrl, 'english.newstrack.com') !== false){
                    $lang_id = 2;
                }elseif(strpos($item->imageUrl, 'apnabharat.org') !== false){
                    $lang_id = 3;
                }
                $data = [
                    "news_id"=>$item->uid,
                    "lang_id"=>$lang_id,
                    "created_at"=>$item->date_news,
                    "updated_at"=>$item->date_news,
                    "has_data"=>1,
                    "has_video"=>0,
                    "cron_type"=>$cron_type,
                    "news_json"=>json_encode($itemObj)
                ];
                if($lang_id == 1){
                    $data["live_status"] = 2;
                }
                return $this->db->insert('posts_backup_photo', $data);
            }else{
                $data = [
                    "news_id"=>$i,
                    "lang_id"=>0,
                    "has_data"=>0,
                    "has_video"=>0,
                    "cron_type"=>$cron_type,
                    "created_at"=>date("Y-m-d H:i:s"),
                    "updated_at"=>date("Y-m-d H:i:s"),
                    "news_json"=>'{}'
                ];
                return $this->db->insert('posts_backup_photo', $data);
            }
        }catch (\Exception $e) {
            echo $e->getMessage();
        }
        return false;
    }
    public function uploadAuthorImage($url,$user_id){
        
        $url = str_replace(".webp",".jpg",$url);
        $this->load->model('upload_model');
        $temp_data = $this->uploadImageFromURL($url);
        if (!empty($temp_data) && copy($temp_data['img_url'], $temp_data['full_path'])) {
            $temp_path = $temp_data['full_path'];
            $avatar = $this->upload_model->avatar_upload_new($user_id, $temp_path , 240, 240);
            $this->upload_model->delete_temp_image($temp_path);
            //delete old
            if($this->general_settings->storage == 'aws_s3'){
                $this->load->model("aws_model");
                $this->aws_model->upload_file($avatar);
            }
            return $avatar;
        }
        return "";
    }
}
