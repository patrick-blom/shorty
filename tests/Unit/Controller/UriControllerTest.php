<?php

namespace App\Tests\Unit\Controller;

use App\Controller\UriController;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
}
