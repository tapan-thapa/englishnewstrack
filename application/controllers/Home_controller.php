<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Home_controller extends Home_Core_Controller {

    public function __construct() {
        if (strpos($_SERVER['REQUEST_URI'], "/?") === 0 || $_SERVER['REQUEST_URI'] == "/" || $_SERVER['REQUEST_URI'] == "/bitbucket-newsdrum/newsdrum/") {
            parent::__construct('home_page');
        } else {
            parent::__construct();
        }

        $this->post_load_more_count = 6;
        $this->comment_limit = 5;
    }

    /**
     * Index Page
     */
    public function index() {
        get_method();
        $data['title'] = $this->settings->home_title;
        $data['description'] = $this->settings->site_description;
        $data['keywords'] = $this->settings->keywords;
        $data['home_title'] = $this->settings->home_title;

        //latest posts
        $data['latest_posts'] = $this->latest_category_posts;
        $data['slider_posts'] = $data['latest_posts'];
        $data['featured_posts'] = $data['latest_posts'];
        $data['special_posts'] = $data['latest_posts'];
        $data['trending_posts'] = $data['latest_posts'];
        $data['most_viewed_posts'] = $data['latest_posts'];
        //slider posts
        if ($this->general_settings->show_latest_posts_on_slider != 1) {
            //$data['slider_posts'] = get_slider_posts();
        }
        //feature posts
        if ($this->general_settings->show_latest_posts_on_featured != 1) {
            $data['featured_posts'] = get_featured_posts();
            //pr($this->db->queries);
        }
        //$data['special_posts'] = get_special_posts();
        //$data['trending_posts'] = get_trending_posts();
        if ($this->selected_lang->id == 2) {
            $data['trending_tags'] = get_trending_tags(-3);
        } elseif ($this->selected_lang->id == 3) {
            $data['trending_tags'] = get_trending_tags(-5);
        } else {
            $data['trending_tags'] = get_trending_tags(-1);
        }

        //breaking news
        //$data['breaking_news'] = get_breaking_news();

        $data['breaking_news'] = get_breaking_news_latest();
        $data['page_name'] = $data['page_type'] = "home";
        $most_viewed_posts = $this->post_model->get_popular_posts_daily($this->selected_lang->id, 10);
        $data['most_viewed_posts'] = $most_viewed_posts;
        $this->load->view('partials/_header', $data);
        $this->load->view('index', $data);
        $this->load->view('partials/_footer', $data);
    }

    /**
     * Posts Page
     */
    public function posts() {

        get_method();
        $data['title'] = trans("posts");
        $data['description'] = trans("posts") . " - " . $this->settings->site_title;
        $data['keywords'] = trans("posts") . "," . $this->settings->application_name;
        //set paginated
        $pagination = $this->paginate(generate_url('posts'), get_total_post_count());
        $data['posts'] = get_cached_data('posts_page_' . $pagination['current_page']);
        if (empty($data['posts'])) {
            $data['posts'] = $this->post_model->get_paginated_posts($pagination['offset'], $pagination['per_page']);
            set_cache_data('posts_page_' . $pagination['current_page'], $data['posts']);
        }

        $this->load->view('partials/_header', $data);
        $this->load->view('post/posts', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * newsletter Page
     */
    public function newsletter_post() {
        get_method();
        $data['title'] = '';
        $data['description'] = '';
        $data['keywords'] = '';
        $data['page_name'] = "newsletter";
        $data['page_type'] = "newsletter";
        if ($this->general_settings->show_latest_posts_on_featured != 1) {
            $data['featured_newsletter_posts'] = $this->post_model->get_featured_posts_newsletter();
        }
        //$this->load->view('partials/_header', $data);
        $this->load->view('newsletter/newsletter', $data);
        //$this->load->view('partials/_footer');
    }

    /**
     * Tag Page
     */
    public function tag($tag_slug) {
        get_method();
        $tag_slug = clean_slug($tag_slug);
        $data['tag_slug'] = $tag_slug;
        $data['tag'] = $this->tag_model->get_tag($tag_slug);
        //check tag exists
        if (empty($data['tag'])) {
            redirect(lang_base_url());
        }
        $data['special_posts'] = get_special_posts();
        $data['breaking_news'] = get_breaking_news_latest();
        $data['page_name'] = "topic";
        $data['page_type'] = "list";

        $data['title'] = $data['tag']->tag . " News, Get Breaking " . $data['tag']->tag . " Daily Alert from Newstrack";
        $data['description'] = "Newstrack: Read all the " . $data['tag']->tag . " News Stories Here, Expert Analysis, People Reaction and Comments, Most read Viral " . $data['tag']->tag . "  Stories only on Newstrack. ";
        $data['keywords'] = $data['tag']->tag . ", " . $data['tag']->tag . " News, " . $data['tag']->tag . " Story";

        //set paginated
        $pagination = $this->paginate(generate_tag_url($tag_slug), $this->post_model->get_post_count_by_tag($tag_slug));
        $data['posts'] = $this->post_model->get_paginated_tag_posts($tag_slug, $pagination['offset'], $pagination['per_page']);

        $this->load->view('partials/_header', $data);
        $this->load->view('tag', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Special news Page
     */
    public function special_stories() {
        get_method();
        $data['special_posts'] = get_special_posts();
        $data['breaking_news'] = get_breaking_news_latest();
        $data['page_type'] = "breaking";
        $data['cur_post'] = array();
        $data['sel_id'] = "";
        $data['title'] = "Special Stories";
        $data['description'] = "Special Stories";
        $data['keywords'] = "Special Stories";
        $category = $this->category_model->get_category_by_slug("special-stories");
        $data['category'] = $category;
        //set paginated

        $pagination = $this->paginate(lang_base_url() . get_route('special-stories', true), count($this->post_model->get_special_posts(2000)));

        $data['posts'] = $this->post_model->get_paginated_special_news($pagination['offset'], $pagination['per_page']);

        $this->load->view('partials/_header', $data);
        $this->load->view('category', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Trending news Page
     */
    public function trending_news() {
        get_method();
        $data['special_posts'] = get_special_posts();
        $data['breaking_news'] = get_breaking_news_latest();
        $data['page_type'] = "breaking";
        $data['cur_post'] = array();
        $data['sel_id'] = "";
        $data['title'] = "Trending";
        $data['description'] = "Trending";
        $data['keywords'] = "Trending";
        $category = $this->category_model->get_category_by_slug("trending");
        $data['category'] = $category;
        //set paginated

        $pagination = $this->paginate(lang_base_url() . get_route('trending', true), count($this->post_model->get_trending_posts(2000)));

        $data['posts'] = $this->post_model->get_paginated_trending_news($pagination['offset'], $pagination['per_page']);

        $this->load->view('partials/_header', $data);
        $this->load->view('category', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Breaking news Page
     */
    public function breaking_news() {
        get_method();
        $data['special_posts'] = get_special_posts();
        $data['breaking_news'] = get_breaking_news_latest();
        $data['page_type'] = "breaking";
        $data['cur_post'] = array();
        $data['sel_id'] = "";
        $data['title'] = "Live Updates";
        $data['description'] = trans("tag") . ': ' . "Live Updates";
        $data['keywords'] = trans("tag") . ', ' . "Live Updates";
        //set paginated
        $pagination = $this->paginate(lang_base_url() . get_route('breaking-news', true), $this->post_model->get_post_count_by_breaking_news());
        if (isset($_GET['id']) && $_GET['id'] != '') {
            $getId = explode('-', $_GET['id']);
            $data['sel_id'] = end($getId);
            $data['cur_post'] = $this->post_model->get_bnews_by_id($data['sel_id']);
            $data['title'] = $data['cur_post']->title . " | Live Updates";
            $data['keywords'] = $data['title'] . ' : NewsDrum - ' . $this->settings->site_title;
        }
        $data['posts'] = $this->post_model->get_paginated_breaking_news($pagination['offset'], $pagination['per_page']);

        //$last = array_pop($array);
        //array_unshift($array, $last);
        //$data['posts']= array();
        $this->load->view('partials/_header', $data);
        $this->load->view('breaking', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Topic Page
     */
    public function topic($topic_slug) {
        get_method();
        $topic_slug = clean_slug($topic_slug);
        $data['topic_slug'] = $topic_slug;
        $data['topic'] = $this->tag_model->get_topic($topic_slug);
        //check tag exists
        if (empty($data['topic'])) {
            redirect(lang_base_url());
        }
        $data['special_posts'] = get_special_posts();
        $data['breaking_news'] = get_breaking_news_latest();
        $data['page_name'] = "topic";
        $data['page_type'] = "list";
        $data['title'] = $data['topic']->topic . " News, Get Breaking " . $data['topic']->topic . " Daily Alert from NewsDrum";
        $data['description'] = "Newstrack: Read all the " . $data['topic']->topic . " News Stories Here, Expert Analysis, People Reaction and Comments, Most read Viral " . $data['topic']->topic . "  Stories only on Newstrack. ";
        $data['keywords'] = $data['topic']->topic . ", " . $data['topic']->topic . " News, " . $data['topic']->topic . " Story";
        //set paginated
        $pagination = $this->paginate(generate_topic_url($topic_slug), $this->post_model->get_post_count_by_topic($topic_slug));
        $data['posts'] = $this->post_model->get_paginated_topic_posts($topic_slug, $pagination['offset'], $pagination['per_page']);

        $this->load->view('partials/_header', $data);
        $this->load->view('topic', $data);
        $this->load->view('partials/_footer');
    }

    public function amp($categoryUrl, $slug, $post_id = '') {
        get_method();

        $slug = clean_slug($slug);
        if (empty($slug)) {
            redirect(lang_base_url());
        }

        $post = $this->post_model->get_post($post_id);
        if (!empty($post)) {
            $this->amp_post($post);
        } else {
            $this->error_404();
        }
    }

    /**
     * Dynamic URL by Slug
     */
    public function any($categoryUrl, $slug, $post_id = '') {
        get_method();

        $slug = clean_slug($slug);
        if (empty($slug)) {
            redirect(lang_base_url());
        }
        //check pages
        $page = $this->page_model->get_page_by_lang($slug, $this->selected_lang->id);
        if (!empty($page)) {
            $this->page($page);
        } else {
            //check categories
            $category = $this->category_model->get_category_by_slug($slug, $this->selected_lang->id);
            if (!empty($category)) {
                if (function_exists('get_site_mod')) {
                    get_site_mod();
                }
                $this->category($category);
            } else {
                //check posts
                if ($categoryUrl == 'photo-stories') {
                    $post = $this->post_model->get_webstory_post($slug);
                } else {
                    $post = $this->post_model->get_post($slug);
                }

                if (!empty($post)) {
                    if (empty($categoryUrl)) {
                        redirect(lang_base_url() . $post->category_slug . "/" . $slug . ".html", 'location', 301);
                    }
                    $this->post($post);
                } else {
                    //not found
                    $this->error_404();
                }
            }
        }
        if (isset($_GET['sqlp']) && $_GET['sqlp'] == 1) {
            pr($this->db->queries);
        }
    }

    /**
     * Page
     */
    private function page($page) {
        if (empty($page)) {
            redirect(lang_base_url());
        }
        if ($page->visibility == 0) {
            $this->error_404();
        } else {
            //check page auth
            $this->checkPageAuth($page);
            $data['special_posts'] = get_special_posts();
            $data['breaking_news'] = get_breaking_news_latest();
            $data['page_type'] = "list";
            $data['title'] = $page->title;
            $data['description'] = $page->description;
            $data['keywords'] = $page->keywords;
            $data['page'] = $page;
            if ($page->page_default_name == 'gallery') {
                $data['gallery_albums'] = $this->gallery_category_model->get_albums_by_lang($this->selected_lang->id);
                $data['js_page'] = "gallery";
                $this->load->view('partials/_header', $data);
                $this->load->view('gallery/gallery', $data);
                $this->load->view('partials/_footer');
            } elseif ($page->page_default_name == 'contact') {
                $this->load->view('partials/_header', $data);
                $this->load->view('contact', $data);
                $this->load->view('partials/_footer');
            } else {
                $this->load->view('partials/_header', $data);
                $this->load->view('page', $data);
                $this->load->view('partials/_footer');
            }
        }
    }

    /**
     * Category
     */
    private function category($category) {
        if (empty($category)) {
            redirect(lang_base_url());
        }
        if ($category->parent_id != 0 && $category->parent_id != 233) {
            $this->error_404();
        } else {
            $data['special_posts'] = get_special_posts();
            $data['breaking_news'] = get_breaking_news_latest();
            $data['page_name'] = $data['page_type'] = "list";
            $data['title'] = $category->title;
            $data['description'] = $category->description;
            $data['keywords'] = $category->keywords;
            $data['category'] = $category;
            $data['categoryParent'] = $category;
            $data['categoryChild'] = (isset($categoryChild)) ? $categoryChild : "";

            $count_key = 'posts_count_category' . $category->id;
            $posts_key = 'posts_category' . $category->id;
            //category posts count
            $total_rows = get_cached_data($count_key);
            if (empty($total_rows)) {
                $total_rows = $this->post_model->get_post_count_by_category($category->id);
                set_cache_data($count_key, $total_rows);
            }
            //set paginated
            $pagination = $this->paginate(generate_category_url($category), $total_rows);
            $data['posts'] = get_cached_data($posts_key . '_page' . $pagination['current_page']);
            if (empty($data['posts'])) {
                $data['posts'] = $this->post_model->get_paginated_category_posts($category->id, $pagination['offset'], $pagination['per_page']);
                set_cache_data($posts_key . '_page' . $pagination['current_page'], $data['posts']);
            }
            //pr($this->db->queries);
            $this->load->view('partials/_header', $data);
            $this->load->view('category', $data);
            $this->load->view('partials/_footer');
        }
    }

    /**
     * Subcategory
     */
    public function subcategory($parent_slug, $slug, $slug2 = "", $slug3 = "") {
        get_method();
        $slug = clean_slug($slug);
        $slug2 = clean_slug($slug2);
        $slug3 = clean_slug($slug3);
        //$data['menu_cat'] = $parent_slug;
        $newsCat = (object) [];
        $newsCat = $category = $this->category_model->get_category_by_slug($slug, $this->selected_lang->id);
        if ($slug3 || $slug2) {
            $category = $categoryChild = $this->category_model->get_category_by_slug($slug2, $this->selected_lang->id);
            $data['menu_cat_child'] = $parent_slug . "/" . $slug;
            $data['menu_cat_child_name'] = (!empty($slug3)) ? $slug3 : $slug2;
        } else {
            $categoryChild = (object) ["parent_id" => $category->id];
            $data['menu_cat_child'] = $parent_slug . "/" . $slug;
            $data['menu_cat_child_name'] = "";
        }
        /* if($slug3){
          $category = $this->category_model->get_category_by_slug($slug);
          }elseif($slug2){
          $category = $this->category_model->get_category_by_slug($slug2);
          }elseif($slug){
          $category = $this->category_model->get_category_by_slug($slug);
          } */
        //print_r($category);
        if (empty($category)) {
            redirect(lang_base_url());
        } else {
            $data['special_posts'] = get_special_posts();
            $data['page_type'] = "list";
            $data['breaking_news'] = get_breaking_news_latest();
            $data['title'] = $category->title;
            $data['description'] = $category->description;
            $data['keywords'] = $category->keywords;
            $data['category'] = $category;
            $data['categoryParent'] = $newsCat;
            $data['categoryChild'] = (isset($categoryChild)) ? $categoryChild : "";
            $data['parent_category'] = null;
            if (!empty($category->parent_id)) {
                $data['parent_category'] = get_category($category->parent_id, $this->categories);
            }

            $count_key = 'posts_count_category' . $category->id;
            $posts_key = 'posts_category' . $category->id;
            //category posts count
            $total_rows = get_cached_data($count_key);
            if (empty($total_rows)) {
                $total_rows = $this->post_model->get_post_count_by_category($category->id);
                set_cache_data($count_key, $total_rows);
            }
            //set paginated
            //pr($category);
            if ($slug3 || $slug2) {
                $category->parent_slug = $data['menu_cat_child'];
            }
            $pagination = $this->paginate(generate_category_url($category), $total_rows);
            $data['posts'] = get_cached_data($posts_key . '_page' . $pagination['current_page']);
            if (empty($data['posts'])) {
                $data['posts'] = $this->post_model->get_paginated_category_posts($category->id, $pagination['offset'], $pagination['per_page']);
                set_cache_data($posts_key . '_page' . $pagination['current_page'], $data['posts']);
            }

            $this->load->view('partials/_header', $data);
            $this->load->view('category', $data);
            $this->load->view('partials/_footer');
        }
        if (isset($_GET['sqlp']) && $_GET['sqlp'] == 1) {
            pr($this->db->queries);
        }
    }

    /**
     * webstory_listing
     */
    public function webstory_listing() {
        $category = $this->category_model->get_category_by_slug('photo-stories');
        $data['special_posts'] = get_special_posts();
        $data['breaking_news'] = get_breaking_news_latest();

        $data['page_name'] = $data['page_type'] = "list";
        $data['title'] = $category->title;
        $data['description'] = $category->description;
        $data['keywords'] = $category->keywords;

        #$data['page_name'] = $data['page_type'] = "list";
        #$data['title'] = $category->name;
        #$data['description'] = $category->description;
        #$data['keywords'] = $category->keywords;
        $data['category'] = $category;
        $data['categoryParent'] = $category;
        $data['categoryChild'] = (isset($categoryChild)) ? $categoryChild : "";

        $count_key = 'posts_count_webstory' . $category->id;
        $posts_key = 'posts_webstory' . $category->id;
        //category posts count
        $total_rows = get_cached_data($count_key);
        if (empty($total_rows)) {
            $total_rows = $this->post_model->get_post_count_by_webstory();
            set_cache_data($count_key, $total_rows);
        }
        //set paginated
        $pagination = $this->paginate(generate_category_url($category), $total_rows);
        $data['posts'] = get_cached_data($posts_key . '_page' . $pagination['current_page']);
        if (empty($data['posts'])) {
            $data['posts'] = $this->post_model->get_paginated_webstory_posts($pagination['offset'], $pagination['per_page']);
            set_cache_data($posts_key . '_page' . $pagination['current_page'], $data['posts']);
        }
        //pr($this->db->queries);
        $this->load->view('partials/_header', $data);
        $this->load->view('webstory', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Post
     */
    private function amp_post($post) {

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
        $this->post_model->increase_post_pageviews($post);
    }

    /**
     * Post
     */
    private function post($post) {

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
        $data['live_blog_history'] = $this->live_history_model->get_live_histories($postID);
        //end
        // for webstories data
        $postID = $data['post']->id;
        $data['webstories'] = $data['webstory_history'] = $this->webstory_model->get_webstories($postID);
        //end
        // for webstories
        //$postID = $data['post']->id;
        //$data['webstories'] = $this->webstory_model->get_webstories($postID);
        //end

        $data['post_user'] = $this->auth_model->get_user($post->user_id);
        $data['post_tags'] = $this->tag_model->get_post_tags($post->id);
        $data['trending_tags'] = get_trending_tags(-2);

        // raj
        $tagArray = [];
        foreach ($data['post_tags'] as $key => $tags) {
            $tagArray[] = $tags->tag_slug;
        }
        if (!empty($tagArray)) {

            $data['related_posts'] = $this->post_model->get_tag_posts($tagArray);
        } else {
            $data['related_posts'] = $this->post_model->get_related_posts($post->category_id, $post->id);
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

        $data['previous_post'] = $this->post_model->get_previous_post($post->id);
        $data['next_post'] = $this->post_model->get_next_post($post->id);

        $data['is_reading_list'] = $this->reading_list_model->is_post_in_reading_list($post->id);

        $data['post_type'] = $post->post_type;

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

        $most_viewed_posts = $this->post_model->get_popular_posts_daily($this->selected_lang->id, 10);
        $data['most_viewed_posts'] = $most_viewed_posts;

        //raj for keyword
        if (!empty($post->post_select_key)) {
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
        if ($post->post_type != "webstory") {
            $this->load->view('partials/_header', $data);
            $this->load->view('post/post', $data);
            $this->load->view('partials/_footer', $data);
        } else {
            $this->load->view('partials/_header_second', $data);
            $this->load->view('post/post', $data);
        }

        //increase pageviews count
        $this->post_model->increase_post_pageviews($post);
    }

    /**
     * Gallery Post Page
     */
    public function gallery_post($slug, $item_order) {
        get_method();
        $slug = clean_slug($slug);
        $item_order = clean_number($item_order);
        $post = $this->post_model->get_post($slug);
        //check if post exists
        if (empty($post)) {
            redirect($this->agent->referrer());
        }
        //check post auth
        if (!$this->auth_check && $post->need_auth == 1) {
            $this->session->set_flashdata('error', trans("message_post_auth"));
            redirect(generate_url('register'));
        }
        $data['post'] = $post;
        $data['post_user'] = $this->auth_model->get_user($post->user_id);
        $data['post_tags'] = $this->tag_model->get_post_tags($post->id);
        $data['post_images'] = $this->post_file_model->get_post_additional_images($post->id);
        $data['comments'] = $this->comment_model->get_comments($post->id, $this->comment_limit);
        $data['comment_count'] = $this->comment_model->get_comment_count_by_post_id($post->id);
        $data['comment_limit'] = $this->comment_limit;

        $data['related_posts'] = $this->post_model->get_related_posts($post->category_id, $post->id);
        $data['previous_post'] = $this->post_model->get_previous_post($post->id);
        $data['next_post'] = $this->post_model->get_next_post($post->id);

        $data['is_reading_list'] = $this->reading_list_model->is_post_in_reading_list($post->id);

        $data['post_type'] = $post->post_type;

        if (!empty($post->feed_id)) {
            $data['feed'] = $this->rss_model->get_feed($post->feed_id);
        }

        $data = $this->set_post_meta_tags($post, $data['post_tags'], $data);

        $this->reaction_model->set_voted_reactions_session($post->id);
        $data["reactions"] = $this->reaction_model->get_reaction($post->id);

        //gallery post item
        $data['gallery_post_total_item_count'] = $this->post_item_model->get_post_list_items_count($post->id, $post->post_type);
        $data['gallery_post_item'] = $this->post_item_model->get_gallery_post_item_by_order($post->id, $item_order);

        if ($item_order <= 0) {
            redirect(generate_post_url($post) . "/1");
        }
        if ($item_order > $data['gallery_post_total_item_count']) {
            redirect(generate_post_url($post) . "/" . $data['gallery_post_total_item_count']);
        }
        $data['gallery_post_item_order'] = $item_order;

        $this->load->view('partials/_header', $data);
        $this->load->view('post/post', $data);
        $this->load->view('partials/_footer', $data);
    }

    /**
     * Preview
     */
    public function preview($slug) {
        get_method();

        if (!auth_check()) {
            redirect(lang_base_url());
            exit();
        }
        $slug = clean_slug($slug);
        if (empty($slug)) {
            redirect(lang_base_url());
            exit();
        }
        //check posts
        $post = $this->post_model->get_post_preview($slug);
        if (!empty($post)) {
            if (!check_post_ownership($this->auth_user->id)) {
                redirect(lang_base_url());
                exit();
            }
            $this->post($post);
        } else {
            //not found
            $this->error_404();
        }
    }

    //set post meta tags
    private function set_post_meta_tags($post, $post_tags, $data) {
        $data['title'] = $post->title;
        $data['description'] = $post->tag_description;
        $data['keywords'] = $post->keywords;

        $data['og_title'] = $post->title;
        $data['og_description'] = $post->tag_description;
        $data['og_type'] = "article";
        $data['og_url'] = generate_post_url($post);
        $data['og_image'] = base_url() . $post->image_big;
        if (!empty($post->image_url)) {
            $data['og_image'] = $post->image_url;
        }
        $data['og_width'] = "800";
        $data['og_height'] = "450";
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

    /**
     * Gallery Album Page
     */
    public function gallery_album($id) {
        get_method();
        $id = clean_number($id);
        $data['page'] = $this->page_model->get_page_by_default_name('gallery', $this->selected_lang->id);
        $data['js_page'] = "gallery";
        //check page exists
        if (empty($data['page'])) {
            redirect(lang_base_url());
        }
        //check page auth
        $this->checkPageAuth($data['page']);
        if ($data['page']->visibility == 0) {
            $this->error_404();
        } else {
            $data['title'] = get_page_title($data['page']);
            $data['description'] = get_page_description($data['page']);
            $data['keywords'] = get_page_keywords($data['page']);
            //get album
            $data['album'] = $this->gallery_category_model->get_album($id);
            if (empty($data['album'])) {
                redirect($this->agent->referrer());
            }
            //get gallery images
            $data['gallery_images'] = $this->gallery_model->get_images_by_album($data['album']->id);
            $data['gallery_categories'] = $this->gallery_category_model->get_categories_by_album($data['album']->id);

            $this->load->view('partials/_header', $data);
            $this->load->view('gallery/gallery_album', $data);
            $this->load->view('partials/_footer', $data);
        }
    }

    /**
     * Contact Page Post
     */
    public function contact_post() {
        post_method();
        //validate inputs
        $this->form_validation->set_rules('name', trans("placeholder_name"), 'required|max_length[200]');
        $this->form_validation->set_rules('email', trans("placeholder_email"), 'required|max_length[200]');
        $this->form_validation->set_rules('message', trans("placeholder_message"), 'required|max_length[5000]');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('errors', validation_errors());
            $this->session->set_flashdata('form_data', $this->contact_model->input_values());
            redirect($this->agent->referrer());
        } else {
            if (!$this->recaptcha_verify_request()) {
                $this->session->set_flashdata('form_data', $this->contact_model->input_values());
                $this->session->set_flashdata('error', trans("msg_recaptcha"));
                redirect($this->agent->referrer());
            } else {
                if ($this->contact_model->add_contact_message()) {
                    $this->session->set_flashdata('success', trans("message_contact_success"));
                    redirect($this->agent->referrer());
                } else {
                    $this->session->set_flashdata('form_data', $this->contact_model->input_values());
                    $this->session->set_flashdata('error', trans("message_contact_error"));
                    redirect($this->agent->referrer());
                }
            }
        }
    }

    /**
     * Search Page
     */
    public function search() {
        get_method();
        $q = trim($this->input->get('q', true));
        if (empty($q)) {
            return redirect(lang_base_url());
        }
        $q = remove_forbidden_characters($q);
        $q = strip_tags($q);

        $data["q"] = $q;
        $data['title'] = trans("search") . ': ' . $q;
        $data['description'] = trans("search") . ': ' . $q;
        $data['keywords'] = trans("search") . ', ' . $q;
        //set paginated
        $pagination = $this->paginate(generate_url('search'), $this->post_model->get_search_post_count($q));
        $data['posts'] = $this->post_model->get_paginated_search_posts($q, $pagination['offset'], $pagination['per_page']);
        $data['special_posts'] = get_special_posts();
        $data['breaking_news'] = get_breaking_news_latest();
        if ($this->selected_lang->id == 2) {
            $data['trending_tags'] = get_trending_tags(-4);
        } elseif ($this->selected_lang->id == 3) {
            $data['trending_tags'] = get_trending_tags(-6);
        } else {
            $data['trending_tags'] = get_trending_tags(-2);
        }
        $data['page_type'] = "list";
        //pr($this->db->queries);
        $this->load->view('partials/_header', $data);
        $this->load->view('search', $data);
        $this->load->view('partials/_footer');
    }

    public function generate_category_feeds($slug) {

        get_method();
        $slug = clean_slug($slug);
        //load the library
        $this->load->helper('xml');
        if ($this->general_settings->show_rss == 1):
            if ($slug != 'google-news-sitemap') {
                $data['category'] = $this->category_model->get_category_by_slug($slug);
            }
            //google-news-sitemap

            if ($slug != 'google-news-sitemap' && empty($data['category'])) {
                redirect(generate_url('rss_feeds'));
            }
            if ($slug != 'google-news-sitemap') {
                $data['feed_name'] = $this->settings->site_title . " - " . trans("title_category") . ": " . $data['category']->name;
                $data['category_name'] = $slug;
                $data['encoding'] = 'utf-8';
                $data['base_url'] = lang_base_url();
                $data['feed_url'] = lang_base_url() . "" . $data['category']->name_slug . ".xml";
                $data['page_description'] = $this->settings->site_title . " - " . trans("title_category") . ": " . $data['category']->name;
                $data['page_language'] = "en";
                $data['creator_email'] = '';
                $data['posts'] = $this->post_model->get_category_posts($data['category']->id, 1000);
            } else {
                $data['posts'] = $this->post_model->get_latest_posts($this->selected_lang->id, 100);
            }
            header("Content-Type: application/xml");
            header('Cache-Control: max-age=60', TRUE);
            header('Pragma: cache');
            if ($slug == 'google-news-sitemap') {
                $data['category'] = 'google-news-sitemap';
                header("Content-Type: application/xml");
                header('Cache-Control: max-age=60', TRUE);
                header('Pragma: cache');
                header('Expires: ' . gmdate('D, d M Y H:i:s T', mktime(date('H'), date('i') + 1, 0)));
                $this->load->view('rss/google_news', $data);
            } else {
                $this->load->view('rss/category_rss', $data);
            }
        endif;
    }

    /**
     * Rss Page
     */
    public function html_sitemap() {
        get_method();

        $data['title'] = "Sitemap";
        $data['description'] = "Sitemap " . " - " . $this->settings->site_title;
        $data['keywords'] = "Sitemap " . "," . $this->settings->application_name;
        $this->load->view('partials/_header', $data);
        $this->load->view('rss/html_sitemap', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Rss Page
     */
    public function rss_feeds() {
        get_method();
        if ($this->general_settings->show_rss == 0) {
            $this->error_404();
        } else {
            $data['title'] = trans("rss_feeds");
            $data['description'] = trans("rss_feeds") . " - " . $this->settings->site_title;
            $data['keywords'] = trans("rss_feeds") . "," . $this->settings->application_name;
            $this->load->view('partials/_header', $data);
            $this->load->view('rss/rss_feeds', $data);
            $this->load->view('partials/_footer');
        }
    }

    /**
     * Rss All Posts
     */
    public function rss_latest_posts() {
        get_method();
        //load the library
        $this->load->helper('xml');
        if ($this->general_settings->show_rss == 1):
            $data['feed_name'] = $this->settings->site_title . " - " . trans("latest_posts");
            $data['encoding'] = 'utf-8';
            $data['feed_url'] = lang_base_url() . "rss/latest-posts";
            $data['page_description'] = $this->settings->site_title . " - " . trans("latest_posts");
            $data['page_language'] = $this->selected_lang->short_form;
            $data['creator_email'] = '';
            $data['posts'] = $this->post_model->get_latest_posts($this->selected_lang->id, 30);
            header("Content-Type: application/rss+xml; charset=utf-8");
            $this->load->view('rss/rss', $data);
        endif;
    }

    /**
     * Rss By Category
     */
    public function rss_by_category($slug) {
        //get_method();
        $slug = clean_slug($slug);
        //load the library
        $this->load->helper('xml');
        if ($this->general_settings->show_rss == 1) {
            $data['category'] = $this->category_model->get_category_by_slug($slug);
            if (empty($data['category'])) {
                redirect(generate_url('rss_feeds'));
            }
            $data['feed_name'] = $this->settings->site_title . " - " . trans("title_category") . ": " . $data['category']->name;
            $data['encoding'] = 'utf-8';
            $data['feed_url'] = lang_base_url() . "rss/category/" . $data['category']->name_slug;
            $data['page_description'] = $this->settings->site_title . " - " . trans("title_category") . ": " . $data['category']->name;
            $data['page_language'] = $this->selected_lang->short_form;
            $data['creator_email'] = '';
            $data['posts'] = $this->post_model->get_category_posts($data['category']->id, 200);
            header("Content-Type: application/rss+xml; charset=utf-8");
            $this->load->view('rss/rss', $data);
        }
    }

    /**
     * Rss By User
     */
    public function rss_by_user($slug) {
        get_method();
        $slug = clean_slug($slug);
        //load the library
        $this->load->helper('xml');
        if ($this->general_settings->show_rss == 1):
            $user = $this->auth_model->get_user_by_slug($slug);
            if (empty($user)) {
                redirect(generate_url('rss_feeds'));
            }
            $user_id = $user->id;
            $data['feed_name'] = $this->settings->site_title . " - " . $user->username;
            $data['encoding'] = 'utf-8';
            $data['feed_url'] = lang_base_url() . "rss/author/" . $user->slug;
            $data['page_description'] = $this->settings->site_title . " - " . $user->username;
            $data['page_language'] = $this->selected_lang->short_form;
            $data['creator_email'] = '';
            $data['posts'] = $this->post_model->get_user_posts($user_id, 1000);
            header("Content-Type: application/rss+xml; charset=utf-8");
            $this->load->view('rss/rss', $data);
        endif;
    }

    /**
     * Add Comment
     */
    public function add_comment_post() {
        post_method();
        if ($this->general_settings->comment_system != 1) {
            exit();
        }
        $limit = clean_number($this->input->post('limit', true));
        $post_id = clean_number($this->input->post('post_id', true));
        if ($this->auth_check) {
            $this->comment_model->add_comment();
        } else {
            if ($this->recaptcha_verify_request()) {
                $this->comment_model->add_comment();
            }
        }
        if ($this->general_settings->comment_approval_system == 1 && !check_user_permission('comments_contact')) {
            $data = array(
                'type' => 'message',
                'message' => "<p class='comment-success-message'><i class='icon-check'></i>&nbsp;&nbsp;" . trans("msg_comment_sent_successfully") . "</p>"
            );
            echo json_encode($data);
        } else {
            $data["post"] = $this->post_model->get_post_by_id($post_id);
            $data['comments'] = $this->comment_model->get_comments($post_id, $limit);
            $data['comment_count'] = $this->comment_model->get_comment_count_by_post_id($post_id);
            $data['comment_limit'] = $limit;

            $data_json = array(
                'type' => 'comments',
                'message' => $this->load->view('post/_comments', $data, true)
            );
            echo json_encode($data_json);
        }
    }

    //delete comment
    public function delete_comment_post() {
        post_method();
        $id = clean_number($this->input->post('id', true));
        $post_id = clean_number($this->input->post('post_id', true));
        $limit = clean_number($this->input->post('limit', true));

        $comment = $this->comment_model->get_comment($id);
        if ($this->auth_check && !empty($comment)) {
            if ($this->auth_user->role == "admin" || $this->auth_user->id == $comment->user_id) {
                $this->comment_model->delete_comment($id);
            }
        }

        $data["post"] = $this->post_model->get_post_by_id($post_id);
        $data['comments'] = $this->comment_model->get_comments($post_id, $limit);
        $data['comment_count'] = $this->comment_model->get_comment_count_by_post_id($post_id);
        $data['comment_limit'] = $limit;

        $data_json = array(
            'result' => 1,
            'html_content' => $this->load->view('post/_comments', $data, true)
        );
        echo json_encode($data_json);
    }

    //load subcomment box
    public function load_subcomment_box() {
        post_method();
        $comment_id = clean_number($this->input->post('comment_id', true));
        $limit = clean_number($this->input->post('limit', true));
        $data["parent_comment"] = $this->comment_model->get_comment($comment_id);
        $data["comment_limit"] = $limit;
        $this->load->view('post/_make_subcomment', $data);
    }

    //load more comment
    public function load_more_comment() {
        post_method();
        $post_id = clean_number($this->input->post('post_id', true));
        $limit = clean_number($this->input->post('limit', true));
        $new_limit = $limit + $this->comment_limit;

        $data["post"] = $this->post_model->get_post_by_id($post_id);
        $data['comments'] = $this->comment_model->get_comments($post_id, $new_limit);
        $data['comment_count'] = $this->comment_model->get_comment_count_by_post_id($post_id);
        $data['comment_limit'] = $new_limit;

        $data_json = array(
            'result' => 1,
            'html_content' => $this->load->view('post/_comments', $data, true)
        );
        echo json_encode($data_json);
    }

    /**
     * Like Comment
     */
    public function like_comment_post() {
        post_method();
        $comment_id = clean_number($this->input->post('comment_id', true));
        $like_count = $this->comment_model->like_comment($comment_id);
        $data = array(
            'result' => 1,
            'like_count' => $like_count
        );
        echo json_encode($data);
    }

    /**
     * Add Poll Vote
     */
    public function add_vote() {
        post_method();
        $poll_id = clean_number($this->input->post('poll_id', true));
        $vote_permission = clean_number($this->input->post('vote_permission', true));
        $option = $this->input->post('option', true);
        if (is_null($option)) {
            echo "required";
        } else {
            if ($vote_permission == "all") {
                $result = $this->poll_model->add_unregistered_vote($poll_id, $option);
                if ($result == "success") {
                    $data["poll"] = $this->poll_model->get_poll($poll_id);
                    $this->load->view('partials/_poll_results', $data);
                } else {
                    echo "voted";
                }
            } else {
                $user_id = user()->id;
                $result = $this->poll_model->add_registered_vote($poll_id, $user_id, $option);
                if ($result == "success") {
                    $data["poll"] = $this->poll_model->get_poll($poll_id);
                    $this->load->view('partials/_poll_results', $data);
                } else {
                    echo "voted";
                }
            }
        }
    }

    /**
     * Reading List Page
     */
    public function reading_list() {
        get_method();
        if (!$this->auth_check) {
            redirect(lang_base_url());
        }
        $data['title'] = trans("reading_list");
        $data['description'] = trans("reading_list") . " - " . $this->settings->site_title;
        $data['keywords'] = trans("reading_list") . "," . $this->settings->application_name;

        //set paginated
        $pagination = $this->paginate(generate_url('reading_list'), $this->reading_list_model->get_reading_list_posts_count());
        $data['posts'] = $this->reading_list_model->get_paginated_reading_list_posts($pagination['offset'], $pagination['per_page']);

        $this->load->view('partials/_header', $data);
        $this->load->view('reading_list', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Load More Posts
     */
    public function load_more_posts() {
        post_method();
        $lang_id = clean_number($this->input->post("load_more_posts_lang_id", true));
        $last_id = clean_number($this->input->post("load_more_posts_last_id", true));

        //set language
        if ($this->general_settings->site_lang != $lang_id) {
            $lang = $this->language_model->get_language($lang_id);
            if (!empty($lang)) {
                $this->lang_base_url = base_url() . $lang->short_form . "/";
            }
        }

        $latest_posts = load_more_posts($lang_id, $last_id, $this->post_load_more_count);
        if (!empty($latest_posts)) {
            $html_content = "";
            $hide_button = false;
            foreach ($latest_posts as $post) {
                $vars = array('post' => $post, 'show_label' => true);
                $html_content .= $this->load->view("post/_post_item_horizontal", $vars, true);
                $last_id = $post->id;
            }
            if (empty($this->post_model->load_more_posts($lang_id, $last_id, $this->post_load_more_count))) {
                $hide_button = true;
            }
            $data = array(
                'result' => 1,
                'html_content' => $html_content,
                'last_id' => $last_id,
                'hide_button' => $hide_button
            );
            echo json_encode($data);
        } else {
            $data = array(
                'result' => 0,
            );
            echo json_encode($data);
        }
    }

    /**
     * Make Reaction
     */
    public function save_reaction() {
        post_method();
        $post_id = clean_number($this->input->post('post_id'));
        $reaction = clean_str($this->input->post('reaction'));

        $data["post"] = $this->post_admin_model->get_post($post_id);
        if (!empty($data["post"])) {
            $this->reaction_model->save_reaction($post_id, $reaction);
        }
        $data["reactions"] = $this->reaction_model->get_reaction($post_id);
        $this->load->view('partials/_emoji_reactions', $data);
    }

    /**
     * Switch Mode
     */
    public function switch_mode() {
        post_method();
        $vr_dark_mode = 0;
        $dark_mode = $this->input->post('dark_mode');
        if ($dark_mode == 1) {
            $vr_dark_mode = 1;
        }
        helper_setcookie('vr_dark_mode', $vr_dark_mode);
        redirect($this->agent->referrer());
        exit();
    }

    //download post file
    public function download_post_file() {
        post_method();
        $this->load->helper('download');
        $id = $this->input->post('file_id', true);
        $file = $this->file_model->get_file($id);
        if (!empty($file)) {
            if ($file->storage == "aws_s3") {
                $this->load->model("aws_model");
                $this->aws_model->download_file($file->file_path);
            } else {
                force_download(FCPATH . $file->file_path, NULL);
            }
        }
        redirect($this->agent->referrer());
        exit();
    }

    //download audio
    public function download_audio() {
        post_method();
        $id = $this->input->post('id', true);
        $audio = $this->file_model->get_audio($id);
        if (!empty($audio)) {
            $this->load->helper('download');
            force_download(FCPATH . $audio->audio_path, NULL);
        }
        redirect($this->agent->referrer());
    }

    //cookies warning
    public function cookies_warning() {
        helper_setcookie('cookies_warning', 1);
    }

    //check page auth
    private function checkPageAuth($page) {
        if (!auth_check() && $page->need_auth == 1) {
            $this->session->set_flashdata('error', trans("message_page_auth"));
            redirect(generate_url('register'));
        }
    }

    //error 404
    public function error_404() {
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

    public function update_sitemap() {
        $items = $this->post_admin_model->get_post_sitemap($hours = 30);
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
                    <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">';
        foreach ($items as $item) {
            $url = site_url() . '' . strtolower($item->name) . '/' . $item->title_slug . '-' . $item->id . '.html';
            $datetime = new DateTime($item->created_at);
            $time_created = $datetime->format('Y-m-d\TH:i:sP');

            //$created_at = date('Y-m-d',strtotime($item->created_at));
            $title = htmlspecialchars($item->title);

            $xml .= "<url>
                        <loc>$url</loc>
                        <news:news>
                            <news:publication>
                                <news:name>Newstrack</news:name>
                                <news:language>en</news:language>
                            </news:publication>
                            <news:publication_date>$time_created</news:publication_date>
                            <news:title>$title</news:title>
                        </news:news>
                        <lastmod>$time_created</lastmod>
                    </url>";
        }
        $xml .= '</urlset>';

        if (ENVIRONMENT == 'development') {
            file_put_contents(FCPATH . 'sitemap/news.xml', $xml);
            echo "Successfully done on stage";
        }

        if (ENVIRONMENT == 'production') {
            file_put_contents(FCPATH . 'sitemap/news.xml', $xml);
            echo "Successfully done on live";
        }
    }

}
