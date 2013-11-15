<?php
namespace DPSFolioProducer\Services;

class Request
{
    public $options = null;
    public $response_headers = array();
    public $response = null;
    public $url = null;

    public function __construct($url, $options)
    {
        $this->options = $options;
        $this->url = $url;
    }

    public function run($filename=null)
    {
        if ($filename) {
            $this->upload_file($filename);
        }
        $context = stream_context_create($this->options);
        $response = @file_get_contents($this->url, false, $context);
        if ($response === false) {
            $response = (object) array('error' => isset($php_errormsg) ? $php_errormsg : 'Error retrieving url: '.$this->url);
        }

        if (isset($http_response_header)) {
            $this->response_headers = $http_response_header;
        }

        if ($this->responseContentType() == 'application/json') {
            $this->response = json_decode($response);
            if ($this->response === null) {
                if (json_last_error() !== JSON_ERROR_NONE) {
                    user_error(json_last_error());
                }
            }
        } else {
            $this->response = $response;
        }

        return $this->response;
    }

    public function get_response_code()
    {
        $response_code = null;
        if ($this->response_headers && count($this->response_headers)) {
            preg_match('/^(([a-zA-Z]+)\/([\d\.]+))\s([\d\.]+)\s(.*)$/', $this->response_headers[0], $matches);
            if ($matches) {
                $response_code = intval($matches[4]);
            }
        }

        return $response_code;
    }

    public function upload_file($filepath)
    {
        if (!is_file($filepath)) {
            throw new \Exception('File cannot be uploaded: '.$filepath);
        }

        $filename = pathinfo($filepath, PATHINFO_BASENAME);
        $data = '';
        $handle = fopen($filepath, 'rb');
        fseek($handle, 0);
        $binary = fread($handle, filesize($filepath));
        fclose($handle);

        $separator = md5(microtime());

        $eol = "\r\n";
        $data = '';
        $data .=  '--' . $separator . $eol;

        if (array_key_exists('content', $this->options['http'])) {
            $data .= 'Content-Disposition: form-data; name="request"' . $eol;
            $data .= 'Content-Type: text/plain; charset=UTF-8' . $eol;
            $data .= 'Content-Transfer-Encoding: 8bit' . $eol . $eol;
            $data .= $this->options['http']['content'] . $eol;
            $data .=  '--' . $separator . $eol;
        }

        $data .= 'Content-Disposition: form-data; name=""; filename="' . $filename . '"' . $eol;
        $data .= 'Content-Transfer-Encoding: binary' . $eol . $eol;
        $data .= $binary . $eol;
        $data .= '--' . $separator . '--' . $eol;

        $this->replace_content_type_header('Content-Type: multipart/form-data; boundary='.$separator);
        $this->options['http']['content'] = $data;
    }

    private function replace_content_type_header($new_header)
    {
        $this->options['http']['header'] = array_map(function ($header) use ($new_header) {
            if (preg_match('/^Content\-Type:/i', $header)) {
                return $new_header;
            }
            return $header;
        }, $this->options['http']['header']);
    }

    private function responseContentType()
    {
        $contentType = null;
        if ($this->response_headers && count($this->response_headers)) {
            foreach($this->response_headers as $header) {
                if (preg_match('/^Content\-Type:\s*(.*);/i', $header, $matches)) {
                    $contentType = $matches[1];
                }
            }
        }
        return $contentType;
    }
}
