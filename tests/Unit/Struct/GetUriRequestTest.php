<?php

namespace App\Tests\Unit\Struct;

use App\Struct\GetUriRequest;
use App\Struct\UriHashInterface;
use PHPUnit\Framework\TestCase;

class GetUriRequestTest extends TestCase
{
    public function testClassCanBeInstantiated(): void
    {
        $string  = 'IAmAHash';
        $request = new GetUriRequest($string);
        $this->assertSame('IAmAHash', $request->getHash());
        $this->assertInstanceOf(UriHashInterface::class, $request);
    }
}
