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

    public function testUriManagerCanSaveAEntity(): void
    {
        $repository = $this->entityManager->getRepository(Uri::class);

        $request = new PutUriRequest('www.foo.com');
        $manager = new UriManager($repository);
        $entity  = $manager->putUri($request);

        $this->assertSame($entity->getOriginalUrl(), $request->getUrl());
    }

    public function testUriManagerWillNotCreateDuplicatedEntries(): void
    {
        $repository = $this->entityManager->getRepository(Uri::class);
        $manager    = new UriManager($repository);

        $firstRequest = new PutUriRequest('www.foo.com');
        $firstEntity  = $manager->putUri($firstRequest);

        $secondRequest = new PutUriRequest('www.foo.com');
        $secondEntity  = $manager->putUri($secondRequest);

        $this->assertSame($firstRequest->getUrl(), $firstEntity->getOriginalUrl());
        $this->assertSame($secondRequest->getUrl(), $secondEntity->getOriginalUrl());
        $this->assertSame($firstEntity->getId(), $secondEntity->getId());
    }

    public function testUriManagerCanFindEntityByShortCode(): void
    {
        $repository = $this->entityManager->getRepository(Uri::class);

        $putRequest    = new PutUriRequest('www.foo.com');
        $manager       = new UriManager($repository);
        $createdEntity = $manager->putUri($putRequest);

        $getRequest    = new GetUriRequest($putRequest->getShortCode());
        $fetchedEntity = $manager->getUri($getRequest);

        $this->assertSame($createdEntity->getId(), $fetchedEntity->getId());
    }

    public function testGetGuaranteedUriWillReturnTheDefaultUriIfNoEntryIsFound(): void
    {
        $getRequest = new GetUriRequest('NoNoCode');

        $repository = $this->entityManager->getRepository(Uri::class);
        $manager    = new UriManager($repository);

        /** @var Uri $uri */
        $uri = $manager->getGuaranteedUri($getRequest);

        $this->assertSame('/', $uri->getOriginalUrl());
    }

    public function testGetGuaranteedUriWillReturnAnExpectedUri(): void
    {
        $repository = $this->entityManager->getRepository(Uri::class);

        $putRequest    = new PutUriRequest('www.foo.com');
        $manager       = new UriManager($repository);
        $createdEntity = $manager->putUri($putRequest);

        $getRequest    = new GetUriRequest($putRequest->getShortCode());
        /** @var Uri $uri */
        $databaseUri = $manager->getGuaranteedUri($getRequest);

        $this->assertSame($createdEntity->getShortCode(), $databaseUri->getShortCode());
        $this->assertSame($createdEntity->getOriginalUrl(), $databaseUri->getOriginalUrl());
    }

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
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
