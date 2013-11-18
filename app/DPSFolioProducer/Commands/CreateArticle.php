<?php
/**
 * Adobe DPS API client library.
 *
 * @category  AdobeDPS
 * @package   DPSFolioProducer
 * @author    Jonathan Knapp <jon@coffeeandcode.com>
 * @copyright 2013 Jonathan Knapp
 * @license   MIT https://github.com/CoffeeAndCode/folio-producer-api/blob/master/LICENSE
 * @version   1.0.0
 * @link      https://github.com/CoffeeAndCode/folio-producer-api
 */
namespace DPSFolioProducer\Commands;

/**
 * CreateArticle command that makes the API call to create a new article.
 *
 * @category AdobeDPS
 * @package  DPSFolioProducer
 * @author   Jonathan Knapp <jon@coffeeandcode.com>
 * @license  MIT https://github.com/CoffeeAndCode/folio-producer-api/blob/master/LICENSE
 * @version  1.0.0
 * @link     https://github.com/CoffeeAndCode/folio-producer-api
 */
class CreateArticle extends Command
{
    protected $requiredOptions = array('filepath', 'folio_id');

    /**
     * Execute the command.
     *
     * @return HTTPRequest Returns a HTTPRequest object from the API call.
     */
    public function execute()
    {
        $filepath = $this->options['filepath'];
        $folioID = $this->options['folio_id'];

        $request = new \DPSFolioProducer\APIRequest('folios/'.$folioID.'/articles', $this->config,
            array(
                'file' => $filepath,
                'type' => 'post'
            )
        );

        return $request->run();
    }
}
