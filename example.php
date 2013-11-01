<?php
session_start();

require 'vendor/autoload.php';
include 'config.php';

if (!isset($config)) { user_error('Missing configuration.'); }
$client = new DPSFolioProducer\Client($config);
$request = $client->execute('get_folios_metadata');
var_dump($request);
var_dump($request->options['http']['header']);

echo '<hr />';

echo '<h1>Folios</h1>';
foreach ($request->response->folios as $folio) {
    print_r($folio);
    echo '<hr />';
}

echo '<hr />';

$request = $client->execute('upload_html_resources', array(
    'filepath' => 'HTMLResources.zip',
    'folio_id' => '4tTCHoOCwUKj4b8OQ26nBw'
));
var_dump($request);
echo '<hr />';

$request = $client->execute('delete_html_resources', array(
    'folio_id' => '4tTCHoOCwUKj4b8OQ26nBw'
));
var_dump($request);
echo '<hr />';

// $request = $client->execute('upload_folio_preview_image', array(
//     'filepath' => 'image.jpg',
//     'folio_id' => 'jqvyvAXHk0CX6OfZcPXZhw',
//     'orientation' => 'portrait'
// ));
// var_dump($request);
// echo '<hr />';

// $request = $client->execute('delete_folio_preview_image', array(
//     'folio_id' => 'jqvyvAXHk0CX6OfZcPXZhw',
//     'orientation' => 'portrait'
// ));
// var_dump($request);
// echo '<hr />';

// $options = array(
//     'folioName' => 'Test Folio Name',
//     'folioNumber' => 'folio-'.time(),
//     'magazineTitle' => 'Magazine Title',
//     'resolutionHeight' => 1024,
//     'resolutionWidth' => 768
// );
// $request = $client->execute('create_folio', $options);
// $folio_id = $request->response->folioID;
// var_dump($request);

// echo '<hr />';

// $folio_id = 'cLrHysf_d0yDHkqimxxqjg';
// $request = $client->execute('get_folio_metadata', array('folio_id' => $folio_id));
// var_dump($request);
// echo '<hr />';

// $request = $client->execute('duplicate_folio', array('folio_id' => $folio_id));
// $request = $client->execute('update_folio', array(
//     'folio_id' => $folio_id,
//     'folioName' => 'Updated AGAIN Folio Name'
// ));
// var_dump($request);

// $request = $client->execute('delete_folio', array('folio_id' => $folio_id));
// var_dump($request);

// $request = $client->execute('create_article', array(
//     'filepath' => 'example.folio',
//     'folio_id' => $folio_id
// ));
// var_dump($request->options['http']['header']);
// var_dump($request);

// echo '<hr />';

// $article_id = $request->response->articleInfo->id;

// $request = $client->execute('get_article_metadata', array(
//     'article_id' => $article_id,
//     'folio_id' => $folio_id
// ));
// var_dump($request);
// echo '<hr />';

// $request = $client->execute('get_articles_metadata', array(
//     'folio_id' => $folio_id
// ));
// echo '<h1>Articles</h1>';
// foreach ($request->response->articles as $article) {
//     print_r($article);
//     echo '<hr />';
// }

// $request = $client->execute('update_article_metadata', array(
//     'article_id' => $article_id,
//     'folio_id' => $folio_id,
//     'description' => 'My new description.'
// ));
// var_dump($request);

// echo '<hr />';

// $article_id = $request->response->articleInfo->id;
// $request = $client->execute('delete_article', array(
//     'article_id' => $article_id,
//     'folio_id' => $folio_id
// ));
// var_dump($request);
