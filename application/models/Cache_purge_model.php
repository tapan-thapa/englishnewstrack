<?php defined('BASEPATH') or exit('No direct script access allowed');

class Cache_purge_model extends CI_Model
{
    function cache_purge($paramArr){
        
        //return false;

		$lang_id = $paramArr['lang_id'];
		if($lang_id == 1){
			$domain = "newstrack.com";
		}elseif($lang_id == 2){
			$domain = "english.newstrack.com";	
		}elseif($lang_id == 3){
			$domain = "apnabharat.org";	
		}
        if($_SERVER['HTTP_HOST'] == "alpha.newstrack.com"){
            $domain = $_SERVER['HTTP_HOST'];
        }elseif($_SERVER['HTTP_HOST'] == "eng.newstrack.com"){
            $domain = $_SERVER['HTTP_HOST'];
        }elseif($_SERVER['HTTP_HOST'] == "apnabharat.newstrack.com"){
            $domain = $_SERVER['HTTP_HOST'];
        }
		if(empty($domain)) return false;
		
		$urls = $paramArr['urls'];
        /* $command = "";
        if(is_array($urls) && count($urls)){
            $purgeURL = [];
            foreach ($urls as $key => $value) {
                $purgeURL[] = "req.url ~ $value";
            }
            $purgeURLString = implode(" && ",$purgeURL);
            $command = "/usr/bin/varnishadm 'ban req.http.host == $domain && $purgeURLString'";
        }elseif(!empty($urls)){
            $command = "/usr/bin/varnishadm 'ban req.http.host == $domain &&  req.url ~ $urls'";
        } */
        //echo $command;die;
        /* if($command){
            $output = shell_exec("/usr/bin/sudo varnishadm 'ban req.http.host == alpha.newstrack.com &&  req.url ~ /'");
            return $output;
        } */
        if(is_array($urls) && count($urls)){
            $this->db->insert("cache_invalidation", ["lang_id"=>$lang_id,"urls"=>json_encode($urls)]);
        }elseif(!is_array($urls)){
            $this->db->insert("cache_invalidation", ["lang_id"=>$lang_id,"urls"=>json_encode([$urls])]);
        }
        //pr($this->db->last_query());die;
        return true;
	}
    public function cache_purge_article($paramArr){
        
        //return false;
        
        $is_edit = $paramArr["is_edit"];
        $lang_id = $paramArr["lang_id"];
        $postData = $paramArr["postData"];
        $cat_ids = $postData['cat_ids'];
        $this->db->where_in('id',json_decode($cat_ids));
        $this->db->select("name_slug");
        $query = $this->db->get('categories');
        $tblData = $query->result();
        $urls = ["/"];
        foreach ($tblData as $key => $value) {
            $urls[] = "/".$value->name_slug."/";
        }
        if($is_edit == 1){
            $urls[] = "/".$postData["category_slug"]."/".$postData["title_slug"]."-".$postData["id"];
            $urls[] = "/amp/".$postData["category_slug"]."/".$postData["title_slug"]."-".$postData["id"];
        }
        $this->cache_purge(["lang_id"=>$lang_id,"urls"=>$urls]);
    }
}