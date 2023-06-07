<?php defined('BASEPATH') or exit('No direct script access allowed');

class PTI_controller extends Core_Controller
{

    public function __construct()
    {
       // parent::__construct();

        // if ($this->general_settings->email_verification == 1 && $this->auth_user->email_status == 0 && $this->auth_user->role != "admin") {
        //     $this->session->set_flashdata('error', trans("msg_confirmed_required"));
        //     redirect(generate_url('settings'));
        // }
    }

    /**
     * Post import
     */
    public function import_post()
    {
        $endTime = date('Y/m/d H:i:s');
        $startTime = date('Y/m/d H:i:s',strtotime('-15 minutes',strtotime($endTime)));

        $url = str_replace(' ', '%20',"http://editorial.pti.in/ptiapi/webservice1.asmx/JsonFile1?centercode=2032022001&FromTime=$startTime&EndTime=$endTime");

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        
        $data =  json_decode($response,true);
        if(!empty($data)){
            foreach($data as $d){
                $this->db->select('id');
                $this->db->where('name',trim($d['category']));
                $query = $this->db->get('categories');
                $ret = $query->row();
                if(empty($ret)){
                    // $cat['name'] = ucfirst(strtolower(trim($d['category'])));
                    // $cat['name_slug'] = strtolower(trim($d['category']));
                    // $this->db->insert('categories', $cat);
                    // $catId = $this->db->insert_id();
                    $catId = 38;
                }else
                    $catId = $ret->id;
                
                $pData = array(
                    'headline' => trim($d['Byline']),
                    'title' => trim($d['Headline']),
                    'title_slug' => str_replace(' ','-',trim(str_replace('(CORRECTED)',' ',$d['slug']))),
                    'summary' => trim($d['Headline']),
                    'status' => 0,
                    'user_id' => 1,
                    'post_type' => 'article',
                    'category_id' => $catId,
                    'content' => trim($d['story']),
                    'created_at' => date('Y-m-d h:i:s',strtotime($d['PublishedAt']))
                );
                $this->db->insert('posts', $pData);
                die('done');
            }
        }else{
            die('No data returned from api.');
        }
        
        //$this->post_admin_model->add_post($pData);
        // echo "<pre>";print_r($pData);
        // echo "<pre>";print_r($data);die;
        
       
    }


}
