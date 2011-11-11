<?php

namespace Funstaff\TikaBundle\Tests\Content;

use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Funstaff\TikaBundle\Content\Document;
use Funstaff\TikaBundle\Content\Metadata;

/**
 * DocumentTest
 *
 * @author Bertrand Zuchuat <bertrand.zuchuat@gmail.com>
 */
class DocumentTest extends TestCase
{
    public function testNoExistanceOfDocument()
    {
        try {
            $document = new Document('test', __DIR__.'/../documents/testerror.pdf');
        } catch (\InvalidArgumentException $e) {
            return;
        }
        $this->fail('Exception on document');
    }

    public function testGetName()
    {
        $document = new Document('test', __DIR__.'/../documents/test.pdf');
        $this->assertEquals('test', $document->getName());
    }

    public function testSetContent()
    {
        $document = new Document('test', __DIR__.'/../documents/test.pdf');
        $document->setContent('foo bar');
        $this->assertEquals('foo bar', $document->getContent());
    }

    public function testSetMetadata()
    {
        $document = new Document('test', __DIR__.'/../documents/test.pdf');
        $metadata = trim(file_get_contents(__DIR__.'/../documents/metadata.txt'));
        $metadatatest = str_replace('xmpTPg:', '', $metadata);
        $document->setMetadata(new Metadata($metadata));

        $this->assertEquals($metadatatest, $document->getMetadata()->getRaw());
    }
}