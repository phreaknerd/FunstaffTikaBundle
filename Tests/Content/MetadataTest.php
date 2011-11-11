<?php

namespace Funstaff\TikaBundle\Tests\Content;

use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Funstaff\TikaBundle\Content\Metadata;

/**
 * MetadataTest
 *
 * @author Bertrand Zuchuat <bertrand.zuchuat@gmail.com>
 */
class MetadataTest extends TestCase
{
    protected $metadata;

    protected $meta;

    public function setup()
    {
        $this->metadata = trim(file_get_contents(__DIR__.'/../documents/metadata.txt'));
        $this->meta = new Metadata($this->metadata);
    }

    public function testGetRaw()
    {
        $metadatatest = str_replace('xmpTPg:', '', $this->metadata);
        $this->assertEquals($metadatatest, $this->meta->getRaw());
    }

    public function testGetAll()
    {
        $this->assertTrue(is_array($this->meta->getAll()));
        $this->assertArrayHasKey('Template', $this->meta->getAll());
    }

    public function testGetWithNoKeyValue()
    {
        try {
            $this->meta->get('foo');
        } catch (\InvalidArgumentException $e) {
            return;
        }
        $this->fail('The key foo does not exist on metadata array');
    }

    public function testGet()
    {
        $this->assertEquals('2011-09-29T13:49:00Z', $this->meta->get('Last-Modified'));
    }
}