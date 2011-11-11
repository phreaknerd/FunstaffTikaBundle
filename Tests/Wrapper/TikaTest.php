<?php

namespace Funstaff\TikaBundle\Tests\Wrapper;

use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Funstaff\TikaBundle\Wrapper\Tika;

/**
 * TikaTest
 *
 * @author Bertrand Zuchuat <bertrand.zuchuat@gmail.com>
 */
class TikaTest extends TestCase
{
    public function setup()
    {
        $this->config = array('tika_path' => '/www/bin/tika-app-1.0.jar');
    }

    public function testWithFailedConfig()
    {
        $config = array('tika_path' => '/www/bin/tika');
        try {
            $tika = new Tika($config,
                'Funstaff\TikaBundle\Content\Document',
                'Funstaff\TikaBundle\Content\Metadata');
        } catch (\InvalidArgumentException $e) {
            return;
        }
        $this->fail('Exception on binary path');
    }

    public function testWithFailedSetOutputFormat()
    {
        $tika = new Tika($this->config,
            'Funstaff\TikaBundle\Content\Document',
            'Funstaff\TikaBundle\Content\Metadata');
        try {
            $tika->setOutputFormat('foo');
        } catch (\InvalidArgumentException $e) {
            return;
        }
        $this->fail('Exception on SetOutputFormat with not authorized value');
    }

    public function testSetOutputFormat()
    {
        $tika = new Tika($this->config,
            'Funstaff\TikaBundle\Content\Document',
            'Funstaff\TikaBundle\Content\Metadata');
        $tika->setOutputFormat('xml');
        $this->assertEquals('xml', $tika->getOutputFormat());
    }

    public function testAddGetDocument()
    {
        $tika = new Tika($this->config,
            'Funstaff\TikaBundle\Content\Document',
            'Funstaff\TikaBundle\Content\Metadata');
        $this->assertTrue(is_array($tika->getDocuments()));
        $this->assertEquals(0, count($tika->getDocuments()));
        $tika->addDocument('test', __DIR__.'/../documents/test.pdf');
        $this->assertEquals(1, count($tika->getDocuments()));
        $this->assertInstanceOf('Funstaff\TikaBundle\Content\Document', $tika->getDocument('test'));
    }

    public function testWithNoAddedDocumentExtractContent()
    {
        $tika = new Tika($this->config,
            'Funstaff\TikaBundle\Content\Document',
            'Funstaff\TikaBundle\Content\Metadata');
        try {
            $tika->extractContent();
        } catch (\InvalidArgumentException $e) {
            return;
        }
        $this->fail('Exception extract if not document added');
    }

    public function testExtractOutputXml()
    {
        $tika = new Tika($this->config,
            'Funstaff\TikaBundle\Content\Document',
            'Funstaff\TikaBundle\Content\Metadata');
        $tika->addDocument('test', __DIR__.'/../documents/test.pdf');
        $tika->extractContent();
        $content = $tika->getDocument('test')->getContent();
        $this->assertTrue(preg_match('/^<\?xml.*/', $content) > 0);
        $this->assertTrue(preg_match('/Ask follow-up questions/', $content) > 0);

        $tika->setOutputFormat('xml');
        $tika->extractContent();
        $content = $tika->getDocument('test')->getContent();
        $this->assertTrue(preg_match('/^<\?xml.*/', $content) > 0);
    }


    public function testExtractOutputHtml()
    {
        $tika = new Tika($this->config,
            'Funstaff\TikaBundle\Content\Document',
            'Funstaff\TikaBundle\Content\Metadata');
        $tika->addDocument('test', __DIR__.'/../documents/test.pdf');
        $tika->setOutputFormat('html');
        $tika->extractContent();
        $content = $tika->getDocument('test')->getContent();
        $this->assertTrue(preg_match('/^<html.*/', $content) > 0);
        $this->assertTrue(preg_match('/Ask follow-up questions/', $content) > 0);
    }

    public function testExtractOutputText()
    {
        $tika = new Tika($this->config,
            'Funstaff\TikaBundle\Content\Document',
            'Funstaff\TikaBundle\Content\Metadata');
        $tika->addDocument('test', __DIR__.'/../documents/test.pdf');
        $tika->setOutputFormat('text');
        $tika->extractContent();
        $content = $tika->getDocument('test')->getContent();
        $this->assertTrue(preg_match('/^33.*/', $content) > 0);
        $this->assertTrue(preg_match('/Ask follow-up questions/', $content) > 0);
    }

    public function testExtractMetadata()
    {
        $tika = new Tika($this->config,
            'Funstaff\TikaBundle\Content\Document',
            'Funstaff\TikaBundle\Content\Metadata');
        $tika->addDocument('test', __DIR__.'/../documents/test.pdf');
        $tika->extractMetadata();
        $metadata = $tika->getDocument('test')->getMetadata();
        $this->assertTrue(preg_match('/^Author.*/', $metadata->getRaw()) > 0);
        $this->assertEquals('application/pdf', $metadata->get('Content-Type'));
    }

    public function testExtractAll()
    {
        $tika = new Tika($this->config,
            'Funstaff\TikaBundle\Content\Document',
            'Funstaff\TikaBundle\Content\Metadata');
        $tika->addDocument('test', __DIR__.'/../documents/test.pdf');
        $tika->extractAll();
        $content = $tika->getDocument('test')->getContent();
        $this->assertTrue(preg_match('/^<\?xml.*/', $content) > 0);
        $metadata = $tika->getDocument('test')->getMetadata();
        $this->assertEquals('application/pdf', $metadata->get('Content-Type'));
    }
}