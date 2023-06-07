<?php
ini_set('max_execution_time', '300');
use Intervention\Image\Size;

defined('BASEPATH') or exit('No direct script access allowed');

class Cron_controller extends Core_Controller
{
   public $catArr = [];
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Check Feed Posts
     */
    public function check_feed_posts()
    {
        get_method();
        //load the library
        $this->load->library('rss_parser');
        //unset all feeds
        $feeds_not_updated = $this->rss_model->get_feeds_not_updated();
        if (empty($feeds_not_updated)) {
            //update feeds
            $this->db->update('rss_feeds', ['is_cron_updated' => 0]);
        }
        //add posts
        $feeds = $this->rss_model->get_feeds_cron();
        if (!empty($feeds)) {
            foreach ($feeds as $feed) {
                if (!empty($feed->feed_url)) {
                    $this->rss_model->add_feed_posts($feed->id);
                    //update feed
                    $data = array(
                        'is_cron_updated' => 1
                    );
                    $this->db->where('id', $feed->id);
                    $this->db->update('rss_feeds', $data);
                }
            }
            reset_cache_data_on_change();
        }
        echo "Feeds have been checked!";
    }

    /**
     * Check Scheduled Posts
     */
    public function check_scheduled_posts()
    {
        get_method();
        $this->post_admin_model->check_scheduled_posts();
    }

    /**
     * Update Sitemap
     */
    public function update_sitemap()
    {
        get_method();
        $this->load->model('sitemap_model');
        $this->sitemap_model->generate_sitemap();
    }

    public function photo_story_crawling()
    {
        get_method();
        //echo "<pre>";
        $parameters = $_SERVER['QUERY_STRING'];
        $this->load->model('newstrackrss_model');
        $items = $this->getDataFromAPI('photo-story',$parameters,'stories');
        //print_r($items);die;
        //$feedData = $this->curlFn('https://newstrack.com/dev/h-api/news?count=100', 'GET', '', ["s-id:OLSpCwNcW0cdFZWOUxTKCgv6KuzXJFvFYMl5GaGL7eowVYt4k0Q5X7jqZ75WM0Dq"]);
        //$items = json_decode($feedData);
        $resArr = ['updated'=>0,'new'=>0,'urls'=>[]];
        if ($items) {
            $cnt = 0;
            foreach ($items as $item) {
                /* if($cnt>0){
                    continue;
                } */
                //if($item->newsId != 359607) continue;
                try {
                    $res = $this->newstrackrss_model->add_photo_story($item);
                    if ($res == 'UPDATED') {
                        $resArr['urls'][] = ['db_action'=>'UPDATED','url'=>$item->url];
                        $resArr['updated']++;
                    }elseif ($res == 'NEW') {
                        $resArr['urls'][] = ['db_action'=>'NEW','url'=>$item->url];
                        $resArr['new']++;
                    }
                } catch (Exception $e) {
                   $resArr['error'][] = ['msg'=>$e->getMessage(),'url'=>$item->url];
                }
                $cnt++;
            }
        }else{
            $resArr['msg'] = "NO DATA IN API";
        }
        reset_cache_data_on_change();
        print(json_encode($resArr));
    }
    public function photo_story_backup_crawling()
    {
        get_method();
        $maxArr = [1=>5000,2=>10000,3=>15000];

        $maxLoop = 5;
        $newsId = $this->input->get('uid', TRUE);
        $cron_type = $this->input->get('cron_type', TRUE);
        if(!$cron_type){
            pr("cron_type need in URL");die;
        }
        $this->load->model('newstrackrss_model');
        if(!empty($newsId)){
            $last_id = $newsId;
            $maxLoop = 0;
        }else{
            $last_id = $this->newstrackrss_model->last_id_backup_photo($cron_type,$maxArr);
        }
        //pr($last_id);
        //pr($last_id ." > " .$maxArr[$cron_type]. " ||  $last_id < ".($maxArr[$cron_type]-4999),"---------");
        if($last_id > $maxArr[$cron_type] || $last_id < ($maxArr[$cron_type]-4999)){
            pr("NO Action for this storyid : ".$last_id);die;
        }
        //pr("Ok,===$last_id======".($maxArr[$cron_type]-50000),"---------");
        //die;
        $cnt = 0;
        ob_end_clean();
        header("Content-Encoding: none");
        header("Connection: close");
        ignore_user_abort();
        ob_start();
        header('Content-Type: application/json');
        echo json_encode(array(
            'status' => 200,
            'message' => "Start crawling data in background...",
            'from'=>(int)$last_id,
            'to'=>($last_id+$maxLoop)
        ));
        $size = ob_get_length();
        header("Content-Length: $size");
        ob_end_flush();
        flush();
        session_write_close();
        if (is_callable('fastcgi_finish_request')) {
            fastcgi_finish_request();
        }
        $timestamp = time().$cron_type;
        $this->newstrackrss_model->add_backup_log_photo([
            "timestamp"=>$timestamp,
            "cron_type"=>$cron_type,
            "json_data"=>json_encode(["msg"=>"START",'url'=>$_SERVER['REQUEST_URI']])
        ]);
        try {
            for($i=$last_id;$i<=$last_id+$maxLoop;$i++){
                $item = $this->getDataFromAPI('photo-story','uid='.$i,'stories');
                //pr($item);
                $res = $this->newstrackrss_model->add_backup_photo($item,$i,$cron_type);
                $cnt++;
            }
            $this->newstrackrss_model->add_backup_log_photo([
                "timestamp"=>$timestamp,
                "cron_type"=>$cron_type,
                "json_data"=>json_encode(["msg"=>"END",'url'=>$_SERVER['REQUEST_URI'],"extra"=>['Total insered '=>$cnt,'from'=>$last_id,'to'=>($last_id+$maxLoop)]])
            ]);
        } catch (Exception $e) {
            $this->newstrackrss_model->add_backup_log_photo([
                "timestamp"=>$timestamp,
                "cron_type"=>$cron_type,
                "json_data"=>json_encode(["msg"=>$e->getMessage(),'url'=>$_SERVER['REQUEST_URI']])
            ]);
        }
        echo json_encode(['Total insered '=>$cnt,'from'=>$last_id,'to'=>($last_id+$maxLoop)]);
    }
    public function post_backup_crawling()
    {
        get_method();
        $maxArr = [1=>50000,2=>100000,3=>150000,4=>200000,5=>250000,6=>300000,7=>350000,8=>400000];

        $maxLoop = 14;
        $newsId = $this->input->get('newsId', TRUE);
        $cron_type = $this->input->get('cron_type', TRUE);
        if(!$cron_type){
            pr("cron_type need in URL");die;
        }
        $this->load->model('newstrackrss_model');
        if(!empty($newsId)){
            $last_id = $newsId;
            $maxLoop = 0;
        }else{
            $last_id = $this->newstrackrss_model->last_id_backup_post($cron_type,$maxArr);
        }
        //pr($last_id);
        //pr($last_id ." > " .$maxArr[$cron_type]. " ||  $last_id < ".($maxArr[$cron_type]-49999),"---------");
        if($last_id > $maxArr[$cron_type] || $last_id < ($maxArr[$cron_type]-49999)){
            pr("NO Action for this storyid : ".$last_id);die;
        }
        //pr("Ok,===$last_id======".($maxArr[$cron_type]-50000),"---------");
        //die;
        $cnt = 0;
        ob_end_clean();
        header("Content-Encoding: none");
        header("Connection: close");
        ignore_user_abort();
        ob_start();
        header('Content-Type: application/json');
        echo json_encode(array(
            'status' => 200,
            'message' => "Start crawling data in background...",
            'from'=>(int)$last_id,
            'to'=>($last_id+$maxLoop)
        ));
        $size = ob_get_length();
        header("Content-Length: $size");
        ob_end_flush();
        flush();
        session_write_close();
        if (is_callable('fastcgi_finish_request')) {
            fastcgi_finish_request();
        }
        $timestamp = time().$cron_type;
        $this->newstrackrss_model->add_backup_log([
            "timestamp"=>$timestamp,
            "cron_type"=>$cron_type,
            "json_data"=>json_encode(["msg"=>"START",'url'=>$_SERVER['REQUEST_URI']])
        ]);
        try {
            for($i=$last_id;$i<=$last_id+$maxLoop;$i++){
                $item = $this->getDataFromAPI('news','newsId='.$i,'news');
                $res = $this->newstrackrss_model->add_backup_post($item,$i,$cron_type);
                $cnt++;
            }
            $this->newstrackrss_model->add_backup_log([
                "timestamp"=>$timestamp,
                "cron_type"=>$cron_type,
                "json_data"=>json_encode(["msg"=>"END",'url'=>$_SERVER['REQUEST_URI'],"extra"=>['Total insered '=>$cnt,'from'=>$last_id,'to'=>($last_id+$maxLoop)]])
            ]);
        } catch (Exception $e) {
            $this->newstrackrss_model->add_backup_log([
                "timestamp"=>$timestamp,
                "cron_type"=>$cron_type,
                "json_data"=>json_encode(["msg"=>$e->getMessage(),'url'=>$_SERVER['REQUEST_URI']])
            ]);
        }
        echo json_encode(['Total insered '=>$cnt,'from'=>$last_id,'to'=>($last_id+$maxLoop)]);
    }
    public function post_crawling()
    {
        get_method();
        $parameters = $_SERVER['QUERY_STRING'];
        $this->load->model('newstrackrss_model');
        $items = $this->getDataFromAPI('news','count=100&'.$parameters,'news');
        //print_r($items);die;
        //$feedData = $this->curlFn('https://newstrack.com/dev/h-api/news?count=100', 'GET', '', ["s-id:OLSpCwNcW0cdFZWOUxTKCgv6KuzXJFvFYMl5GaGL7eowVYt4k0Q5X7jqZ75WM0Dq"]);
        //$items = json_decode($feedData);
        if ($items) {
            $cnt = 0;
            foreach ($items as $item) {
                //if($item->newsId != 359607) continue;
                try {
                    $res = $this->newstrackrss_model->add_post($item);
                    if ($res == 'UPDATED') {
                        $cnt++;
                        echo "<br>Post Updated : " . $item->url;
                    }elseif ($res == 'NEW') {
                        $cnt++;
                        echo "<br>Post Created : " . $item->url;
                    }elseif ($res == 'UPDATED_NEW') {
                        $cnt++;
                        echo "<br>Post Created : " . $item->url;
                    }
                } catch (Exception $e) {
                    print_r($item);
                    echo $e->getMessage();
                }
            }
            if($cnt){
                echo "<br>Total Articles Published : ".$cnt;
            }else{
                echo "<br>No data to publish";
            }
            pr($this->db->queries);
        }
    }

    public function category_crawling()
    {
        get_method();
        echo "<pre>";
        $parameters = $_SERVER['QUERY_STRING'];
        $this->load->model('newstrackrss_model');
        for($i=0;$i<=200;$i++){
            $cnt = 0;
            $items = $this->getDataFromAPI('category','count=1&startIndex='.$i,'categories');
            if($items){
                foreach ($items  as $item) {
                    //if($item->catId != 221) continue;
                    $cnt++;
                    $cat_data = $this->set_category_data($item);
                    //print_r($cat_data);
                    //$this->newstrackrss_model->add_category($cat_data);
                    $items2 = $this->getDataFromAPI('category','parentId='.$cat_data['id'],'categories');
                    if($items2){
                        foreach ($items2  as $item2) {
                            $cnt++;
                            $cat_data2 = $this->set_category_data($item2);
                            //$this->newstrackrss_model->add_category($cat_data2);
                            //print_r($cat_data2);
                            $items3 = $this->getDataFromAPI('category','parentId='.$cat_data2['id'],'categories');
                            if($items3){
                                foreach ($items3  as $item3) {
                                    $cnt++;
                                    $cat_data3 = $this->set_category_data($item3);
                                    //$this->newstrackrss_model->add_category($cat_data3);
                                    //print_r($cat_data3);
                                    $items4 = $this->getDataFromAPI('category','parentId='.$cat_data3['id'],'categories');
                                    if($items4){
                                        foreach ($items4  as $item4) {
                                            $cnt++;
                                            $cat_data4 = $this->set_category_data($item4);
                                            //$this->newstrackrss_model->add_category($cat_data4);
                                            //print_r($cat_data4);
                                        }
                                    }
                                }
                            }
                        }
                    }
                    //echo "<br>=======".$cat_data['id']."==========<br>";
                }
            }
        }
        foreach ($this->catArr  as $v) {
            $this->newstrackrss_model->add_category($v);
        }
        echo "Total Category : ".count($this->catArr);
        
    }
    public function flag_update_crawling()
    {
        get_method();
        $categoryIds = $this->input->get('categoryIds', TRUE);
        $flag_name = $this->input->get('flag_name', TRUE);
        /* $specialArr = [204=>'is_featured',214=>''];
        if(!in_array($categoryIds,[204,214])){
            echo "invalid request";
            die;    
        } */
        $this->load->model('newstrackrss_model');
        $items = $this->getDataFromAPI('news','categoryIds='.$categoryIds,'news');
        print_r($items);die;
        if ($items) {
            $storyIDs = [];
            foreach ($items as $item) {
                $this->newstrackrss_model->add_post($item);
                $storyIDs[] = $item->newsId;
            }
            //print_r($storyIDs);die;
            try {
                $res = $this->newstrackrss_model->updateSpecialFlag($storyIDs,$flag_name);    
                echo json_encode($res);
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
    }
    public function getDataFromAPI($apiName,$queryString,$dataVariable){

        $lang_id = $this->input->get('lang_id', TRUE);
        if($lang_id == 2){
            $api = 'https://english.newstrack.com/dev/h-api/'.$apiName.'?'.$queryString;
            $feedData = $this->curlFn( $api,'GET', '',["s-id:jUXgFkXkoPZlDQoUzdrrQOo8X9iJ9uiZt146sur6K4nKtaoHn7M51qpB5K0kQHse"]);
        }elseif($lang_id == 3){
            $api = 'https://apnabharat.org/dev/h-api/'.$apiName.'?'.$queryString;
            $feedData = $this->curlFn( $api,'GET', '',["s-id:QDSlOxBjOz4n9zEihGq0sn0VYhGuDMHUPtVfWfch0JDYqoFCFHnR8RvhvitY9Nw2"]);
        }else{
            $api = 'https://api.newstrack.com/dev/h-api/'.$apiName.'?'.$queryString;
            $feedData = $this->curlFn( $api,'GET', '',["s-id:OLSpCwNcW0cdFZWOUxTKCgv6KuzXJFvFYMl5GaGL7eowVYt4k0Q5X7jqZ75WM0Dq"]);
        }
        
        
        $items = json_decode($feedData);
        //print_r($items);
        if (is_object($items)) {
            return $items->$dataVariable;     
        }
        return false;
    }
    public function set_category_data($item){
        $url = explode("/",$item->url);
        $name_slug = end($url);
        $description = "";
        /* try {
            if(isset($item->description)){
                $description = iconv("UTF-8", "ISO-8859-1", $item->description);
            }
        } catch (Exception $e) {
           //code here 
        } */
        $lang_id = $this->input->get('lang_id', TRUE);
        $categoryArr = [
            'id'=>$item->catId, 
            'lang_id'=>(!empty($lang_id) && $lang_id==2)?2:((!empty($lang_id) && $lang_id==3)?3:1), 
            'name'=>(isset($item->name))?$item->name:'',  
            'name_slug'=>$name_slug, 
            'parent_id'=>(!empty($item->parentId))?$item->parentId:0,  
            'title'=>(isset($item->page_title))?$item->page_title:'',  
            'description'=>(isset($item->description))?$item->description:'', 
            'keywords'=>(isset($item->keywords))?$item->keywords:'',  
            'color'=>'#000000',
            //'url'=>$item->url,
            'created_at'=>date("Y-m-d H:i:s")
        ];
        $this->catArr[$item->catId] = $categoryArr;
        return $categoryArr;
    }
    public function authors_crawling(){

        $this->load->model('newstrackrss_model');
        $authorsArr = [];
        echo "<pre>";
        for($i=0;$i<=500;$i++){
            $items = $this->getDataFromAPI('authors','count=1&startIndex='.$i,'authors');
            if($items){
                foreach ($items  as $item) {
                    $authorsArr[$item->userId] = $item;
                }
            }
        }
        $cnt = 0;
        foreach ($authorsArr as $key => $item) {
            if(empty($item->name)) continue;
            $udata = [
                'id'=>$key,
                'username'=>$item->name,
                'slug'=>str_replace(" ","-",strtolower($item->name)),
                'email'=>$item->email,
                'email_status'=>(isset($item->email_verified) && $item->email_verified == 'true')?1:0,
                'token'=>'61f7fc7cae85a2-41149377-97232227',
                'password'=>'$2y$10$t4hQFfFsIU1cssCStu8yyO.0hpfn.SpnMhtKkPwmt9wSOMgfjOmzu',
                'role'=>'author',
                'user_type'=>'registered',
                'status'=>1,
                'last_seen'=>date("Y-m-d H:i:s"),
                'created_at'=>date("Y-m-d H:i:s")
            ];
            //pr($udata);
            $cnt++;
            $this->db->where('users.id',$key);
            $query = $this->db->get('users');
            $data = $query->row();
            if(!isset($data->id)){
                if(!empty($item->profile_image)){
                    //$udata['avatar'] = $this->newstrackrss_model->uploadAuthorImage($item->profile_image,$key);
                }
                $this->db->insert('users', $udata);
            }else{
                $this->db->where('id',$key);
                $this->db->update('users', $udata);
            }
        }
        echo "Total Authors : ".$cnt;
    }
    public static function curlFn($posturl, $postmethod, $postData = '', $postHeader = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $posturl);
        $headerArray = array();
        $headerArray = array('Expect:', 'Content-Type: application/json');
        if ($postHeader) {
            foreach ($postHeader as $val) {
                $headerArray[] = $val;
            }
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArray);
        if ($postmethod == 'POST') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        } else if ($postmethod == 'DELETE') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        } else if ($postmethod == 'PATCH') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
            if (!empty($postData)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            }
        } else if ($postmethod == 'put') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 20000);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        $result = curl_exec($ch);
        $err  = curl_error($ch);
        if ($err) {
            return $err;
        }
        return $result;
    }
    public function posts_sitemap($type){
        
        $this->load->model("sitemap_model");
        $paramArr = ["post_type"=>"posts","type"=>$type,"default_limit"=>1000,"hours"=>48];
        $page = $this->input->get('pg', TRUE);
        $limit = $this->input->get('limit', TRUE);
        if(isset($page)){
            $page                   =   (int)$page;
            $limitwebpage           =   (!empty($limit))?$limit:500;
            $startwebpage	        =   ( $page <=1 )?0:( $page -1 )*$limitwebpage;
            $paramArr['offset']     =   $startwebpage;
            $paramArr['per_page']   =   $limitwebpage;
        }
        $items1 = $this->sitemap_model->get_posts_sitemap($paramArr);
        $paramArr["post_type"] = "webstory";
        $items2 = $this->sitemap_model->get_posts_sitemap($paramArr);
        $items = (object) array_merge(
            (array) $items1, (array) $items2);

        $res ='<?xml version="1.0" encoding="UTF-8"?>';
        $res.='<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';
        foreach($items as $item){
            $url = site_url().''.strtolower($item->category_slug).'/'.$item->title_slug.'-'.$item->id;
            $datetime = new DateTime($item->created_at);
            $time_created = $datetime->format('Y-m-d\TH:i:sP');

            $lang = "hi";
            $res.="\t\t<url>\n";

            $res.="\t\t\t<loc>".stripslashes($url)."</loc>\n";
                $res.="\t\t\t<news:news>\n";
                $res.="\t\t\t\t<news:publication>\n";
                $res.="\t\t\t\t<news:name><![CDATA[ Newstrack ]]></news:name>";
                $res.="\t\t\t\t<news:language>".$lang."</news:language>";
                $res.="\t\t\t\t</news:publication>\n";
                $res.="\t\t\t\t<news:publication_date>".$time_created."</news:publication_date>";
                $res.="\t\t\t\t<news:title><![CDATA[".$item->title."]]></news:title>";
                $res.="\t\t\t\t<news:keywords><![CDATA[".$item->keywords."]]></news:keywords>";
                $res.="\t\t\t</news:news>\n";
            $res.="\t\t</url>\n";	

        }
        $res .='
        </urlset>';
        header('Content-Type: application/xml; charset=utf-8');
        echo $res;
    }
    public function posts_sitemap_generic($type){
        
        $this->load->model("sitemap_model");
        $paramArr = ["post_type"=>"posts","type"=>$type,"default_limit"=>1000,"hours"=>48];
        if($type == "webstory"){
            $paramArr = ["post_type"=>"webstory","type"=>$type,"default_limit"=>1000,"hours"=>48];    
        }
        $page = $this->input->get('pg', TRUE);
        $limit = $this->input->get('limit', TRUE);
        if(isset($page)){
            $page                   =   (int)$page;
            $limitwebpage           =   (!empty($limit))?$limit:500;
            $startwebpage	        =   ( $page <=1 )?0:( $page -1 )*$limitwebpage;
            $paramArr['offset']     =   $startwebpage;
            $paramArr['per_page']   =   $limitwebpage;
        }
        $items = $this->sitemap_model->get_posts_sitemap($paramArr);
        $res ='<?xml version="1.0" encoding="UTF-8"?>';
        $res.='<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';
        foreach($items as $item){
            $url = site_url().''.strtolower($item->category_slug).'/'.$item->title_slug.'-'.$item->id;
            $datetime = new DateTime($item->created_at);
            $time_created = $datetime->format('Y-m-d\TH:i:sP');
            
            $res.="\t\t<url>\n";
            $res.="\t\t\t<loc>".stripslashes($url)."</loc>\n";
            $res.="\t\t\t<changefreq>always</changefreq>\n";
            $res.="\t\t\t<priority>1.00</priority>\n";
            $res.="\t\t\t<lastmod>".$time_created."</lastmod>\n";
            /* $res.="\t\t\t<image:image>\n";
            if(!empty($item->image_big)){
                $res.="\t\t\t\t<image:loc>".get_post_image($item, "big")."</image:loc>\n";
            }else{
                $res.="\t\t\t\t<image:loc></image:loc>\n";
            }
            $res.="\t\t\t</image:image>\n"; */
            $res.="\t\t</url>\n";	

        }
        $res .='
        </urlset>';
        header('Content-Type: application/xml; charset=utf-8');
        echo $res;
    }
    public function library_images_crawling(){

        $parameters = $_SERVER['QUERY_STRING'];
        $this->load->model('newstrackrss_model');
        $data = $this->db->select('*')->from('library_images_crawl_index')->where('id',1)->limit(1)->get()->row();
        //pr($data);
        if(!empty($data->id)){
            $startIndex = $data->start_index;
            $count = $data->end_index;
        }else{
            echo "No index in table to crawling";die;
        }
        $items = $this->getDataFromAPI('fileLibrary','count='.$count.'&startIndex='.$startIndex,'files');
        //pr($items);
        foreach($items as $val){
            $this->db->insert('library_images', [
                "id"=>$val->id,
                "extension"=>$val->extension,
                "url"=>$val->url,
                "json_data"=>json_encode($val)
            ]);
        }
        $this->db->where("id",1);
        $this->db->update('library_images_crawl_index', ['start_index' => $startIndex+$count]);
        echo json_encode(["status"=>"Ok","Total Inserted"=>$count]);
        //$this->general_settings->storage;
    }
    public function library_image_upload_backup(){
        
        $this->load->model('newstrackrss_model');
        $dataObj = $this->db->select('*')->from('library_images')->where('cron_flag',0)->order_by('id',"desc")->limit(10)->get()->result();
        $resArr = [];
        $ids = [];
        if(count($dataObj)){
            foreach ($dataObj as $data) {
                
                $this->db->where_in('id',$data->id);
                $this->db->update('library_images', ["cron_flag"=>2]);
                $data->url = str_replace(".webp",".".$data->extension,$data->url);
                $img_url_array = explode("upload/", $data->url);
                $key = 'uploads/images/' . substr(end($img_url_array), 0, 8).basename($data->url);
                $this->load->model("aws_model");
                $buffer = file_get_contents("https://api.newstrack.com".$data->url);
                $ContentType = $this->newstrackrss_model->getUrlMimeType($buffer);
                $this->aws_model->put_object_remote($key,$buffer,$ContentType);

                $img_data['image_type'] = 'library';
                $img_data["image_slider"] = $key;
                $img_data["image_big"] = $img_data["image_slider"];
                $img_data["image_default"] = $img_data["image_slider"];
                $img_data["image_mid"] = $img_data["image_slider"];
                $img_data["image_small"] = $img_data["image_slider"];
                $img_data["image_mime"] = $data->extension;
                $img_data["user_id"] = '-1';
                $img_data["file_name"] = basename($data->url);
                $img_data["storage"] = $this->general_settings->storage;
                pr($img_data);
                $this->db->insert('images', $img_data);
            }
        }

    }
}
