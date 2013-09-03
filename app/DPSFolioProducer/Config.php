<?php
namespace DPSFolioProducer;

class Config
{
    public $data;

    public function __construct($config=array())
    {
        $this->data = $config;
    }

    public function __get($name)
    {
        return $this->data[$name];
    }

    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }
}
