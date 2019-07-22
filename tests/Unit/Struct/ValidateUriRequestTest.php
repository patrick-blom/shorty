<?php

namespace App\Tests\Unit\Struct;

use App\Struct\ValidateUriRequest;
use PHPUnit\Framework\TestCase;

class ValidateUriRequestTest extends TestCase
{
    public function testClassCanBeInstantiated()
    {
        $string = 'IAmAHash';
        $request = new ValidateUriRequest($string);
        $this->assertSame('IAmAHash', $request->getHash());
    }
}
