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
        $hash = trim((string)$request->getContent());
        if (false === $this->validateHash($hash)) {
            throw new RequestDoesNotContainAValidShortyHashException(
                $request->getContent() . ': is not a valid shorty hash'
            );
        }

        return new DeleteUriRequest($hash);
    }

    public function fromString(string $value): DeleteUriRequest
    {
        $hash = trim($value);
        if (false === $this->validateHash($hash)) {
            throw new RequestDoesNotContainAValidShortyHashException(
                $hash . ': is not a valid shorty hash'
            );
        }

        return new DeleteUriRequest($hash);
    }

    /**
     * @param string $hash
     *
     * @return bool
     */
    private function validateHash(string $hash)
    {
        $matches = [];
        return (bool)preg_match('/^[a-z0-9]{8}$/', $hash, $matches);
    }
}
