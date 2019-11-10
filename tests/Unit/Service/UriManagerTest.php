<?php

namespace App\Tests\Unit\Service;

use App\Entity\Uri;
use App\Repository\UriRepository;
use App\Service\UriManager;
use App\Struct\GetUriRequest;
use PHPUnit\Framework\TestCase;


class UriManagerTest extends TestCase
{
    /**
     * @var UriRepository
     */
    private $uriRepository;

    public function testUriManagerWillReturnGuaranteedUri(): void
    {
        $uriManager = new UriManager($this->uriRepository);
        $this->assertInstanceOf(UriManager::class, $uriManager);

        $uri = $uriManager->getGuaranteedUri(
            new GetUriRequest('a078b1f4')
        );

        $this->assertSame('http://www.blabla.com', $uri->getOriginalUrl());
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
    }
}
