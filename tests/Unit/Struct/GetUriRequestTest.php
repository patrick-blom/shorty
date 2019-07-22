<?php

namespace App\Tests\Unit\Struct;

use App\Struct\GetUriRequest;
use PHPUnit\Framework\TestCase;

class GetUriRequestTest extends TestCase
{
    public function testClassCanBeInstantiated()
    {
        $string = 'IAmAHash';
        $request = new GetUriRequest($string);
        $this->assertSame('IAmAHash', $request->getHash());
    }
}
