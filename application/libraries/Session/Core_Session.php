<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Core_Session extends CI_Session {

    public function __construct(array $params = array()) {
        $CI = get_instance();
        $CI->load->library('user_agent');
        $CI->load->helper('url');
        $url_parts = parse_url(current_url());
        $host = str_replace('www.', '', $url_parts['host']);
        if ($CI->agent->is_robot() || in_array($host, ['english.newstrack.com'])) {
            log_message('debug', 'Session: Robot detected, initialization aborted.');
            return;
        }

        parent::__construct($params);
    }

}
