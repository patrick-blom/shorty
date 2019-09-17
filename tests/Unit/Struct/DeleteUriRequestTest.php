<?php

namespace App\Tests\Unit\Struct;

use App\Struct\DeleteUriRequest;
use App\Struct\UriHashInterface;
use PHPUnit\Framework\TestCase;

class DeleteUriRequestTest extends TestCase
{
    public function testClassCanBeInstantiated(): void
    {
        $string  = 'IAmAHash';
        $request = new DeleteUriRequest($string);
        $this->assertSame('IAmAHash', $request->getHash());
        $this->assertInstanceOf(UriHashInterface::class, $request);
    }
}
