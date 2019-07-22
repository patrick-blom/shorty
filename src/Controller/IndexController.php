<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class IndexController
{
    /**
     * @Route("/{code}")
     * @param Request $request
     * @return JsonResponse
     */
    public function get(Request $request)
    {
        if ($shortCode = $request->attributes->get('code')) {
            return new JsonResponse(
                [
                    'code' => $shortCode
                ]
            );
        }

        return new JsonResponse(
            [
                'error' => 'invalid data'
            ]
        );
    }
}
