    <?php defined('BASEPATH') or exit('No direct script access allowed');

    class Shortvideos_controller extends Core_Controller
    {
        public function __construct()
        {
            parent::__construct();
        }
        public function index($slug, $post_id)
        {
            get_method();
            $slug = clean_slug($slug);
            if (empty($slug)) {
                redirect(lang_base_url());
            }
            $this->categories = null;
            $post = $this->post_model->get_short_video(486,0,20);
            //pr($post);
            if (!empty($post)) {
                $this->post($post);
            } else {
                //not found
                $this->error_404();
            }
        }

        /**
         * Post
         */
        private function post($post)
        {
            $data['posts'] = $post;
            $this->load->view('partials/_header_third', $data);
            $this->load->view('post/short_video', $data);
        }
    }
