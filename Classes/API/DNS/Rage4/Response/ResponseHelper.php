<?php

namespace App\Service\API\DNS\Rage4\Response;


use App\Service\API\DNS\Rage4\Exception\Rage4Exception;

class ResponseHelper
{
    public static function checkResponseError($response)
    {
        if (is_string($response)) {
            throw new Rage4Exception($response);
        }
    }
}