<?php
namespace DPSFolioProducer\Commands;

class CreateFolio extends Command
{
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
