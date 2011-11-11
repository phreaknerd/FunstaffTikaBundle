<?php

namespace Funstaff\TikaBundle\Wrapper;

/**
 * TikaInterface
 *
 * @author Bertrand Zuchuat <bertrand.zuchuat@gmail.com>
 */
interface TikaInterface
{
    function setOutputFormat($outputFormat);

    function addDocument($name, $path);

    function extractContent();

    function extractMetadata();
}