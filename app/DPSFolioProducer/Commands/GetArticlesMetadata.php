<?php
namespace DPSFolioProducer\Commands;

class GetArticlesMetadata extends Command
{
    public function execute()
    {
        return $this->folio->get_articles_metadata($this->options);
    }
}
