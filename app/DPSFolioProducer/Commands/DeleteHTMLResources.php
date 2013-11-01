<?php
namespace DPSFolioProducer\Commands;

class DeleteHTMLResources extends Command
{
    public function execute()
    {
        return $this->folio->delete_html_resources($this->options);
    }
}
