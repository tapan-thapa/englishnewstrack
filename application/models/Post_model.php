<?php defined('BASEPATH') or exit('No direct script access allowed');

class Post_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();

        $this->featured_posts_limit = 17;
        $this->slider_posts_limit = 20;
        $this->breaking_posts_limit = 50;
        $this->random_posts_limit = 15;
        $this->recommended_posts_limit = 5;
        $this->popular_posts_limit = 5;
        $this->popular_video_posts_limit = 6;
        $this->latest_posts_limit = 6;
        $this->related_posts_limit = 10;
    }

    //build sql query string
    public function query_string($join_tags = false,$join_topic = false)
    {
        /* $sql = "SELECT posts.id, posts.lang_id, posts.title,posts.breaking_datetime, posts.topic,posts.content,  posts.headline,  posts.title_slug, posts.summary, posts.category_id,posts.pri_category_id, posts.image_big, posts.image_slider, posts.image_mid, 
                posts.image_small, posts.image_mime, posts.image_storage, posts.slider_order, posts.featured_order, posts.post_type, posts.image_url, posts.user_id, posts.pageviews, posts.post_url, posts.updated_at, posts.created_at,
                categories.name AS category_name, categories.name_slug AS category_slug , categories.color AS category_color, 
                users.username AS author_username, users.slug AS author_slug,
                 comments.comment_count
                FROM posts
                LEFT JOIN (SELECT post_id,COUNT(id) AS comment_count FROM comments WHERE comments.parent_id = 0 AND comments.status = 1 GROUP BY post_id)comments ON posts.id = comments.post_id
                INNER JOIN categories ON categories.id = posts.category_id
                INNER JOIN users ON users.id = posts.user_id "; */
        
        $sql = "SELECT posts.id, posts.lang_id, posts.title,posts.breaking_datetime, posts.topic,posts.content,  posts.headline,  posts.title_slug, posts.summary, posts.category_id,posts.pri_category_id, posts.image_big, posts.image_slider, posts.image_mid, 
                posts.image_small, posts.image_mime, posts.image_storage, posts.slider_order, posts.featured_order, posts.post_type, posts.image_url, posts.user_id, posts.pageviews, posts.post_url, posts.updated_at, posts.created_at,
                categories.name AS category_name, categories.name_slug AS category_slug , categories.color AS category_color, 
                users.username AS author_username, users.slug AS author_slug,'0' AS comment_count
                FROM posts
                LEFT JOIN categories ON categories.id = posts.category_id
                LEFT JOIN users ON users.id = posts.user_id ";

        if ($join_tags == true) {
            $sql .= "INNER JOIN tags ON tags.post_id = posts.id ";
        }
        if ($join_topic == true) {
            $sql .= "INNER JOIN topic ON topic.post_id = posts.id ";
        }
        $sql .= "WHERE posts.is_scheduled = 0 AND posts.visibility = 1 AND posts.status = 1 ";
        return $sql;
    }

    //get latest posts
    public function get_latest_posts($lang_id, $limit)
    {
        $sql = "SELECT * FROM (" . str_replace("FROM posts","FROM posts USE INDEX(UIDX_COMP3)",$this->query_string()) . " AND posts.lang_id = ? AND post_type<>'webstory' ORDER BY posts.updated_at DESC LIMIT ?) AS table_posts";
        $query = $this->db->query($sql, array(clean_number($this->selected_lang->id), clean_number($limit)));
        return $query->result();
    }

    //get latest posts
    public function get_webstory_posts($lang_id, $limit)
    {
        $sql = "SELECT * FROM (" . str_replace("posts","posts_webstory",$this->query_string()) . " AND posts_webstory.lang_id = ? AND posts_webstory.post_type='webstory' ORDER BY posts_webstory.created_at DESC LIMIT ?) AS table_posts";
        $query = $this->db->query($sql, array(clean_number($this->selected_lang->id), clean_number($limit)));
        return $query->result();
    }

    //load more posts
    public function load_more_posts($lang_id, $last_id, $limit)
    {
        $sql = "SELECT * FROM (" . $this->query_string() . " AND posts.lang_id = ? AND posts.id < ? ORDER BY posts.id DESC LIMIT ?) AS table_posts";
        $query = $this->db->query($sql, array(clean_number($lang_id), clean_number($last_id), clean_number($limit)));
        return $query->result();
    }

    //get latest posts by category
    public function get_latest_category_posts($lang_id)
    {
        $sql = "SELECT id FROM
                (SELECT id, category_id, @row_number := IF(@prev = category_id, @row_number + 1, 1) AS number_of_rows, @prev := category_id
                FROM posts
                JOIN (SELECT @prev := NULL, @row_number := 0) AS vars
                WHERE posts.is_scheduled = 0 AND posts.visibility = 1 AND posts.status = 1 AND image_default is not null
                ORDER BY category_id, created_at DESC
                ) AS table_posts
                WHERE number_of_rows <= 20";
        $query = $this->db->query($sql);
        $result = $query->result();
        $post_ids_array = array();
        if (!empty($result)) {
            foreach ($result as $item) {
                array_push($post_ids_array, $item->id);
            }
        }
        $post_ids = generate_ids_string($post_ids_array);
        if (!empty($post_ids)) {
            $sql = "SELECT posts.id, posts.title, posts.topic,  posts.headline, posts.title_slug, posts.summary, posts.category_id, posts.image_big, posts.image_slider, posts.image_mid,
                posts.image_small, posts.image_mime, posts.image_storage, posts.slider_order, posts.featured_order, posts.post_type, posts.image_url, posts.user_id, posts.pageviews, posts.lang_id, posts.post_url, posts.created_at,
                categories.name AS category_name, categories.name_slug AS category_slug , categories.color AS category_color,
                users.username AS author_username, users.slug AS author_slug,
                comments.comment_count
                FROM posts
                LEFT JOIN (SELECT post_id,COUNT(id) AS comment_count FROM comments WHERE comments.parent_id = 0 AND comments.status = 1 GROUP BY post_id)comments ON posts.id = comments.post_id
                INNER JOIN categories ON categories.id = posts.category_id
                INNER JOIN users ON users.id = posts.user_id
                WHERE posts.id IN (" . $post_ids . ") AND posts.lang_id = ?
                ORDER BY posts.created_at DESC";
            $query = $this->db->query($sql, array(clean_number($lang_id)));
            return $query->result();
        }
        return array();
    }

    //get latest posts by category
    public function get_category_posts_widget($lang_id)
    {
        $homeCategoryArr = [];
        $homePosts = [];
        foreach ($this->categories as $value) {
            if ($value->show_at_homepage == 1 || $value->id==486){
                $homeCategoryArr[] = $value;     
            }
        }
        foreach ($homeCategoryArr as $catRow) {
            $category_ids = generate_ids_string(get_category_tree($catRow->id, $this->categories));
            if (empty($category_ids)) {
                continue;
            }
            $posts_sql = "select DISTINCT posts.id, posts.title, posts.topic,  posts.headline, posts.title_slug, posts.summary, posts.category_id, posts.image_big, posts.image_slider, posts.image_mid, posts.image_small, posts.image_mime, posts.image_storage, posts.slider_order, posts.featured_order, posts.post_type, posts.image_url, posts.user_id, posts.pageviews, posts.lang_id, posts.post_url, posts.created_at, posts.category_slug, posts.category_color, posts.category_name, posts.author_username, posts.author_slug, posts.cat_url,posts.video_path,posts.updated_at FROM posts INNER JOIN posts_cat as c ON posts.id=c.post_id WHERE c.cat_id IN (".$category_ids.") AND c.is_deleted = 0 AND posts.is_scheduled = 0 AND posts.visibility = 1 AND posts.status = 1 AND posts.lang_id = ? order by posts.updated_at DESC LIMIT ?";
            $rows = $this->db->query($posts_sql, array(clean_number($this->selected_lang->id),clean_number($catRow->home_limit)))->result();
            //pr($this->db->last_query());
            $homePosts[$catRow->id] = $rows;
        }
        return $homePosts;
    }

    //get slider posts
    public function get_slider_posts()
    {
        $sql = "SELECT * FROM (" . $this->query_string() . " AND posts.lang_id = ? AND posts.is_slider = 1 ORDER BY posts.created_at DESC LIMIT ?) AS table_posts";
        if ($this->general_settings->sort_slider_posts == 'by_slider_order') {
            $sql = "SELECT * FROM (" . $this->query_string() . " AND posts.lang_id = ? AND posts.is_slider = 1 ORDER BY posts.slider_order, posts.id LIMIT ?) AS table_posts";
        }
        $query = $this->db->query($sql, array(clean_number($this->selected_lang->id), $this->slider_posts_limit));
        return $query->result();
    }

    //get featured posts
    public function get_featured_posts()
    {
        $sql = "SELECT * FROM (" . $this->query_string() . " AND posts.lang_id = ? AND is_featured = 1 ORDER BY posts.created_at DESC LIMIT ?) AS table_posts";
        if ($this->general_settings->sort_featured_posts == 'by_featured_order') {
            $sql = "SELECT * FROM (" . $this->query_string() . " AND posts.lang_id = ? AND is_featured = 1 ORDER BY posts.featured_order, posts.id LIMIT ?) AS table_posts";
        }
        $query = $this->db->query($sql, array(clean_number($this->selected_lang->id), $this->featured_posts_limit));
        return $query->result();
    }

    //get featured posts
    public function get_featured_posts_newsletter()
    {
        $sql = "SELECT * FROM (" . $this->query_string() . " AND posts.lang_id = ? AND is_featured = 1 AND newsletter = 1 ORDER BY posts.created_at DESC LIMIT ?) AS table_posts";
        if ($this->general_settings->sort_featured_posts == 'by_featured_order') {
            $sql = "SELECT * FROM (" . $this->query_string() . " AND posts.lang_id = ? AND is_featured = 1 AND newsletter = 1 ORDER BY posts.featured_order, posts.id LIMIT ?) AS table_posts";
        }
        $query = $this->db->query($sql, array(clean_number($this->selected_lang->id), $this->featured_posts_limit));
        return $query->result();
    }

     //get special posts
    public function get_special_posts($slimit=5)
    {
        
        $sql = "SELECT * FROM (" . $this->query_string() . " AND posts.lang_id = ? AND is_special = 1 ORDER BY posts.special_order, posts.id LIMIT ?) AS table_posts";
        
        $query = $this->db->query($sql, array(clean_number($this->selected_lang->id), $slimit));
        return $query->result();
    }
    
    //get paginated special news
    public function get_paginated_special_news($offset, $per_page)
    {
        $sql = "SELECT * FROM (" . $this->query_string() . " AND posts.lang_id = ? AND is_special = 1 ORDER BY posts.special_order, posts.id LIMIT ?,?) AS table_posts";
        
        $query = $this->db->query($sql, array(clean_number($this->selected_lang->id), clean_number($offset), clean_number($per_page)));
        return $query->result();
    }
    
    //get trending posts
    public function get_trending_posts($tlimit=5)
    {
        $sql = "SELECT * FROM (" . $this->query_string() . " AND posts.lang_id = ? AND is_trending = 1 ORDER BY posts.trending_order, posts.id LIMIT ?) AS table_posts";
        
        $query = $this->db->query($sql, array(clean_number($this->selected_lang->id), $tlimit));
        return $query->result();
    }

    //get trending tags
    public function get_trending_tags($id)
    {
        $sql = "SELECT * FROM tags WHERE post_id=?";
        $query = $this->db->query($sql, array($id));
        return $query->result();
    }
    
    //get paginated trending news
    public function get_paginated_trending_news($offset, $per_page)
    {
        $sql = "SELECT * FROM (" . $this->query_string() . " AND posts.lang_id = ? AND is_trending = 1 ORDER BY posts.trending_order, posts.id LIMIT ?,?) AS table_posts";
        
        $query = $this->db->query($sql, array(clean_number($this->selected_lang->id), clean_number($offset), clean_number($per_page)));
        return $query->result();
    }

    //get recommended posts
    public function get_recommended_posts()
    {
        $sql = "SELECT * FROM (" . $this->query_string() . " AND posts.lang_id = ? AND posts.is_recommended ORDER BY posts.created_at DESC LIMIT ?) AS table_posts";
        $query = $this->db->query($sql, array(clean_number($this->selected_lang->id), $this->recommended_posts_limit));
        return $query->result();
    }

    //get breaking news
    public function get_breaking_news()
    {
        $sql = "SELECT * FROM (" . $this->query_string() . " AND posts.lang_id = ? AND posts.is_breaking = 1 and posts.breaking_datetime > DATE_SUB(NOW(), INTERVAL 24 HOUR) ORDER BY posts.breaking_datetime DESC LIMIT ?) AS table_posts";
        
        //$sql = "SELECT * FROM (" . $this->query_string() . " AND posts.lang_id = ? AND posts.is_breaking = 1 ORDER BY posts.created_at DESC LIMIT ?) AS table_posts";
        $query = $this->db->query($sql, array(clean_number($this->selected_lang->id), $this->breaking_posts_limit));
        return $query->result();
    }
    
    //get breaking news l
    public function get_breaking_news_latest()
    {
        $sql = "SELECT * FROM breaking_news where created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR) AND lang_id = ? ORDER BY created_at DESC LIMIT ?";
        $query = $this->db->query($sql, array(clean_number($this->selected_lang->id),$this->breaking_posts_limit));
        return $query->result();
    }

    //get random posts
    public function get_random_posts()
    {
        $posts = $this->latest_category_posts;
        if (empty($posts)) {
            return array();
        } else {
            shuffle($posts);
            $posts = array_slice($posts, 0, 15);
        }
        return $posts;
    }

      //get popular posts video
    public function get_popular_videos_post($lang_id)
    {
        $sql = "SELECT categories.name_slug as category_slug,posts.id, posts.title, posts.topic,  posts.headline, posts.title_slug, posts.post_type, posts.image_small, posts.image_mime, posts.image_storage, posts.image_url, posts.post_url, posts.created_at
                FROM posts
                INNER JOIN categories ON categories.id = posts.category_id
                WHERE posts.is_scheduled = 0 AND posts.visibility = 1 AND posts.post_type='video' AND posts.status = 1 AND posts.lang_id = ? ORDER BY created_at DESC, posts.id LIMIT ?";
        $query = $this->db->query($sql, array(clean_number($lang_id), $this->popular_video_posts_limit));
        return $query->result();
    }

    
    //get popular posts weekly
    public function get_popular_posts_weekly($lang_id)
    {
        $sql = "SELECT categories.name_slug as category_slug,posts.id, posts.title, posts.topic,  posts.headline, posts.title_slug, posts.post_type, posts.image_small, posts.image_mime, posts.image_storage, posts.image_url, posts.post_url, users.slug AS author_slug, users.username AS author_username, posts.created_at, hit_counts.count AS pageviews_count,
                comments.comment_count
                FROM posts
                LEFT JOIN (SELECT post_id,COUNT(id) AS comment_count FROM comments WHERE comments.parent_id = 0 AND comments.status = 1 GROUP BY post_id)comments ON posts.id = comments.post_id
                INNER JOIN (SELECT post_pageviews_week.post_id, COUNT(*) AS count FROM post_pageviews_week GROUP BY post_pageviews_week.post_id ORDER BY count DESC, post_pageviews_week.post_id LIMIT 10) AS hit_counts ON hit_counts.post_id = posts.id
                INNER JOIN users ON users.id = posts.user_id
                INNER JOIN categories ON categories.id = posts.category_id
                WHERE posts.is_scheduled = 0 AND posts.visibility = 1 AND posts.status = 1 AND posts.lang_id = ? ORDER BY count DESC, posts.id LIMIT ?";
        $query = $this->db->query($sql, array(clean_number($lang_id), $this->popular_posts_limit));
        return $query->result();
    }

    //get popular posts monthly
    public function get_popular_posts_monthly($lang_id)
    {
        $sql = "SELECT categories.name_slug as category_slug,posts.id, posts.title, posts.topic,  posts.headline, posts.title_slug, posts.post_type, posts.image_small, posts.image_mime, posts.image_storage, posts.image_url, posts.post_url, users.slug AS author_slug, users.username AS author_username, posts.created_at, hit_counts.count AS pageviews_count,
                comments.comment_count
                FROM posts
                LEFT JOIN (SELECT post_id,COUNT(id) AS comment_count FROM comments WHERE comments.parent_id = 0 AND comments.status = 1 GROUP BY post_id)comments ON posts.id = comments.post_id
                INNER JOIN (SELECT post_pageviews_month.post_id, COUNT(*) AS count FROM post_pageviews_month GROUP BY post_pageviews_month.post_id ORDER BY count DESC, post_pageviews_month.post_id LIMIT 10) AS hit_counts ON hit_counts.post_id = posts.id
                INNER JOIN users ON users.id = posts.user_id
                INNER JOIN categories ON categories.id = posts.category_id
                WHERE posts.is_scheduled = 0 AND posts.visibility = 1 AND posts.status = 1 AND posts.lang_id = ? ORDER BY count DESC, posts.id LIMIT ?";
        $query = $this->db->query($sql, array(clean_number($lang_id), $this->popular_posts_limit));
        return $query->result();
    }

    //get popular posts all time
    public function get_popular_posts_all_time($lang_id,$limit_posts = 5)
    {
        $sql = "SELECT categories.name_slug as category_slug,posts.id, posts.title, posts.topic,  posts.headline, posts.title_slug, posts.post_type, posts.image_small, posts.image_mime, posts.image_storage, posts.image_url, posts.post_url, users.slug AS author_slug, users.username AS author_username, posts.created_at, posts.pageviews AS pageviews_count,
                comments.comment_count
                FROM posts
                LEFT JOIN (SELECT post_id,COUNT(id) AS comment_count FROM comments WHERE comments.parent_id = 0 AND comments.status = 1 GROUP BY post_id)comments ON posts.id = comments.post_id
                INNER JOIN users ON users.id = posts.user_id
                INNER JOIN categories ON categories.id = posts.category_id
                WHERE posts.is_scheduled = 0 AND posts.visibility = 1 AND posts.status = 1 AND posts.lang_id = ? ORDER BY pageviews DESC, id LIMIT ?";
        $query = $this->db->query($sql, array(clean_number($lang_id), $limit_posts));
        return $query->result();
    }

    
    //get popular posts daily
    public function get_popular_posts_daily($lang_id,$limit_posts = 5)
    {
        $sql = "SELECT categories.name_slug as category_slug,posts.id, posts.title, posts.topic,  posts.headline, posts.title_slug, posts.post_type, posts.image_small, posts.image_mime, posts.image_storage, posts.image_url, posts.post_url, users.slug AS author_slug, users.username AS author_username, posts.created_at, hit_counts.count AS pageviews_count
                FROM posts
                INNER JOIN (SELECT post_pageviews_week.post_id, COUNT(*) AS count FROM post_pageviews_week where post_pageviews_week.created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR) GROUP BY post_pageviews_week.post_id ORDER BY count DESC, post_pageviews_week.post_id LIMIT 10) AS hit_counts ON hit_counts.post_id = posts.id
                INNER JOIN users ON users.id = posts.user_id
                INNER JOIN categories ON categories.id = posts.category_id
                WHERE posts.is_scheduled = 0 and posts.created_at > NOW() - INTERVAL 1 DAY AND posts.visibility = 1 AND posts.status = 1 AND posts.lang_id = ? ORDER BY count DESC, posts.id LIMIT ?";
        $query = $this->db->query($sql, array(clean_number($lang_id), $limit_posts));
        return $query->result();
    }
    
    
    //get paginated posts
    public function get_paginated_posts($offset, $per_page)
    {
        $sql = "SELECT * FROM (" . $this->query_string() . " AND posts.lang_id = ? ORDER BY posts.created_at DESC LIMIT ?,?) AS table_posts";
        $query = $this->db->query($sql, array(clean_number($this->selected_lang->id), clean_number($offset), clean_number($per_page)));
        return $query->result();
    }

    //get total post count
    public function get_total_post_count()
    {
        $sql = "SELECT COUNT(posts.id) AS count FROM posts
                INNER JOIN categories ON categories.id = posts.category_id
                INNER JOIN users ON users.id = posts.user_id
                WHERE posts.is_scheduled = 0 AND posts.visibility = 1 AND posts.status = 1 AND posts.lang_id = ?";
        $query = $this->db->query($sql, array(clean_number($this->selected_lang->id)));
        return $query->row()->count;
    }

    //get category posts
    public function get_category_posts($category_id, $limit)
    {
        $category_ids = generate_ids_string(get_category_tree($category_id, $this->categories));
        if (empty($category_ids)) {
            return array();
        }
        $sql = "SELECT * FROM (" . $this->query_string() . " AND posts.lang_id = ? AND posts.category_id IN ({$category_ids}) ORDER BY posts.created_at DESC LIMIT ?) AS table_posts";
        $query = $this->db->query($sql, array(clean_number($this->selected_lang->id), clean_number($limit)));
        return $query->result();
    }

    //get paginated category posts
    public function get_paginated_category_posts($category_id, $offset, $per_page)
    {
        if($category_id == 358 || $category_id == 357){
            $sql = "select posts.id, posts.title, posts.topic,  posts.headline, posts.title_slug, posts.summary, posts.category_id, posts.image_big, posts.image_slider, posts.image_mid, posts.image_small, posts.image_mime, posts.image_storage, posts.slider_order, posts.featured_order, posts.post_type, posts.image_url, posts.user_id, posts.pageviews, posts.lang_id, posts.post_url, posts.created_at, posts.category_slug, posts.category_color, posts.category_name, posts.author_username, posts.author_slug, posts.cat_url FROM posts USE INDEX(UIDX_COMP3) WHERE posts.is_scheduled = 0 AND posts.visibility = 1 AND posts.status = 1 AND posts.lang_id = ? order by posts.updated_at DESC LIMIT ?,?";
        }else{
            $category_ids = generate_ids_string(get_category_tree($category_id, $this->categories));
            if (empty($category_ids)) {
                return array();
            }
            $sql = "select DISTINCT posts.id, posts.title, posts.topic,  posts.headline, posts.title_slug, posts.summary, posts.category_id, posts.image_big, posts.image_slider, posts.image_mid, posts.image_small, posts.image_mime, posts.image_storage, posts.slider_order, posts.featured_order, posts.post_type, posts.image_url, posts.user_id, posts.pageviews, posts.lang_id, posts.post_url, posts.created_at, posts.category_slug, posts.category_color, posts.category_name, posts.author_username, posts.author_slug, posts.cat_url, posts.updated_at FROM posts USE INDEX(UIDX_COMP3) INNER JOIN posts_cat as c ON posts.id=c.post_id WHERE c.cat_id IN (".$category_ids.") AND c.is_deleted = 0 AND posts.is_scheduled = 0 AND posts.visibility = 1 AND posts.status = 1 AND posts.lang_id = ? order by posts.updated_at DESC LIMIT ?,?";
        }
        $query = $this->db->query($sql, array(clean_number($this->selected_lang->id), clean_number($offset), clean_number($per_page)));
        return $query->result();
    }

    //get post count by category
    public function get_post_count_by_category($category_id)
    {
        if($category_id == 358 || $category_id == 357){
            $sql = "SELECT COUNT(*) AS count FROM posts WHERE posts.is_scheduled = 0 AND posts.visibility = 1 AND posts.status = 1 AND posts.lang_id = ?";
        }else{
            $category_ids = generate_ids_string(get_category_tree($category_id, $this->categories));
            if (empty($category_ids)) {
                return array();
            }
            $sql = "select count(DISTINCT posts.id) AS count FROM posts USE INDEX(UIDX_COMP3) INNER JOIN posts_cat as c ON posts.id=c.post_id WHERE c.cat_id IN (".$category_ids.") AND c.is_deleted = 0 AND posts.is_scheduled = 0 AND posts.visibility = 1 AND posts.status = 1 AND posts.lang_id = ?";
            //$sql = "SELECT COUNT(DISTINCT post_id) AS count FROM posts_cat as c USE INDEX(idx_post_date_langid_catid) WHERE c.cat_id IN (".$category_ids.") AND c.is_deleted = 0";
        }
        $query = $this->db->query($sql, array(clean_number($this->selected_lang->id)));
        return $query->row()->count;
    }
    public function get_short_video($category_id, $offset, $per_page,$post_id)
    {
        $category_ids = $category_id;
        /* $sql = "SELECT * FROM (" . $this->query_string() . " AND posts.lang_id = ? AND posts.category_id IN ({$category_ids}) ORDER BY posts.created_at DESC LIMIT ?,?) AS table_posts"; */
        $sql = "select DISTINCT posts.id, posts.title, posts.topic,  posts.headline, posts.title_slug, posts.summary, posts.category_id, posts.image_big, posts.image_slider, posts.image_mid, posts.image_small, posts.image_mime, posts.image_storage, posts.slider_order, posts.featured_order, posts.post_type, posts.image_url, posts.user_id, posts.pageviews, posts.lang_id, posts.post_url, posts.created_at, posts.category_slug, posts.category_color, posts.category_name, posts.author_username, posts.author_slug, posts.cat_url,posts.video_path,posts.video_url,posts.video_storage FROM posts INNER JOIN posts_cat as c ON posts.id=c.post_id WHERE c.cat_id IN (".$category_ids.") AND c.is_deleted = 0 AND posts.is_scheduled = 0 AND posts.visibility = 1 AND posts.status = 1 AND posts.video_path<>'' AND posts.lang_id = ? ORDER BY FIELD (posts.id,$post_id) DESC,posts.updated_at DESC LIMIT ?,?";
        $query = $this->db->query($sql, array(clean_number($this->selected_lang->id), clean_number($offset), clean_number($per_page)));
        return $query->result();
    }
    public function get_paginated_webstory_posts($offset, $per_page)
    {
         $sql = "select posts.id, posts.title, posts.topic,  posts.headline, posts.title_slug, posts.summary, posts.category_id, 
         posts.image_big, posts.image_slider, posts.image_mid, posts.image_small, posts.image_mime, posts.image_storage, 
         posts.slider_order, posts.featured_order, posts.post_type, posts.image_url, posts.user_id, posts.pageviews, 
         posts.lang_id, posts.post_url, posts.created_at, posts.category_slug, posts.category_color, posts.category_name, 
         posts.author_username, posts.author_slug, posts.cat_url FROM posts_webstory as posts WHERE 
         posts.is_scheduled = 0 AND posts.visibility = 1 AND posts.lang_id=? AND posts.status = 1 ORDER BY created_at DESC LIMIT ?,?";
        $query = $this->db->query($sql, array(clean_number($this->selected_lang->id), clean_number($offset), clean_number($per_page)));
        return $query->result();
    }
    //get post count by webstory
    public function get_post_count_by_webstory()
    {
        $sql = "select count(*) AS count FROM posts_webstory as posts WHERE 
        posts.is_scheduled = 0 AND posts.visibility = 1 AND posts.lang_id=? AND posts.status = 1";
        $query = $this->db->query($sql, array(clean_number($this->selected_lang->id)));
        return $query->row()->count;
    }

    //Raj add for related tag post
    public function get_tag_posts($tag_slug)
    {
        if(count($tag_slug) > 1){
            $slugStrng = "('" . implode ( "', '", $tag_slug ) . "')";
        }else{
            $slugStrng = "('" . $tag_slug[0] . "')";
        }

        $sql = "SELECT * FROM (" . $this->query_string(true) . " AND posts.lang_id = ? AND tags.tag_slug IN ".$slugStrng." group by posts.id ORDER BY posts.created_at DESC LIMIT 10 ) AS table_posts";
        $query = $this->db->query($sql, array(clean_number($this->selected_lang->id)));
        return $query->result();
    }
    

    //get paginated tag posts
    public function get_paginated_tag_posts($tag_slug, $offset, $per_page)
    {
        $sql = "SELECT * FROM (" . $this->query_string(true) . " AND posts.lang_id = ? AND tags.tag_slug=? ORDER BY posts.created_at DESC LIMIT ?,?) AS table_posts";
        $query = $this->db->query($sql, array(clean_number($this->selected_lang->id), clean_str($tag_slug), clean_number($offset), clean_number($per_page)));
        return $query->result();
    }
    
    //get paginated breaking news
    public function get_paginated_breaking_news($offset, $per_page)
    {
        
        $sql = "SELECT * FROM breaking_news where lang_id=? and created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR) ORDER BY created_at DESC LIMIT ?,?";
        $query = $this->db->query($sql, array(clean_number($this->selected_lang->id),clean_number($offset), clean_number($per_page)));
        return $query->result();
    }
    
    //get post count by breaking news
    public function get_post_count_by_breaking_news()
    {
        $sql = "SELECT COUNT(id) AS count FROM breaking_news where lang_id=? and created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)";
        $query = $this->db->query($sql,array(clean_number($this->selected_lang->id)));
        return $query->row()->count;
    }

     //get paginated topic posts
    public function get_paginated_topic_posts($topic_slug, $offset, $per_page)
    {
        
      $sql = "SELECT * FROM (" . $this->query_string(false,true) . " AND posts.lang_id = ? AND topic.topic_slug=? ORDER BY posts.created_at DESC LIMIT ?,?) AS table_posts";
        $query = $this->db->query($sql, array(clean_number($this->selected_lang->id), clean_str($topic_slug), clean_number($offset), clean_number($per_page)));
        return $query->result();
    }

    
    //get post count by topic
    public function get_post_count_by_topic($topic_slug)
    {
        $sql = "SELECT COUNT(table_posts.id) AS count FROM (" . $this->query_string(false,true) . " AND posts.lang_id = ? AND topic.topic_slug=?) AS table_posts";
        $query = $this->db->query($sql, array(clean_number($this->selected_lang->id), clean_str($topic_slug)));
        return $query->row()->count;
    }
    
    //get post count by tag
    public function get_post_count_by_tag($tag_slug)
    {
        $sql = "SELECT COUNT(table_posts.id) AS count FROM (" . $this->query_string(true) . " AND posts.lang_id = ? AND tags.tag_slug=?) AS table_posts";
        $query = $this->db->query($sql, array(clean_number($this->selected_lang->id), clean_str($tag_slug)));
        return $query->row()->count;
    }

    //get post
    public function get_post($post_id)
    {
        $sql = "SELECT posts.*,
                /* categories.name AS category_name, categories.name_slug AS category_slug , categories.color AS category_color,  */
                users.username AS author_username, users.slug AS author_slug, users.reward_system_enabled AS author_reward_system_enabled, users.avatar as author_image,
                comments.comment_count
                FROM posts
                LEFT JOIN (SELECT post_id,COUNT(id) AS comment_count FROM comments WHERE comments.parent_id = 0 AND comments.status = 1 GROUP BY post_id)comments ON posts.id = comments.post_id
                /* LEFT JOIN categories ON categories.id = posts.category_id */
                LEFT JOIN users ON users.id = posts.user_id
                WHERE posts.is_scheduled = 0 AND posts.visibility = 1 AND posts.status = 1 AND posts.id = ? AND posts.lang_id = ?";
        $query = $this->db->query($sql, array(clean_number($post_id), clean_number($this->selected_lang->id)));
        return $query->row();
    }

    //get post
    public function get_webstory_post($post_id)
    {
        $sql = "SELECT posts_webstory.*,
                categories.name AS category_name, categories.name_slug AS category_slug , categories.color AS category_color, 
                comments.comment_count
                FROM posts_webstory
                LEFT JOIN (SELECT post_id,COUNT(id) AS comment_count FROM comments WHERE comments.parent_id = 0 AND comments.status = 1 GROUP BY post_id)comments ON posts_webstory.id = comments.post_id
                INNER JOIN categories ON categories.id = posts_webstory.category_id
                WHERE posts_webstory.is_scheduled = 0 AND posts_webstory.visibility = 1 AND posts_webstory.status = 1 AND posts_webstory.id = ? AND posts_webstory.lang_id = ?";
        $query = $this->db->query($sql, array(clean_number($post_id), clean_number($this->selected_lang->id)));
        return $query->row();
    }

    //get preview post
    public function get_post_preview($slug)
    {
        $sql = "SELECT posts.*,
                categories.name AS category_name, categories.name_slug AS category_slug , categories.color AS category_color, 
                users.username AS author_username, users.slug AS author_slug, users.reward_system_enabled AS author_reward_system_enabled,
                comments.comment_count
                FROM posts
                LEFT JOIN (SELECT post_id,COUNT(id) AS comment_count FROM comments WHERE comments.parent_id = 0 AND comments.status = 1 GROUP BY post_id)comments ON posts.id = comments.post_id
                INNER JOIN categories ON categories.id = posts.category_id
                INNER JOIN users ON users.id = posts.user_id
                WHERE posts.title_slug = ?";
        $query = $this->db->query($sql, array(clean_str($slug)));
        return $query->row();
    }

    //get user post by id
    public function get_post_by_id($post_id,$table='posts')
    {
        $sql = "SELECT * FROM ".$table." WHERE id = ?";
        $query = $this->db->query($sql, array(clean_number($post_id)));
        return $query->row();
    }

    //get raj user post by id
    public function get_post_by_id_limit($post_id, $limit)
    {
        $sql = "SELECT * FROM posts WHERE id = ? limit ?";
        $query = $this->db->query($sql, array(clean_number($post_id), $limit));
        return $query->row();
    }
    
     //get b news by id
    public function get_bnews_by_id($id)
    {
        $sql = "SELECT * FROM breaking_news WHERE id = ?";
        $query = $this->db->query($sql, array(clean_number($id)));
        return $query->row();
    }

    //get related posts
    public function get_related_posts($category_id, $post_id, $lang_id = 1)
    {
        $category_ids = generate_ids_string(get_category_tree($category_id, $this->categories));
        if (empty($category_ids)) {
            return array();
        }
        $sql = "SELECT * FROM ( select DISTINCT posts.id, posts.title, posts.topic,  posts.headline, posts.title_slug, posts.summary, posts.category_id, posts.image_big, posts.image_slider, posts.image_mid, posts.image_small, posts.image_mime, posts.image_storage, posts.slider_order, posts.featured_order, posts.post_type, posts.image_url, posts.user_id, posts.pageviews, posts.lang_id, posts.post_url, posts.created_at, posts.category_slug, posts.category_color, posts.category_name, posts.author_username, posts.author_slug, posts.cat_url FROM posts INNER JOIN posts_cat as c ON posts.id=c.post_id WHERE c.cat_id IN (".$category_ids.") AND c.is_deleted = 0 AND posts.is_scheduled = 0 AND posts.visibility = 1 AND posts.status = 1 AND posts.lang_id = ? AND posts.id != ? order by posts.updated_at DESC LIMIT 500) AS table_posts ORDER BY RAND() LIMIT ?";

        /* $sql = "SELECT * FROM (SELECT posts.id, posts.lang_id, posts.title,posts.breaking_datetime, posts.topic,posts.content,  posts.headline,  posts.title_slug, posts.summary, posts.category_id,posts.pri_category_id, posts.image_big, posts.image_slider, posts.image_mid, 
                posts.image_small, posts.image_mime, posts.image_storage, posts.slider_order, posts.featured_order, posts.post_type, posts.image_url, posts.user_id, posts.pageviews, posts.post_url, posts.updated_at, posts.created_at,
                categories.name AS category_name, categories.name_slug AS category_slug , categories.color AS category_color, 
                users.username AS author_username, users.slug AS author_slug,
                comments.comment_count
                FROM posts
                LEFT JOIN (SELECT post_id,COUNT(id) AS comment_count FROM comments WHERE comments.parent_id = 0 AND comments.status = 1 GROUP BY post_id)comments ON posts.id = comments.post_id
                INNER JOIN categories ON categories.id = posts.category_id
                INNER JOIN users ON users.id = posts.user_id AND posts.id != ? AND posts.image_url IS NOT NULL AND posts.category_id IN ({$category_ids}) LIMIT 1000) AS table_posts ORDER BY RAND() LIMIT ?"; */
        $query = $this->db->query($sql, array(clean_number($lang_id), clean_number($post_id), $this->related_posts_limit));
        return $query->result();
    }
    //get related posts
    public function get_next_posts_ids($category_id, $post_id, $lang_id = 1)
    {
        $category_ids = generate_ids_string(get_category_tree($category_id, $this->categories));
        if (empty($category_ids)) {
            return array();
        }
        
        $sql = "select DISTINCT posts.id FROM posts INNER JOIN posts_cat as c ON posts.id=c.post_id WHERE c.cat_id IN (".$category_ids.") AND c.is_deleted = 0 AND posts.is_scheduled = 0 AND posts.visibility = 1 AND posts.status = 1 AND posts.lang_id = ? AND posts.id != ? order by posts.updated_at DESC LIMIT 50";
        $query = $this->db->query($sql, array(clean_number($lang_id), clean_number($post_id)));
        return $query->result();
    }

    //get user posts
    public function get_user_posts($user_id, $limit)
    {
        $sql = "SELECT * FROM (" . $this->query_string() . " AND posts.lang_id = ? AND posts.user_id = ? ORDER BY posts.created_at DESC LIMIT ?) AS table_posts";
        $query = $this->db->query($sql, array(clean_number($this->selected_lang->id), clean_number($user_id), clean_number($limit)));
        return $query->result();
    }

    //get paginated user posts
    public function get_paginated_user_posts($user_id, $offset, $per_page)
    {
        $sql = "SELECT * FROM (" . $this->query_string() . " AND posts.lang_id = ? AND posts.user_id=? ORDER BY posts.created_at DESC LIMIT ?,?) AS table_posts";
        $query = $this->db->query($sql, array(clean_number($this->selected_lang->id), clean_number($user_id), clean_number($offset), clean_number($per_page)));
        return $query->result();
    }

    //get post count by user
    public function get_post_count_by_user($user_id)
    {
        $sql = "SELECT COUNT(table_posts.id) AS count FROM (" . $this->query_string() . " AND posts.lang_id = ? AND posts.user_id=?) AS table_posts";
        $query = $this->db->query($sql, array(clean_number($this->selected_lang->id), clean_number($user_id)));
        return $query->row()->count;
    }

    //get user posts ids
    public function get_user_posts_ids($user_id)
    {
        $sql = "SELECT id FROM (" . $this->query_string() . " AND posts.user_id = ?) AS table_posts";
        $query = $this->db->query($sql, array(clean_number($user_id)));
        return $query->result_array();
    }

    //get paginated search posts
    public function get_paginated_search_posts($q, $offset, $per_page)
    {
        //$like = '%' . $q . '%';
        $like = $q;
        $sql = "SELECT posts.id, posts.lang_id, posts.title,posts.breaking_datetime, posts.topic,posts.content,  posts.headline,  posts.title_slug, posts.summary, posts.category_id,posts.pri_category_id, posts.image_big, posts.image_slider, posts.image_mid, posts.image_small, posts.image_mime, posts.image_storage, posts.slider_order, posts.featured_order, posts.post_type, posts.image_url, posts.user_id, posts.pageviews, posts.post_url, posts.updated_at, posts.created_at, posts.category_name, posts.category_slug , posts.category_color, posts.author_username, posts.author_slug,'0' AS comment_count FROM posts WHERE posts.is_scheduled = 0 AND posts.visibility = 1 AND posts.status = 1 AND posts.lang_id = ? AND MATCH(posts.title,posts.title_slug,posts.keywords) AGAINST (? IN BOOLEAN MODE) ORDER BY posts.updated_at DESC LIMIT ?,?";
        $query = $this->db->query($sql, array(clean_number($this->selected_lang->id), $like, clean_number($offset), clean_number($per_page)));
        return $query->result();
    }

    //get search post count
    public function get_search_post_count($q)
    {
        $like = $q;
        $sql = "SELECT count(posts.id) as count FROM posts WHERE posts.is_scheduled = 0 AND posts.visibility = 1 AND posts.status = 1 AND posts.lang_id = ? AND MATCH(posts.title,posts.title_slug,posts.keywords) AGAINST (? IN BOOLEAN MODE)";
        $query = $this->db->query($sql, array(clean_number($this->selected_lang->id), $like));
        return $query->row()->count;
    }

    //get previous post
    public function get_previous_post($id)
    {
        $sql = "SELECT * FROM posts WHERE posts.is_scheduled = 0 AND posts.visibility=1 AND posts.status=1 AND posts.id < ? AND posts.lang_id= ? ORDER BY posts.created_at DESC LIMIT 1";
        $query = $this->db->query($sql, array(clean_number($id), clean_number($this->selected_lang->id)));
        return $query->row();
    }

    //get next post
    public function get_next_post($id)
    {
        $sql = "SELECT * FROM posts WHERE posts.is_scheduled = 0 AND posts.visibility=1 AND posts.status=1 AND posts.id > ? AND posts.lang_id= ? ORDER BY posts.created_at LIMIT 1";
        $query = $this->db->query($sql, array(clean_number($id), clean_number($this->selected_lang->id)));
        return $query->row();
    }

    //increase post pageviews
    public function increase_post_pageviews($post)
    {
        return false;
        if (!empty($post) && !$this->agent->is_robot() && !is_bot()) {
            if ($this->agent->is_browser() || $this->agent->is_mobile()) {
                if (empty(helper_getcookie('post_' . $post->id))) {
                    $user_agent = $this->agent->agent_string();
                    $ip_address = $this->input->ip_address();
                    $date = date('Y-m-d H:i:s');
                    $reward_amount = 0;
                    if ($this->general_settings->reward_system_status == 1 && !empty($this->general_settings->reward_amount) && $post->author_reward_system_enabled == 1) {
                        $reward_amount = number_format($this->general_settings->reward_amount / 1000, 5, ".", "");
                    }
                    //check database
                    $sql = "SELECT id FROM post_pageviews_week WHERE post_id = ? AND ip_address = ? AND user_agent = ? LIMIT 1";
                    $query = $this->db->query($sql, array($post->id, $ip_address, $user_agent));
                    $visit = $query->row();
                    if (empty($visit)) {
                        $this->db->where('id', $post->id);
                        if ($this->db->update('posts', ['pageviews' => $post->pageviews + 1])) {
                            helper_setcookie('post_' . $post->id, '1');
                            if ($this->db->insert('post_pageviews_week', ['post_id' => $post->id, 'post_user_id' => $post->user_id, 'ip_address' => $ip_address, 'user_agent' => $user_agent, 'reward_amount' => $reward_amount, 'created_at' => $date])) {
                                $this->db->insert('post_pageviews_month', ['post_id' => $post->id, 'post_user_id' => $post->user_id, 'ip_address' => $ip_address, 'user_agent' => $user_agent, 'reward_amount' => $reward_amount, 'created_at' => $date]);
                                if ($reward_amount > 0) {
                                    $this->db->query("UPDATE users SET balance = FORMAT(balance + ?, 5), total_pageviews = total_pageviews + 1 WHERE id = ?", array($reward_amount, $post->user_id));
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function update_newsletter_id($id)
    {
        if($id == 'all'){
            $this->db->query("UPDATE posts SET newsletter = ? WHERE newsletter = ?", array(0, 1));
        }else{
            $this->db->query("UPDATE posts SET newsletter = ? WHERE id = ?", array(1, $id));
        }
        
    }

    //delete old posts
    public function delete_old_posts()
    {
        if ($this->general_settings->auto_post_deletion == 1) {
            $day = clean_number($this->general_settings->auto_post_deletion_days);
            $sql = "SELECT id FROM posts WHERE created_at < DATE_SUB(NOW(), INTERVAL ? DAY) AND feed_id != ''";
            if ($this->general_settings->auto_post_deletion_delete_all == 1) {
                $sql = "SELECT id FROM posts WHERE created_at < DATE_SUB(NOW(), INTERVAL ? DAY)";
            }
            $posts = $this->db->query($sql, array($day))->result();
            if (!empty($posts)) {
                foreach ($posts as $post) {
                    $this->post_admin_model->delete_post($post->id);
                }
            }
        }
    }

    //delete old page views
    public function delete_old_pageviews()
    {
        $now = date('Y-m-d H:i:s');
        $week = strtotime("-7 days", strtotime($now));
        $month = strtotime("-30 days", strtotime($now));
        $this->db->query("DELETE FROM post_pageviews_week WHERE created_at < '" . date('Y-m-d H:i:s', $week) . "'");
        $this->db->query("DELETE FROM post_pageviews_month WHERE created_at < '" . date('Y-m-d H:i:s', $month) . "'");
    }

     //raj
    public function get_postFor_key($post_id)
    {
        $sql = "SELECT posts.*, post_images.image_big, post_images.image_default  FROM posts left JOIN post_images ON post_images.post_id = posts.id WHERE posts.id = ?";
        $query = $this->db->query($sql, array(clean_number($post_id)));
        return $query->row();
    }

}
