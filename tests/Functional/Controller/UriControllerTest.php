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
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->initDatabase($kernel);

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }


    public function testIndexAction()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertEquals('418', $client->getResponse()->getStatusCode());
    }

    public function testGetUriActionWithInvalidShortCode()
    {
        $client = static::createClient();
        $client->request('GET', '/foobarbz');

        $this->assertEquals('302', $client->getResponse()->getStatusCode());
    }

    public function testGetUriActionWithShortCode()
    {
        $url = 'https://www.example.com';

        $client = static::createClient();
        $client->request(
            'PUT',
            '/',
            [],
            [],
            ['HTTP_AUTHORIZATION' => '$ecretf0rt3st'],
            $url
        );

        $this->assertEquals(201, $client->getResponse()->getStatusCode());

        $shortCode = $client->getResponse()->getContent();

        $client->request('GET', '/' . $shortCode);
        $this->assertEquals('302', $client->getResponse()->getStatusCode());

        /** @var  RedirectResponse $response */
        $response = $client->getResponse();
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($url, $response->getTargetUrl());
    }

    public function testPutUriAction()
    {
        $url = 'https://www.example.com';

        $client = static::createClient();
        $client->request(
            'PUT',
            '/',
            [],
            [],
            ['HTTP_AUTHORIZATION' => '$ecretf0rt3st'],
            $url
        );

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
    }

    public function testPutUriActionWithNoCredentials()
    {
        $url = 'https://www.example.com';

        $client = static::createClient();
        $client->request(
            'PUT',
            '/',
            [],
            [],
            [],
            $url
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testPutUriActionWithNoVaildUrl()
    {
        $url = 'this is no a url';

        $client = static::createClient();
        $client->request(
            'PUT',
            '/',
            [],
            [],
            ['HTTP_AUTHORIZATION' => '$ecretf0rt3st'],
            $url
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leaks
    }

    /**
     * @param KernelInterface $kernel
     * @throws \Exception
     */
    private function initDatabase(KernelInterface $kernel)
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
            'command' => 'doctrine:migrations:migrate',
            '--no-interaction' => true

        ]);
        $application->run($input, new NullOutput());

    }

}

