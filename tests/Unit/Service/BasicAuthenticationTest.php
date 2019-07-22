<?php

namespace App\Tests\Unit\Service;

use App\Service\BasicAuthentication;
use PHPUnit\Framework\TestCase;

class BasicAuthenticationTest extends TestCase
{
    public function testIfTokenValidationSuccessfull()
    {
        $validation = new BasicAuthentication('foobar');
        $this->assertTrue($validation->validateTokenAuthentication('foobar'));
    }

    public function testIfTokenValidationNotSuccessfull()
    {
        $validation = new BasicAuthentication('foobar');
        $this->assertFalse($validation->validateTokenAuthentication('barfoo'));
    }
}
