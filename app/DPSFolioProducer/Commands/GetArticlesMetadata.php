<?php
namespace DPSFolioProducer\Commands;

class GetArticlesMetadata extends Command
{
    public function execute()
    {
        $data = $this->options;
        $folioID = $this->options['folio_id'];
        unset($data['folio_id']);
        $request = new \DPSFolioProducer\APIRequest('folios/'.$folioID.'/articles', $this->config,
            array(
                'content' => json_encode($data),
                'type' => 'get'
            )
        );

        return $request->run();
    }
}
