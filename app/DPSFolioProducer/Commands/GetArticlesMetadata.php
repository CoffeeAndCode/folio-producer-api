<?php
namespace DPSFolioProducer\Commands;

class GetArticlesMetadata extends Command
{
    protected $requiredOptions = array('folio_id');

    public function execute()
    {
        $folioID = $this->options['folio_id'];

        $data = $this->options;
        unset($data['folio_id']);

        $request = new \DPSFolioProducer\APIRequest('folios/'.$folioID.'/articles', $this->config,
            array(
                'data' => json_encode($data),
                'type' => 'get'
            )
        );

        return $request->run();
    }
}
