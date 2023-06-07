<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Common_controller extends Core_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Admin Login
     */
    public function admin_login()
    {
        get_method();
        //check if logged in
        if (auth_check()) {
            redirect(admin_url());
        }

        $data['title'] = trans("login");
        $data['description'] = trans("login") . " - " . $this->settings->site_title;
        $data['keywords'] = trans("login") . ', ' . $this->settings->application_name;
        $this->load->view('admin/login', $data);

    }

    /**
     * Admin Login Post
     */
    public function admin_login_post()
    {
        post_method();
        //validate inputs
        $this->form_validation->set_rules('email', trans("form_email"), 'required|max_length[200]');
        $this->form_validation->set_rules('password', trans("form_password"), 'required|max_length[128]');

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('errors', validation_errors());
            $this->session->set_flashdata('form_data', $this->auth_model->input_values());
            redirect($this->agent->referrer());
        } else {
            $user = $this->auth_model->get_user_by_email($this->input->post('email', true));
            if (!empty($user) && $user->role != 'admin' && $this->general_settings->maintenance_mode_status == 1) {
                $this->session->set_flashdata('error', "Site under construction! Please try again later.");
                redirect($this->agent->referrer());
            }
            if ($this->auth_model->login()) {
                redirect(admin_url());
            } else {
                //error
                $this->session->set_flashdata('form_data', $this->auth_model->input_values());
                $this->session->set_flashdata('error', trans("login_error"));
                redirect($this->agent->referrer());
            }
        }
    }

    /**
     * Logout
     */
    public function logout()
    {
        get_method();
        $this->auth_model->logout();
        redirect($this->agent->referrer());
    }

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
        //echo "<pre>";print_r($data);die;
        if(!empty($data)){
            $skipWordsList = [
                'BULLION',
                'Business briefs ',
                'ARECA & COCONUT PRICES',
                'SBI-DIRECT EXC RATES ',
                'SCHEDULE',
                'NEWSALERT',
                'NEWS HIGHLIGHTS ',
                'Sports Highlights ',
                'MUM PEPPER COPRA CLOSING RATES',
                'COMMODITY PRICES ',
                'Duplicate entries',
                'OILSEEDS PRICES ',
                'Business highlights',
                'SBI-DIRECT EXCHAPTI PWR PWRNGE RATES',
                'MGR-ARECA-COCONUT-PRICES',
                'Following are the top foreign stories at 1700 hours',
                'Highlights',
                'MONEY MARKET OPERATION',
                'PTI PWR PWR',
                'KOLKATA BULLION CLOSING PRICES',
                'MUM BULLIONS OPENING RATE',
                'pwr',
                'newsvoir',
                'PRNewswire',
                'PTI PWR PWR',
                'NEWSALERT',
                'Business Wire India',
                'PTI takes no editorial responsibility for the same',
                'The above content is a press release and PTI takes no editorial responsibility for the same',
                'PTI PWR PWR',
                'PTI AMR KK KK KK',
                'BULLIONS',

            ];
            //echo "<pre>";print_r($data);die;
            foreach($data as $d){
                $headLine = strtolower(trim($d['Headline']));
                $byline = strtolower(trim($d['Byline']));
                $story = strtolower(trim($d['story']));
                $filename = strtolower(trim($d['FileName']));
                foreach($skipWordsList as $skip){
                    if (strpos($headLine, $skip) || strpos($byline, $skip) || strpos($story, $skip)){ 
                        echo "<pre>skip--";print_r($d['FileName']);
                        continue 2;
                    }
                }    
                echo "add--".$filename;
                $this->db->select('id');
                if(trim($d['category']) == 'Regional')
                    $this->db->where('name','city');                
                else
                    $this->db->where('name',trim($d['category']));
                $query = $this->db->get('categories');
                $ret = $query->row();
                if(empty($ret)){
                    // $cat['name'] = ucfirst(strtolower(trim($d['category'])));
                    // $cat['name_slug'] = strtolower(trim($d['category']));
                    // $this->db->insert('categories', $cat);
                    // $catId = $this->db->insert_id();
                    //$catId = 38;
                    $catId = 42;
                }else
                    $catId = $ret->id;

                $pos=strpos(strip_tags(trim($d['story'])), ' ', 200);
                $desc = substr(strip_tags($d['story']),0,$pos ); 
                $date = date('M d')." (PTI)";
                $date_pos = strpos($desc, $date);
                $new_desc = substr($desc, $date_pos);
                $k = str_replace($date, "", $new_desc);
                $slug = str_slug(trim($d['Headline']));
                $pData = array(
                    //'headline' => trim($d['Byline']),
                    'title' => trim($d['Headline']),
                    'title_slug' => $slug,
                    'pti_filename' => trim($d['FileName']),
                    'status' => 1,
                    'user_id' => 16,
                    'is_breaking' => 0,
                    'post_type' => 'article',
                    'category_id' => $catId,
                    'content' => trim($d['story']),
                    'tag_description' => $k,
                    'pri_category_id' => $catId,
                    //'summary' => $desc,
                    'created_at' => date('Y-m-d H:i:s',strtotime($d['PublishedAt'])),
                    'pti_category' => trim($d['category'])
                );
                $this->db->where('title_slug',$slug);
                $result = $this->db->get('posts')->num_rows();
                if($result == 0)
                    $this->db->insert('posts', $pData);
                
                //die('done');
            }
        }else{
            die('No data returned from api.');
        }
        die('done');
        //$this->post_admin_model->add_post($pData);
        // echo "<pre>";print_r($pData);
        // echo "<pre>";print_r($data);die;
        
       
    } 
}
