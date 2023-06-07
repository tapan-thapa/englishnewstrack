<?php defined('BASEPATH') or exit('No direct script access allowed');

class Webstory_model extends CI_Model
{

    

    /*
    *-------------------------------------------------------------------------------------------------
    * Live History
    *-------------------------------------------------------------------------------------------------
    */

    //add live history results
    public function add_webstories($post_id)
    {
        $result_titles = $this->input->post('result_title', true);
        $result_url = $this->input->post('result_url', true);
        $result_images = $this->input->post('list_item_image', true);
        $result_image_storages = $this->input->post('list_item_image_storage', true);
        $result_descriptions = $this->input->post('result_description', false);
        $min_correct_counts = $this->input->post('min_correct_count', true);
        $max_correct_counts = $this->input->post('max_correct_count', true);
        $result_orders = $this->input->post('result_order', true);
        $curr_timestamp = date("Y-m-d H:i:s");

        if (!empty($result_titles)) {
            for ($i = 0; $i < count($result_titles); $i++) {
                $data = array(
                    'post_id' => $post_id,
                    'title' => !empty($result_titles[$i]) ? $result_titles[$i] : '',
                    'url' => !empty($result_url[$i]) ? $result_url[$i] : '',
                    'image_path' => !empty($result_images[$i]) ? $result_images[$i] : '',
                    'image_storage' => !empty($result_image_storages[$i]) ? $result_image_storages[$i] : '',
                    'description' => !empty($result_descriptions[$i]) ? $result_descriptions[$i] : '',
                    'history_order' => !empty($result_orders[$i]) ? $result_orders[$i] : 1,
                    'created_at' => $curr_timestamp,
                    'updated_at' => $curr_timestamp
                );
                //add to database
                $this->db->insert('webstories_items', $data);
            }
        }
    }

    //add history of live 
    public function add_webstory($post_id)
    {
        $data = array(
            'post_id' => $post_id,
            'title' => "",
            'url' => "",
            'image_path' => "",
            'image_storage' => "",
            'description' => ""
        );
        //add to database
        $this->db->insert('webstories_items', $data);
        return $this->db->insert_id();
    }

    //update live history data
    public function update_webstories($post_id)
    {
        $curr_timestamp = date("Y-m-d H:i:s");
        
        $results = $this->get_webstories($post_id);
        if (!empty($results)) {
            foreach ($results as $result) {
                $data = array(
                    'title' => $this->input->post('result_title_' . $result->id, true),
                    'url' => $this->input->post('result_url_' . $result->id, true),
                    'image_path' => $this->input->post('list_item_image_' . $result->id, true),
                    'image_storage' => $this->input->post('list_item_image_storage_' . $result->id, true),
                    'description' => $this->input->post('result_description_' . $result->id, false),
                    'history_order' => $this->input->post('result_order_' . $result->id, true),
                    'created_at' => $curr_timestamp,
                    'updated_at' => $curr_timestamp
                );

                $this->db->where('id', $result->id);
                $this->db->update('webstories_items', $data);
            }
        }else{
            $result_titles = $this->input->post('result_title', true);
            if(is_array($result_titles) && count($result_titles)){
                $this->add_webstories($post_id);
            }
        }
    }

    //get live blog history
    public function get_webstories($post_id)
    {
        $sql = "SELECT * FROM webstories_items WHERE post_id = ? ORDER BY id";
        $query = $this->db->query($sql, array(clean_number($post_id)));
        return $query->result();
    }

    //get live blog data
    public function get_webstory($result_id)
    {
        $sql = "SELECT * FROM webstories_items WHERE id = ?";
        $query = $this->db->query($sql, array(clean_number($result_id)));
        return $query->row();
    }

    //get live history by order number
    public function get_quiz_result_by_order_number($post_id, $order)
    {
        $sql = "SELECT * FROM webstories_items WHERE post_id = ? AND history_order = ?";
        $query = $this->db->query($sql, array(clean_number($post_id), clean_number($order)));
        return $query->row();
    }

    //delete 
    //delete 
    public function delete_webstory($result_id)
    {
        $result = $this->get_webstory($result_id);
        if (!empty($result)) {
            $this->db->where('id', $result->id);
            $this->db->delete('webstories_items');
        }
    }

    //delete 
    public function delete_quiz_results($post_id)
    {
        $results = $this->get_webstories($post_id);
        if (!empty($results)) {
            foreach ($results as $result) {
                $this->delete_webstory($result->id);
            }
        }
    }

}