<?php

namespace App\Tests\Unit\Factory;

use App\Exception\RequestDoesNotContainAValidShortyHashException;
use App\Factory\DeleteUriRequestFactory;
use App\Factory\PutUriRequestFactory;
use App\Struct\DeleteUriRequest;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class DeleteUriRequestFactoryTest extends TestCase
{
    public function testDeleteRequestCreatedFromRequest(): void
    {
        $content = '86d0952a';
        $request = (new DeleteUriRequestFactory())->fromDirtyRequestContent(
            new Request([], [], [], [], [], [], $content)
        );

        $this->assertInstanceOf(DeleteUriRequest::class, $request);
        $this->assertSame('86d0952a', $request->getHash());
    }

    public function testDeleteRequestCreatedFromRequestWithWhitespaces(): void
    {
        $content = '     86d0952a      ';
        $request = (new DeleteUriRequestFactory())->fromDirtyRequestContent(
            new Request([], [], [], [], [], [], $content)
        );

        $this->assertInstanceOf(DeleteUriRequest::class, $request);
        $this->assertSame('86d0952a', $request->getHash());
    }

    public function testCreatingDeleteRequestOnRubbish(): void
    {
        $this->expectException(RequestDoesNotContainAValidShortyHashException::class);

        $content = '86d0 952a';
        (new DeleteUriRequestFactory())->fromDirtyRequestContent(
            new Request([], [], [], [], [], [], $content)
        );

        $this->expectException(RequestDoesNotContainAValidShortyHashException::class);

        $content = '86d02';
        (new DeleteUriRequestFactory())->fromDirtyRequestContent(
            new Request([], [], [], [], [], [], $content)
        );

        $this->expectException(RequestDoesNotContainAValidShortyHashException::class);

        $content = '86d0//52a';
        (new DeleteUriRequestFactory())->fromDirtyRequestContent(
            new Request([], [], [], [], [], [], $content)
        );


        $this->expectException(RequestDoesNotContainAValidShortyHashException::class);

        $content = null;
        (new DeleteUriRequestFactory())->fromDirtyRequestContent(
            new Request([], [], [], [], [], [], $content)
        );
    }
}
