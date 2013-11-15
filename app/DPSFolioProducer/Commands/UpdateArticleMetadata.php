<?php
namespace DPSFolioProducer\Commands;

class UpdateArticleMetadata extends Command
{
    public function execute()
    {
        $articleID = $this->options['article_id'];
        $folioID = $this->options['folio_id'];
        $data = $this->options;
        unset($data['article_id']);
        unset($data['folio_id']);
        $request = new \DPSFolioProducer\APIRequest('folios/'.$folioID.'/articles/'.$articleID.'/metadata', $this->config,
            array(
                'data' => json_encode($data),
                'type' => 'post'
            )
        );

        return $request->run();
    }
}
