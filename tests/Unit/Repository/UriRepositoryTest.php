<?php

namespace App\Tests\Unit\Repository;

use App\Entity\Uri;
use App\Repository\UriRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Exception\ORMException;
use PHPUnit\Framework\TestCase;

class UriRepositoryTest extends TestCase
{
    /**
     * @var ManagerRegistry
     */
    public $managerRegistryMock;

    /**
     * @var ManagerRegistry
     */
    public $managerRegistryBrokenMock;

    public function testUriRepositoryWillReturnTheExpectedSuccessOnSave(): void
    {

        $repository = new UriRepository($this->managerRegistryMock);

        $uri = new Uri();
        $uri->setOriginalUrl('http://www.blabla.com');
        $uri->setUrlHash('a078b1f4bc1ec3defc681e5f3fc89c7b71a65369');
        $uri->setShortCode('a078b1f4');

        $expected = $repository->saveUri($uri);

        $this->assertTrue($expected);
    }


    public function testUriRepositoryWillReturnTheExpectedSuccessOnDelete(): void
    {
        $repository = new UriRepository($this->managerRegistryMock);

        $uri = new Uri();
        $uri->setOriginalUrl('http://www.blabla.com');
        $uri->setUrlHash('a078b1f4bc1ec3defc681e5f3fc89c7b71a65369');
        $uri->setShortCode('a078b1f4');

        $expected = $repository->deleteUri($uri);

        $this->assertTrue($expected);
    }

    public function testUriRepositoryWillReturnTheExpectedFailureOnSave(): void
    {

        $repository = new UriRepository($this->managerRegistryBrokenMock);

        $uri = new Uri();
        $uri->setOriginalUrl('http://www.blabla.com');
        $uri->setUrlHash('a078b1f4bc1ec3defc681e5f3fc89c7b71a65369');
        $uri->setShortCode('a078b1f4');

        $expected = $repository->saveUri($uri);

        $this->assertFalse($expected);
    }

    public function testUriRepositoryWillReturnTheExpectedFailureOnDelete(): void
    {
        $repository = new UriRepository($this->managerRegistryBrokenMock);

        $uri = new Uri();
        $uri->setOriginalUrl('http://www.blabla.com');
        $uri->setUrlHash('a078b1f4bc1ec3defc681e5f3fc89c7b71a65369');
        $uri->setShortCode('a078b1f4');

        $expected = $repository->deleteUri($uri);

        $this->assertFalse($expected);
    }

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        $metadataMock = $this->createMock(ClassMetadata::class);

        $entityManagerMock = $this->createMock(EntityManager::class);
        $entityManagerMock->expects($this->any())
                          ->method('getClassMetadata')
                          ->willReturn($metadataMock);

        $this->managerRegistryMock = $this->createMock(ManagerRegistry::class);
        $this->managerRegistryMock->expects($this->any())
                                  ->method('getManagerForClass')
                                  ->willReturn($entityManagerMock);

        $entityManagerBrokenMock = $this->createMock(EntityManager::class);
        $entityManagerBrokenMock->expects($this->any())
                                ->method('getClassMetadata')
                                ->willReturn($metadataMock);

        $entityManagerBrokenMock->expects($this->any())
                                ->method('flush')
                                ->willThrowException(new ORMException('Kwaboom'));

        $entityManagerBrokenMock->expects($this->any())
                                ->method('persist')
                                ->willThrowException(new ORMException('Kwaboom'));

        $this->managerRegistryBrokenMock = $this->createMock(ManagerRegistry::class);

        $this->managerRegistryBrokenMock->expects($this->any())
                                        ->method('getManagerForClass')
                                        ->willReturn($entityManagerBrokenMock);
    }
}
