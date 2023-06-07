<?php defined('BASEPATH') or exit('No direct script access allowed');

class Tag_model extends CI_Model
{
    //add post tags
    public function checkTagIsExists($id,$post_id){
        $this->db->select('COUNT(id) as count');
        $this->db->where("tag_id",$id);
        $this->db->where('post_id',clean_number($post_id));
        $query = $this->db->get('tags');
        return $query->row()->count;
    }
    public function manage_post_tags($post_id)
    {
        $tags = trim($this->input->post('tags', true));
        $tags_array = explode(",", $tags);
        $tagIds = [];
        if (!empty($tags_array)) {
            foreach ($tags_array as $tag) {
                $tag = trim($tag);
                if (strlen($tag) > 1) {
                    $tagArr = explode("(",$tag);
                    $tagIdString = end($tagArr);
                    $tagIdStringArr = explode(")",$tagIdString);
                    $tag_id = current($tagIdStringArr);
                    if(!empty($tag_id) && is_numeric($tag_id)){
                        $tagIds[] = $tag_id;
                        if($this->checkTagIsExists($tag_id,$post_id)<=0){
                            $tag_name = $tagArr[0];
                            $data = array(
                                'post_id' => clean_number($post_id),
                                'tag' => trim($tag_name),
                                'tag_id' => $tag_id,
                                'tag_slug' => str_slug(trim($tag_name))
                            );
                            if (empty($data["tag_slug"]) || $data["tag_slug"] == "-") {
                                $data["tag_slug"] = "tag-" . uniqid();
                            }
                            $this->db->insert('tags', $data);
                        }
                    }
                }
            }
        }
        $this->db->where('post_id',clean_number($post_id));
        if(count($tagIds)){
            $this->db->where_not_in('tag_id',$tagIds);
        }
        return $this->db->delete('tags');
    }
    public function get_post_tags_with_ids($post_id)
    {
        $tags_array = $this->get_post_tags($post_id);
        $dataObj = [];
        foreach ($tags_array as $item) {
            if($item->tag_id){
                $dataObj[] = $item->tag.' ('.$item->tag_id.')';
            }
        }
        return implode(",",$dataObj);
    }
    public function add_post_tags($post_id, $tags)
    {
        $tags_array = explode(",", $tags);
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

    public function add_quick_links($post_id = '-2'){
        
        $hd_edit_id = $this->input->post('hd_edit_id', true);
        $tag = $this->input->post('tag', true);
        $tag_slug = $this->input->post('tag_slug', true);
        $data = array(
            'tag' => $tag,
            'tag_slug' => $tag_slug,
            'post_id' => $post_id
        );
        if(isset($hd_edit_id) && !empty($hd_edit_id)){
            $this->db->where('id',$hd_edit_id);
            $this->db->where('post_id',$post_id);
            return $this->db->update('tags', $data);
        }else{
            return $this->db->insert('tags', $data);
        }
        
    }
    public function delete_quick_link($id){
        $this->db->where('id', $id);
        return $this->db->delete('tags');
    }

    // raj
    public function get_post_by_tag_slug($tagArray)
    {
        if(!empty($tagArray)){
            $out = [];
            foreach ($tagArray as $tags) {
                $sql = "SELECT post_id FROM `tags` WHERE `tag_slug` LIKE '$tags' limit 10 ";
                $query = $this->db->query($sql);
                foreach ($query->result() as $key => $res) {
                    $out[] = $res->post_id;
                }
            }
            return array_unique($out);

        }else{
            return false;
        }
    }
    public function get_post_topic_limit($post_id, $limit)
    {
        $sql = "SELECT * FROM topic WHERE post_id = ? limit ?";
        $query = $this->db->query($sql, array(clean_number($post_id), $limit));
        return $query->result();
    }
    //end
	
	 //add post topic
    public function add_post_topic($post_id, $topic)
    {
       $data = array(
				'post_id' => clean_number($post_id),
				'topic' => trim($topic),
				'topic_slug' => str_slug(trim($topic))
			);
			if (empty($data["topic_slug"]) || $data["topic_slug"] == "-") {
				$data["topic_slug"] = "topic-" . uniqid();
			}
			//insert tag
			$this->db->insert('topic', $data);
    }

    //update post tags
    public function update_post_tags($post_id)
    {
        //delete old tags
        $this->delete_post_tags($post_id);
        //add new tags
        $tags = trim($this->input->post('tags', true));

        $tags_array = explode(",", $tags);
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
	
	//update post topic
    public function update_post_topic($post_id)
    {
        //delete old topic
		
        $this->delete_post_topic($post_id);
		
        //add new topic
        $topic = trim($this->input->post('topic', true));

        $data = array(
			'post_id' => clean_number($post_id),
			'topic' => trim($topic),
			'topic_slug' => str_slug(trim($topic))
		);

		if (empty($data["topic_slug"]) || $data["topic_slug"] == "-") {
			$data["topic_slug"] = "topic-" . uniqid();
		}
		//insert topic
		$this->db->insert('topic', $data);
    }

    //get random tags
    public function get_random_tags()
    {
        $this->db->join('posts', 'posts.id = tags.post_id');
        $this->db->join('users', 'posts.user_id = users.id');
        $this->db->select('tags.tag_slug, tags.tag');
        $this->db->group_by('tags.tag_slug, tags.tag');
        $this->db->where('posts.status', 1);
        $this->db->where('posts.lang_id', clean_number($this->selected_lang->id));
        $this->db->where('posts.visibility', 1);
        $this->db->where('posts.is_scheduled', 0);
        $this->db->order_by('rand()');
        $this->db->limit(15);
        $query = $this->db->get('tags');
        return $query->result();
    }

    //get tags
    public function get_tags($limit='')
    {
        $this->db->join('posts', 'posts.id = tags.post_id');
        $this->db->join('users', 'posts.user_id = users.id');
        $this->db->select('tags.tag_slug, tags.tag');
        $this->db->group_by('tags.tag_slug, tags.tag');
        $this->db->order_by('tags.tag');
        $this->db->where('posts.status', 1);
        $this->db->where('posts.lang_id', clean_number($this->selected_lang->id));
        $this->db->where('posts.visibility', 1);
        $this->db->where('posts.is_scheduled', 0);
		if(!empty($limit)){
			$this->db->limit(15);
		}
        $query = $this->db->get('tags');
        return $query->result();
    }


    //get tag
    public function get_tag($tag_slug)
    {
        $this->db->join('posts', 'posts.id = tags.post_id');
        $this->db->join('users', 'posts.user_id = users.id');
        $this->db->select('tags.*, posts.lang_id as tag_lang_id');
        $this->db->order_by('tags.tag');
        $this->db->where('tags.tag_slug', clean_str($tag_slug));
        $this->db->where('posts.status', 1);
        $this->db->where('posts.visibility', 1);
        $this->db->where('posts.is_scheduled', 0);
        $query = $this->db->get('tags');
        return $query->row();
    }

	
	 //get topic
    public function get_topic($topic_slug)
    {
        $this->db->join('posts', 'posts.id = topic.post_id');
        $this->db->join('users', 'posts.user_id = users.id');
        $this->db->select('topic.*, posts.lang_id as tag_lang_id');
        $this->db->order_by('topic.topic');
        $this->db->where('topic.topic_slug', clean_str($topic_slug));
        $this->db->where('posts.status', 1);
        $this->db->where('posts.visibility', 1);
        $this->db->where('posts.is_scheduled', 0);
        $query = $this->db->get('topic');
        return $query->row();
    }

	
    //get posts tags
    public function get_post_tags_row($post_id,$id)
    {
        $sql = "SELECT * FROM tags WHERE post_id = ? AND id=?";
        $query = $this->db->query($sql, array(clean_number($post_id),clean_number($id)));
        return $query->row();
    }

    //get posts tags
    public function get_post_tags($post_id)
    {
        $sql = "SELECT * FROM tags WHERE post_id = ?";
        $query = $this->db->query($sql, array(clean_number($post_id)));
        return $query->result();
    }

	//get posts topic
    public function get_post_topic($post_id)
    {
        $sql = "SELECT * FROM topic WHERE post_id = ?";
        $query = $this->db->query($sql, array(clean_number($post_id)));
        return $query->result();
    }
	
    //get posts tags string
    public function get_post_tags_string($post_id)
    {
        $str = "";
        $count = 0;
        $tags_array = $this->get_post_tags($post_id);
        foreach ($tags_array as $item) {
            if ($count > 0) {
                $str .= ",";
            }
            $str .= $item->tag;
            $count++;
        }
        return $str;
    }

    //delete tags
    public function delete_post_tags($post_id)
    {
        $tags = $this->get_post_tags($post_id);
        if (!empty($tags)) {
            foreach ($tags as $tag) {
                //delete
                $this->db->where('id', $tag->id);
                $this->db->delete('tags');
            }
        }
    }
	
	//delete topic
    public function delete_post_topic($post_id)
    {
        $topics = $this->get_post_topic($post_id);
	
        if (!empty($topics)) {
			 foreach ($topics as $topic) {
				//delete
                $this->db->where('id', $topic->id);
                $this->db->delete('topic');
			 }
            
        }
    }

    public function get_paginated_tags_library_count()
    {
		$this->db->select('COUNT(id) as count');
        //$this->db->where("lang_id",$this->selected_lang->id);
        $query = $this->db->get('tags_library');
        return $query->row()->count;
    }
    public function get_paginated_tags_library($per_page, $offset)
    {
        $this->db->order_by('id', 'DESC');
        //$this->db->where("lang_id",$this->selected_lang->id);
        $this->db->limit($per_page, $offset);
        $query = $this->db->get('tags_library');
        return $query->result();
    }
    public function get_tag_by_id($id)
    {
        $sql = "SELECT * FROM tags_library WHERE id = ?";
        $query = $this->db->query($sql, array(clean_number($id)));
        return $query->row();
    }
    public function get_tag_count_by_tag($tag,$id)
    {
        $this->db->select('COUNT(id) as count');
        $this->db->where("tag",$tag);
        $this->db->where("id <>",$id);
        $query = $this->db->get('tags_library');
        return $query->row()->count;   
    }
    public function library_tags_update($tag,$tag_id){

        $data = array(
			//'lang_id' => clean_number($this->selected_lang->id),
			'tag' => trim($tag),
			'tag_slug' => str_slug(trim($tag))
		);
        $this->db->where("id",$tag_id);
        return $this->db->update("tags_library",$data);
    }
    public function library_tags_add($tag){

        $data = array(
			//'lang_id' => clean_number($this->selected_lang->id),
			'tag' => trim($tag),
			'tag_slug' => str_slug(trim($tag))
		);
        return $this->db->insert("tags_library",$data);
    }
    public function delete_library_tags($id){
        $this->db->where('id', $id);
        return $this->db->delete('tags_library');
    }

}