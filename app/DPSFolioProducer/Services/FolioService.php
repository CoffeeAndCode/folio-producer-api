<?php
namespace DPSFolioProducer\Services;

class FolioService extends Service
{
    public $config = null;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function create($data)
    {
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
        $request->run();

        return $request;
    }

    public function create_article($data) {
        $folioID = $data['folio_id'];
        $headers = array(
            'Content-Type: application/json; charset=utf-8',
            $this->auth_header()
        );

        $defaults = array(
            //'folioName' => null,
            //'magazineTitle' => null,
            //'folioNumber' => null,
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
                //'proxy' => 'tcp://localhost:8888',
                'protocol_version' => 1.1
            )
        );

        $request = new Request($this->create_url('folios/'.$folioID.'/articles'), $options);
        $request->run('example.folio');

        return $request;
    }

    public function delete($data)
    {
        $headers = array(
            'Content-Type: application/json; charset=utf-8',
            $this->auth_header()
        );

        // use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'header'  => $headers,
                'method'  => 'DELETE',
                'protocol_version' => 1.1
            )
        );

        $request = new Request($this->create_url('folios/'.$data['folio_id']), $options);
        $request->run();

        return $request;
    }

    public function delete_article($data)
    {
        $headers = array(
            'Content-Type: application/json; charset=utf-8',
            $this->auth_header()
        );

        // use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'header'  => $headers,
                'method'  => 'DELETE',
                'protocol_version' => 1.1
            )
        );

        $request = new Request($this->create_url('folios/'.$data['folio_id'].'/articles/'.$data['article_id']), $options);
        $request->run();

        return $request;
    }

    public function delete_html_resources($folio_id)
    {
    }

    public function delete_preview_image($folio_id, $orientation)
    {
    }

    public function duplicate_folio($data)
    {
        $headers = array(
            'Content-Type: application/json; charset=utf-8',
            $this->auth_header()
        );

        // use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'content' => '',
                'header'  => $headers,
                'method'  => 'POST',
                'protocol_version' => 1.1
            )
        );
        array_push($options['http']['header'], 'Content-Length: '.strlen($options['http']['content']));

        $request = new Request($this->create_url('folios/'.$data['folio_id'].'/duplicate'), $options);
        $request->run();

        return $request;
    }

    public function get_articles($folio_id)
    {
    }

    public function get_folio_metadata($data)
    {
        $folio_id = $data['folio_id'];
        $headers = array(
            'Content-Type: application/json; charset=utf-8',
            $this->auth_header()
        );

        // use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'header'  => $headers,
                'method'  => 'GET',
                'protocol_version' => 1.1
            )
        );

        $request = new Request($this->create_url('folios/'.$folio_id), $options);
        $request->run();

        return $request;
    }

    public function get_folios_metadata()
    {
        $headers = array(
            'Content-Type: application/json; charset=utf-8',
            $this->auth_header()
        );

        // use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'header'  => $headers,
                'method'  => 'GET',
                'protocol_version' => 1.1
            )
        );

        $request = new Request($this->create_url('folios'), $options);
        $request->run();

        return $request;
    }

    public function update($data)
    {
        $folioID = $data['folio_id'];
        unset($data['folio_id']);
        $headers = array(
            'Content-Type: application/json; charset=utf-8',
            $this->auth_header()
        );

        $defaults = array(
            //'folioName' => null,
            //'magazineTitle' => null,
            //'folioNumber' => null,
            //'folioDescription' => '',
            //'publicationDate' => null,
            //'coverDate' => '',
            //'bindingRight' => false,
            //'locked' => false,
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
                'protocol_version' => 1.1
            )
        );

        $request = new Request($this->create_url('folios').'/'.$folioID, $options);
        $request->run();

        return $request;
    }

    public function get_articles_metadata($data) {
        $folio_id = $data['folio_id'];
        $headers = array(
            'Content-Type: application/json; charset=utf-8',
            $this->auth_header()
        );

        // use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'header'  => $headers,
                'method'  => 'GET',
                'protocol_version' => 1.1
            )
        );

        $request = new Request($this->create_url('folios/'.$folio_id.'/articles'), $options);
        $request->run();

        return $request;
    }

    public function update_article_metadata($data)
    {
        $articleID = $data['article_id'];
        unset($data['article_id']);
        $folioID = $data['folio_id'];
        unset($data['folio_id']);
        $headers = array(
            'Content-Type: application/json; charset=utf-8',
            $this->auth_header()
        );

        $defaults = array(
            // access
            // articleName
            // author
            // canAccessReceipt
            // description
            // downloadPriority
            // hideFromTOC
            // isAdvertisement
            // kicker
            // locked
            // section
            // sortOrder
            // tags
            // title
            // userData
        );
        $data = array_merge($defaults, $data);

        // use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'header'  => $headers,
                'method'  => 'POST',
                'content' => json_encode($data),
                'protocol_version' => 1.1
            )
        );

        $request = new Request($this->create_url('folios').'/'.$folioID.'/articles/'.$articleID.'/metadata', $options);
        $request->run();

        return $request;
    }

    public function upload_article($folio_id)
    {
    }

    public function upload_folio_preview_image($data)
    {
        $folio_id = $data['folio_id'];
        $orientation = $data['orientation'];

        $headers = array(
            'Content-Type: application/json; charset=utf-8',
            $this->auth_header()
        );

        $defaults = array(
            //fileName
        );
        $data = array_merge($defaults, $data);

        // use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'content' => json_encode(array('fileName' => 'image.png')),
                'header'  => $headers,
                'method'  => 'POST',
                'protocol_version' => 1.1
            )
        );

        $request = new Request($this->create_url('folios/'.$folio_id.'/previews/'.$orientation), $options);
        $request->run('image.png');

        return $request;
    }

    public function download_folio_preview_image($data)
    {
        $folio_id = $data['folio_id'];
        $orientation = $data['orientation'];

        $headers = array(
            'Content-Type: application/json; charset=utf-8',
            $this->auth_download_header()
        );

        // use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'header'  => $headers,
                'method'  => 'GET',
                'protocol_version' => 1.1
            )
        );

        $request = new Request($this->create_download_url('folios/'.$folio_id.'/previews/'.$orientation), $options);
        $request->run();

        return $request;
    }

    public function upload_html_resources($folio_id)
    {
    }
}
