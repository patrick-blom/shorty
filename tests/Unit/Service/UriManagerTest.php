<?php

namespace App\Tests\Unit\Service;

use App\Entity\Uri;
use App\Exception\UriCouldNotBeSavedByUriManagerException;
use App\Repository\UriRepository;
use App\Service\UriManager;
use App\Struct\GetUriRequest;
use App\Struct\PutUriRequest;
use PHPUnit\Framework\TestCase;

class UriManagerTest extends TestCase
{
    /**
     * @var UriRepository
     */
    private $uriRepository;

    public function testUriManagerWillReturnDefaultUriByPassingRubbish(): void
    {
        $uriManager = new UriManager(
            $this->createMock(UriRepository::class)
        );
        $this->assertInstanceOf(UriManager::class, $uriManager);

        $uri = $uriManager->getGuaranteedUri(
            new GetUriRequest('00000000')
        );

        $this->assertSame('/', $uri->getOriginalUrl());
    }

    public function testUriManagerWillReturnCorrectUriByPassingShortCode(): void
    {
        $uriManager = new UriManager($this->uriRepository);
        $this->assertInstanceOf(UriManager::class, $uriManager);

        $uri = $uriManager->getGuaranteedUri(
            new GetUriRequest('a078b1f4')
        );

        $this->assertSame('http://www.blabla.com', $uri->getOriginalUrl());
    }

    public function testUriManagerCanHandlePutRequestAndReturnsExpectedUriObejct(): void
    {
        $uriManager = new UriManager($this->uriRepository);
        $this->assertInstanceOf(UriManager::class, $uriManager);

        $uri = $uriManager->putUri(
            new PutUriRequest('http://www.blabla.com')
        );

        $this->assertNull($uri->getId());
        $this->assertSame('http://www.blabla.com', $uri->getOriginalUrl());
        $this->assertSame('a078b1f4', $uri->getShortCode());
        $this->assertSame('a078b1f4bc1ec3defc681e5f3fc89c7b71a65369', $uri->getUrlHash());
    }

    public function testUriManagerWillThrowAnExceptionIfUriCanNotBeSaved(): void
    {
        /** @var UriRepository $brokenUriRepository */
        $brokenUriRepository = $this->createMock(UriRepository::class);
        $uriManager          = new UriManager($brokenUriRepository);

        $this->assertInstanceOf(UriManager::class, $uriManager);
        $this->expectException(UriCouldNotBeSavedByUriManagerException::class);
        $this->expectExceptionMessage('The putUri-Request for: http://www.blabla.com could not be saved');

        $uriManager->putUri(
            new PutUriRequest('http://www.blabla.com')
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $this->uriRepository = $this->createMock(UriRepository::class);

        $uri = new Uri();
        $uri->setOriginalUrl('http://www.blabla.com');
        $uri->setUrlHash('a078b1f4bc1ec3defc681e5f3fc89c7b71a65369');
        $uri->setShortCode('a078b1f4');

        $this->uriRepository->method('findUriByShortCode')
                            ->willReturn(
                                $uri
                            );

        $this->uriRepository->method('saveUri')
                            ->willReturn(
                                true
                            );
    }
}
