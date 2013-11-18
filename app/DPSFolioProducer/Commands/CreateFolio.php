<?php
namespace DPSFolioProducer\Commands;

class CreateFolio extends Command
{
    protected $requiredOptions = array(
        'folioName',
        'folioNumber',
        'magazineTitle',
        'resolutionWidth',
        'resolutionHeight'
    );

    public function execute()
    {
        $request = new \DPSFolioProducer\APIRequest('folios', $this->config,
            array(
                'data' => json_encode($this->options),
                'type' => 'post'
            )
        );

        return $request->run();
    }
}
