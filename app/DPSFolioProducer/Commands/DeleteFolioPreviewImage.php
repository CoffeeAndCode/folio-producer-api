<?php
namespace DPSFolioProducer\Commands;

class DeleteFolioPreviewImage extends Command
{
    protected $requiredOptions = array('folio_id', 'orientation');

    public function execute()
    {
        $folioID = $this->options['folio_id'];
        $orientation = $this->options['orientation'];
        $request = new \DPSFolioProducer\APIRequest('folios/'.$folioID.'/previews/'.$orientation, $this->config,
            array(
                'type' => 'delete'
            )
        );

        return $request->run();
    }
}
