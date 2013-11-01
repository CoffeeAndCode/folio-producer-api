<?php
namespace DPSFolioProducer\Commands;

class GetFoliosMetadata extends Command
{
    public function execute()
    {
        return $this->folio->get_folios_metadata();
    }
}
