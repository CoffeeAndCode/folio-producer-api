<?php
namespace DPSFolioProducer\Errors;

class APIResponseError extends Error
{
    public $httpStatusCode;
    public $status;

    public function __construct($message, $status, $httpStatusCode)
    {
        parent::__construct($message);
        $this->httpStatusCode = $httpStatusCode;
        $this->status = $status;
    }
}
