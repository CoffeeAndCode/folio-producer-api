<?php
namespace DPSFolioProducer\Services;

class FolioService extends Service {
    public $config = null;

    public function __construct($config) {
        $this->config = $config;
    }

    public function create($data) {
        $headers = array(
            'Content-Type: application/json; charset=utf-8',
            $this->auth_header()
        );

        $defaults = array(
            'folioName' => null,
            'magazineTitle' => null,
            'folioNumber' => null,
            //'folioDescription' => '',
            //'publicationDate' => null,
            //'coverDate' => '',
            //'resolutionWidth' => null,   // 240 - 4095
            //'resolutionHeight' => null,  // 240 - 4095
            //'defaultAssetFormat' => 'Auto',
            //'defaultJPEGQuality' => 'High',
            //'bindingRight' => false,
            //'locked' => false,
            //'folioIntent' => 'Both',
            //'targetViewer' => 'Unset',
            //'filters' => '',
            //'viewer' => ''
        );
        $data = array_merge($defaults, $data);

        // use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'header'  => $headers,
                'method'  => 'POST',
                'content' => json_encode($data),
                //'proxy' => 'tcp://localhost:8888',
                'protocol_version' => 1.1
            )
        );

        $request = new Request($this->create_url('folios'), $options);
        $response = $request->run();
        return $request;
    }

    public function delete($folio_id) {
    }

    public function delete_article($folio_id, $article_id) {
    }

    public function delete_html_resources($folio_id) {
    }

    public function delete_preview_image($folio_id, $orientation) {
    }

    public function download_folio_preview_image($folio_id, $orientation) {
    }

    public function duplicate($folio_id) {
    }

    public function get_articles($folio_id) {
    }

    public function get_folio_metadata($folio_id=null) {
        $response = null;
        if ($folio_id === null) {
            $headers = array(
                'Content-Type: application/json; charset=utf-8',
                $this->auth_header()
            );

            // use key 'http' even if you send the request to https://...
            $options = array(
                'http' => array(
                    'header'  => $headers,
                    'method'  => 'GET',
                    //'proxy' => 'tcp://localhost:8888',
                    'protocol_version' => 1.1
                )
            );

            $request = new Request($this->create_url('folios'), $options);
            $response = $request->run();
        }
        return $request;
    }

    public function update($folio_id) {
    }

    public function update_article_metadata($folio_id, $article_id) {
    }

    public function upload_article($folio_id) {
    }

    public function upload_folio_preview_image($folio_id, $orientation) {
    }

    public function upload_html_resources($folio_id) {
    }
}
