<?php

declare(strict_types=1);

namespace App\Factory;

use App\Exception\RequestDoesNotContainAValidShortyHashException;
use App\Struct\DeleteUriRequest;
use Symfony\Component\HttpFoundation\Request;

final class DeleteUriRequestFactory
{
    /**
     * @param Request $request
     *
     * @return DeleteUriRequest
     * @throws RequestDoesNotContainAValidShortyHashException
     */
    public function fromDirtyRequestContent(Request $request): DeleteUriRequest
    {
        $hash = trim((string) $request->getContent());

        $matches = [];
        $validHash = (bool)preg_match('/^[a-z0-9]{8}$/', $hash, $matches);

        if (false === $validHash) {
            throw new RequestDoesNotContainAValidShortyHashException(
                $request->getContent() . ': is not a valid shorty hash'
            );
        }

        return new DeleteUriRequest($hash);
    }
}
