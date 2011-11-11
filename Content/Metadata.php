<?php

namespace Funstaff\TikaBundle\Content;

use Funstaff\TikaBundle\Content\MetadataInterface;

/**
 * Metadata
 *
 * @author Bertrand Zuchuat <bertrand.zuchuat@gmail.com>
 */
class Metadata implements MetadataInterface
{
    protected $rawMetadata;

    protected $metadata;

    /**
     * Construct
     *
     * @param string $metadata
     */
    public function __construct($metadata)
    {
        $this->metadata = array();
        $metadata = str_replace('xmpTPg:', '', trim($metadata));
        $this->rawMetadata = $metadata;
        $this->extract($metadata);
    }

    /**
     * Get Raw Metadata
     *
     * @return string
     */
    public function getRaw()
    {
        return $this->rawMetadata;
    }

    /**
     * Get
     *
     * @param string $name
     *
     * @return string metadata
     */
    public function get($name)
    {
        if (!array_key_exists($name, $this->metadata)) {
            throw new \InvalidArgumentException('This key "%s" does not exist on metadata');
        }

        return $this->metadata[$name];
    }

    /**
     * Get All
     *
     * @return Array metadata
     */
    public function getAll()
    {
        return $this->metadata;
    }

    /**
     * Extract
     *
     * @param string $data
     */
    protected function extract($data)
    {
        $lines = explode("\n", $data);
        foreach ($lines as $line) {
            list($key, $value) = explode('////',
                                preg_replace('/^(.*): (.*)$/', '$1////$2', $line)
                                );
            $this->metadata[trim($key)] = trim($value);
        }
    }
}