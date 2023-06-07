<?php defined('BASEPATH') or exit('No direct script access allowed');

class Post_controller extends Admin_Core_Controller
{

    public function __construct()
    {
        parent::__construct();

        if ($this->general_settings->email_verification == 1 && $this->auth_user->email_status == 0 && $this->auth_user->role != "admin") {
            $this->session->set_flashdata('error', trans("msg_confirmed_required"));
            redirect(generate_url('settings'));
        }
    }
    function alpha_dash_space($title_slug){
        if (! preg_match('/^[a-zA-Z0-9\s]+$/', $title_slug)) {
            $this->form_validation->set_message('alpha_dash_space', 'The %s field may only contain alpha characters & White spaces');
            return FALSE;
        } else {
            return TRUE;
        }
    }
    public function searchRecByInput()
    {
        check_permission('add_post');
        $response = [];
        if ($query = $this->post_admin_model->post_search_byTerm($this->input->post('searchTerm'))){
            $search['searches'] = $query;
            $data = array();
            foreach ($search['searches'] as $keyRes) {
                $data[] = array(
                    "id" => $keyRes->id,
                    "text" => $keyRes->title 
                );
            }

            $response['token'] =  $this->security->get_csrf_hash();
            $response['data'] = $data;
            echo json_encode($response); // output JSON    
        }

    }

    /**
     * Post newslatter
     */
    public function post_newslatter()
    {
        $featured_posts = get_featured_posts();
	$randnum = rand(10,10000);
        // update 0 for match featured ID's
        $this->post_model->update_newsletter_id('all');
        
        $title = '';
        foreach ($featured_posts as $key => $feat_post) {
            if($key == 0){
                $title = $feat_post->title;
            }
            $this->post_model->update_newsletter_id($feat_post->id);
        }

        // create a new cURL resource of the mail sending
	$url =  base_url().'mailchimp.php';
	$data = array(
		"mailtitle" => 'Daily Newsletter- '. $title
	);
        $ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_exec($ch);
        curl_close($ch);
	echo $ch;

        $data['title'] = trans('posts');
        $data['authors'] = $this->auth_model->get_all_users();
        $data['form_action'] = admin_url() . "posts";
        $data['list_type'] = "posts";
        $data['panel_settings'] = panel_settings();
        //get paginated posts
        $pagination = $this->paginate(admin_url() . 'posts', $this->post_admin_model->get_paginated_posts_count('posts'));
        $data['posts'] = $this->post_admin_model->get_paginated_posts($pagination['per_page'], $pagination['offset'], 'posts');

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/post/posts', $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * Post Format
     */
    public function post_format()
    {
        check_permission('add_post');
        $data['title'] = trans("choose_post_format");
        $data['panel_settings'] = panel_settings();
        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/post/post_format', $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * Add Post
     */
    public function add_post()
    {

        check_permission('add_post');
        $type = $this->input->get('type', true);
        if ($type != 'article' && $type != 'gallery' && $type != 'sorted_list' && $type != 'video' && $type != 'audio' && $type != 'trivia_quiz' && $type != 'personality_quiz' && $type != 'live_post' && $type != 'webstory' && $type != 'short_videos') {
            redirect(admin_url() . 'post-format');
            exit();
        }

        $post_format = "post_format_" . $type;

        if ($this->general_settings->$post_format != 1) {
            redirect(admin_url() . 'post-format');
            exit();
        }

        $title = "add_" . $type;
        $data['title'] = trans($title);
        $data['post_type'] = $type;
        $data['panel_settings'] = panel_settings();
        //$data['parent_categories'] = $this->category_model->get_parent_categories_by_lang($this->selected_lang->id);
        $data['parent_categories'] = $this->category_model->categoryTree($this->selected_lang->id);
        //print_r($data['parent_categories']);die;
		$data['users'] = $this->auth_model->get_active_users();

         //raj get
        //$data['latetest_post'] = $this->post_model->get_latest_posts(1, 10);

        $view = $title;
        if ($type == 'trivia_quiz' || $type == 'personality_quiz') {
            $view = 'quiz/' . $title;
        }
        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/post/' . $view, $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * Add Post Post
     */
    public function add_post_post()
    {   
        check_permission('add_post');
        //validate inputs
        $this->form_validation->set_rules('title', trans("title"), 'required|max_length[500]');
        $this->form_validation->set_rules('summary', trans("summary"), 'max_length[5000]');
        $this->form_validation->set_rules('category_id', trans("category"), 'required');
        $this->form_validation->set_rules('optional_url', trans("optional_url"), 'max_length[1000]');
        $this->form_validation->set_rules('title_slug', trans('slug'), 'min_length[5]|max_length[300]|trim|required|xss_clean');


        if ($this->form_validation->run() === false) {
            $this->session->set_flashdata('errors', validation_errors());
            $this->session->set_flashdata('form_data', $this->post_admin_model->input_values());
            redirect($this->agent->referrer());
        } else {


            $post_type = $this->input->post('post_type', true);
            
            //if post added
            $insertResponse = $this->post_admin_model->add_post($post_type);
            if ($insertResponse["res"]) {
                //last id
                $last_id = $this->db->insert_id();

                $this->post_admin_model->add_post_cat($last_id,$post_type);
                //echo $this->db->last_query();die;
                //update slug
                $this->post_admin_model->update_slug($last_id);
                //insert post tags
                //$tags = trim($this->input->post('tags', true));
                $this->tag_model->manage_post_tags($last_id);
                //pr($this->db->queries);die;
				
				//insert post topic
				$topic = trim($this->input->post('topic', true));
                $this->tag_model->add_post_topic($last_id, $topic);
				
                //post types
                if ($post_type == 'gallery') {
                    //insert gallery items
                    $this->post_item_model->add_post_list_items($last_id, 'gallery');
                } elseif ($post_type == "sorted_list") {
                    //insert sorted list items
                    $this->post_item_model->add_post_list_items($last_id, 'sorted_list');
                } elseif ($post_type == "audio") {
                    $this->post_file_model->add_post_audios($last_id);
                } elseif ($post_type == "article") {
                    $this->post_file_model->add_post_additional_images($last_id);
                } elseif ($post_type == 'trivia_quiz') {
                    $this->quiz_model->add_quiz_questions($last_id);
                    $this->quiz_model->add_quiz_results($last_id);
                } elseif ($post_type == 'personality_quiz') {
                    $this->quiz_model->add_quiz_results($last_id);
                    $this->quiz_model->add_quiz_questions($last_id);
                }
                elseif ($post_type == 'live_post') {
                    $this->live_history_model->add_live_histories($last_id);
                }
                elseif ($post_type == 'webstory') {
                   $this->webstory_model->add_webstories($last_id);
                }elseif ($post_type == 'short_videos') {
                    $this->load->model('short_videos_model');
                    $this->short_videos_model->add_short_videos($last_id);
                 }
                
                //add post files
                if ($post_type != 'gallery' && $post_type != 'sorted_list') {
                    $this->post_file_model->add_post_files($last_id);
                }

                 /* Used for cache purged */
                    $this->load->model('cache_purge_model');
                    $this->cache_purge_model->cache_purge_article(["is_edit"=>0,"lang_id"=>$this->selected_lang->id,"postData"=>$insertResponse["data"]]);
                /* END Used for cache purged */


                $this->session->set_flashdata('success', trans("post") . " " . trans("msg_suc_added"));
                reset_cache_data_on_change();
                
                redirect($this->agent->referrer());
            } else {
                $this->session->set_flashdata('form_data', $this->post_admin_model->input_values());
                if(!empty($insertResponse["message"])){
                    $this->session->set_flashdata('error', $insertResponse["message"]);
                }else{
                    $this->session->set_flashdata('error', trans("msg_error"));
                }
                redirect($this->agent->referrer());
            }
        }
    }

    /**
     * Update Post
     */
    public function update_post($id)
    {
        check_permission('add_post');

        //get post
        $table = $this->input->get('type', TRUE);
        if($table == 'webstory'){
            $data['post'] = $this->post_admin_model->get_post($id,'posts_webstory');
        }else{
            $data['post'] = $this->post_admin_model->get_post($id);
        }
        if (empty($data['post'])) {
            redirect($this->agent->referrer());
        }
        if (!check_post_ownership($data['post']->user_id)) {
            redirect($this->agent->referrer());
        }

        $data['title'] = trans("update_" . $data['post']->post_type);
        $data['tags'] = $this->tag_model->get_post_tags_with_ids($id);
        $data['post_images'] = $this->post_file_model->get_post_additional_images($id);
        $data['users'] = $this->auth_model->get_active_users();
        $data['panel_settings'] = panel_settings();
        //define category ids
        //$category = $this->category_model->get_category($data["post"]->category_id);
        $data['parent_category_id'] = $data["post"]->category_id;
        $data['subcategory_id'] = 0;
        $data['cat_ids'] =  (!empty($data["post"]->cat_ids))?json_decode($data["post"]->cat_ids):[];
        /* if (!empty($category) && $category->parent_id != 0) {
            $parent_category = $this->category_model->get_category($category->parent_id);
            if (!empty($parent_category)) {
                $data['parent_category_id'] = $parent_category->id;
                $data['subcategory_id'] = $category->id;
            }
        } */
        $data['parent_categories'] = $this->category_model->categoryTree($this->selected_lang->id);
        //$data['categories'] = $this->category_model->get_parent_categories_by_lang($data['post']->lang_id);
        //$data['subcategories'] = $this->category_model->get_subcategories_by_parent_id($data['parent_category_id']);

        if($data['post']->post_type == 'gallery'){
        $data['post_list_items'] = $this->post_item_model->get_post_list_items($data['post']->id, $data['post']->post_type);
        }else{
            $data['post_list_items'] = $this->post_item_model->get_post_list_items($data['post']->id, $data['post']->post_type);
        }

        $view = "update_" . $data['post']->post_type;

        if ($data['post']->post_type == 'trivia_quiz' || $data['post']->post_type == 'personality_quiz') {
            $data['title'] = trans("update_" . $data['post']->post_type);
            $view = "quiz/update_" . $data['post']->post_type;
            $data['quiz_questions'] = $this->quiz_model->get_quiz_questions($data['post']->id);
            $data['quiz_results'] = $this->quiz_model->get_quiz_results($data['post']->id);
        }

        if ($data['post']->post_type == 'live_post'){
            $data['live_history'] = $this->live_history_model->get_live_histories($data['post']->id);
           
        }

        if ($data['post']->post_type == 'webstory'){
            $data['webstories'] = $this->webstory_model->get_webstories($data['post']->id);
        }

        if ($data['post']->post_type == 'short_videos'){
            $this->load->model('short_videos_model');
            $data['short_videos'] = $this->short_videos_model->get_short_videos($data['post']->id);
            //print_r($data['short_videos']);die;
        }
        
        //raj
        if($data['post']->post_select_key != NULL){
            $postSlectKeys = json_decode($data['post']->post_select_key);
            $selKeyArray = [];
            foreach ($postSlectKeys as $key => $postId) {
                $res = $this->post_admin_model->get_post($postId);
                $selKeyArray[] = $res;
            }
            $data['get_postKeys'] = array_column($selKeyArray,'title','id');
            $data['postSelectIds'] = $postSlectKeys; 
        }

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/post/' . $view, $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * Update Post Post
     */
    public function update_post_post()
    {
        check_permission('add_post');
        //post id
        $post_id = $this->input->post('id', true);
        $post_type = $this->input->post('post_type', true);

        if($post_type == 'webstory'){
            $post = $this->post_model->get_post_by_id($post_id,'posts_webstory');
        }else{
            $post = $this->post_model->get_post_by_id($post_id);
        }
        
        if (empty($post)) {
            redirect($this->agent->referrer());
        }
        if (!check_post_ownership($post->user_id)) {
            redirect($this->agent->referrer());
        }

        //validate inputs
        $this->form_validation->set_rules('title', trans("title"), 'required|max_length[500]');
        $this->form_validation->set_rules('summary', trans("summary"), 'max_length[5000]');
        $this->form_validation->set_rules('category_id', trans("category"), 'required');
        $this->form_validation->set_rules('optional_url', trans("optional_url"), 'max_length[1000]');

        if ($this->form_validation->run() === false) {
            $this->session->set_flashdata('errors', validation_errors());
            $this->session->set_flashdata('form_data', $this->post_admin_model->input_values());
            redirect($this->agent->referrer());
        } else {
			
            $post_type = $this->input->post('post_type', true);
			
            if ($this->post_admin_model->update_post($post_id, $post_type,$post->status)) {
                //update slug
               // $this->post_admin_model->update_slug($post_id);

                $this->post_admin_model->update_post_cat($post_id,$post_type);
                //pr($this->db->queries);die;
                //update post tags
                $this->tag_model->manage_post_tags($post_id);
				//pr($this->db->queries);die;
				//update post topic
                $this->tag_model->update_post_topic($post_id);
				
                //post types
                if ($post_type == 'gallery') {
                    //insert gallery items
                    $this->post_item_model->update_post_list_items($post_id, 'gallery');
                } elseif ($post_type == "sorted_list") {
                    //insert sorted list items
                    $this->post_item_model->update_post_list_items($post_id, 'sorted_list');
                } elseif ($post_type == "audio") {
                    $this->post_file_model->add_post_audios($post_id);
                } elseif ($post_type == "article") {
                    $this->post_file_model->add_post_additional_images($post_id);
                } elseif ($post_type == 'trivia_quiz') {
                    $this->quiz_model->update_quiz_questions($post_id);
                    $this->quiz_model->update_quiz_results($post_id);
                } elseif ($post_type == 'personality_quiz') {
                    $this->quiz_model->update_quiz_results($post_id);
                    $this->quiz_model->update_quiz_questions($post_id);
                }

                elseif ($post_type == 'live_post') {
                    $this->live_history_model->update_live_histories($post_id);
                }
                elseif ($post_type == 'webstory') {
                    $this->webstory_model->update_webstories($post_id);
                }elseif ($post_type == 'short_videos') {
                    $this->load->model('short_videos_model');
                    $this->short_videos_model->update_short_videos($post_id);
                 }



                //add post files
                if ($post_type != 'gallery' && $post_type != 'sorted_list') {
                    $this->post_file_model->add_post_files($post_id);
                }

                $this->session->set_userdata('msg_success', trans("post") . " " . trans("msg_suc_updated"));
                reset_cache_data_on_change();
                
            } else {
                $this->session->set_userdata('msg_error', trans("msg_error"));
            }
        }

        redirect($this->agent->referrer());
    }

    /**
     * Delete Additional Image
     */
    public function delete_post_additional_image()
    {
        check_permission('add_post');
        $file_id = $this->input->post('file_id', true);
        $this->post_file_model->delete_post_additional_image($file_id);
    }

    /**
     * Delete Post Item List Item Post
     */
    public function delete_post_list_item_post()
    {

        check_permission('add_post');
        $item_id = $this->input->post('item_id', true);
        $post_type = $this->input->post('post_type', true);
        $this->post_item_model->delete_post_list_item($item_id, $post_type);
    }

    /**
     * Get List Item HTML
     */
    public function get_list_item_html()
    {
        check_permission('add_post');
        $vars = array('new_item_order' => $this->input->post('new_item_order', true));
        $data = array(
            'result' => 1,
            'html' => $this->load->view('admin/post/_post_list_item', $vars, true)
        );
        echo json_encode($data);
    }

    /**
     * Add List Item
     */
    public function add_list_item()
    {
        check_permission('add_post');
        $post_id = $this->input->post('post_id', true);
        $post_type = $this->input->post('post_type', true);
        $list_item_id = $this->post_item_model->add_post_list_item($post_id, $post_type);
        $list_item = $this->post_item_model->get_post_list_item($list_item_id, $post_type);
        if (!empty($list_item)) {
            $vars = array('post_list_item' => $list_item);
            $data = array(
                'result' => 1,
                'html' => $this->load->view('admin/post/_post_list_item', $vars, true)
            );
        } else {
            $data = array(
                'result' => 0,
                'html' => ""
            );
        }
        echo json_encode($data);
    }


    /*
    *-------------------------------------------------------------------------------------------------
    * AUDIO
    *-------------------------------------------------------------------------------------------------
    */

    /**
     * Delete Post Audio
     */
    public function delete_post_audio()
    {
        $post_audio_id = $this->input->post('post_audio_id', true);
        $this->post_file_model->delete_post_audio($post_audio_id);
    }

    /*
    *-------------------------------------------------------------------------------------------------
    * VIDEO
    *-------------------------------------------------------------------------------------------------
    */

    /**
     * Get Video from URL
     */
    public function get_video_from_url()
    {
        $url = $this->input->post('url', true);
        $this->load->model('video_model');
        $data = array(
            'video_embed_code' => $this->video_model->get_video_embed_code($url),
            'video_thumbnail' => $this->video_model->get_video_thumbnail($url),
        );
        echo json_encode($data);
    }

    /**
     * Get Video Thumbnail
     */
    public function get_video_thumbnail()
    {
        $url = $this->input->post('url', true);
        echo $this->file_model->get_video_thumbnail($url);
    }

    /**
     * Delete Video
     */
    public function delete_post_video()
    {
        $post_id = $this->input->post('post_id', true);
        $this->post_file_model->delete_post_video($post_id);
    }

    /*
    *-------------------------------------------------------------------------------------------------
    * QUIZ
    *-------------------------------------------------------------------------------------------------
    */

    /**
     * Get Quiz Question HTML
     */
    public function get_quiz_question_html()
    {
        $post_type = $this->input->post('post_type', true);
        $new_question_order = $this->input->post('new_question_order', true);
        $vars = array('post_type' => $post_type, 'new_question_order' => $new_question_order);
        $data = array(
            'result' => 1,
            'html' => $this->load->view('admin/post/quiz/_add_question', $vars, true)
        );
        echo json_encode($data);
    }

    /**
     * Add Quiz Question
     */
    public function add_quiz_question()
    {
        $post_id = $this->input->post('post_id', true);
        $post_type = $this->input->post('post_type', true);
        $question_id = $this->quiz_model->add_quiz_question($post_id);
        $question = $this->quiz_model->get_quiz_question($question_id);
        if (!empty($question)) {
            $vars = array('post_type' => $post_type, 'question' => $question);
            $data = array(
                'result' => 1,
                'html' => $this->load->view('admin/post/quiz/_update_question', $vars, true)
            );
        } else {
            $data = array(
                'result' => 0,
                'html' => ""
            );
        }
        echo json_encode($data);
    }

    /**
     * Delete Quiz Question
     */
    public function delete_quiz_question()
    {
        $question_id = $this->input->post('question_id', true);
        $this->quiz_model->delete_quiz_question($question_id);
    }

    /**
     * Get Quiz Result HTML
     */
    public function get_quiz_result_html()
    {
        $post_type = $this->input->post('post_type', true);
        $vars = array('post_type' => $post_type);
        $data = array(
            'result' => 1,
            'html' => $this->load->view('admin/post/quiz/_add_result', $vars, true)
        );
        echo json_encode($data);
    }

    /**
     * Add Quiz Result
     */
    public function add_quiz_result()
    {
        $post_id = $this->input->post('post_id', true);
        $post_type = $this->input->post('post_type', true);
        $result_id = $this->quiz_model->add_quiz_result($post_id);
        $result = $this->quiz_model->get_quiz_result($result_id);
        if (!empty($result)) {
            $vars = array('result' => $result, 'post_type' => $post_type);
            $data = array(
                'result' => 1,
                'html' => $this->load->view('admin/post/quiz/_update_result', $vars, true)
            );
        } else {
            $data = array(
                'result' => 0,
                'html' => ""
            );
        }
        echo json_encode($data);
    }

     /**
     * Add live blog 
     */
    public function add_live_history()
    {
        $post_id = $this->input->post('post_id', true);
        $post_type = $this->input->post('post_type', true);
        $result_id = $this->live_history_model->add_live_history($post_id);
        $result = $this->live_history_model->get_live_history($result_id);
        if (!empty($result)) {
            $vars = array('result' => $result, 'post_type' => $post_type);
            $data = array(
                'result' => 1,
                'html' => $this->load->view('admin/post/live/_update_live_history', $vars, true)
            );
        } else {
            $data = array(
                'result' => 0,
                'html' => ""
            );
        }
        echo json_encode($data);
    }

     /**
     * Get live history raj
     */
    public function get_live_history_html()
    {
        $post_type = $this->input->post('post_type', true);
        $vars = array('post_type' => $post_type);
        $data = array(
            'result' => 1,
            'html' => $this->load->view('admin/post/live/_add_live_history', $vars, true)
        );
        echo json_encode($data);
    }
    public function delete_live_history()
    {
        $result_id = $this->input->post('result_id', true);
        $this->live_history_model->delete_live_history($result_id);
    }


    /**
     * Get webstory history raj
     */
    public function add_webstory()
    {
        $post_id = $this->input->post('post_id', true);
        $post_type = $this->input->post('post_type', true);
        $result_id = $this->webstory_model->add_webstory($post_id);
        $result = $this->webstory_model->get_webstory($result_id);
        if (!empty($result)) {
            $vars = array('result' => $result, 'post_type' => $post_type);
            $data = array(
                'result' => 1,
                'html' => $this->load->view('admin/post/webstory/_update_webstory', $vars, true)
            );
        } else {
            $data = array(
                'result' => 0,
                'html' => ""
            );
        }
        echo json_encode($data);
    }
    public function get_webstory_html()
    {
        $post_type = $this->input->post('post_type', true);
        $vars = array('post_type' => $post_type);
        $data = array(
            'result' => 1,
            'html' => $this->load->view('admin/post/webstory/_add_webstory', $vars, true)
        );
        echo json_encode($data);
    }
    public function add_short_videos()
    {
        $post_id = $this->input->post('post_id', true);
        $post_type = $this->input->post('post_type', true);
        $this->load->model('short_videos_model');
        $result_id = $this->short_videos_model->add_short_video($post_id);
        $result = $this->short_videos_model->get_short_video($result_id);
        if (!empty($result)) {
            $vars = array('result' => $result, 'post_type' => $post_type);
            $data = array(
                'result' => 1,
                'html' => $this->load->view('admin/post/short_videos/_update_short_videos', $vars, true)
            );
        } else {
            $data = array(
                'result' => 0,
                'html' => ""
            );
        }
        echo json_encode($data);
    }
    public function get_short_video_html()
    {
        $post_type = $this->input->post('post_type', true);
        $vars = array('post_type' => $post_type);
        $data = array(
            'result' => 1,
            'html' => $this->load->view('admin/post/short_videos/_add_short_videos', $vars, true)
        );
        echo json_encode($data);
    }
    public function delete_webstory()
    {
        $result_id = $this->input->post('result_id', true);
        $this->webstory_model->delete_webstory($result_id);
    }

    public function delete_short_videos()
    {
        $result_id = $this->input->post('result_id', true);
        $this->load->model('short_videos_model');
        $this->short_videos_model->delete_short_video($result_id);
    }

    /**
     * Delete Quiz Result
     */
    public function delete_quiz_result()
    {
        $result_id = $this->input->post('result_id', true);
        $this->quiz_model->delete_quiz_result($result_id);
    }

    /**
     * Get Quiz Answer HTML
     */
    public function get_quiz_answer_html()
    {
        $post_type = $this->input->post('post_type', true);
        $vars = array('post_type' => $post_type, 'question_id' => $this->input->post('question_id', true));
        $data = array(
            'result' => 1,
            'html' => $this->load->view('admin/post/quiz/_add_answer', $vars, true)
        );
        echo json_encode($data);
    }

    /**
     * Add Quiz Question Answer
     */
    public function add_quiz_question_answer()
    {
        $question_id = $this->input->post('question_id', true);
        $post_type = $this->input->post('post_type', true);
        $answer_id = $this->quiz_model->add_quiz_question_answer($question_id);
        $answer = $this->quiz_model->get_quiz_question_answer($answer_id);
        if (!empty($answer)) {
            $vars = array('post_type' => $post_type, 'answer' => $answer);
            $data = array(
                'result' => 1,
                'html' => $this->load->view('admin/post/quiz/_update_answer', $vars, true)
            );
        } else {
            $data = array(
                'result' => 0,
                'html' => ""
            );
        }
        echo json_encode($data);
    }

    /**
     * Delete Quiz Question Answer
     */
    public function delete_quiz_question_answer()
    {
        $answer_id = $this->input->post('answer_id', true);
        $this->quiz_model->delete_quiz_question_answer($answer_id);
    }

    /*
    *-------------------------------------------------------------------------------------------------
    * COMMON
    *-------------------------------------------------------------------------------------------------
    */

    /**
     * Publish Draft
     */
    public function publish_draft_post()
    {
        check_permission('add_post');
        $post_id = $this->input->post('post_id', true);
        if ($this->post_admin_model->publish_draft($post_id)) {
            $this->session->set_flashdata('success', trans("post") . " " . trans("msg_suc_added"));
            $this->session->set_flashdata('msg_post_published', 1);
            reset_cache_data_on_change();
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
        }
        redirect($this->agent->referrer());
    }

    /**
     * Posts
     */
    public function posts()
    {
        check_permission('add_post');
        $csv = $this->input->get('csv', true);

        $data['title'] = trans('posts');
        $data['authors'] = $this->auth_model->get_all_users();
        $data['form_action'] = admin_url() . "posts";
        $data['list_type'] = "posts";
        $data['panel_settings'] = panel_settings();
        $data['categoryTree'] = $this->category_model->categoryTree($this->selected_lang->id);
        //get paginated posts
        $pagination = $this->paginate(admin_url() . 'posts', $this->post_admin_model->get_paginated_posts_count('posts'));
        if($csv  == 1){
            $pagination = array('per_page' => 2000, 'offset' => 0);
        }

        $data['posts'] = $this->post_admin_model->get_paginated_posts($pagination['per_page'], $pagination['offset'], 'posts');
        //pr($this->db->queries);die;
        if($csv  == 1){
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="post_data.csv"');
            $user_CSV = ["ID","Image","Title","URL","Post_Type","Category","Author","created_at","updated_at"];
            $fp = fopen('php://output', 'wb');
            fputcsv($fp, $user_CSV, ',');
            foreach ($data['posts'] as $key => $item) {
                $user_CSV = [
                    $item->id,
                    get_post_image($item, "small"),
                    html_escape($item->title),
                    site_url().$item->category_slug."/". $item->title_slug."-".$item->id,
                    trans($item->post_type),
                    $item->category_name,
                    $item->author_username,
                    $item->created_at,
                    $item->updated_at
                ];
                fputcsv($fp, $user_CSV, ',');
            }
            fclose($fp);
            die;
        }
        
        $data['user_email'] = $this->session->userdata('vr_sess_user_email');
        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/post/posts', $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * Slider Posts
     */
    public function slider_posts()
    {
        check_permission('manage_all_posts');
        $data['title'] = trans('slider_posts');
        $data['authors'] = $this->auth_model->get_all_users();
        $data['form_action'] = admin_url() . "slider-posts";
        $data['list_type'] = "slider_posts";
        $data['panel_settings'] = panel_settings();
        //get paginated posts
        $pagination = $this->paginate(admin_url() . 'slider-posts', $this->post_admin_model->get_paginated_posts_count('slider_posts'));
        $data['posts'] = $this->post_admin_model->get_paginated_posts($pagination['per_page'], $pagination['offset'], 'slider_posts');

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/post/posts', $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * Featured Posts
     */
    public function featured_posts()
    {
        check_permission('manage_all_posts');
        $data['title'] = trans('featured_posts');
        $data['authors'] = $this->auth_model->get_all_users();
        $data['form_action'] = admin_url() . "featured-posts";
        $data['list_type'] = "featured_posts";
        $data['panel_settings'] = panel_settings();
        $data['categoryTree'] = $this->category_model->categoryTree($this->selected_lang->id);
        //get paginated posts
        $pagination = $this->paginate(admin_url() . 'featured-posts', $this->post_admin_model->get_paginated_posts_count('featured_posts'));
        $data['posts'] = $this->post_admin_model->get_paginated_posts($pagination['per_page'], $pagination['offset'], 'featured_posts');
        //pr($this->db->queries);
        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/post/posts', $data);
        $this->load->view('admin/includes/_footer');
    }
	
	 /**
     * Special Posts
     */
    public function special_posts()
    {
        check_permission('manage_all_posts');
        $data['title'] = "Special Posts";
        $data['authors'] = $this->auth_model->get_all_users();
        $data['form_action'] = admin_url() . "special-posts";
        $data['list_type'] = "special_posts";
        $data['panel_settings'] = panel_settings();
        $data['categoryTree'] = $this->category_model->categoryTree($this->selected_lang->id);
        //get paginated posts
        $pagination = $this->paginate(admin_url() . 'special-posts', $this->post_admin_model->get_paginated_posts_count('special_posts'));
        $data['posts'] = $this->post_admin_model->get_paginated_posts($pagination['per_page'], $pagination['offset'], 'special_posts');

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/post/posts', $data);
        $this->load->view('admin/includes/_footer');
    }
	
	 /**
     * Trending Posts
     */
    public function trending_posts()
    {
        check_permission('manage_all_posts');
        $data['title'] = "Trending Posts";
        $data['authors'] = $this->auth_model->get_all_users();
        $data['form_action'] = admin_url() . "trending-posts";
        $data['list_type'] = "trending_posts";
        $data['panel_settings'] = panel_settings();
        $data['categoryTree'] = $this->category_model->categoryTree($this->selected_lang->id);
        //get paginated posts
        $pagination = $this->paginate(admin_url() . 'trending-posts', $this->post_admin_model->get_paginated_posts_count('trending_posts'));
        $data['posts'] = $this->post_admin_model->get_paginated_posts($pagination['per_page'], $pagination['offset'], 'trending_posts');

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/post/posts', $data);
        $this->load->view('admin/includes/_footer');
    }

	
	
	
	/**
     * Breaking news
     */
    public function breaking_news_list()
    {
        check_permission('manage_all_posts');
		$data['edit_id'] = "";
		$data['edit_posts'] = array();
        $edit_id = $this->input->get('edit_id', TRUE);
		if(isset($edit_id) && !empty($edit_id)){
			$data['edit_id'] = $edit_id;
			$data['edit_posts'] = $this->post_admin_model->get_brnews_by_id($data['edit_id']);
		}
        $data['title'] = trans('breaking_news');
        $data['list_type'] = "breaking_news";
        //get paginated posts
        $pagination = $this->paginate(admin_url() . 'breaking-news-list', $this->post_admin_model->get_paginated_bnews_count());
        $data['posts'] = $this->post_admin_model->get_paginated_bnews($pagination['per_page'], $pagination['offset']);
        if(isset($_GET['sql']) && $_GET['sql']==1){
            pr($this->selected_lang->id,"---------lang id");
            pr($this->db->queries);
            pr($data);
            die;
        }
        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/breakingnews', $data);
        $this->load->view('admin/includes/_footer');
    }
	
    /**
     * Breaking news
     */
    public function breaking_news()
    {
        check_permission('manage_all_posts');
        $data['title'] = trans('breaking_news');
        $data['authors'] = $this->auth_model->get_all_users();
        $data['form_action'] = admin_url() . "breaking-news";
        $data['list_type'] = "breaking_news";
        $data['panel_settings'] = panel_settings();
        //get paginated posts
        $pagination = $this->paginate(admin_url() . 'breaking-news', $this->post_admin_model->get_paginated_posts_count('breaking_news'));
        $data['posts'] = $this->post_admin_model->get_paginated_posts($pagination['per_page'], $pagination['offset'], 'breaking_news');

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/post/posts', $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * live news
     */
    public function live_news()
    {
        check_permission('manage_all_posts');
        $data['title'] = trans('live_news');
        $data['authors'] = $this->auth_model->get_all_users();
        $data['form_action'] = admin_url() . "live-news";
        $data['list_type'] = "live_news";
        $data['panel_settings'] = panel_settings();
        $data['categoryTree'] = $this->category_model->categoryTree($this->selected_lang->id);
        //get paginated posts
        $pagination = $this->paginate(admin_url() . 'live-news', $this->post_admin_model->get_paginated_posts_count('live_news'));
        $data['posts'] = $this->post_admin_model->get_paginated_posts($pagination['per_page'], $pagination['offset'], 'live_news');
         //pr($this->db->queries);
        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/post/posts', $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * webstories
     */
    public function webstories()
    {
        check_permission('manage_all_posts');
        $csv = $this->input->get('csv', true);
        $data['title'] = trans('webstories');
        $data['authors'] = $this->auth_model->get_all_users();
        $data['form_action'] = admin_url() . "webstories";
        $data['list_type'] = "webstories";
        $data['panel_settings'] = panel_settings();
        $data['categoryTree'] = $this->category_model->categoryTree($this->selected_lang->id);
        //get paginated posts
        $pagination = $this->paginate(admin_url() . 'webstories', $this->post_admin_model->get_paginated_posts_count('webstories'));
        
        if($csv  == 1){
            $pagination = array('per_page' => 2000, 'offset' => 0);
        }

        $data['posts'] = $this->post_admin_model->get_paginated_posts($pagination['per_page'], $pagination['offset'], 'webstories');

        if($csv  == 1){
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="post_data.csv"');
            $user_CSV = ["ID","Image","Title","URL","Post_Type","Category","Author","created_at","updated_at"];
            $fp = fopen('php://output', 'wb');
            fputcsv($fp, $user_CSV, ',');
            foreach ($data['posts'] as $key => $item) {
                $user_CSV = [
                    $item->id,
                    get_post_image($item, "small"),
                    html_escape($item->title),
                    site_url().$item->category_slug."/". $item->title_slug."-".$item->id,
                    trans($item->post_type),
                    $item->category_name,
                    $item->author_username,
                    $item->created_at,
                    $item->updated_at
                ];
                fputcsv($fp, $user_CSV, ',');
            }
            fclose($fp);
            die;
        }

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/post/posts', $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * Recommended Posts
     */
    public function recommended_posts()
    {
        check_permission('manage_all_posts');
        $data['title'] = trans('recommended_posts');
        $data['authors'] = $this->auth_model->get_all_users();
        $data['form_action'] = admin_url() . "recommended-posts";
        $data['list_type'] = "recommended_posts";
        $data['panel_settings'] = panel_settings();
        $data['categoryTree'] = $this->category_model->categoryTree($this->selected_lang->id);
        //get paginated posts
        $pagination = $this->paginate(admin_url() . 'recommended-posts', $this->post_admin_model->get_paginated_posts_count('recommended_posts'));
        $data['posts'] = $this->post_admin_model->get_paginated_posts($pagination['per_page'], $pagination['offset'], 'recommended_posts');

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/post/posts', $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * Pending Posts
     */
    public function pending_posts()
    {
        check_permission('add_post');
        $data['title'] = trans('pending_posts');
        $data['authors'] = $this->auth_model->get_all_users();
        $data['form_action'] = admin_url() . "pending-posts";
        $data['list_type'] = "pending_posts";
        $data['panel_settings'] = panel_settings();
        $data['categoryTree'] = $this->category_model->categoryTree($this->selected_lang->id);
        //get paginated posts
        $pagination = $this->paginate(admin_url() . 'pending-posts', $this->post_admin_model->get_paginated_pending_posts_count());
        $data['posts'] = $this->post_admin_model->get_paginated_pending_posts($pagination['per_page'], $pagination['offset']);

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/post/pending_posts', $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * Scheduled Posts
     */
    public function scheduled_posts()
    {
        check_permission('add_post');
        $data['title'] = trans('scheduled_posts');
        $data['authors'] = $this->auth_model->get_all_users();
        $data['form_action'] = admin_url() . "scheduled-posts";
        $data['panel_settings'] = panel_settings();
        $data['categoryTree'] = $this->category_model->categoryTree($this->selected_lang->id);
        //get paginated posts
        $pagination = $this->paginate(admin_url() . 'scheduled-posts', $this->post_admin_model->get_paginated_scheduled_posts_count());
        $data['posts'] = $this->post_admin_model->get_paginated_scheduled_posts($pagination['per_page'], $pagination['offset']);
        //pr($this->db->queries);
        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/post/scheduled_posts', $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * Drafts
     */
    public function drafts()
    {
        check_permission('add_post');
        $data['title'] = trans('drafts');
        $data['authors'] = $this->auth_model->get_all_users();
        $data['form_action'] = admin_url() . "drafts";
        $data['panel_settings'] = panel_settings();
        //$data['categoryTree'] = [];
        $data['categoryTree'] = $this->category_model->categoryTree($this->selected_lang->id);
        //get paginated posts
        $pagination = $this->paginate(admin_url() . 'drafts', $this->post_admin_model->get_paginated_drafts_count());
        $data['posts'] = $this->post_admin_model->get_paginated_drafts($pagination['per_page'], $pagination['offset']);
        //pr($this->db->queries);

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/post/drafts', $data);
        $this->load->view('admin/includes/_footer');
    }

	
	
	 /**
     * Post  Post
     */
    public function save_breaking_news_data()
    {
		
		$hd_edit_id = $this->input->post('hd_edit_id', true);
		$title = $this->input->post('title', true);
        $url = $this->input->post('url', true);
		$this->load->model('cache_purge_model');

		if(!empty($hd_edit_id)){
			$this->post_admin_model->breaking_news_update($title,$hd_edit_id,$url);
            $this->cache_purge_model->cache_purge(["lang_id"=>$this->selected_lang->id,"urls"=>["/"]]);
            redirect(generate_url('admin/breaking-news-list'));
		}else{
			$this->post_admin_model->breaking_news_save($title,$url);
            $this->cache_purge_model->cache_purge(["lang_id"=>$this->selected_lang->id,"urls"=>["/"]]);
        }
        redirect($this->agent->referrer());
	}
		
    /**
     * Post Options Post
     */
    public function post_options_post()
    {
        $option = $this->input->post('option', true);
        $id = $this->input->post('id', true);
        
        $post_type =  $this->input->post('post_type', true);
        $table = ($post_type=="webstory")?"posts_webstory":"posts";
        
        $data["post"] = $this->post_admin_model->get_post($id,$table);

        //check if exists
        if (empty($data['post'])) {
            redirect($this->agent->referrer());
        }

        //if option add remove from slider
        if ($option == 'add-remove-from-slider') {
            check_permission('manage_all_posts');
            $result = $this->post_admin_model->post_add_remove_slider($id);
            if ($result == "removed") {
                $this->session->set_flashdata('success', trans("msg_remove_slider"));
            }
            if ($result == "added") {
                $this->session->set_flashdata('success', trans("msg_add_slider"));
            }
        } elseif ($option == 'add-remove-from-featured') {
            check_permission('manage_all_posts');
            $result = $this->post_admin_model->post_add_remove_featured($id);
            if ($result == "removed") {
                $this->session->set_flashdata('success', trans("msg_remove_featured"));
            }
            if ($result == "added") {
                $this->session->set_flashdata('success', trans("msg_add_featured"));
            }
        } elseif ($option == 'add-remove-from-special') {
            check_permission('manage_all_posts');
            $result = $this->post_admin_model->post_add_remove_special($id);
            if ($result == "removed") {
                $this->session->set_flashdata('success', trans("msg_remove_special"));
            }
            if ($result == "added") {
                $this->session->set_flashdata('success', trans("msg_add_special"));
            }
        } elseif ($option == 'add-remove-from-trending') {
            check_permission('manage_all_posts');
            $result = $this->post_admin_model->post_add_remove_trending($id);
            if ($result == "removed") {
                $this->session->set_flashdata('success', trans("msg_remove_trending"));
            }
            if ($result == "added") {
                $this->session->set_flashdata('success', trans("msg_add_trending"));
            }
        } elseif ($option == 'add-remove-from-breaking') {
            check_permission('manage_all_posts');
            $result = $this->post_admin_model->post_add_remove_breaking($id);
            if ($result == "removed") {
                $this->session->set_flashdata('success', trans("msg_remove_breaking"));
            }
            if ($result == "added") {
                $this->session->set_flashdata('success', trans("msg_add_breaking"));
            }
        } elseif ($option == 'add-remove-from-recommended') {
            check_permission('manage_all_posts');
            $result = $this->post_admin_model->post_add_remove_recommended($id);
            if ($result == "removed") {
                $this->session->set_flashdata('success', trans("msg_remove_recommended"));
            }
            if ($result == "added") {
                $this->session->set_flashdata('success', trans("msg_add_recommended"));
            }
        } elseif ($option == 'approve') {
            check_permission('manage_all_posts');
            if ($this->post_admin_model->approve_post($id)) {
                $this->session->set_flashdata('success', trans("msg_post_approved"));
            } else {
                $this->session->set_flashdata('error', trans("msg_error"));
            }
        } elseif ($option == 'publish') {
            check_permission('add_post');
            if ($this->post_admin_model->publish_post($id)) {
                $this->session->set_flashdata('success', trans("msg_published"));
            } else {
                $this->session->set_flashdata('error', trans("msg_error"));
            }
        } elseif ($option == 'unpublish') {
            check_permission('manage_all_posts');
            if ($this->post_admin_model->unpublish_post($id,$table)) {
                $this->session->set_flashdata('success', "Post successfully unpublished");
            } else {
                $this->session->set_flashdata('error', trans("msg_error"));
            }
        } elseif ($option == 'publish_draft') {
            check_permission('add_post');
            if ($this->post_admin_model->publish_draft($id,$table)) {
                $this->session->set_flashdata('success', trans("msg_published"));
            } else {
                $this->session->set_flashdata('error', trans("msg_error"));
            }
        }
        reset_cache_data_on_change();
        redirect($this->agent->referrer());
    }

	/**
     * Delete Post
     */
    public function delete_bnews()
    {
		$id = $this->input->post('id', true);
		$this->post_admin_model->delete_bnews_p($id);
		$this->session->set_flashdata('success', trans("post") . " " . trans("msg_suc_deleted"));
	}
    /**
     * Delete Post
     */
    public function delete_post()
    {
        check_permission('add_post');
        $id = $this->input->post('id', true);

        $post_type =  $this->input->post('post_type', true);
        $table = ($post_type=="webstory")?"posts_webstory":"posts";

        $data["post"] = $this->post_admin_model->get_post($id,$table);
        //check if exists
        if (empty($data['post'])) {
            $this->session->set_flashdata('error', trans("msg_error"));
        } else {
            if ($this->post_admin_model->delete_post($id,$table)) {
                //delete post tags
                $this->tag_model->delete_post_tags($id);
                $this->session->set_flashdata('success', trans("post") . " " . trans("msg_suc_deleted"));
                reset_cache_data_on_change();
            } else {
                $this->session->set_flashdata('error', trans("msg_error"));
            }
        }
    }

    /**
     * Post Buld Options
     */
    public function post_bulk_options()
    {
        check_permission('manage_all_posts');
        $operation = $this->input->post('operation', true);
        $post_ids = $this->input->post('post_ids', true);
        $this->post_admin_model->post_bulk_options($operation, $post_ids);
        reset_cache_data_on_change();
    }

    /**
     * Delete Selected Posts
     */
    public function delete_selected_posts()
    {
        check_permission('manage_all_posts');
        $post_ids = $this->input->post('post_ids', true);
        $this->post_admin_model->delete_multi_posts($post_ids);
        reset_cache_data_on_change();
    }

    /**
     * Save Featured Post Order
     */
    public function featured_posts_order_post()
    {
        check_permission('manage_all_posts');
        //$post_id = $this->input->post('id', true);
        //$order = $this->input->post('featured_order', true);
        $list_type = $this->input->post('list_type', true);
        $featured_order_ids = $this->input->post('featured_order_ids', true);
        if(!empty($featured_order_ids)){
            $featured_order_ids = explode(",",rtrim($featured_order_ids, ','));
            foreach ($featured_order_ids as $key => $value) {
                if(!empty($value)){
                    $post_id = $value;
                    $order = $key+1;
                    $this->post_admin_model->save_featured_post_order($post_id, $order, $list_type);
                }
            }
        }/* else{
            $this->post_admin_model->save_featured_post_order($post_id, $order);
        } */
        //pr($this->db->queries);
        $this->load->model('cache_purge_model');
        $this->cache_purge_model->cache_purge(["lang_id"=>$this->selected_lang->id,"urls"=>["/"]]);
        
        reset_cache_data_on_change();
        redirect($this->agent->referrer());
    }
	
	/**
     * Save Special Post Order
     */
    public function special_posts_order_post()
    {
        check_permission('manage_all_posts');
        $post_id = $this->input->post('id', true);
        $order = $this->input->post('special_order', true);
        $this->post_admin_model->save_special_post_order($post_id, $order);
        reset_cache_data_on_change();
        redirect($this->agent->referrer());
    }
	
	/**
     * Save Trending Post Order
     */
    public function trending_posts_order_post()
    {
        check_permission('manage_all_posts');
        $post_id = $this->input->post('id', true);
        $order = $this->input->post('trending_order', true);
        $this->post_admin_model->save_trending_post_order($post_id, $order);
        reset_cache_data_on_change();
        redirect($this->agent->referrer());
    }

    /**
     * Save Home Slider Post Order
     */
    public function home_slider_posts_order_post()
    {
        check_permission('manage_all_posts');
        $post_id = $this->input->post('id', true);
        $order = $this->input->post('slider_order', true);
        $this->post_admin_model->save_home_slider_post_order($post_id, $order);
        reset_cache_data_on_change();
        redirect($this->agent->referrer());
    }

    /**
     * Delete Post Main Image
     */
    public function delete_post_main_image()
    {
        $post_id = $this->input->post('post_id', true);
        $post_type = $this->input->post('post_type', true);
        $this->post_file_model->delete_post_main_image($post_id,$post_type);
    }

    /**
     * Delete Rss Post Main Image
     */
    public function delete_rss_post_main_image()
    {
        $post_id = $this->input->post('post_id', true);
        $this->post_file_model->delete_rss_post_main_image($post_id);
    }

    /**
     * Delete Post File
     */
    public function delete_post_file()
    {
        $id = $this->input->post('id', true);
        $this->post_file_model->delete_post_file($id);
    }
    
    /*
    *-------------------------------------------------------------------------------------------------
    * IMPORT POSTS
    *-------------------------------------------------------------------------------------------------
    */

    /**
     * Bulk Post Upload
     */
    public function bulk_post_upload()
    {
        check_permission('add_post');
        $data['title'] = trans("bulk_post_upload");

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/post/bulk_post_upload', $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * Generate CSV Object Post
     */
    public function generate_csv_object_post()
    {
        check_permission('add_post');
        $this->load->model('upload_model');

        //delete old txt files
        $files = glob(FCPATH . 'uploads/tmp/*.txt');
        if (!empty($files)) {
            foreach ($files as $item) {
                @unlink($item);
            }
        }

        $file = null;
        if (isset($_FILES['file'])) {
            if (!empty($_FILES['file']['name'])) {
                $file = $_FILES['file'];
            }
        }

        $file_path = "";
        $config['upload_path'] = './uploads/tmp/';
        $config['allowed_types'] = 'csv';
        $config['file_name'] = uniqid();
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('file')) {
            $data = $this->upload->data();
            if (isset($data['full_path'])) {
                $file_path = $data['full_path'];
            }
        }

        if (!empty($file_path)) {
            $csv_object = $this->post_admin_model->generate_csv_object($file_path);
            if (!empty($csv_object)) {
                $data = array(
                    'result' => 1,
                    'number_of_items' => $csv_object->number_of_items,
                    'txt_file_name' => $csv_object->txt_file_name,
                );
                echo json_encode($data);
                exit();
            }
        }
        $data = array(
            'result' => 0
        );
        echo json_encode($data);
    }

    /**
     * Import CSV Item Post
     */
    public function import_csv_item_post()
    {
        check_permission('add_post');
        $txt_file_name = $this->input->post('txt_file_name', true);
        $index = $this->input->post('index', true);

        $title = $this->post_admin_model->import_csv_item($txt_file_name, $index);
        if (!empty($title)) {
            reset_cache_data_on_change();
            $data = array(
                'result' => 1,
                'title' => $title,
                'index' => $index
            );
            echo json_encode($data);
        } else {
            $data = array(
                'result' => 0,
                'index' => $index
            );
            echo json_encode($data);
        }
    }

    /**
     * Download CSV Files Post
     */
    public function download_csv_files_post()
    {
        post_method();
        $submit = $this->input->post('submit', true);
        if ($submit == 'csv_template') {
            $this->load->helper('download');
            force_download(FCPATH . "assets/file/csv_template.csv", NULL);
        } elseif ($submit == 'csv_example') {
            $this->load->helper('download');
            force_download(FCPATH . "assets/file/csv_example.csv", NULL);
        }
    }
}
