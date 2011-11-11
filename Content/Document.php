<?php

namespace Funstaff\TikaBundle\Content;

use Funstaff\TikaBundle\Content\DocumentInterface;
use Funstaff\TikaBundle\Content\MetadataInterface;

/**
 * Document
 *
 * @author Bertrand Zuchuat <bertrand.zuchuat@gmail.com>
 */
class Document implements DocumentInterface
{
    protected $name;

    protected $path;

    protected $content;

    protected $metadata;

    /**
     * Construct
     *
     * @param string $name
     * @param string $path
     */
    public function __construct($name, $path)
    {
        $this->name = $name;
        $this->path = $path;
        if (!file_exists($path)) {
            throw new \InvalidArgumentException(sprintf(
                        'The document "%s" with path "%s" does not exist',
                        $name,
                        $path
            ));
        }
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setMetaData(MetadataInterface $metadata)
    {
        $this->metadata = $metadata;
    }

    public function getMetadata()
    {
        return $this->metadata;
    }
}