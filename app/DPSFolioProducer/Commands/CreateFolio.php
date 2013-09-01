<?php
namespace DPSFolioProducer\Commands;

class CreateFolio extends Command {
    public function execute() {
        return $this->folio->create($this->options);
    }
}
