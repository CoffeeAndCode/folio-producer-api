<?php
namespace DPSFolioProducer\Commands;

abstract class Command implements ICommand
{
    public $folio;
    public $session;

    protected $options;

    public function __construct($options=array())
    {
        $this->options = $options;
    }
}
