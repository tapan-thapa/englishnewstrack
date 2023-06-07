<?php defined('BASEPATH') or exit('No direct script access allowed');

class Live_history_model extends CI_Model
{

    

    /*
    *-------------------------------------------------------------------------------------------------
    * Live History
    *-------------------------------------------------------------------------------------------------
    */

    //add live history results
    public function add_live_histories($post_id)
    {
        $result_titles = $this->input->post('result_title', true);
        $result_images = $this->input->post('list_item_image', true);
        $result_image_storages = $this->input->post('list_item_image_storage', true);
        $result_descriptions = $this->input->post('result_description', false);
        $min_correct_counts = $this->input->post('min_correct_count', true);
        $max_correct_counts = $this->input->post('max_correct_count', true);
        $result_orders = $this->input->post('result_order', true);
        if (!empty($result_titles)) {
            for ($i = 0; $i < count($result_titles); $i++) {
                $data = array(
                    'post_id' => $post_id,
                    'title' => !empty($result_titles[$i]) ? $result_titles[$i] : '',
                    'image_path' => !empty($result_images[$i]) ? $result_images[$i] : '',
                    'image_storage' => !empty($result_image_storages[$i]) ? $result_image_storages[$i] : '',
                    'description' => !empty($result_descriptions[$i]) ? $result_descriptions[$i] : '',
                    'history_order' => !empty($result_orders[$i]) ? $result_orders[$i] : 1,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                );
               
                //add to database
                $this->db->insert('live_blog_history', $data);
            }
        }
    }

    //add history of live 
    public function add_live_history($post_id)
    {
        $data = array(
            'post_id' => $post_id,
            'title' => "",
            'image_path' => "",
            'image_storage' => "",
            'description' => "",
            'created_at' => date("Y-m-d H:i:s"),
        );
        //add to database
        $this->db->insert('live_blog_history', $data);
        return $this->db->insert_id();
    }

    //update live history data
    public function update_live_histories($post_id)
    {
        
        $curr_timestamp = date("Y-m-d H:i:s");
        $results = $this->get_live_histories($post_id);
        if (!empty($results)) {
            foreach ($results as $result) {
                similar_text($result->title,$this->input->post('result_title_' . $result->id, true),$per1);
                similar_text($result->description,$this->input->post('result_description_' . $result->id, true),$per2);
                similar_text($result->image_path,$this->input->post('list_item_image_' . $result->id, true),$per3);

                if ($per1 != 100 || $per2 != 100 || $per3 != 100){
                    $timeUpdate =  $curr_timestamp;
                }else{
                    $timeUpdate =  $result->updated_at;
                }
                $data = array(
                    'title' => $this->input->post('result_title_' . $result->id, true),
                    'image_path' => $this->input->post('list_item_image_' . $result->id, true),
                    'image_storage' => $this->input->post('list_item_image_storage_' . $result->id, true),
                    'description' => $this->input->post('result_description_' . $result->id, false),
                    'history_order' => $this->input->post('result_order_' . $result->id, true),
                    'updated_at' => $timeUpdate
                );
                $this->db->where('id', $result->id);
                $this->db->update('live_blog_history', $data);
            }
        }
    }

    //get live blog history
    public function get_live_histories($post_id)
    {
        $sql = "SELECT * FROM live_blog_history WHERE post_id = ? ORDER BY id DESC";
        $query = $this->db->query($sql, array(clean_number($post_id)));
        return $query->result();
    }

    //get live blog data
    public function get_live_history($result_id)
    {
        $sql = "SELECT * FROM live_blog_history WHERE id = ?";
        $query = $this->db->query($sql, array(clean_number($result_id)));
        return $query->row();
    }

    //get live history by order number
    public function get_quiz_result_by_order_number($post_id, $order)
    {
        $sql = "SELECT * FROM live_blog_history WHERE post_id = ? AND history_order = ?";
        $query = $this->db->query($sql, array(clean_number($post_id), clean_number($order)));
        return $query->row();
    }

    //delete 
    //delete 
    public function delete_live_history($result_id)
    {
        $result = $this->get_live_history($result_id);
        if (!empty($result)) {
            $this->db->where('id', $result->id);
            $this->db->delete('live_blog_history');
        }
    }

    //delete 
    public function delete_quiz_results($post_id)
    {
        $results = $this->get_live_histories($post_id);
        if (!empty($results)) {
            foreach ($results as $result) {
                $this->delete_live_history($result->id);
            }
        }
    }

}