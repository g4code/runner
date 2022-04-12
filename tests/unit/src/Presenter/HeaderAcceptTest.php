<?php


use G4\Runner\Presenter\HeaderAccept;


class HeaderAcceptTest extends PHPUnit_Framework_TestCase
{

    public function testDefault()
    {
        $headerAccept = new HeaderAccept();

        $this->assertEquals('json', $headerAccept->getFormat());
    }

    public function testTextType()
    {
        $_SERVER['HTTP_ACCEPT'] = 'text/*';
        $headerAccept           = new HeaderAccept();

        $this->assertEquals('twig', $headerAccept->getFormat());
    }

    public function testTwig()
    {
        $_SERVER['HTTP_ACCEPT'] = 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8';
        $headerAccept           = new HeaderAccept();

        $this->assertEquals('twig', $headerAccept->getFormat());
    }

    public function testJson()
    {
        $_SERVER['HTTP_ACCEPT'] = 'application/json';
        $headerAccept           = new HeaderAccept();

        $this->assertEquals('json', $headerAccept->getFormat());
    }

    public function testFirstAllowed()
    {
        $_SERVER['HTTP_ACCEPT'] = 'text/html';
        $headerAccept           = new HeaderAccept(['application/json']);

        $this->assertEquals('json', $headerAccept->getFormat());

        $_SERVER['HTTP_ACCEPT'] = 'application/json';
        $headerAccept           = new HeaderAccept(['text/html']);

        $this->assertEquals('twig', $headerAccept->getFormat());
    }
}
