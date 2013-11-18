<?php
namespace DPSFolioProducer\Errors;

class Error
{
    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }
}
