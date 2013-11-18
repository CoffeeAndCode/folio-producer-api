<?php
namespace DPSFolioProducer\Commands;

class DeleteArticle extends Command
{
    protected $requiredOptions = array('article_id', 'folio_id');

    public function execute()
    {
        $articleID = $this->options['article_id'];
        $folioID = $this->options['folio_id'];
        $request = new \DPSFolioProducer\APIRequest('folios/'.$folioID.'/articles/'.$articleID, $this->config,
            array(
                'type' => 'delete'
            )
        );

        return $request->run();
    }
}
