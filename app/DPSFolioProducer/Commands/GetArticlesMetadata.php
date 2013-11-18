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

        $options = array('type' => 'get');

        if (!empty($data) {
            $options['data'] = json_encode($data);
        });

        $request = new \DPSFolioProducer\APIRequest('folios/'.$folioID.'/articles', $this->config, $options);

        return $request->run();
    }
}
