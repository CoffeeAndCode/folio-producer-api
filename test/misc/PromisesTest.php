<?php
class PromisesTest extends PHPUnit_Framework_TestCase {
    public function test_deferred_has_promise_method() {
        $deferred = new React\Promise\Deferred();
        $this->assertTrue(method_exists($deferred, 'promise'));
    }
}
