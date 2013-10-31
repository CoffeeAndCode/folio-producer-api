<?php
namespace DPSFolioProducer\Commands;

class DeleteArticle extends Command
{
    public function execute()
    {
        return $this->folio->delete_article($this->options);
    }
}
