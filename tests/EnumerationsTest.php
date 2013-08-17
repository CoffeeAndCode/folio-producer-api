<?php
require 'enumerations.php';

class EnumerationsTest extends PHPUnit_Framework_TestCase
{
    public function testExistanceOfCreateSession()
    {
        $this->assertTrue(method_exists('DPSFolioProducer\FolioProducer', 'create_session'));
    }
}
