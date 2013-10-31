<?php
namespace DPSFolioProducer\Commands;

class UpdateFolio extends Command
{
    public function execute()
    {
        return $this->folio->update($this->options);
    }
}
