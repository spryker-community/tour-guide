<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerCommunityTest\Zed\TourGuide\Business;

use Generated\Shared\Transfer\TourGuideCollectionTransfer;
use Generated\Shared\Transfer\TourGuideCriteriaTransfer;
use Generated\Shared\Transfer\TourGuideStepCollectionTransfer;
use Generated\Shared\Transfer\TourGuideStepTransfer;
use Generated\Shared\Transfer\TourGuideTransfer;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;
use SprykerCommunity\Zed\TourGuide\Business\Reader\TourGuideReader;
use SprykerCommunity\Zed\TourGuide\Business\TourGuideFacade;
use SprykerCommunity\Zed\TourGuide\Business\Writer\TourGuideWriter;
use SprykerCommunity\Zed\TourGuide\Persistence\TourGuideEntityManagerInterface;
use SprykerCommunity\Zed\TourGuide\Persistence\TourGuideRepositoryInterface;

/**
 * Integration tests for {@link \SprykerCommunity\Zed\TourGuide\Business\TourGuideFacade}.
 * This test uses the real facade methods but mocks the repository and entity manager.
 * It demonstrates how to test the facade's integration with its business layer components
 * while isolating the persistence layer.
 *
 * @group SprykerCommunityTest
 * @group Zed
 * @group TourGuide
 * @group Business
 * @group Facade
 * @group TourGuideFacadeIntegrationTest
 */
final class TourGuideFacadeIntegrationTest extends TestCase
{
    /**
     * @return void
     */
    public function testCreateAndFindTourGuide(): void
    {
        // Arrange
        $tourGuideTransfer = new TourGuideTransfer();
        $tourGuideTransfer->setRoute('/test/route');
        $tourGuideTransfer->setIsActive(true);

        $createdTourGuideTransfer = clone $tourGuideTransfer;
        $createdTourGuideTransfer->setIdTourGuide(1);

        // Create repository mock
        $repositoryMock = $this->createMock(TourGuideRepositoryInterface::class);

        // Configure findTourGuideById method on repository
        $repositoryMock->expects($this->once())
            ->method('findTourGuideById')
            ->with($this->equalTo(1))
            ->willReturn($createdTourGuideTransfer);

        // Create entity manager mock
        $entityManagerMock = $this->createMock(TourGuideEntityManagerInterface::class);

        // Configure saveTourGuide method on entity manager
        $entityManagerMock->expects($this->once())
            ->method('saveTourGuide')
            ->with($this->equalTo($tourGuideTransfer))
            ->willReturn($createdTourGuideTransfer);

        // Create reader with mocked repository
        $tourGuideReader = new TourGuideReader($repositoryMock);

        // Create writer with mocked entity manager and repository
        $tourGuideWriter = new TourGuideWriter($entityManagerMock, $repositoryMock);

        // Create real facade
        $tourGuideFacade = new TourGuideFacade();

        // Use reflection to replace the factory with our custom implementation
        $factoryProperty = new ReflectionProperty(TourGuideFacade::class, 'factory');
        $factoryProperty->setAccessible(true);
        $factoryProperty->setValue($tourGuideFacade, new class ($tourGuideReader, $tourGuideWriter) {
            private $reader;

            private $writer;

            public function __construct($reader, $writer)
            {
                $this->reader = $reader;
                $this->writer = $writer;
            }

            public function createTourGuideReader()
            {
                return $this->reader;
            }

            public function createTourGuideWriter()
            {
                return $this->writer;
            }
        });

        // Act
        $actualCreatedTourGuideTransfer = $tourGuideFacade->createTourGuide($tourGuideTransfer);
        $actualFoundTourGuideTransfer = $tourGuideFacade->findTourGuideById($actualCreatedTourGuideTransfer->getIdTourGuide());

        // Assert
        $this->assertNotNull($actualCreatedTourGuideTransfer->getIdTourGuide());
        $this->assertNotNull($actualFoundTourGuideTransfer);
        $this->assertSame($actualCreatedTourGuideTransfer->getIdTourGuide(), $actualFoundTourGuideTransfer->getIdTourGuide());
        $this->assertSame('/test/route', $actualFoundTourGuideTransfer->getRoute());
        $this->assertTrue($actualFoundTourGuideTransfer->getIsActive());
    }

    /**
     * @return void
     */
    public function testGetTourGuideCollection(): void
    {
        // Arrange
        $tourGuideCriteriaTransfer = new TourGuideCriteriaTransfer();

        $tourGuideTransfer = new TourGuideTransfer();
        $tourGuideTransfer->setIdTourGuide(1);
        $tourGuideTransfer->setRoute('/test/route-collection');
        $tourGuideTransfer->setIsActive(true);

        $tourGuideCollectionTransfer = new TourGuideCollectionTransfer();
        $tourGuideCollectionTransfer->addTourGuide($tourGuideTransfer);

        // Create repository mock
        $repositoryMock = $this->createMock(TourGuideRepositoryInterface::class);

        // Configure getTourGuideCollection method on repository
        $repositoryMock->expects($this->once())
            ->method('getTourGuideCollection')
            ->with($this->equalTo($tourGuideCriteriaTransfer))
            ->willReturn($tourGuideCollectionTransfer);

        // Create entity manager mock
        $entityManagerMock = $this->createMock(TourGuideEntityManagerInterface::class);

        // Create reader with mocked repository
        $tourGuideReader = new TourGuideReader($repositoryMock);

        // Create writer with mocked entity manager and repository
        $tourGuideWriter = new TourGuideWriter($entityManagerMock, $repositoryMock);

        // Create real facade
        $tourGuideFacade = new TourGuideFacade();

        // Use reflection to replace the factory with our custom implementation
        $factoryProperty = new ReflectionProperty(TourGuideFacade::class, 'factory');
        $factoryProperty->setAccessible(true);
        $factoryProperty->setValue($tourGuideFacade, new class ($tourGuideReader, $tourGuideWriter) {
            private $reader;

            private $writer;

            public function __construct($reader, $writer)
            {
                $this->reader = $reader;
                $this->writer = $writer;
            }

            public function createTourGuideReader()
            {
                return $this->reader;
            }

            public function createTourGuideWriter()
            {
                return $this->writer;
            }
        });

        // Act
        $actualTourGuideCollectionTransfer = $tourGuideFacade->getTourGuideCollection($tourGuideCriteriaTransfer);

        // Assert
        $this->assertGreaterThan(0, $actualTourGuideCollectionTransfer->getTourGuides()->count());
        $this->assertSame('/test/route-collection', $actualTourGuideCollectionTransfer->getTourGuides()[0]->getRoute());
        $this->assertTrue($actualTourGuideCollectionTransfer->getTourGuides()[0]->getIsActive());
    }

    /**
     * @return void
     */
    public function testGetTourGuideStepsByRoute(): void
    {
        // Arrange
        $route = '/test/route-steps';

        $tourGuideStepTransfer = new TourGuideStepTransfer();
        $tourGuideStepTransfer->setIdTourGuideStep(1);
        $tourGuideStepTransfer->setFkTourGuide(1);
        $tourGuideStepTransfer->setTitle('Test Step By Route');
        $tourGuideStepTransfer->setText('Test Content By Route');
        $tourGuideStepTransfer->setStepIndex(1);
        $tourGuideStepTransfer->setIsActive(true);

        $tourGuideStepCollectionTransfer = new TourGuideStepCollectionTransfer();
        $tourGuideStepCollectionTransfer->addTourGuideStep($tourGuideStepTransfer);

        // Create repository mock
        $repositoryMock = $this->createMock(TourGuideRepositoryInterface::class);

        // Configure getTourGuideStepsByRoute method on repository
        $repositoryMock->expects($this->once())
            ->method('getTourGuideStepsByRoute')
            ->with($this->equalTo($route))
            ->willReturn($tourGuideStepCollectionTransfer);

        // Create entity manager mock
        $entityManagerMock = $this->createMock(TourGuideEntityManagerInterface::class);

        // Create reader with mocked repository
        $tourGuideReader = new TourGuideReader($repositoryMock);

        // Create writer with mocked entity manager and repository
        $tourGuideWriter = new TourGuideWriter($entityManagerMock, $repositoryMock);

        // Create real facade
        $tourGuideFacade = new TourGuideFacade();

        // Use reflection to replace the factory with our custom implementation
        $factoryProperty = new ReflectionProperty(TourGuideFacade::class, 'factory');
        $factoryProperty->setAccessible(true);
        $factoryProperty->setValue($tourGuideFacade, new class ($tourGuideReader, $tourGuideWriter) {
            private $reader;

            private $writer;

            public function __construct($reader, $writer)
            {
                $this->reader = $reader;
                $this->writer = $writer;
            }

            public function createTourGuideReader()
            {
                return $this->reader;
            }

            public function createTourGuideWriter()
            {
                return $this->writer;
            }
        });

        // Act
        $actualTourGuideStepCollectionTransfer = $tourGuideFacade->getTourGuideStepsByRoute($route);

        // Assert
        $this->assertGreaterThan(0, $actualTourGuideStepCollectionTransfer->getTourGuideSteps()->count());
        $this->assertSame('Test Step By Route', $actualTourGuideStepCollectionTransfer->getTourGuideSteps()[0]->getTitle());
        $this->assertSame('Test Content By Route', $actualTourGuideStepCollectionTransfer->getTourGuideSteps()[0]->getText());
        $this->assertSame(1, $actualTourGuideStepCollectionTransfer->getTourGuideSteps()[0]->getStepIndex());
    }

    /**
     * @return void
     */
    public function testCreateUpdateAndFindTourGuideStep(): void
    {
        // Arrange
        $tourGuideStepTransfer = new TourGuideStepTransfer();
        $tourGuideStepTransfer->setFkTourGuide(1);
        $tourGuideStepTransfer->setTitle('Test Step');
        $tourGuideStepTransfer->setText('Test Content');
        $tourGuideStepTransfer->setStepIndex(1);
        $tourGuideStepTransfer->setIsActive(true);

        $createdTourGuideStepTransfer = clone $tourGuideStepTransfer;
        $createdTourGuideStepTransfer->setIdTourGuideStep(1);

        $updatedTourGuideStepTransfer = clone $createdTourGuideStepTransfer;
        $updatedTourGuideStepTransfer->setTitle('Updated Step');
        $updatedTourGuideStepTransfer->setText('Updated Content');

        // Create repository mock
        $repositoryMock = $this->createMock(TourGuideRepositoryInterface::class);

        // Configure findTourGuideStepById method on repository
        $repositoryMock->method('findTourGuideStepById')
            ->willReturnCallback(function ($id) use ($createdTourGuideStepTransfer, $updatedTourGuideStepTransfer) {
                static $callCount = 0;
                if ($id === 1) {
                    return $callCount++ === 0 ? $createdTourGuideStepTransfer : $updatedTourGuideStepTransfer;
                }

                return null;
            });

        // Create entity manager mock
        $entityManagerMock = $this->createMock(TourGuideEntityManagerInterface::class);

        // Configure saveTourGuideStep method on entity manager
        $entityManagerMock->method('saveTourGuideStep')
            ->willReturnCallback(function ($transfer) use ($createdTourGuideStepTransfer, $updatedTourGuideStepTransfer) {
                if ($transfer->getIdTourGuideStep() === null) {
                    return $createdTourGuideStepTransfer;
                }
                if (
                    $transfer->getIdTourGuideStep() === 1 &&
                    $transfer->getTitle() === 'Updated Step' &&
                    $transfer->getText() === 'Updated Content'
                ) {
                    return $updatedTourGuideStepTransfer;
                }

                return null;
            });

        // Create reader with mocked repository
        $tourGuideReader = new TourGuideReader($repositoryMock);

        // Create writer with mocked entity manager and repository
        $tourGuideWriter = new TourGuideWriter($entityManagerMock, $repositoryMock);

        // Create real facade
        $tourGuideFacade = new TourGuideFacade();

        // Use reflection to replace the factory with our custom implementation
        $factoryProperty = new ReflectionProperty(TourGuideFacade::class, 'factory');
        $factoryProperty->setAccessible(true);
        $factoryProperty->setValue($tourGuideFacade, new class ($tourGuideReader, $tourGuideWriter) {
            private $reader;

            private $writer;

            public function __construct($reader, $writer)
            {
                $this->reader = $reader;
                $this->writer = $writer;
            }

            public function createTourGuideReader()
            {
                return $this->reader;
            }

            public function createTourGuideWriter()
            {
                return $this->writer;
            }
        });

        // Act - Create
        $actualCreatedTourGuideStepTransfer = $tourGuideFacade->createTourGuideStep($tourGuideStepTransfer);

        // Act - Update
        $actualCreatedTourGuideStepTransfer->setTitle('Updated Step');
        $actualCreatedTourGuideStepTransfer->setText('Updated Content');
        $actualUpdatedTourGuideStepTransfer = $tourGuideFacade->updateTourGuideStep($actualCreatedTourGuideStepTransfer);

        // Act - Find
        $actualFoundTourGuideStepTransfer = $tourGuideFacade->findTourGuideStepById($actualUpdatedTourGuideStepTransfer->getIdTourGuideStep());

        // Assert
        $this->assertNotNull($actualCreatedTourGuideStepTransfer->getIdTourGuideStep());
        $this->assertSame('Updated Step', $actualFoundTourGuideStepTransfer->getTitle());
        $this->assertSame('Updated Content', $actualFoundTourGuideStepTransfer->getText());
        $this->assertSame(1, $actualFoundTourGuideStepTransfer->getStepIndex());
        $this->assertTrue($actualFoundTourGuideStepTransfer->getIsActive());
    }

    /**
     * @return void
     */
    public function testDeleteTourGuideStep(): void
    {
        // Arrange
        $idTourGuideStep = 1;

        // Create repository mock
        $repositoryMock = $this->createMock(TourGuideRepositoryInterface::class);

        // Configure findTourGuideStepById method on repository to return null after deletion
        $repositoryMock->expects($this->once())
            ->method('findTourGuideStepById')
            ->with($this->equalTo($idTourGuideStep))
            ->willReturn(null);

        // Create entity manager mock
        $entityManagerMock = $this->createMock(TourGuideEntityManagerInterface::class);

        // Configure deleteTourGuideStep method on entity manager
        $entityManagerMock->expects($this->once())
            ->method('deleteTourGuideStep')
            ->with($this->equalTo($idTourGuideStep))
            ->willReturn(true);

        // Create reader with mocked repository
        $tourGuideReader = new TourGuideReader($repositoryMock);

        // Create writer with mocked entity manager and repository
        $tourGuideWriter = new TourGuideWriter($entityManagerMock, $repositoryMock);

        // Create real facade
        $tourGuideFacade = new TourGuideFacade();

        // Use reflection to replace the factory with our custom implementation
        $factoryProperty = new ReflectionProperty(TourGuideFacade::class, 'factory');
        $factoryProperty->setAccessible(true);
        $factoryProperty->setValue($tourGuideFacade, new class ($tourGuideReader, $tourGuideWriter) {
            private $reader;

            private $writer;

            public function __construct($reader, $writer)
            {
                $this->reader = $reader;
                $this->writer = $writer;
            }

            public function createTourGuideReader()
            {
                return $this->reader;
            }

            public function createTourGuideWriter()
            {
                return $this->writer;
            }
        });

        // Act
        $deleteResult = $tourGuideFacade->deleteTourGuideStep($idTourGuideStep);
        $deletedTourGuideStepTransfer = $tourGuideFacade->findTourGuideStepById($idTourGuideStep);

        // Assert
        $this->assertTrue($deleteResult);
        $this->assertNull($deletedTourGuideStepTransfer);
    }

    /**
     * @return void
     */
    public function testCreateUpdateAndDeleteTourGuide(): void
    {
        // Arrange
        $tourGuideTransfer = new TourGuideTransfer();
        $tourGuideTransfer->setRoute('/test/route-update');
        $tourGuideTransfer->setIsActive(true);

        $createdTourGuideTransfer = clone $tourGuideTransfer;
        $createdTourGuideTransfer->setIdTourGuide(1);

        $updatedTourGuideTransfer = clone $createdTourGuideTransfer;
        $updatedTourGuideTransfer->setIsActive(false);

        // Create repository mock
        $repositoryMock = $this->createMock(TourGuideRepositoryInterface::class);

        // Configure findTourGuideById method on repository
        $repositoryMock->method('findTourGuideById')
            ->willReturnCallback(function ($id) use ($createdTourGuideTransfer) {
                static $callCount = 0;
                if ($id === 1) {
                    return $callCount++ === 0 ? $createdTourGuideTransfer : null;
                }

                return null;
            });

        // Create entity manager mock
        $entityManagerMock = $this->createMock(TourGuideEntityManagerInterface::class);

        // Configure saveTourGuide method on entity manager
        $entityManagerMock->method('saveTourGuide')
            ->willReturnCallback(function ($transfer) use ($createdTourGuideTransfer, $updatedTourGuideTransfer) {
                if ($transfer->getIdTourGuide() === null) {
                    return $createdTourGuideTransfer;
                }
                if ($transfer->getIdTourGuide() === 1 && $transfer->getIsActive() === false) {
                    return $updatedTourGuideTransfer;
                }

                return null;
            });

        // Configure deleteTourGuide method on entity manager
        $entityManagerMock->method('deleteTourGuide')
            ->willReturnCallback(function ($id) {
                return $id === 1;
            });

        // Create reader with mocked repository
        $tourGuideReader = new TourGuideReader($repositoryMock);

        // Create writer with mocked entity manager and repository
        $tourGuideWriter = new TourGuideWriter($entityManagerMock, $repositoryMock);

        // Create real facade
        $tourGuideFacade = new TourGuideFacade();

        // Use reflection to replace the factory with our custom implementation
        $factoryProperty = new ReflectionProperty(TourGuideFacade::class, 'factory');
        $factoryProperty->setAccessible(true);
        $factoryProperty->setValue($tourGuideFacade, new class ($tourGuideReader, $tourGuideWriter) {
            private $reader;

            private $writer;

            public function __construct($reader, $writer)
            {
                $this->reader = $reader;
                $this->writer = $writer;
            }

            public function createTourGuideReader()
            {
                return $this->reader;
            }

            public function createTourGuideWriter()
            {
                return $this->writer;
            }
        });

        // Act - Create
        $actualCreatedTourGuideTransfer = $tourGuideFacade->createTourGuide($tourGuideTransfer);

        // Act - Update
        $actualCreatedTourGuideTransfer->setIsActive(false);
        $actualUpdatedTourGuideTransfer = $tourGuideFacade->updateTourGuide($actualCreatedTourGuideTransfer);

        // Act - Delete
        $deleteResult = $tourGuideFacade->deleteTourGuide($actualUpdatedTourGuideTransfer->getIdTourGuide());
        $deletedTourGuideTransfer = $tourGuideFacade->findTourGuideById($actualUpdatedTourGuideTransfer->getIdTourGuide());

        // Assert
        $this->assertNotNull($actualCreatedTourGuideTransfer->getIdTourGuide());
        $this->assertFalse($actualUpdatedTourGuideTransfer->getIsActive());
        $this->assertTrue($deleteResult);
        $this->assertNull($deletedTourGuideTransfer);
    }
}
