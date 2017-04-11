<?php

require 'vendor/autoload.php';
use Plivo\RestAPI;

class SmsSender {

    public function __construct($auth_token = '', $auth_id = '') {
        $this->auth_id = $auth_id;
        $this->auth_token = $auth_token;
    }

    public function sendMessage($message, $dstnumber = '') {
        $p = new RestAPI($this->auth_id, $this->auth_token);
        $params = array(
            'src' => '19193645259',
            'dst' => $dstnumber,
            'text' => $message
        );
        return $p->send_message($params);
    }

}

