<?php
namespace DPSFolioProducer\Commands;

class DeleteFolio extends Command
{
    public function execute()
    {
        return $this->folio->delete($this->options);
    }
}
