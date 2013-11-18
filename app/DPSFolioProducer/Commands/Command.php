<?php
namespace DPSFolioProducer\Commands;

abstract class Command implements ICommand
{
    public $errors = array();
    public $is_retry = false;

    protected $config;
    protected $options;
    protected $requiredOptions = array();

    public function __construct($config, $options=array())
    {
        $this->config = $config;
        $this->options = $options;
    }

    public function isValid() {
        foreach ($this->requiredOptions as $requiredOption) {
            if (!isset($this->options[$requiredOption])) {
                $this->errors[] = $requiredOption.' is required.';
            }
        }
        return empty($this->errors);
    }

    public function retry() {
        if (!$this->is_retry) {
            $this->is_retry = true;
            return $this->execute();
        }
    }
}
