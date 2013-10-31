<?php
namespace DPSFolioProducer\Commands;

class DuplicateFolio extends Command
{
    public function execute()
    {
        return $this->folio->duplicate_folio($this->options);
    }
}
