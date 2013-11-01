<?php
namespace DPSFolioProducer\Commands;

class UploadFolioPreviewImage extends Command
{
    public function execute()
    {
        return $this->folio->upload_folio_preview_image($this->options);
    }
}
