<?php

namespace Funstaff\TikaBundle\Content;

/**
 * MetadataInterface
 *
 * @author Bertrand Zuchuat <bertrand.zuchuat@gmail.com>
 */
Interface MetadataInterface
{
    function __construct($metadata);

    function get($name);
}