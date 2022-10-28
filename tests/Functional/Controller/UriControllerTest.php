<?php

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\KernelInterface;

class UriControllerTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * @var \Symfony\Bundle\FrameworkBundle\KernelBrowser
     */
    private $client;

    public function testIndexAction(): void
    {

        $this->client->request('GET', '/');

        $this->assertEquals('418', $this->client->getResponse()->getStatusCode());

        $this->client->request('POST', '/');

        $this->assertEquals('418', $this->client->getResponse()->getStatusCode());

        $this->client->request('PATCH', '/');

        $this->assertEquals('418', $this->client->getResponse()->getStatusCode());

        $this->client->request('OPTIONS', '/');

        $this->assertEquals('418', $this->client->getResponse()->getStatusCode());

        $this->client->request('CONNECT', '/');

        $this->assertEquals('418', $this->client->getResponse()->getStatusCode());

        $this->client->request('PURGE', '/');

        $this->assertEquals('418', $this->client->getResponse()->getStatusCode());

        $this->client->request('TRACE', '/');

        $this->assertEquals('418', $this->client->getResponse()->getStatusCode());
    }

    public function testGetUriActionWithInvalidShortCode(): void
    {
        $this->client->request('GET', '/foobarbz');

        $this->assertEquals('301', $this->client->getResponse()->getStatusCode());
    }

    public function testGetUriActionWithShortCode(): void
    {
        $url = 'https://www.example.com';

        $this->client->request(
            'PUT',
            '/',
            [],
            [],
            ['HTTP_AUTHORIZATION' => '$ecretf0rt3st'],
            $url
        );
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());

        $shortCode = $this->client->getResponse()->getContent();

        $this->client->request('GET', '/' . $shortCode);
        $this->assertEquals('301', $this->client->getResponse()->getStatusCode());

        /** @var  RedirectResponse $response */
        $response = $this->client->getResponse();
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($url, $response->getTargetUrl());
    }

    public function testPutUriAction(): void
    {
        $url = 'https://www.example.com';

        $this->client->request(
            'PUT',
            '/',
            [],
            [],
            ['HTTP_AUTHORIZATION' => '$ecretf0rt3st'],
            $url
        );

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
    }

    public function testPutUriActionWithNoCredentials(): void
    {
        $url = 'https://www.example.com';

        $this->client->request(
            'PUT',
            '/',
            [],
            [],
            [],
            $url
        );

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
    }

    public function testPutUriActionWithNoVaildUrl(): void
    {
        $url = 'this is not a url';

        $this->client->request(
            'PUT',
            '/',
            [],
            [],
            ['HTTP_AUTHORIZATION' => '$ecretf0rt3st'],
            $url
        );

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
    }

    public function testDeleteUriActionWithValidShortCode(): void
    {
        $url = 'https://www.delete.me';

        $this->client->request(
            'PUT',
            '/',
            [],
            [],
            ['HTTP_AUTHORIZATION' => '$ecretf0rt3st'],
            $url
        );

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());

        $hash = $this->client->getResponse()->getContent();

        $this->client->request(
            'DELETE',
            '/',
            [],
            [],
            ['HTTP_AUTHORIZATION' => '$ecretf0rt3st'],
            $hash
        );

        $this->assertEquals(410, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('Gone', $this->client->getResponse()->getContent());
    }

    public function testDeleteUriActionWithRubbishCode(): void
    {
        $this->client->request(
            'DELETE',
            '/',
            [],
            [],
            ['HTTP_AUTHORIZATION' => '$ecretf0rt3st'],
            'nocode'
        );

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());

        $this->client->request(
            'DELETE',
            '/',
            [],
            [],
            ['HTTP_AUTHORIZATION' => '$ecretf0rt3st'],
            '00code00'
        );

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
    }


    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        self::ensureKernelShutdown();

        $this->client = static::createClient();

        $kernel = self::bootKernel();

        $this->initDatabase($kernel);

        $this->entityManager = $kernel->getContainer()
                                      ->get('doctrine')
                                      ->getManager();
    }

    /**
     * @param KernelInterface $kernel
     *
     * @throws \Exception
     */
    private function initDatabase(KernelInterface $kernel): void
    {
        $application = new Application($kernel);
        $application->setAutoExit(false);

        // drop db
        $input = new ArrayInput([
            'command' => 'doctrine:database:drop',
            '--force' => true

        ]);
        $application->run($input, new NullOutput());

        // create db
        $input = new ArrayInput([
            'command' => 'doctrine:database:create'

        ]);
        $application->run($input, new NullOutput());

        // run migrations
        $input = new ArrayInput([
            'command'          => 'doctrine:migrations:migrate',
            '--no-interaction' => true

        ]);
        $application->run($input, new NullOutput());
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leaks
    }
}
