<?php

namespace App\Tests\Functional\Service;

use App\Entity\Uri;
use App\Service\UriManager;
use App\Struct\GetUriRequest;
use App\Struct\PutUriRequest;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpKernel\KernelInterface;

class UriManagerTest extends WebTestCase
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

    public function testUriManagerCanSaveAEntity()
    {
        $repository = $this->entityManager->getRepository(Uri::class);

        $request = new PutUriRequest('www.foo.com');
        $manager = new UriManager($repository);
        $entity = $manager->putUri($request);

        $this->assertSame($entity->getOriginalUrl(), $request->getUrl());
    }

    public function testUriManagerWillNotCreateDuplicatedEntries()
    {
        $repository = $this->entityManager->getRepository(Uri::class);
        $manager = new UriManager($repository);

        $firstRequest = new PutUriRequest('www.foo.com');
        $firstEntity = $manager->putUri($firstRequest);

        $secondRequest = new PutUriRequest('www.foo.com');
        $secondEntity = $manager->putUri($secondRequest);

        $this->assertSame($firstRequest->getUrl(),$firstEntity->getOriginalUrl());
        $this->assertSame($secondRequest->getUrl(),$secondEntity->getOriginalUrl());
        $this->assertSame($firstEntity->getId(), $secondEntity->getId());
    }

    public function testUriManagerCanFindEntityByShortCode()
    {
        $repository = $this->entityManager->getRepository(Uri::class);

        $putRequest = new PutUriRequest('www.foo.com');
        $manager = new UriManager($repository);
        $createdEntity = $manager->putUri($putRequest);

        $getRequest= new GetUriRequest($putRequest->getShortCode());
        $fetchedEntity = $manager->getUri($getRequest);

        $this->assertSame($createdEntity->getId(), $fetchedEntity->getId());
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
