<?php
namespace DPSFolioProducer\Commands;

class GetFolioMetadata extends Command
{
    public function execute()
    {
        return $this->folio->get_folio_metadata($this->options);
    }
}
