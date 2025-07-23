<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerCommunityTest\Zed\TourGuide\Business\Reader;

use Generated\Shared\Transfer\TourGuideCollectionTransfer;
use Generated\Shared\Transfer\TourGuideCriteriaTransfer;
use Generated\Shared\Transfer\TourGuideStepCollectionTransfer;
use Generated\Shared\Transfer\TourGuideStepCriteriaTransfer;
use Generated\Shared\Transfer\TourGuideStepTransfer;
use Generated\Shared\Transfer\TourGuideTransfer;
use PHPUnit\Framework\TestCase;
use SprykerCommunity\Zed\TourGuide\Business\Reader\TourGuideReader;
use SprykerCommunity\Zed\TourGuide\Persistence\TourGuideRepositoryInterface;

final class TourGuideReaderTest extends TestCase
{
    /**
     * @return void
     */
    public function testGetTourGuideCollectionReturnsCollectionFromRepository(): void
    {
        // Arrange
        $tourGuideCriteriaTransfer = new TourGuideCriteriaTransfer();
        $expectedCollection = new TourGuideCollectionTransfer();

        $tourGuideRepositoryMock = $this->createMock(TourGuideRepositoryInterface::class);
        $tourGuideRepositoryMock->expects($this->once())
            ->method('getTourGuideCollection')
            ->with($tourGuideCriteriaTransfer)
            ->willReturn($expectedCollection);

        $tourGuideReader = new TourGuideReader($tourGuideRepositoryMock);

        // Act
        $actualCollection = $tourGuideReader->getTourGuideCollection($tourGuideCriteriaTransfer);

        // Assert
        $this->assertSame($expectedCollection, $actualCollection);
    }

    /**
     * @return void
     */
    public function testFindTourGuideByIdReturnsTransferFromRepository(): void
    {
        // Arrange
        $idTourGuide = 1;
        $expectedTransfer = new TourGuideTransfer();

        $tourGuideRepositoryMock = $this->createMock(TourGuideRepositoryInterface::class);
        $tourGuideRepositoryMock->expects($this->once())
            ->method('findTourGuideById')
            ->with($idTourGuide)
            ->willReturn($expectedTransfer);

        $tourGuideReader = new TourGuideReader($tourGuideRepositoryMock);

        // Act
        $actualTransfer = $tourGuideReader->findTourGuideById($idTourGuide);

        // Assert
        $this->assertSame($expectedTransfer, $actualTransfer);
    }

    /**
     * @return void
     */
    public function testFindTourGuideByIdReturnsNullWhenNotFound(): void
    {
        // Arrange
        $idTourGuide = 999;

        $tourGuideRepositoryMock = $this->createMock(TourGuideRepositoryInterface::class);
        $tourGuideRepositoryMock->expects($this->once())
            ->method('findTourGuideById')
            ->with($idTourGuide)
            ->willReturn(null);

        $tourGuideReader = new TourGuideReader($tourGuideRepositoryMock);

        // Act
        $actualTransfer = $tourGuideReader->findTourGuideById($idTourGuide);

        // Assert
        $this->assertNull($actualTransfer);
    }

    /**
     * @return void
     */
    public function testFindTourGuideByRouteReturnsTransferFromRepository(): void
    {
        // Arrange
        $route = '/test-route';
        $expectedTransfer = new TourGuideTransfer();

        $tourGuideRepositoryMock = $this->createMock(TourGuideRepositoryInterface::class);
        $tourGuideRepositoryMock->expects($this->once())
            ->method('findTourGuideByRoute')
            ->with($route)
            ->willReturn($expectedTransfer);

        $tourGuideReader = new TourGuideReader($tourGuideRepositoryMock);

        // Act
        $actualTransfer = $tourGuideReader->findTourGuideByRoute($route);

        // Assert
        $this->assertSame($expectedTransfer, $actualTransfer);
    }

    /**
     * @return void
     */
    public function testFindTourGuideByRouteReturnsNullWhenNotFound(): void
    {
        // Arrange
        $route = '/non-existent-route';

        $tourGuideRepositoryMock = $this->createMock(TourGuideRepositoryInterface::class);
        $tourGuideRepositoryMock->expects($this->once())
            ->method('findTourGuideByRoute')
            ->with($route)
            ->willReturn(null);

        $tourGuideReader = new TourGuideReader($tourGuideRepositoryMock);

        // Act
        $actualTransfer = $tourGuideReader->findTourGuideByRoute($route);

        // Assert
        $this->assertNull($actualTransfer);
    }

    /**
     * @return void
     */
    public function testGetTourGuideStepCollectionReturnsCollectionFromRepository(): void
    {
        // Arrange
        $tourGuideStepCriteriaTransfer = new TourGuideStepCriteriaTransfer();
        $expectedCollection = new TourGuideStepCollectionTransfer();

        $tourGuideRepositoryMock = $this->createMock(TourGuideRepositoryInterface::class);
        $tourGuideRepositoryMock->expects($this->once())
            ->method('getTourGuideStepCollection')
            ->with($tourGuideStepCriteriaTransfer)
            ->willReturn($expectedCollection);

        $tourGuideReader = new TourGuideReader($tourGuideRepositoryMock);

        // Act
        $actualCollection = $tourGuideReader->getTourGuideStepCollection($tourGuideStepCriteriaTransfer);

        // Assert
        $this->assertSame($expectedCollection, $actualCollection);
    }

    /**
     * @return void
     */
    public function testFindTourGuideStepByIdReturnsTransferFromRepository(): void
    {
        // Arrange
        $idTourGuideStep = 1;
        $expectedTransfer = new TourGuideStepTransfer();

        $tourGuideRepositoryMock = $this->createMock(TourGuideRepositoryInterface::class);
        $tourGuideRepositoryMock->expects($this->once())
            ->method('findTourGuideStepById')
            ->with($idTourGuideStep)
            ->willReturn($expectedTransfer);

        $tourGuideReader = new TourGuideReader($tourGuideRepositoryMock);

        // Act
        $actualTransfer = $tourGuideReader->findTourGuideStepById($idTourGuideStep);

        // Assert
        $this->assertSame($expectedTransfer, $actualTransfer);
    }

    /**
     * @return void
     */
    public function testFindTourGuideStepByIdReturnsNullWhenNotFound(): void
    {
        // Arrange
        $idTourGuideStep = 999;

        $tourGuideRepositoryMock = $this->createMock(TourGuideRepositoryInterface::class);
        $tourGuideRepositoryMock->expects($this->once())
            ->method('findTourGuideStepById')
            ->with($idTourGuideStep)
            ->willReturn(null);

        $tourGuideReader = new TourGuideReader($tourGuideRepositoryMock);

        // Act
        $actualTransfer = $tourGuideReader->findTourGuideStepById($idTourGuideStep);

        // Assert
        $this->assertNull($actualTransfer);
    }

    /**
     * @return void
     */
    public function testGetTourGuideStepsByRouteReturnsCollectionFromRepository(): void
    {
        // Arrange
        $route = '/test-route';
        $expectedCollection = new TourGuideStepCollectionTransfer();

        $tourGuideRepositoryMock = $this->createMock(TourGuideRepositoryInterface::class);
        $tourGuideRepositoryMock->expects($this->once())
            ->method('getTourGuideStepsByRoute')
            ->with($route)
            ->willReturn($expectedCollection);

        $tourGuideReader = new TourGuideReader($tourGuideRepositoryMock);

        // Act
        $actualCollection = $tourGuideReader->getTourGuideStepsByRoute($route);

        // Assert
        $this->assertSame($expectedCollection, $actualCollection);
    }

    /**
     * @return void
     */
    public function testGetTourGuideStepsByTourGuideIdReturnsCollectionFromRepository(): void
    {
        // Arrange
        $idTourGuide = 1;
        $expectedCollection = new TourGuideStepCollectionTransfer();

        $tourGuideRepositoryMock = $this->createMock(TourGuideRepositoryInterface::class);
        $tourGuideRepositoryMock->expects($this->once())
            ->method('getTourGuideStepsByTourGuideId')
            ->with($idTourGuide)
            ->willReturn($expectedCollection);

        $tourGuideReader = new TourGuideReader($tourGuideRepositoryMock);

        // Act
        $actualCollection = $tourGuideReader->getTourGuideStepsByTourGuideId($idTourGuide);

        // Assert
        $this->assertSame($expectedCollection, $actualCollection);
    }
}
