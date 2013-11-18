<?php
namespace DPSFolioProducer\Commands;

class DeleteFolio extends Command
{
    protected $requiredOptions = array('folio_id');

    public function execute()
    {
        $folioID = $this->options['folio_id'];
        $request = new \DPSFolioProducer\APIRequest('folios/'.$folioID, $this->config,
            array(
                'type' => 'delete'
            )
        );

        return $request->run();
    }
}
