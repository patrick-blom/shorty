<?php

declare(strict_types=1);

namespace App\Factory;

use App\Exception\RequestDoesNotContainAValidUrlException;
use App\Struct\PutUriRequest;
use Symfony\Component\HttpFoundation\Request;

final class PutUriRequestFactory
{
    /**
     * @param Request $request
     *
     * @return PutUriRequest
     */
    public static function fromDirtyRequestContent(Request $request)
    {
        $url = filter_var($request->getContent(), FILTER_VALIDATE_URL);

        if (false === $url) {
            throw new RequestDoesNotContainAValidUrlException($request->getContent() . ': is not a valid url');
        }

        return new PutUriRequest($url);
    }
}
