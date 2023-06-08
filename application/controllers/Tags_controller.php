<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Tags_controller extends Admin_Core_Controller
{

    public function __construct()
    {
        parent::__construct();
        check_permission('widgets');
    }

    /**
     * Add Widget
     */
    public function add_tags()
    {
        $data['title'] = "Update Tags";
        $data['panel_settings'] = panel_settings();
        if($this->selected_lang->id == 2){
            $data['tags'] = $this->tag_model->get_post_tags_string('-3');
        }elseif($this->selected_lang->id == 1){
            $data['tags'] = $this->tag_model->get_post_tags_string('-1');
        }elseif($this->selected_lang->id == 3){
            $data['tags'] = $this->tag_model->get_post_tags_string('-5');
        }else{
            $data['tags'] = [];
        }
        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/tags/add', $data);
        $this->load->view('admin/includes/_footer');
    }
    
    /**
     * Add Widget Post
     */
    public function add_tags_post()
    {
        //validate inputs
        if($this->selected_lang->id == 2){
            $this->tag_model->update_post_tags('-3');
        }elseif($this->selected_lang->id == 1){
            $this->tag_model->update_post_tags('-1');
        }elseif($this->selected_lang->id == 3){
            $this->tag_model->update_post_tags('-5');
        }else{
            //no action
        }
        $this->session->set_flashdata('success', trans("tags") . " " . trans("msg_suc_added"));
        reset_cache_data_on_change();
        redirect($this->agent->referrer());
    }
    public function add_quick_links(){

        $data['edit_id'] = $this->input->get('edit_id', TRUE);

        if(isset($data['edit_id']) && $data['edit_id']!=''){
            if($this->selected_lang->id == 2){
                $data['edit_tags'] = $this->tag_model->get_post_tags_row('-4',$data['edit_id']);
            }elseif($this->selected_lang->id == 1){
                $data['edit_tags'] = $this->tag_model->get_post_tags_row('-2',$data['edit_id']);
            }elseif($this->selected_lang->id == 3){
                $data['edit_tags'] = $this->tag_model->get_post_tags_row('-6',$data['edit_id']);
            }else{
                $data['edit_tags'] = [];
            }
			
        }
        $data['title'] = "Update Quick Links";
        $data['panel_settings'] = panel_settings();
        if($this->selected_lang->id == 2){
            $data['tags'] = $this->tag_model->get_post_tags('-4');
        }elseif($this->selected_lang->id == 1){
            $data['tags'] = $this->tag_model->get_post_tags('-2');
        }elseif($this->selected_lang->id == 3){
            $data['tags'] = $this->tag_model->get_post_tags('-6');
        }else{
            //no action
        }
        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/tags/add_quick_links', $data);
        $this->load->view('admin/includes/_footer');

    }
    public function delete_quick_link(){
        $id = $this->input->post('id', true);
        if(!empty($id)){
            $this->tag_model->delete_quick_link($id);
            reset_cache_data_on_change();
        }
    }
    public function add_quick_links_post(){

        $hd_edit_id = $this->input->post('hd_edit_id', true);
        $tag = $this->input->post('tag', true);
        $tag_slug = $this->input->post('tag_slug', true);

        $this->form_validation->set_rules('tag', 'Title', 'required|max_length[255]');
        $this->form_validation->set_rules('tag_slug', 'Link', 'required|max_length[255]');
        
        if ($this->form_validation->run() === false) {
            $this->session->set_flashdata('errors', validation_errors());
            $this->session->set_flashdata('form_data', $this->post_admin_model->input_values());
            redirect($this->agent->referrer());
        } else {
            $resQuickLink = "";
            if($this->selected_lang->id == 2){
                $resQuickLink = $this->tag_model->add_quick_links('-4');
            }elseif($this->selected_lang->id == 1){
                $resQuickLink = $this->tag_model->add_quick_links('-2');
            }elseif($this->selected_lang->id == 3){
                $resQuickLink = $this->tag_model->add_quick_links('-6');
            }
            if($resQuickLink){  
                if(isset($hd_edit_id) && !empty($hd_edit_id)){
                    $this->session->set_flashdata('success', "Quick Links " . trans("msg_suc_updated"));
                }else{
                    $this->session->set_flashdata('success', "Quick Links " . trans("msg_suc_added"));
                }
                reset_cache_data_on_change();
                redirect($this->agent->referrer());
            } else {
                $this->session->set_flashdata('form_data',['tag'=>$tag,'tag_slug'=>$tag_slug]);
                $this->session->set_flashdata('error', trans("msg_error"));
                redirect($this->agent->referrer());
            }

        }
    }
    public function tags_library_listing()
    {
        $data['edit_id'] = "";
		$data['edit_tag'] = array();
        $edit_id = $this->input->get('edit_id', TRUE);
		if(isset($edit_id)){
            $data['edit_id'] = $edit_id;
			$data['edit_tag'] = $this->tag_model->get_tag_by_id($data['edit_id']);
            if(empty($data['edit_tag']->id)){
                redirect(generate_url('admin/tags-library-listing'));
            }
		}
        $data['title'] = "Tags Library";
        $data['list_type'] = "tags";
        //get paginated posts
        $pagination = $this->paginate(admin_url() . 'tags-library-listing', $this->tag_model->get_paginated_tags_library_count());
        $data['posts'] = $this->tag_model->get_paginated_tags_library($pagination['per_page'], $pagination['offset']);
        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/tags/tags_liasting', $data);
        $this->load->view('admin/includes/_footer');
    }
    public function save_library_tags(){
        $tag = $this->input->post('tag', true);
        $tag_id = (int)$this->input->post('tag_id', true);
        $countTag = $this->tag_model->get_tag_count_by_tag($tag,$tag_id);
        //pr($this->db->last_query());die;
        if($countTag<=0){
            if(!empty($tag_id)){
                $this->tag_model->library_tags_update($tag,$tag_id);
                $this->session->set_flashdata('success', "Tag Updated Successfully!");
            }else{
                $this->tag_model->library_tags_add($tag);
                $this->session->set_flashdata('success', "Tag added Successfully!");
            }
        }else{
            $this->session->set_flashdata('form_data',['tag'=>$tag,'edit_id'=>$tag_id]);
            $this->session->set_flashdata('error', "Tag already exists in databse, Please check the same");
        }
        redirect(generate_url('admin/tags-library-listing'));
    }
    public function delete_library_tags(){
        $id = $this->input->post('id', true);
        if(!empty($id)){
            $this->tag_model->delete_library_tags($id);
            $this->session->set_flashdata('success', "Tag Deleted Successfully!");
        }
    }
    public function get_serach_library_tags(){
        $term = $this->input->post('term', true);
        if(!empty($term)){
            //$like = '%' . $term . '%';
            $sql = "SELECT id,tag FROM tags_library WHERE MATCH (tag) AGAINST ('".$term."' IN BOOLEAN MODE) LIMIT ?";
            $query = $this->db->query($sql, array(150));
            $result = $query->result();
            //pr($this->db->last_query());
            $dataObj = [];
            if(is_array($result) && count($result)){
                foreach ($result as $key => $value) {

                    $dataObj[] = ["id"=>$value->id,"value"=>$value->tag.' ('.$value->id.')'];
                }
            }
            echo json_encode($dataObj);
        }else{
            echo json_encode([]);
        }
        
    }
}
