    <?php defined('BASEPATH') or exit('No direct script access allowed');

class Post_detail_controller extends Home_Core_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->post_load_more_count = 6;
        $this->comment_limit = 5;
    }

    public function any($categoryUrl,$slug,$post_id='',$post_id2='',$post_id3='')
    {
        if(!empty($post_id3)){
            $categoryUrl = $categoryUrl."/".$slug.'/'.$post_id;
            $slug = $post_id2;
            $post_id = $post_id3;
        }elseif(!empty($post_id2)){
            $categoryUrl = $categoryUrl."/".$slug;
            $slug = $post_id;
            $post_id = $post_id2;
        }
        get_method();
		
        $slug = clean_slug($slug);
        if (empty($slug)) {
            redirect(lang_base_url());
        }
        if($categoryUrl == 'photo-stories'){
            $post = $this->post_model->get_webstory_post($post_id);
        }else{
            $post = $this->post_model->get_post($post_id);
        }
        //pr($this->db->queries);
        if (!empty($post)) {
            
            /* Set Default Image */
            if(empty($post->image_big)){
                $post->image_big = "uploads/images/2023/05/default_post.jpg";
                $post->image_storage = "aws_s3";
            }
            if(empty($post->image_default)){
                $post->image_default = "uploads/images/2023/05/default_post.jpg";
                $post->image_storage = "aws_s3";
            }
            if(empty($post->image_slider)){
                $post->image_slider = "uploads/images/2023/05/default_post.jpg";
                $post->image_storage = "aws_s3";
            }
            if(empty($post->image_mid)){
                $post->image_mid = "uploads/images/2023/05/default_post.jpg";
                $post->image_storage = "aws_s3";
            }
            if(empty($post->image_small)){
                $post->image_small = "uploads/images/2023/05/default_post.jpg";
                $post->image_storage = "aws_s3";
            }


            if(empty($categoryUrl))
            {
              redirect(lang_base_url().$post->category_slug."/".$slug.".html",'location',301);  
              
            }
            $this->post($post);
        } else {
            //not found
            $this->error_404();
        }
        if(isset($_GET['sqlp']) && $_GET['sqlp'] == 1){
            pr($this->db->queries);
        }
    }
    
    /**
     * Post
     */
    private function post($post)
    {
		
        if (empty($post)) {
            redirect(lang_base_url());
        }
        //check post auth
        if (!$this->auth_check && $post->need_auth == 1) {
            $this->session->set_flashdata('error', trans("message_post_auth"));
            redirect(generate_url('register'));
        }
		$data['breaking_news'] = get_breaking_news_latest();
		$data['featured_posts'] = get_featured_posts();
		
		$data['page_type'] = "post";
        $data['post'] = $post;
        
        // for live blog history
        $postID = $data['post']->id;
        if ($post->post_type == "webstory") {
            $data['webstories'] = $data['webstory_history'] = $this->webstory_model->get_webstories($postID);
        }elseif ($post->post_type == "live_post") {
            $data['live_blog_history'] = $this->live_history_model->get_live_histories($postID);
        }
        //end
        
        $data['post_user'] = $this->auth_model->get_user($post->user_id);
        $data['post_tags'] = $this->tag_model->get_post_tags($post->id);

        if($this->selected_lang->id==2){
            $data['trending_tags'] = get_trending_tags(-4);
        }elseif($this->selected_lang->id==3){
            $data['trending_tags'] = get_trending_tags(-6);
        }else{
            $data['trending_tags'] = get_trending_tags(-2);
        }
       
        // raj
         $tagArray = [];
        foreach ($data['post_tags'] as $key => $tags) {
            $tagArray[] = $tags->tag_slug;
        }
        /* if(!empty($tagArray)){
            
            $data['related_posts']= $this->post_model->get_tag_posts($tagArray);
        }else{ 
            $data['related_posts'] = $this->post_model->get_related_posts($post->category_id, $post->id, $this->selected_lang->id);
        }*/
        $data['next_posts_ids'] = $this->post_model->get_next_posts_ids($post->category_id, $post->id, $this->selected_lang->id);
        if(is_array($data['next_posts_ids']) && count($data['next_posts_ids'])>=1){
            shuffle($data['next_posts_ids']);
            $data['next_posts_ids'] = array_column(array_slice($data['next_posts_ids'],0,$this->post_model->related_posts_limit),"id");
        }else{
            $data['next_posts_ids'] = [];
        }
        // $data['postTagIds'] = $this->tag_model->get_post_by_tag_slug($tagArray);
        // if(!empty($data['postTagIds']) && count($data['postTagIds']) > 1){
        //     foreach ($data['postTagIds'] as $key => $resId) {
                
        //         if($post->id != $resId){
        //             $data['related_posts'][] = $this->post_model->get_post_by_id_limit($resId, 10);
        //             //$data['related_posts'][] = $this->tag_model->get_paginated_tag_posts($resId, 10);
                    
        //         }
        //     }
        // }else{
        //     $data['related_posts'] = $this->post_model->get_related_posts($post->category_id, $post->id);
        // }
        



        $data['post_images'] = $this->post_file_model->get_post_additional_images($post->id);

        $data['comments'] = $this->comment_model->get_comments($post->id, $this->comment_limit);
        $data['comment_limit'] = $this->comment_limit;
        
        //$data['previous_post'] = $this->post_model->get_previous_post($post->id);
        //$data['next_post'] = $this->post_model->get_next_post($post->id);

        //$data['is_reading_list'] = $this->reading_list_model->is_post_in_reading_list($post->id);

        $data['post_type'] = $post->post_type;
        $data['ajax_load'] = 0;
        if (!empty($post->feed_id)) {
            $data['feed'] = $this->rss_model->get_feed($post->feed_id);
        }

        $data = $this->set_post_meta_tags($post, $data['post_tags'], $data);

        $this->reaction_model->set_voted_reactions_session($post->id);
        $data["reactions"] = $this->reaction_model->get_reaction($post->id);

        //gallery post
        if ($post->post_type == "gallery") {
            $data['gallery_post_total_item_count'] = $this->post_item_model->get_post_list_items_count($post->id, $post->post_type);
            $data['gallery_post_slider_data'] = $this->post_item_model->get_post_list_items($post->id, $post->post_type);
            $data['gallery_post_item'] = $this->post_item_model->get_gallery_post_item_by_order($post->id, 1);
            $data['gallery_post_item_order'] = 1;
        }
        //sorted list post
        if ($post->post_type == "sorted_list") {
            $data['sorted_list_items'] = $this->post_item_model->get_post_list_items($post->id, $post->post_type);
        }

        //quiz
        if ($post->post_type == "trivia_quiz" || $post->post_type == "personality_quiz") {
            $data['quiz_questions'] = $this->quiz_model->get_quiz_questions($post->id);
        }

		$most_viewed_posts = $this->post_model->get_popular_posts_daily($this->selected_lang->id,10);
		$data['most_viewed_posts'] = $most_viewed_posts;

        //raj for keyword
        $data['keyword_Res'] = [];
         if(!empty($post->post_select_key)){
            $selKeyArray = [];
            $postSlectKeys = json_decode($data['post']->post_select_key);
            foreach ($postSlectKeys as $key => $postId) {
                $res = $this->post_model->get_postFor_key($postId);
                $selKeyArray[] = $res;
            }
            $data['keyword_Res'] = $selKeyArray;
         }
         // end

        $data['keywords'] = $post->keywords;
		if($post->post_type != "webstory"){
            $this->load->view('partials/_header', $data);
            $this->load->view('post/post', $data);
            $this->load->view('partials/_footer', $data);
        }else{
            $this->load->view('partials/_header_second', $data);
            $this->load->view('post/details/_webstory', ['post'=>$post]);
            $this->load->view('partials/_footer_webstory', $data);
        }

        //increase pageviews count
        //$this->post_model->increase_post_pageviews($post);
    }

    public function next_post($post_id=0){
        $data = [];
        $post = $this->post_model->get_post($post_id);

        /* Set Default Image */
        if(empty($post->image_big)){
            $post->image_big = "uploads/images/2023/05/default_post.jpg";
            $post->image_storage = "aws_s3";
        }
        if(empty($post->image_default)){
            $post->image_default = "uploads/images/2023/05/default_post.jpg";
            $post->image_storage = "aws_s3";
        }
        if(empty($post->image_slider)){
            $post->image_slider = "uploads/images/2023/05/default_post.jpg";
            $post->image_storage = "aws_s3";
        }
        if(empty($post->image_mid)){
            $post->image_mid = "uploads/images/2023/05/default_post.jpg";
            $post->image_storage = "aws_s3";
        }
        if(empty($post->image_small)){
            $post->image_small = "uploads/images/2023/05/default_post.jpg";
            $post->image_storage = "aws_s3";
        }

        $data['post'] = $post;
        $data['post_user'] = $this->auth_model->get_user($post->user_id);
        $data['post_tags'] = $this->tag_model->get_post_tags($post->id);

        if ($post->post_type == "live_post") {
            $data['live_blog_history'] = $this->live_history_model->get_live_histories($postID);
        }
        $data['post_user'] = $this->auth_model->get_user($post->user_id);
        $data['post_tags'] = $this->tag_model->get_post_tags($post->id);
        $tagArray = [];
        foreach ($data['post_tags'] as $key => $tags) {
            $tagArray[] = $tags->tag_slug;
        }
        $data['post_images'] = $this->post_file_model->get_post_additional_images($post->id);
        $data['comments'] = $this->comment_model->get_comments($post->id, $this->comment_limit);
        $data['comment_limit'] = $this->comment_limit;
        $data['post_type'] = $post->post_type;
        $data['ajax_load'] = 1;
        $this->reaction_model->set_voted_reactions_session($post->id);
        $data["reactions"] = $this->reaction_model->get_reaction($post->id);
        //gallery post
        if ($post->post_type == "gallery") {
            $data['gallery_post_total_item_count'] = $this->post_item_model->get_post_list_items_count($post->id, $post->post_type);
            $data['gallery_post_slider_data'] = $this->post_item_model->get_post_list_items($post->id, $post->post_type);
            $data['gallery_post_item'] = $this->post_item_model->get_gallery_post_item_by_order($post->id, 1);
            $data['gallery_post_item_order'] = 1;
        }elseif ($post->post_type == "sorted_list") {
            $data['sorted_list_items'] = $this->post_item_model->get_post_list_items($post->id, $post->post_type);
        }elseif($post->post_type == "trivia_quiz" || $post->post_type == "personality_quiz") {
            $data['quiz_questions'] = $this->quiz_model->get_quiz_questions($post->id);
        }

        if(isset($post->id)){
            $this->load->view('post/post_lhs.php', $data);
        }else{
            return "";
        }
        
    }

    //set post meta tags
    private function set_post_meta_tags($post, $post_tags, $data)
    {
        if($this->selected_lang->id == 1){
            $data['title'] = str_replace("-"," ",$post->title_slug).' Latest News in Hindi, Newstrack Samachar, Aaj Ki Taja Khabar | '.$post->title;
            $data['og_title'] = $post->title.' | News Track in Hindi';
        }else{
            $data['title'] = str_replace("-"," ",$post->title_slug);
            $data['og_title'] = $post->title;
        }
        
        $data['description'] = $post->tag_description;
        $data['keywords'] = $post->keywords;

        
        $data['og_description'] = $post->tag_description;
        $data['og_type'] = "article";
        $data['og_url'] = generate_post_url($post);
        $data['og_image'] = get_post_image($post, "big");
        if (!empty($post->image_url)) {
            $data['og_image'] = $post->image_url;
        }
        $data['og_width'] = "1200";
        $data['og_height'] = "628";
        $data['og_creator'] = $post->author_username;
        $data['og_author'] = $post->author_username;
        $data['og_published_time'] = $post->created_at;
        $data['og_modified_time'] = $post->updated_at;
        if (empty($post->updated_at)) {
            $data['og_modified_time'] = $post->created_at;
        }
        $data['og_tags'] = $post_tags;
        return $data;
    }
    //error 404
    public function error_404()
    {
        get_method();
        header("HTTP/1.0 404 Not Found");
        $data['title'] = "Error 404";
        $data['description'] = "Error 404";
        $data['keywords'] = "error,404";
        $data['page_name'] = "e_404";

        $this->load->view('partials/_header', $data);
        $this->load->view('errors/error_404');
        $this->load->view('partials/_footer');
    }

    public function amp($categoryUrl,$slug,$post_id='',$post_id2='',$post_id3=''){

        if(!empty($post_id3)){
            $slug = $post_id2;
            $post_id = $post_id3;
        }elseif(!empty($post_id2)){
            $slug = $post_id;
            $post_id = $post_id2;
        }
        get_method();
		
        $slug = clean_slug($slug);
        if (empty($slug)) {
            redirect(lang_base_url());
        }
		
		$post = $this->post_model->get_post($post_id);

        /* Set Default Image */
        if(empty($post->image_big)){
            $post->image_big = "uploads/images/2023/05/default_post.jpg";
            $post->image_storage = "aws_s3";
        }
        if(empty($post->image_default)){
            $post->image_default = "uploads/images/2023/05/default_post.jpg";
            $post->image_storage = "aws_s3";
        }
        if(empty($post->image_slider)){
            $post->image_slider = "uploads/images/2023/05/default_post.jpg";
            $post->image_storage = "aws_s3";
        }
        if(empty($post->image_mid)){
            $post->image_mid = "uploads/images/2023/05/default_post.jpg";
            $post->image_storage = "aws_s3";
        }
        if(empty($post->image_small)){
            $post->image_small = "uploads/images/2023/05/default_post.jpg";
            $post->image_storage = "aws_s3";
        }
        
		if (!empty($post)) {
			$this->amp_post($post);
		} else {
			$this->error_404();
		}
        
	}
    
    /**
     * Post
     */
    private function amp_post($post)
    {
		
        if (empty($post)) {
            redirect(lang_base_url());
        }
        //check post auth
        if (!$this->auth_check && $post->need_auth == 1) {
            $this->session->set_flashdata('error', trans("message_post_auth"));
            redirect(generate_url('register'));
        }
		//$data['breaking_news'] = get_breaking_news_latest();
		//$data['special_posts'] = get_special_posts();
		$data['page_type'] = "post";
        $post->content = $this->filterAMPContent($post->content);
        $data['post'] = $post;
        
        $data['post_user'] = $this->auth_model->get_user($post->user_id);
        $data['post_tags'] = $this->tag_model->get_post_tags($post->id);
        //$data['post_images'] = $this->post_file_model->get_post_additional_images($post->id);

        //$data['comments'] = $this->comment_model->get_comments($post->id, $this->comment_limit);
        //$data['comment_limit'] = $this->comment_limit;
        //$data['related_posts'] = $this->post_model->get_related_posts($post->category_id, $post->id);
        //$data['previous_post'] = $this->post_model->get_previous_post($post->id);
        //$data['next_post'] = $this->post_model->get_next_post($post->id);

        //$data['is_reading_list'] = $this->reading_list_model->is_post_in_reading_list($post->id);

        $data['post_type'] = $post->post_type;

        if (!empty($post->feed_id)) {
            $data['feed'] = $this->rss_model->get_feed($post->feed_id);
        }

        $data = $this->set_post_meta_tags($post, $data['post_tags'], $data);

       /*  $this->reaction_model->set_voted_reactions_session($post->id);
        $data["reactions"] = $this->reaction_model->get_reaction($post->id); */

        $this->load->view('partials/_amp_header', $data);
        $this->load->view('post/amp_post', $data);
        $this->load->view('partials/_amp_footer', $data);

        //increase pageviews count
        //$this->post_model->increase_post_pageviews($post);
    }
    function filterAMPContent($content){
        preg_match_all('/<a .*?>(.*?)<\/a>/',$content,$matches);
        if (isset($matches[0][0])) {
            preg_match_all('@https?://(www\.)?newsdrum.in+@i', $content,$match);
            if(isset($match[0][0])){
                //echo "in--------";
            }else{
                //echo "else";
                $content = str_replace('<a ', '<a rel="nofollow" ', $content);
            }
        }
        $content = replaceInstagram($content);
        
        $find_tw = '~https://twitter.(.+)~';
        $output_array_data = array();
        preg_match_all($find_tw, $content, $output_array_data);
        if (!empty($output_array_data[0])){
            $content = replaceTwitter($content);
        }
        preg_match_all('/<img[^>]+>/i', $content, $imgTags);
        for ($i = 0; $i < count($imgTags[0]); $i++) {
            if(strpos($imgTags[0][$i], 'newstrack.com/') !== false){
                $content = str_replace($imgTags[0][$i],"", $content);
            }else{
                preg_match('/src="([^"]+)/i',$imgTags[0][$i], $imgage);
                $imgTagHTML  =  '<amp-img width="414" height="232" src="'.str_ireplace( 'src="', '',  $imgage[0]).'"></amp-img>';
                $content = str_replace($imgTags[0][$i],$imgTagHTML, $content);
            }
        }
        //$content = preg_replace('/(<img[^>]+>(?:<\/img>)?)/i', '$1</amp-img>', $content);
        //$content = str_replace('<img', '<amp-img width="414"  height="232"', $content);
        preg_match_all('@https?://(www\.)?youtube.com/.[^\s.,"\']+@i', $content, $matches);
        if (isset($matches[0][0])) {
            $content = str_replace('<iframe', '<amp-iframe width="414" height="232" sandbox="allow-scripts allow-same-origin allow-popups"', $content);
            $content = str_replace('</iframe>', '</amp-iframe>', $content);
        }
        $content = str_replace('<iframe', '<amp-iframe width="414" height="232" ', $content);
        $content = str_replace('</iframe>', '</amp-iframe>', $content);
        $content = str_replace('marginwidth="0"', '', $content);
        $content = str_replace('marginheight="0"', '', $content);
        $content = str_replace('allowtransparency="true"', '', $content);
        $content = str_replace('allowTransparency="true"', '', $content);
        $content = str_replace('allowfullscreen="true"', '', $content);
        $content = str_replace('allowFullScreen="true"', '', $content);
        $content = str_replace('target="_parent"', '', $content);
        $content = str_replace('width="100%"', 'width="320px"', $content);
        $content = str_replace('width="95%"', 'width="320px"', $content);
        $content = str_replace('width="95%"', 'width="320px"', $content);
        $content = str_replace(['contenteditable="false"','contenteditable="true"'], '', $content);
        $content = preg_replace('/(<font[^>]*>)|(<\/font>)/', '', $content);
        $content = preg_replace('/(<blockquote[^>]*>)|(<\/blockquote>)/', '', $content);
        $content = preg_replace('/(<script[^>]*>)|(<\/script>)/', '', $content);
        $content = str_replace('has-title="true"', '', $content);

        $regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
        if(preg_match_all("/$regexp/siU", $content, $matches)) {
            if(is_array($matches[3])){
            foreach ($matches[3] as $key => $a_tag_url) {
                if (!filter_var($a_tag_url, FILTER_VALIDATE_URL)) {
                    $content = str_replace($matches[0][$key],'', $content);
                }
            }
            }
        }
        $content = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $content);
        $content = strip_tags($content, ['p','b','i','br','div','amp-iframe','amp-img','ul','li','strong','a']);
        return $content;
    }
    
}
