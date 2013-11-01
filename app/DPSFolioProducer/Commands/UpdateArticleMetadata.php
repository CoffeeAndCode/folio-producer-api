<?php
namespace DPSFolioProducer\Commands;

class UpdateArticleMetadata extends Command
{
    public function execute()
    {
        return $this->folio->update_article_metadata($this->options);
    }
}
