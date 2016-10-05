<?php

/**
 * Created by PhpStorm.
 * User: mattias.nording
 * Date: 2016-10-05
 * Time: 16:47
 */
namespace Klarna\Helpers;
class DigestCreator
{
    function CreateOfflineDigest($eid,$shared)
    {
       return base64_encode($eid.":".$shared);
    }
}