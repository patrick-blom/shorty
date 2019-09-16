<?php

namespace App\Tests\Unit\Service\Authentication;

use App\Service\Authentication\BasicDeleteAuthentication;
use App\Service\Authentication\TokenAuthenticationInterface;
use PHPUnit\Framework\TestCase;

class BasicDeleteAuthenticationTest extends TestCase
{
    public function testIfTokenValidationSuccessful(): void
    {
        $validation = new BasicDeleteAuthentication('foobar');
        $this->assertInstanceOf(TokenAuthenticationInterface::class,$validation);
        $this->assertTrue($validation->validateTokenAuthentication('foobar'));
    }

    public function testIfTokenValidationNotSuccessful(): void
    {
        $validation = new BasicDeleteAuthentication('foobar');
        $this->assertInstanceOf(TokenAuthenticationInterface::class,$validation);
        $this->assertFalse($validation->validateTokenAuthentication('barfoo'));
    }
}
