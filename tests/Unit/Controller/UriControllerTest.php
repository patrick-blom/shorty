<?php

namespace App\Tests\Unit\Controller;

use App\Controller\UriController;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UriControllerTest extends TestCase
{

    public function testCreateRedirectResponseTo(): void
    {
        $method = new ReflectionMethod(UriController::class, 'createRedirectResponseTo');
        $method->setAccessible(true);

        /** @var RedirectResponse $response */
        $response = $method->invoke(new UriController(), 'https://foo.bar');

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertSame(Response::HTTP_MOVED_PERMANENTLY, $response->getStatusCode());
        $this->assertSame('https://foo.bar', $response->getTargetUrl());
    }

    public function testCreateBadRequestResponse(): void
    {
        $method = new ReflectionMethod(UriController::class, 'createBadRequestResponse');
        $method->setAccessible(true);

        /** @var Response $response */
        $response = $method->invoke(new UriController());

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testGetTokenFromRequestHeader(): void
    {
        $request = new Request([], [], [], [], [], ['HTTP_AUTHORIZATION' => 'foobar']);

        $method = new ReflectionMethod(UriController::class, 'getTokenFromRequestHeader');
        $method->setAccessible(true);

        /** @var string $token */
        $token = $method->invoke(new UriController(), $request);

        $this->assertInternalType('string', $token);
        $this->assertSame('foobar', $token);


        $request = new Request([], [], [], [], [], ['HTTP_AUTHORIZATION' => 123]);

        $method = new ReflectionMethod(UriController::class, 'getTokenFromRequestHeader');
        $method->setAccessible(true);

        /** @var string $token */
        $token = $method->invoke(new UriController(), $request);

        $this->assertInternalType('string', $token);
        $this->assertSame('123', $token);
    }
}
