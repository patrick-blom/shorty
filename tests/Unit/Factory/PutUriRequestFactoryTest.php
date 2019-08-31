<?php

namespace App\Tests\Unit\Factory;

use App\Exception\RequestDoesNotContainAValidUrlException;
use App\Factory\PutUriRequestFactory;
use App\Struct\PutUriRequest;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class PutUriRequestFactoryTest extends TestCase
{
    public function testUrlCreatedFromRequest(): void
    {
        $content = 'https://www.foo.bar';
        $request = (new PutUriRequestFactory())->fromDirtyRequestContent(
            new Request([], [], [], [], [], [], $content)
        );

        $this->assertInstanceOf(PutUriRequest::class, $request);
        $this->assertSame('https://www.foo.bar', $request->getUrl());
    }

    public function testCreatingUrlOnRubbish(): void
    {
        $this->expectException(RequestDoesNotContainAValidUrlException::class);

        $content = 'ThisIsNotAUrl';
        (new PutUriRequestFactory())->fromDirtyRequestContent(
            new Request([], [], [], [], [], [], $content)
        );
    }
}
