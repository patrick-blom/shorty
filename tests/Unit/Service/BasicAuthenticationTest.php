<?php

namespace App\Tests\Unit\Service;

use App\Service\BasicAuthentication;
use PHPUnit\Framework\TestCase;

class BasicAuthenticationTest extends TestCase
{
    public function testIfTokenValidationSuccessful(): void
    {
        $validation = new BasicAuthentication('foobar');
        $this->assertTrue($validation->validateTokenAuthentication('foobar'));
    }

    public function testIfTokenValidationNotSuccessful(): void
    {
        $validation = new BasicAuthentication('foobar');
        $this->assertFalse($validation->validateTokenAuthentication('barfoo'));
    }
}
