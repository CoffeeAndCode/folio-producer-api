<?php

class Request {
    private $headers;
    private $params;

    public function __construct() {
        $this->headers = array(
            'Content-Type: application/json; charset=utf-8'
        );

        $credentials = array(
            'email' => '',
            'password' => ''
        );
        $this->params = array();
    }
}
