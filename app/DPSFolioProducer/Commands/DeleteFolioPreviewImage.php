<?php
namespace DPSFolioProducer\Commands;

class DeleteFolioPreviewImage extends Command
{
    public function execute()
    {
        return $this->folio->delete_folio_preview_image($this->options);
    }
}
