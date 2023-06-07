<?php
ini_set('max_execution_time', '300');
#use Intervention\Image\Size;

defined('BASEPATH') or exit('No direct script access allowed');

class Backup_controller extends Core_Controller
{
    private $imageArray = [];
    public function __construct()
    {
        parent::__construct();
    }
    public function backup_restore(){
        
        $this->load->model('newstrackrss_model');
        $dataObj = $this->db->select('*')->from('posts_backup')->where('has_data',1)->where_in('lang_id',[1,2,3])->where('live_status IS NULL')->order_by('news_id',"desc")->limit(5)->get()->result();
        $resArr = [];
        $ids = [];
        if(count($dataObj)){
            foreach ($dataObj as $data) {
                $ids[] = $data->news_id;
            }
            $this->db->where_in('news_id',$ids);
            $this->db->update('posts_backup', ["live_status"=>2]);
            ob_end_clean();
            header("Content-Encoding: none");
            header("Connection: close");
            ignore_user_abort();
            ob_start();
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => 200,
                'message' => "Start backup restore in background...",
                'ids'=>implode(",",$ids)
            ));
            $size = ob_get_length();
            header("Content-Length: $size");
            ob_end_flush();
            flush();
            session_write_close();
            if (is_callable('fastcgi_finish_request')) {
                fastcgi_finish_request();
            }
            foreach ($dataObj as $data) {
                if(isset($data->news_json) && !empty($data->news_json)){
                    /* if($data->lang_id==1){
                        continue;
                    } */
                    $feed_item = json_decode($data->news_json)[0];
                    $set_data = $this->newstrackrss_model->set_data($feed_item,$data);
                    $live_status = 1;
                    /* $postResArr = $this->newstrackrss_model->checkOldPostExists($feed_item);
                    if($postResArr["slno_post"] && !$postResArr["matchedSlug"]){
                        $tblData = (object)[];
                    }elseif($postResArr["slno_post"] && $postResArr["matchedSlug"]){
                        $tblData = $postResArr["slno_post"];
                    }
                    if(!isset($tblData->id)){
                        $live_status = 3;
                        if($postResArr["slno_post"]){
                            unset($set_data["id"]);
                            $live_status = 4;
                        } */
                        $this->db->insert('posts', $set_data);
                        /* $feed_item->newsId = $this->db->insert_id();
                        $set_data["id"] = $feed_item->newsId; */

                        $this->tag_model->add_post_tags($feed_item->newsId, $feed_item->tags);
                        $this->newstrackrss_model->add_post_cat($feed_item->newsId,$this->newstrackrss_model->set_cat_data($set_data));
                        
                        $this->db->where('news_id',$data->news_id);
                        $this->db->update('posts_backup', ["live_status"=>$live_status]);
                        $resArr[] = ['newsId'=>$data->news_id,'status'=>'Ok'];

                    /* }else{
                        $this->db->where('news_id',$data->news_id);
                        $this->db->update('posts_backup', ["live_status"=>3]);
                        $resArr[] = ['newsId'=>$data->news_id,'status'=>'Ok'];

                    } */
                    
                }else{
                    $resArr[] = ['newsId'=>$data->news_id,'status'=>'No Data in json'];
                }
            }
        }
        echo json_encode($resArr);
    }
    public function backup_restore_photo(){
        
        $this->load->model('newstrackrss_model');
        $dataObj = $this->db->select('*')->from('posts_backup_photo')->where('has_data',1)->where('live_status IS NULL')->order_by('news_id',"desc")->limit(10)->get()->result();
        $resArr = [];
        $ids = [];
        if(count($dataObj)){
            foreach ($dataObj as $data) {
                $ids[] = $data->news_id;
            }
            $this->db->where_in('news_id',$ids);
            $this->db->update('posts_backup_photo', ["live_status"=>2]);
            /* ob_end_clean();
            header("Content-Encoding: none");
            header("Connection: close");
            ignore_user_abort();
            ob_start();
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => 200,
                'message' => "Start backup restore in background...",
                'ids'=>implode(",",$ids)
            ));
            $size = ob_get_length();
            header("Content-Length: $size");
            ob_end_flush();
            flush();
            session_write_close();
            if (is_callable('fastcgi_finish_request')) {
                fastcgi_finish_request();
            } */
            foreach ($dataObj as $data) {
                if(isset($data->news_json) && !empty($data->news_json)){
                    if($data->lang_id==1){
                        continue;
                    }
                    $feed_item = json_decode($data->news_json)[0];
                    $set_data = $this->newstrackrss_model->set_photo_story_data($feed_item);
                    
                    $webstories_items = $set_data['webstories_items'];
                    if(isset($set_data['webstories_items'])){
                        unset($set_data['webstories_items']); 
                    }
                    $this->db->insert('posts_webstory', $set_data);
                    if(is_array($webstories_items) && count($webstories_items)){
                        foreach($webstories_items as $webstory){
                            $this->db->insert('webstories_items', $webstory);
                        }
                    }
                    $this->newstrackrss_model->update_post_cat($feed_item->uid,$this->newstrackrss_model->set_cat_data($set_data),$set_data['post_type']);
                    
                    
                    $this->db->where('news_id',$data->news_id);
                    $this->db->update('posts_backup_photo', ["live_status"=>1]);
                    $resArr[] = ['newsId'=>$data->news_id,'status'=>'Ok'];
                }else{
                    $resArr[] = ['newsId'=>$data->news_id,'status'=>'No Data in json'];
                }
            }
        }
        echo json_encode($resArr);
    }
    /* public function set_data($feed_item,$tblData)
    {
        $this->load->model('newstrackrss_model');
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

        /* Category Logic *-/
        $is_featured = $is_trending = 0;
        $is_featured = 0;
        $maincategory = $feed_item->maincategory;
        $maincat_name = $feed_item->maincat_name;
        $category_color = "#000000";
        if(is_array($feed_item->categories)){
            /* if(in_array(204,$feed_item->categories)){
                $is_featured = 1;
            } *-/
            if(in_array(214,$feed_item->categories)){
                $is_trending = 1;
            }
            $child_cat_id = end($feed_item->categories);
            if($child_cat_id == 204){
                $child_cat_id = prev($feed_item->categories);
            }
            $catData = $this->newstrackrss_model->getData('categories',['id','name','name_slug','color'],'id',$child_cat_id);
            if(isset($catData->id)){
                $maincategory = $catData->id;
                $maincat_name = $catData->name;
                $category_color = $catData->color;
            }
        }
        /* End Category Logic *-/

        $tableData = array(
            'id'=>(int)$feed_item->newsId,
            'reporter_id' => 0,
            'updated_at' => date("Y-m-d H:i:s", strtotime($feed_item->date_news)),
            'created_at' => date("Y-m-d H:i:s", strtotime($feed_item->date_created)),
            'lang_id' => $tblData->lang_id,
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
            'content' => $feed_item->story,
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
            'post_type' => "article",
            'status' => 1,
            'visibility' => 1,
            'cat_ids' => json_encode($feed_item->categories),
        );

        $imgTableData = [];
        $imgTableData["image_big"] = "";
        $imgTableData["image_default"] = "";
        $imgTableData["image_slider"] = "";
        $imgTableData["image_mid"] = "";
        $imgTableData["image_small"] = "";
        $imgTableData["image_mime"] = "jpg";
        $imgTableData["image_url"] = "";
        $imgTableData["image_storage"] = "local";
        //pr($feed_item->media[0]);
        if (!empty($feed_item->media[0]->url) && strpos($feed_item->media[0]->url, 'h-upload') !== false ) {
            if(!empty($feed_item->mediaId)){
                $this->imageArray[] = ["url"=>$feed_item->mediaId,"source"=>$this->getImagePath($feed_item->mediaId),"isContentImage"=>0];
            }else{
                $this->imageArray[] = ["url"=>$feed_item->media[0]->url,"source"=>$this->getImagePath($feed_item->media[0]->url),"isContentImage"=>0];
            }
        }elseif(!empty($feed_item->mediaId)){
            $this->imageArray[] = ["url"=>$feed_item->mediaId,"source"=>$this->getImagePath($feed_item->mediaId),"isContentImage"=>0];
        }
        if($feed_item->story){
            $this->uploadBackupContentImage($feed_item->story);
        }
        
        return $tableData;
    }
    private function getImagePath($url,$webp = ""){
        if($webp != 'webp'){
            $url = str_replace(".webp",".jpg",$url);
            $url = str_replace("/500x300_","/",$url);
        }
        $url_path = explode("/h-upload/",$url)[1];
        $directory = 'uploads/images/'.str_replace(basename($url_path),'',$url_path);
        $directory_final = $this->makecopyfolder($directory);
        $directory_final_with_img = $directory_final . basename($url);
        return $directory_final_with_img;
    }
    private function uploadBackupContentImage($content){
        $source = $destination = [];
        preg_match_all('/<(img|source)[^>]+src="([^"]+)"/', $content, $match);
        if(is_array($match) && count($match)==3 && is_array($match[2])){
            foreach ($match[2] as $key => $value) {
                if ($value && strpos($value, 'h-upload') !== false ) {
                    $this->getImagePath($value);
                    $this->imageArray[] = ["url"=>$value,"source"=>$this->getImagePath($value),"isContentImage"=>1];
                }
            }
        }
    }
    public function makecopyfolder($directory){
        $host = $_SERVER['HTTP_HOST'];
        if($host == 'localhost:8888'){
            $root_directory = FCPATH;
        }else{
            $root_directory = "/mnt/volume_blr1_01/newstrack-alpha/htdocs/alpha.newstrack.com/";
        }
        $directory_day = $root_directory.$directory;
        if (!is_dir($directory_day)) {
            mkdir($directory_day, 0755, true);
        }
        return $directory_day;
    } */
}
