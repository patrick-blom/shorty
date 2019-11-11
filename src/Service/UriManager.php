<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Uri;
use App\Exception\UriCouldNotBeSavedByUriManagerException;
use App\Repository\UriRepository;
use App\Struct\DeleteUriRequest;
use App\Struct\GetUriRequest;
use App\Struct\PutUriRequest;
use App\Struct\UriHashInterface;
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
     * Returns an Uri by looking for a hash if nothing is found a default Uri is returned
     *
     * @param \App\Struct\GetUriRequest $request
     * @param string $defaultUrl
     *
     * @return \App\Entity\Uri
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getGuaranteedUri(GetUriRequest $request, string $defaultUrl = '/'): Uri
    {
        $uri = $this->getUri($request);

        if ($uri === null) {
            $uri = new Uri();
            $uri->setOriginalUrl($defaultUrl);
        }

        return $uri;
    }

    /**
     * @param UriHashInterface $request
     *
     * @return Uri|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getUri(UriHashInterface $request): ?Uri
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
     * @throws UriCouldNotBeSavedByUriManagerException
     */
    public function putUri(PutUriRequest $request): ?Uri
    {
        $hash = sha1($request->getUrl());

        $uri = $this->validatePutUri(
            new ValidateUriRequest($hash)
        );

        if (null === $uri) {
            $uri = new Uri();
            $uri->setOriginalUrl($request->getUrl());
            $uri->setUrlHash($request->getHash());
            $uri->setShortCode($request->getShortCode());

            if (false === $this->uriRepository->saveUri($uri)) {
                throw new UriCouldNotBeSavedByUriManagerException(
                    'The putUri-Request for: ' . $uri->getOriginalUrl() . ' could not be saved'
                );
            }
        }

        return $uri;
    }

    /**
     * @param DeleteUriRequest $request
     *
     * @return bool
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function deleteUri(DeleteUriRequest $request): bool
    {
        $uri = $this->getUri($request);

        if (null === $uri) {
            return false;
        }

        return $this->uriRepository->deleteUri($uri);
    }

    /**
     * @param ValidateUriRequest $request
     *
     * @return Uri|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    private function validatePutUri(ValidateUriRequest $request): ?Uri
    {
        return $this->uriRepository->findUriByShortOriginalHash(
            $request->getHash()
        );
    }
}
