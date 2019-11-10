<?php

namespace App\Tests\Unit\Service\Authentication;

use App\Service\Authentication\BasicPutAuthentication;
use App\Service\Authentication\TokenAuthenticationInterface;
use PHPUnit\Framework\TestCase;

class BasicPutAuthenticationTest extends TestCase
{
    public function testIfTokenValidationSuccessful(): void
    {
        $validation = new BasicPutAuthentication('foobar');
        $this->assertInstanceOf(TokenAuthenticationInterface::class, $validation);
        $this->assertTrue($validation->validateTokenAuthentication('foobar'));
    }

    public function testIfTokenValidationNotSuccessful(): void
    {
        $validation = new BasicPutAuthentication('foobar');
        $this->assertInstanceOf(TokenAuthenticationInterface::class, $validation);
        $this->assertFalse($validation->validateTokenAuthentication('barfoo'));
    }
}
