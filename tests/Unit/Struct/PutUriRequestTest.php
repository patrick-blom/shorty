<?php

namespace App\Tests\Unit\Struct;

use App\Struct\PutUriRequest;
use PHPUnit\Framework\TestCase;

class PutUriRequestTest extends TestCase
{
    public function testClassCanBeInstantiatedAndHaveAVaildOutput(): void
    {
        $url  = 'www.foo.com';
        $hash = sha1($url);

        $request = new PutUriRequest($url);

        $this->assertSame($hash, $request->getUrlHash());
        $this->assertSame($url, $request->getUrl());
        $this->assertEquals(8, strlen($request->getShortCode()));
        $this->assertSame(substr($hash, 0, 8), $request->getShortCode());
    }
}
