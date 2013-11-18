<?php
namespace DPSFolioProducer\Commands;

abstract class Command implements ICommand
{
    public $is_retry;

    protected $config;
    protected $options;

    public function __construct($config, $options=array())
    {
        $this->config = $config;
        $this->is_retry = false;
        $this->options = $options;
    }

    public function retry() {
        if (!$this->is_retry) {
            $this->is_retry = true;
            return $this->execute();
        }
    }
}
