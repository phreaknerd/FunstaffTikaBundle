<?php

namespace Funstaff\TikaBundle\Content;

use Funstaff\TikaBundle\Content\MetadataInterface;

/**
 * DocumentInterface
 *
 * @author Bertrand Zuchuat <bertrand.zuchuat@gmail.com>
 */
Interface DocumentInterface
{
    function __construct($name, $path);

    function getName();

    function getPath();

    function setContent($content);

    function getContent();

    function setMetaData(MetadataInterface $metadata);

    function getMetadata();
}