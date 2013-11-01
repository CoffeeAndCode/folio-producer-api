<?php
namespace DPSFolioProducer\Commands;

class UploadHTMLResources extends Command
{
    public function execute()
    {
        return $this->folio->upload_html_resources($this->options);
    }
}
