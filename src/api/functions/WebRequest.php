<?php

namespace Functions;

use Exception;
class WebRequest
{
    var $url = '';
    var $token = '';
    var $return_transfer = TRUE;
    
    var $follow_location = TRUE;
    var $max_redirs = 0;
    var $auto_referer = FALSE;
    
    var $method = 'get';
    var $post_fields = array();
    var $connect_timeout = 0;
    var $timeout = 0;
    
    var $header = FALSE;
    var $http_header = array();
    
    var $parse_response = 'json';
    var $json_assoc = FALSE;
    var $encoding = 'utf-8';
    
    var $_ch;
    
    function initialize($config)
    {
        foreach ($config as $key => $val)
        {
            $this->$key = $val;
        }
    }
    
    function get()
    {
        $this->method = 'get';
        return $this->run();
    }
    
    function post()
    {
        $this->method = 'post';
        return $this->run();
    }
    
    function put()
    {
        $this->method = 'put';
        return $this->run();
    }

    function delete()
    {
        $this->method = 'delete';
        return $this->run();
    }

    function run()
    {

        $this->_ch = curl_init();
        
        $this->_set_options();
        
        $output = curl_exec($this->_ch);
        $response_code = curl_getinfo($this->_ch, CURLINFO_HTTP_CODE);

        // return $output . $response_code;
      //  print($response_code."\n");
      //  print( $output."\n\n");
        $response = array(
            'http_code' => $response_code,
            'body' => json_decode($output)
        );
        
        curl_close($this->_ch);
        return (object) $response;
    }
    
    function _set_options()
    {
        curl_setopt($this->_ch, CURLOPT_URL, $this->url);
        curl_setopt($this->_ch, CURLOPT_RETURNTRANSFER, $this->return_transfer);
        curl_setopt($this->_ch, CURLOPT_FOLLOWLOCATION, $this->follow_location);
        curl_setopt($this->_ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->_ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($this->_ch, CURLOPT_USERPWD, $this->token);
        
        if($this->max_redirs)
            curl_setopt($this->_ch, CURLOPT_MAXREDIRS, $this->max_redirs);
        
        curl_setopt($this->_ch, CURLOPT_AUTOREFERER, $this->auto_referer);
        
        if($this->method == 'post')
            curl_setopt($this->_ch, CURLOPT_POSTFIELDS, json_encode($this->post_fields));
        elseif($this->method == 'put') 
        {
            curl_setopt($this->_ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($this->_ch, CURLOPT_POSTFIELDS, json_encode($this->post_fields));
        }
        elseif($this->method == 'delete')
            curl_setopt($this->_ch, CURLOPT_CUSTOMREQUEST, "DELETE");

        if($this->connect_timeout)
            curl_setopt($this->_ch, CURLOPT_CONNECTTIMEOUT, $this->connect_timeout);

        if($this->timeout)
            curl_setopt($this->_ch, CURLOPT_TIMEOUT, $this->timeout);

        curl_setopt($this->_ch, CURLOPT_HEADER, $this->header);

        // if(count($this->http_header) > 0)
        //     curl_setopt($this->_ch, CURLOPT_HTTPHEADER, $this->http_header);
    }
}