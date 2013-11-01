<?php
namespace DPSFolioProducer\Commands;

class DownloadFolioPreviewImage extends Command
{
    public function execute()
    {
        return $this->folio->download_folio_preview_image($this->options);
    }
}
