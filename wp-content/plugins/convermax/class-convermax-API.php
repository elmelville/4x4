<?php

class ConvermaxAPI
{

    private $debug = 0;
    private $base_url;
    private $base_surl;
    private $url;
    private $surl;
    private $cert;

    public function __construct()
    {
        $url = ConvermaxAPI::get_option('convermax_url');
        if (stristr(substr($url, -1), '/')) {
            $url = substr($url, 0, -1);
        }
        $this->url = $url;
        if (preg_match('|(.*?://.*?\.convermax\.com/.*?)/.*|', $url, $matches)) {
            $this->base_url = $matches[1];
        } else {
            $this->base_url = $url;
        }

        $surl = ConvermaxAPI::get_option('convermax_surl');
        if (stristr(substr($surl, -1), '/')) {
            $surl = substr($surl, 0, -1);
        }
        $this->surl = $surl;
        if (preg_match('|(.*?://.*?\.convermax\.com/.*?)/.*|', $surl, $matches)) {
            $this->base_surl = $matches[1];
        } else {
            $this->base_surl = $surl;
        }

        $client = ConvermaxAPI::get_option('convermax_client');
	    $this->cert = plugin_dir_path( __FILE__ ) . '/clients/'.$client.'/'.$client.'.pem';
    }

    public function __destruct()
    {
        /*if ($this->cert) {
            unlink($this->cert);
        }*/
    }

    private static function get_current_hash()
    {
        if ($_SERVER['SERVER_NAME'] == 'the4x4guys.com') {
            return 'the4x4guys';
        }
        return 'the4x4guys-dev';
    }

    public static function get_option($optionName)
    {
        $currentHash = ConvermaxAPI::get_current_hash();

        switch ($optionName) {
            case 'convermax_url':
                return 'http://api.convermax.com/v3/'.$currentHash;
                break;
            case 'convermax_surl':
                return 'https://admin.convermax.com/v3/'.$currentHash;
                break;
            case 'convermax_client':
                return 'the4x4guys';
                break;
            case 'convermax_js_file':
                return '//client.convermax.com/static/'.$currentHash.'/search.min.js';
                break;
            case 'convermax_css_file':
                return '//client.convermax.com/static/'.$currentHash.'/search.css';
                break;
            default:
               return get_option($optionName);
        }
    }

    public function batchStart()
    {
        if ($this->debug) {
            return true;
        }
        if ($this->inProgress()) {
            return false;
        }
        $url = $this->surl . '/batchupdate/start?incremental=false';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSLCERT, $this->cert);
        $data = curl_exec($ch);
        if (curl_errno($ch) || curl_getinfo($ch, CURLINFO_HTTP_CODE) != 204) {
            $result =  false;
        } else {
            $result = true;
        }
        curl_close($ch);
        return $result;
    }

    public function batchEnd()
    {
        if ($this->debug) {
            return true;
        }
        $url = $this->surl . '/batchupdate/end';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSLCERT, $this->cert);
        $data = curl_exec($ch);
        if (curl_errno($ch)) {
            $result = false;
        } else {
            $result = true;
        }
        curl_close($ch);
        return $result;
    }

    public function batchAdd($items, $catalog = '')
    {
        if ($this->debug) {
            return true;
        }
        if(!empty($catalog)) {
            $url = $this->surl . '/batchupdate/add?catalog='.$catalog;
        } else {
            $url = $this->surl . '/batchupdate/add';
        }
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($items));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSLCERT, $this->cert);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
        $data = curl_exec($ch);
        if (curl_errno($ch)) {
            $result = false;
        } else {
            $result = true;
        }
        curl_close($ch);

        return $result;
    }

    public function batchUpdate($items)
    {
        $url = $this->surl . '/batchupdate/update';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($items));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSLCERT, $this->cert);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
        $data = curl_exec($ch);
        if (curl_errno($ch)) {
            $result = false;
        } else {
            $result = true;
        }
        curl_close($ch);

        return $result;
    }

    public function add($items)
    {
        if ($this->inProgress()) {
            return false;
        }
        $url = $this->surl . '/update/add?incremental=true';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($items));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSLCERT, $this->cert);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
        $data = curl_exec($ch);
        if (curl_errno($ch)) {
            $result = false;
        } else {
            $result = true;
        }
        curl_close($ch);

        return $result;
    }

    public function update($items)
    {
        if ($this->inProgress()) {
            return false;
        }
        $url = $this->surl . '/update/update';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($items));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSLCERT, $this->cert);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
        $data = curl_exec($ch);
        if (curl_errno($ch)) {
            $result = false;
        } else {
            $result = true;
        }
        curl_close($ch);

        return $result;
    }

    public function delete($items)
    {
        if ($this->inProgress()) {
            return false;
        }
        $url = $this->surl . '/update/deletebymask';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($items));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSLCERT, $this->cert);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
        $data = curl_exec($ch);
        if (curl_errno($ch)) {
            $result = false;
        } else {
            $result = true;
        }
        curl_close($ch);

        return $result;
    }

    public function search($query, $page_number = 0, $page_size = 10, $facets = null, $order_by = 'position', $order_desc = false, $catalog = null)
    {
        if ($order_by == 'position') {
            $order_by = false;
            $order_desc = false;
        }
        $url = $this->url . '/search/json?query=' . urlencode($query);
        $url .= '&page=' . $page_number . '&pagesize=' . $page_size;
        if ($facets) {
            $i = 0;
            foreach ($facets as $key => $val) {
                $url .= '&facet.' . $i . '.field=' . urlencode($key);
                foreach ($val as $v) {
                    $url .= '&facet.' . $i . '.selection=' . urlencode($v);
                }
                $i++;
            }
        }

        if ($order_by) {
            $url .= '&sort.0.fieldname=' . $order_by . ($order_desc ? '&sort.0.descending=true' : '');
        }
        if ($catalog) {
            $url .= '&catalog=' . $catalog ;
        }
        /*$url .= '&analytics.userid=' . $_COOKIE['cmuid'];
        $url .= '&analytics.sessionid=' . $_COOKIE['cmsid'];
        $url .= '&analytics.useragent=' . urlencode($_SERVER['HTTP_USER_AGENT']);
        $url .= '&analytics.userip=' . $_SERVER['REMOTE_ADDR'];
        if (isset($_GET['searchfeatures'])) {
            $url .= '&analytics.eventparams.searchfeatures=' . urlencode($_GET['searchfeatures']);
        }*/

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        $data = curl_exec($ch);

        $data = json_decode($data);

        if (isset($data->TotalHits)) {
            return $data;
        }
        return false;
    }

    public function autocomplete($query)
    {
        $url = $this->url . '/autocomplete/json?query=' . urlencode($query);
        $url .= '&analytics.userid=' . $_COOKIE['cmuid'];
        $url .= '&analytics.sessionid=' . $_COOKIE['cmsid'];
        $url .= '&analytics.useragent=' . urlencode($_SERVER['HTTP_USER_AGENT']);
        $url .= '&analytics.userip=' . $_SERVER['REMOTE_ADDR'];
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        $data = curl_exec($ch);
        if (curl_errno($ch) || empty($data) || curl_getinfo($ch, CURLINFO_HTTP_CODE) != 200) {
            curl_close($ch);
            return false;
        }
        curl_close($ch);
        return $data;
    }

    public function track($event_type, $event_params)
    {
        if (!$this->cert || !$_COOKIE['cmuid'] || !$_COOKIE['cmsid']) {
            return true;
        }
        $params = array(
            'EventType' => $event_type,
            'EventParams' => $event_params,
            'UserID' => $_COOKIE['cmuid'],
            'SessionID' => $_COOKIE['cmsid'],
            'UserAgent' => $_SERVER['HTTP_USER_AGENT'],
            'UserIP' => $_SERVER['REMOTE_ADDR']
        );

        $url = $this->url . '/track';
        $url = str_replace('https://', 'http://', $url);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
        $data = curl_exec($ch);
        if (curl_errno($ch)) {
            $result = false;
        } else {
            $result = true;
        }
        curl_close($ch);

        return $result;
    }

    private function createTmpCertFile($cert)
    {
        $file = tempnam(sys_get_temp_dir(), 'CM_');
        file_put_contents($file, $cert);
        return $file;
    }

    public function getCertificate($token)
    {
	    if ($this->debug) {
		    return true;
	    }
	    $url = $this->base_url . '/createcertificate?token=' . $token;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $data = curl_exec($ch);
        if (curl_errno($ch) || empty($data) || curl_getinfo($ch, CURLINFO_HTTP_CODE) != 201) {
            curl_close($ch);
            return false;
        } else {
            curl_close($ch);
            file_put_contents($this->cert, $data);
            return $data;
        }
    }

    public function getHash($name, $hash)
    {
	    if ($this->debug) {
		    return true;
	    }
        $url = $this->base_surl . '/scheme/create?name=' . urlencode( $name ) . '&hash=' . urlencode( $hash );// . '&template=woocommerce';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSLCERT, $this->cert);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
        $data = curl_exec($ch);
        if (curl_errno($ch) || empty($data) || curl_getinfo($ch, CURLINFO_HTTP_CODE) != 200) {
            curl_close($ch);
            return false;
        } else {
            curl_close($ch);
	        $data = str_replace('"', '', $data);
            $this->url = $this->base_url . '/' . $data;
            $this->surl = $this->base_surl . '/' . $data;
            return $data;
        }
    }

    public function createIndexFields()
    {
	    if ($this->debug) {
		    return true;
	    }
	    $fields = array(
		    'ID' => '',
		    'post_author' => '',
		    'post_date' => '',
		    'post_date_gmt' => '',
		    'post_content' => '',
		    'post_title' => '',
		    'post_excerpt' => '',
		    'post_status' => '',
		    'comment_status' => '',
		    'ping_status' => '',
		    'post_password' => '',
		    'post_name' => '',
		    'to_ping' => '',
		    'pinged' => '',
		    'post_modified' => '',
		    'post_modified_gmt' => '',
		    'post_content_filtered' => '',
		    'post_parent' => '',
		    'guid' => '',
		    'menu_order' => '',
		    'post_type' => '',
		    'post_mime_type' => '',
		    'comment_count' => '',
		    'categories' => '',
		    'tags' => '',
		    'image' => '',
		    'price' => '',
		    'url' => ''
	    );

        $s_fields = array();
        foreach ($fields as $key => $val) {
            $s_fields[$this->sanitize(($key))] = '';
        }

        $url = $this->surl . '/scheme/createfields?catalog=product';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array($s_fields)));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSLCERT, $this->cert);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
        $data = curl_exec($ch);
        if (curl_errno($ch)) {
            $result = false;
        } else {
            $result = true;
        }
        curl_close($ch);

        return $result;
    }

    public function sanitize($string)
    {
        $chars = array('+', '-', '&', '|', '!', '(', ')', '{', '}', '[', ']', '^', '"', '~', '*', '?', ':', '\\', ',', "'", ' ');
        $string = str_replace($chars, '_', $string);
        $string = preg_replace('|_+|', '_', $string);
        $string = trim($string, '_');
        return $string;
    }

    public function getIndexedProducts()
    {
        $url = $this->url . '/search/json?pagesize=0&catalog=default';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        $data = curl_exec($ch);
        if (curl_errno($ch) || !($data = json_decode($data))) {
            curl_close($ch);
            return 0;
        } else {
            curl_close($ch);
            return isset($data->TotalHits) ? (int)$data->TotalHits : 0;
        }
    }

    public function inProgress()
    {
        $url = $this->url . '/indexing/status/json';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        $data = curl_exec($ch);
        if (curl_errno($ch) || !($data = json_decode($data))) {
            curl_close($ch);
            return false;
        } else {
            curl_close($ch);
            return $data->InProgress;
        }
    }
}
