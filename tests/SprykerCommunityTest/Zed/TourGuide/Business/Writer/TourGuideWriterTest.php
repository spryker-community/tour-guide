<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerCommunityTest\Zed\TourGuide\Business\Writer;

use Generated\Shared\Transfer\TourGuideStepTransfer;
use Generated\Shared\Transfer\TourGuideTransfer;
use PHPUnit\Framework\TestCase;
use SprykerCommunity\Zed\TourGuide\Business\Sanitizer\TourGuideSanitizerInterface;
use SprykerCommunity\Zed\TourGuide\Business\Writer\TourGuideWriter;
use SprykerCommunity\Zed\TourGuide\Persistence\TourGuideEntityManagerInterface;
use SprykerCommunity\Zed\TourGuide\Persistence\TourGuideRepositoryInterface;

final class TourGuideWriterTest extends TestCase
{
    /**
     * @return void
     */
    public function testCreateTourGuideStepSavesStepUsingEntityManager(): void
    {
        // Arrange
        $tourGuideStepTransfer = new TourGuideStepTransfer();
        $expectedTourGuideStepTransfer = new TourGuideStepTransfer();

        $tourGuideEntityManagerMock = $this->createMock(TourGuideEntityManagerInterface::class);
        $tourGuideEntityManagerMock->expects($this->once())
            ->method('saveTourGuideStep')
            ->with($tourGuideStepTransfer)
            ->willReturn($expectedTourGuideStepTransfer);

        $tourGuideRepositoryMock = $this->createMock(TourGuideRepositoryInterface::class);

        $tourGuideSanitizerMock = $this->createMock(TourGuideSanitizerInterface::class);
        $tourGuideSanitizerMock->expects($this->once())
            ->method('sanitizeTourGuideStepTransfer')
            ->with($tourGuideStepTransfer)
            ->willReturn($tourGuideStepTransfer);

        $tourGuideWriter = new TourGuideWriter(
            $tourGuideEntityManagerMock,
            $tourGuideRepositoryMock,
            $tourGuideSanitizerMock,
        );

        // Act
        $actualTourGuideStepTransfer = $tourGuideWriter->createTourGuideStep($tourGuideStepTransfer);

        // Assert
        $this->assertSame($expectedTourGuideStepTransfer, $actualTourGuideStepTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateTourGuideStepSavesExistingStepUsingEntityManager(): void
    {
        // Arrange
        $idTourGuideStep = 1;
        $tourGuideStepTransfer = (new TourGuideStepTransfer())->setIdTourGuideStep($idTourGuideStep);
        $existingTourGuideStepTransfer = new TourGuideStepTransfer();
        $expectedTourGuideStepTransfer = new TourGuideStepTransfer();

        $tourGuideRepositoryMock = $this->createMock(TourGuideRepositoryInterface::class);
        $tourGuideRepositoryMock->expects($this->once())
            ->method('findTourGuideStepById')
            ->with($idTourGuideStep)
            ->willReturn($existingTourGuideStepTransfer);

        $tourGuideEntityManagerMock = $this->createMock(TourGuideEntityManagerInterface::class);
        $tourGuideEntityManagerMock->expects($this->once())
            ->method('saveTourGuideStep')
            ->with($tourGuideStepTransfer)
            ->willReturn($expectedTourGuideStepTransfer);

        $tourGuideSanitizerMock = $this->createMock(TourGuideSanitizerInterface::class);
        $tourGuideSanitizerMock->expects($this->once())
            ->method('sanitizeTourGuideStepTransfer')
            ->with($tourGuideStepTransfer)
            ->willReturn($tourGuideStepTransfer);

        $tourGuideWriter = new TourGuideWriter(
            $tourGuideEntityManagerMock,
            $tourGuideRepositoryMock,
            $tourGuideSanitizerMock,
        );

        // Act
        $actualTourGuideStepTransfer = $tourGuideWriter->updateTourGuideStep($tourGuideStepTransfer);

        // Assert
        $this->assertSame($expectedTourGuideStepTransfer, $actualTourGuideStepTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateTourGuideStepCreatesNewStepWhenNotFound(): void
    {
        // Arrange
        $idTourGuideStep = 999;
        $tourGuideStepTransfer = (new TourGuideStepTransfer())->setIdTourGuideStep($idTourGuideStep);
        $expectedTourGuideStepTransfer = new TourGuideStepTransfer();

        $tourGuideRepositoryMock = $this->createMock(TourGuideRepositoryInterface::class);
        $tourGuideRepositoryMock->expects($this->once())
            ->method('findTourGuideStepById')
            ->with($idTourGuideStep)
            ->willReturn(null);

        $tourGuideEntityManagerMock = $this->createMock(TourGuideEntityManagerInterface::class);
        $tourGuideEntityManagerMock->expects($this->once())
            ->method('saveTourGuideStep')
            ->with($tourGuideStepTransfer)
            ->willReturn($expectedTourGuideStepTransfer);

        $tourGuideSanitizerMock = $this->createMock(TourGuideSanitizerInterface::class);
        $tourGuideSanitizerMock->expects($this->once())
            ->method('sanitizeTourGuideStepTransfer')
            ->with($tourGuideStepTransfer)
            ->willReturn($tourGuideStepTransfer);

        $tourGuideWriter = new TourGuideWriter(
            $tourGuideEntityManagerMock,
            $tourGuideRepositoryMock,
            $tourGuideSanitizerMock,
        );

        // Act
        $actualTourGuideStepTransfer = $tourGuideWriter->updateTourGuideStep($tourGuideStepTransfer);

        // Assert
        $this->assertSame($expectedTourGuideStepTransfer, $actualTourGuideStepTransfer);
    }

    /**
     * @return void
     */
    public function testDeleteTourGuideStepDelegatesCallToEntityManager(): void
    {
        // Arrange
        $idTourGuideStep = 1;
        $expectedResult = true;

        $tourGuideEntityManagerMock = $this->createMock(TourGuideEntityManagerInterface::class);
        $tourGuideEntityManagerMock->expects($this->once())
            ->method('deleteTourGuideStep')
            ->with($idTourGuideStep)
            ->willReturn($expectedResult);

        $tourGuideRepositoryMock = $this->createMock(TourGuideRepositoryInterface::class);

        $tourGuideSanitizerMock = $this->createMock(TourGuideSanitizerInterface::class);

        $tourGuideWriter = new TourGuideWriter(
            $tourGuideEntityManagerMock,
            $tourGuideRepositoryMock,
            $tourGuideSanitizerMock,
        );

        // Act
        $actualResult = $tourGuideWriter->deleteTourGuideStep($idTourGuideStep);

        // Assert
        $this->assertSame($expectedResult, $actualResult);
    }

    /**
     * @return void
     */
    public function testCreateTourGuideSavesGuideUsingEntityManager(): void
    {
        // Arrange
        $tourGuideTransfer = new TourGuideTransfer();
        $expectedTourGuideTransfer = new TourGuideTransfer();

        $tourGuideEntityManagerMock = $this->createMock(TourGuideEntityManagerInterface::class);
        $tourGuideEntityManagerMock->expects($this->once())
            ->method('saveTourGuide')
            ->with($tourGuideTransfer)
            ->willReturn($expectedTourGuideTransfer);

        $tourGuideRepositoryMock = $this->createMock(TourGuideRepositoryInterface::class);

        $tourGuideSanitizerMock = $this->createMock(TourGuideSanitizerInterface::class);

        $tourGuideWriter = new TourGuideWriter(
            $tourGuideEntityManagerMock,
            $tourGuideRepositoryMock,
            $tourGuideSanitizerMock,
        );

        // Act
        $actualTourGuideTransfer = $tourGuideWriter->createTourGuide($tourGuideTransfer);

        // Assert
        $this->assertSame($expectedTourGuideTransfer, $actualTourGuideTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateTourGuideSavesExistingGuideUsingEntityManager(): void
    {
        // Arrange
        $idTourGuide = 1;
        $tourGuideTransfer = (new TourGuideTransfer())->setIdTourGuide($idTourGuide);
        $existingTourGuideTransfer = new TourGuideTransfer();
        $expectedTourGuideTransfer = new TourGuideTransfer();

        $tourGuideRepositoryMock = $this->createMock(TourGuideRepositoryInterface::class);
        $tourGuideRepositoryMock->expects($this->once())
            ->method('findTourGuideById')
            ->with($idTourGuide)
            ->willReturn($existingTourGuideTransfer);

        $tourGuideEntityManagerMock = $this->createMock(TourGuideEntityManagerInterface::class);
        $tourGuideEntityManagerMock->expects($this->once())
            ->method('saveTourGuide')
            ->with($tourGuideTransfer)
            ->willReturn($expectedTourGuideTransfer);

        $tourGuideSanitizerMock = $this->createMock(TourGuideSanitizerInterface::class);

        $tourGuideWriter = new TourGuideWriter(
            $tourGuideEntityManagerMock,
            $tourGuideRepositoryMock,
            $tourGuideSanitizerMock,
        );

        // Act
        $actualTourGuideTransfer = $tourGuideWriter->updateTourGuide($tourGuideTransfer);

        // Assert
        $this->assertSame($expectedTourGuideTransfer, $actualTourGuideTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateTourGuideCreatesNewGuideWhenNotFound(): void
    {
        // Arrange
        $idTourGuide = 999;
        $tourGuideTransfer = (new TourGuideTransfer())->setIdTourGuide($idTourGuide);
        $expectedTourGuideTransfer = new TourGuideTransfer();

        $tourGuideRepositoryMock = $this->createMock(TourGuideRepositoryInterface::class);
        $tourGuideRepositoryMock->expects($this->once())
            ->method('findTourGuideById')
            ->with($idTourGuide)
            ->willReturn(null);

        $tourGuideEntityManagerMock = $this->createMock(TourGuideEntityManagerInterface::class);
        $tourGuideEntityManagerMock->expects($this->once())
            ->method('saveTourGuide')
            ->with($tourGuideTransfer)
            ->willReturn($expectedTourGuideTransfer);

        $tourGuideSanitizerMock = $this->createMock(TourGuideSanitizerInterface::class);

        $tourGuideWriter = new TourGuideWriter(
            $tourGuideEntityManagerMock,
            $tourGuideRepositoryMock,
            $tourGuideSanitizerMock,
        );

        // Act
        $actualTourGuideTransfer = $tourGuideWriter->updateTourGuide($tourGuideTransfer);

        // Assert
        $this->assertSame($expectedTourGuideTransfer, $actualTourGuideTransfer);
    }

    /**
     * @return void
     */
    public function testDeleteTourGuideDelegatesCallToEntityManager(): void
    {
        // Arrange
        $idTourGuide = 1;
        $expectedResult = true;

        $tourGuideEntityManagerMock = $this->createMock(TourGuideEntityManagerInterface::class);
        $tourGuideEntityManagerMock->expects($this->once())
            ->method('deleteTourGuide')
            ->with($idTourGuide)
            ->willReturn($expectedResult);

        $tourGuideRepositoryMock = $this->createMock(TourGuideRepositoryInterface::class);

        $tourGuideSanitizerMock = $this->createMock(TourGuideSanitizerInterface::class);

        $tourGuideWriter = new TourGuideWriter(
            $tourGuideEntityManagerMock,
            $tourGuideRepositoryMock,
            $tourGuideSanitizerMock,
        );

        // Act
        $actualResult = $tourGuideWriter->deleteTourGuide($idTourGuide);

        // Assert
        $this->assertSame($expectedResult, $actualResult);
    }

    /**
     * @return void
     */
    public function testCreateTourGuideStepSanitizesInputToPreventXssAttacks(): void
    {
        // Arrange
        $maliciousTitle = '<script>alert("XSS Title")</script>';
        $maliciousText = '<script>alert("XSS Text")</script>';
        $maliciousCssSelector = '<script>alert("XSS Selector")</script>';

        $sanitizedTitle = '&lt;script&gt;alert(&quot;XSS Title&quot;)&lt;/script&gt;';
        $sanitizedText = '&lt;script&gt;alert(&quot;XSS Text&quot;)&lt;/script&gt;';
        $sanitizedCssSelector = '&lt;script&gt;alert(&quot;XSS Selector&quot;)&lt;/script&gt;';

        $tourGuideStepTransfer = (new TourGuideStepTransfer())
            ->setTitle($maliciousTitle)
            ->setText($maliciousText)
            ->setAttachToElement($maliciousCssSelector);

        $sanitizedTourGuideStepTransfer = (new TourGuideStepTransfer())
            ->setTitle($sanitizedTitle)
            ->setText($sanitizedText)
            ->setAttachToElement($sanitizedCssSelector);

        $tourGuideEntityManagerMock = $this->createMock(TourGuideEntityManagerInterface::class);
        $tourGuideEntityManagerMock->expects($this->once())
            ->method('saveTourGuideStep')
            ->willReturnCallback(function (TourGuideStepTransfer $transfer) use ($sanitizedTourGuideStepTransfer) {
                $this->assertEquals($sanitizedTourGuideStepTransfer->getTitle(), $transfer->getTitle());
                $this->assertEquals($sanitizedTourGuideStepTransfer->getText(), $transfer->getText());
                $this->assertEquals($sanitizedTourGuideStepTransfer->getAttachToElement(), $transfer->getAttachToElement());
                return $transfer;
            });

        $tourGuideRepositoryMock = $this->createMock(TourGuideRepositoryInterface::class);

        $tourGuideSanitizerMock = $this->createMock(TourGuideSanitizerInterface::class);
        $tourGuideSanitizerMock->expects($this->once())
            ->method('sanitizeTourGuideStepTransfer')
            ->with($tourGuideStepTransfer)
            ->willReturn($sanitizedTourGuideStepTransfer);

        $tourGuideWriter = new TourGuideWriter(
            $tourGuideEntityManagerMock,
            $tourGuideRepositoryMock,
            $tourGuideSanitizerMock,
        );

        // Act
        $actualTourGuideStepTransfer = $tourGuideWriter->createTourGuideStep($tourGuideStepTransfer);

        // Assert
        $this->assertEquals($sanitizedTitle, $actualTourGuideStepTransfer->getTitle());
        $this->assertEquals($sanitizedText, $actualTourGuideStepTransfer->getText());
        $this->assertEquals($sanitizedCssSelector, $actualTourGuideStepTransfer->getAttachToElement());
    }
}
