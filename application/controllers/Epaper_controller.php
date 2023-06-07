<?php defined('BASEPATH') or exit('No direct script access allowed');

class Epaper_controller extends Home_Core_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(){
        $data = $this->set_post_meta_tags();
        $this->load->view('partials/_header', $data);
        $this->load->view('epaper/epaper_home');
        $this->load->view('partials/_footer', $data);
    }

    public function detail(){
        $data = $this->set_post_meta_tags();
        $this->load->view('partials/_header', $data);
        $this->load->view('epaper/epaper_detail');
        $this->load->view('partials/_footer', $data);
    }

    private function set_post_meta_tags($db_data = [])
    {
        $data = [];
        $data['title'] = "Main Edition";
        $data['og_title'] = "Main Edition";
        
        $data['description'] = "Apna Bharat Epaper Main Edition";
        $data['keywords'] = "";

        
        $data['og_description'] = "Apna Bharat Epaper Main Edition";
        $data['og_type'] = "epaper";
        $data['og_url'] = "/epaper";
        $data['og_image'] = "";
        $data['og_width'] = "1200";
        $data['og_height'] = "628";
        $data['og_creator'] = "";
        $data['og_author'] = "";
        $data['og_published_time'] = "";
        $data['og_modified_time'] = "";
        $data['og_tags'] = "";
        return $data;
    }
    
    
}
