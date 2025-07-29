<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerCommunityTest\Zed\TourGuide\Business\Writer;

use Generated\Shared\Transfer\TourGuideEventTransfer;
use PHPUnit\Framework\TestCase;
use SprykerCommunity\Zed\TourGuide\Business\Writer\TourGuideEventWriter;
use SprykerCommunity\Zed\TourGuide\Persistence\TourGuideEntityManagerInterface;
use SprykerCommunity\Zed\TourGuide\Persistence\TourGuideRepositoryInterface;

final class TourGuideEventWriterTest extends TestCase
{
    /**
     * @return void
     */
    public function testCreateTourGuideEventSavesEventUsingEntityManager(): void
    {
        // Arrange
        $tourGuideEventTransfer = new TourGuideEventTransfer();
        $expectedTourGuideEventTransfer = new TourGuideEventTransfer();

        $tourGuideEntityManagerMock = $this->createMock(TourGuideEntityManagerInterface::class);
        $tourGuideEntityManagerMock->expects($this->once())
            ->method('saveTourGuideEvent')
            ->with($tourGuideEventTransfer)
            ->willReturn($expectedTourGuideEventTransfer);

        $tourGuideRepositoryMock = $this->createMock(TourGuideRepositoryInterface::class);

        $tourGuideEventWriter = new TourGuideEventWriter(
            $tourGuideEntityManagerMock,
            $tourGuideRepositoryMock,
        );

        // Act
        $actualTourGuideEventTransfer = $tourGuideEventWriter->createTourGuideEvent($tourGuideEventTransfer);

        // Assert
        $this->assertSame($expectedTourGuideEventTransfer, $actualTourGuideEventTransfer);
    }

    /**
     * @return void
     */
    public function testTrackTourGuideEventCreatesAndSavesEventUsingEntityManager(): void
    {
        // Arrange
        $idTourGuide = 1;
        $eventType = 'test-event';
        $tourVersion = 2;
        $expectedTourGuideEventTransfer = new TourGuideEventTransfer();

        $tourGuideEntityManagerMock = $this->createMock(TourGuideEntityManagerInterface::class);
        $tourGuideEntityManagerMock->expects($this->once())
            ->method('saveTourGuideEvent')
            ->willReturnCallback(function (TourGuideEventTransfer $tourGuideEventTransfer) use ($idTourGuide, $eventType, $tourVersion, $expectedTourGuideEventTransfer) {
                $this->assertSame($idTourGuide, $tourGuideEventTransfer->getFkTourGuide());
                $this->assertSame($eventType, $tourGuideEventTransfer->getEventType());
                $this->assertSame($tourVersion, $tourGuideEventTransfer->getTourVersion());
                return $expectedTourGuideEventTransfer;
            });

        $tourGuideRepositoryMock = $this->createMock(TourGuideRepositoryInterface::class);

        $tourGuideEventWriter = new TourGuideEventWriter(
            $tourGuideEntityManagerMock,
            $tourGuideRepositoryMock,
        );

        // Act
        $actualTourGuideEventTransfer = $tourGuideEventWriter->trackTourGuideEvent($idTourGuide, $eventType, $tourVersion);

        // Assert
        $this->assertSame($expectedTourGuideEventTransfer, $actualTourGuideEventTransfer);
    }
}
