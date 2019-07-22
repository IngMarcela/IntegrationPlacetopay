<?php

namespace AppBundle\Controller;

use Dnetix\Redirection\PlacetoPay;

class PlacetopayController
{

    public static function initConnection(string $identification, string $secretKey, string $endPoint)
    {
        return new PlacetoPay([
            'login' => $identification,
            'tranKey' => $secretKey,
            'url' => $endPoint,
        ]);
    }
}
