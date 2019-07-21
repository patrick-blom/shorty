<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Uri;
use App\Repository\UriRepository;
use App\Struct\GetUriRequest;
use App\Struct\PutUriRequest;
use App\Struct\ValidateUriRequest;

final class UriManager
{
    /**
     * @var UriRepository
     */
    private $uriRepository;

    /**
     * @param UriRepository $uriRepository
     */
    public function __construct(UriRepository $uriRepository)
    {
        $this->uriRepository = $uriRepository;
    }

    /**
     * @param GetUriRequest $request
     *
     * @return Uri|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getUri(GetUriRequest $request): ?Uri
    {
        return $this->uriRepository->findUriByShortCode(
            $request->getHash()
        );
    }

    /**
     * @param PutUriRequest $request
     *
     * @return Uri|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function putUri(PutUriRequest $request): ?Uri
    {
        $hash = sha1($request->getUrl());

        $uri = $this->validatePutUril(
            new ValidateUriRequest($hash)
        );

        if (null === $uri) {
            $uri = new Uri();
            $uri->setOriginalUrl($request->getUrl());
            $uri->setUrlHash($request->getUrlHash());
            $uri->setShortCode($request->getShortCode());

            $this->uriRepository->saveUri($uri);
        }

        return $uri;
    }

    /**
     * @param ValidateUriRequest $request
     *
     * @return Uri|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    private function validatePutUril(ValidateUriRequest $request): ?Uri
    {
        return $this->uriRepository->findUriByShortOriginalHash(
            $request->getHash()
        );
    }
}
