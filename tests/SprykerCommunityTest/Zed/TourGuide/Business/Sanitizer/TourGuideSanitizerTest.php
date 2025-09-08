<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerCommunityTest\Zed\TourGuide\Business\Sanitizer;

use Generated\Shared\Transfer\TourGuideStepTransfer;
use PHPUnit\Framework\TestCase;
use SprykerCommunity\Zed\TourGuide\Business\Sanitizer\TourGuideSanitizer;

final class TourGuideSanitizerTest extends TestCase
{
    /**
     * @return void
     */
    public function testSanitizeTourGuideStepTransferSanitizesTitle(): void
    {
        // Arrange
        $maliciousTitle = '<script>alert("XSS Title")</script>';
        $expectedSanitizedTitle = '&lt;script&gt;alert(&quot;XSS Title&quot;)&lt;/script&gt;';

        $tourGuideStepTransfer = new TourGuideStepTransfer();
        $tourGuideStepTransfer->setTitle($maliciousTitle);

        $tourGuideSanitizer = new TourGuideSanitizer();

        // Act
        $sanitizedTourGuideStepTransfer = $tourGuideSanitizer->sanitizeTourGuideStepTransfer($tourGuideStepTransfer);

        // Assert
        $this->assertEquals($expectedSanitizedTitle, $sanitizedTourGuideStepTransfer->getTitle());
    }

    /**
     * @return void
     */
    public function testSanitizeTourGuideStepTransferSanitizesText(): void
    {
        // Arrange
        $maliciousText = '<script>alert("XSS Text")</script>';
        $expectedSanitizedText = '&lt;script&gt;alert(&quot;XSS Text&quot;)&lt;/script&gt;';

        $tourGuideStepTransfer = new TourGuideStepTransfer();
        $tourGuideStepTransfer->setText($maliciousText);

        $tourGuideSanitizer = new TourGuideSanitizer();

        // Act
        $sanitizedTourGuideStepTransfer = $tourGuideSanitizer->sanitizeTourGuideStepTransfer($tourGuideStepTransfer);

        // Assert
        $this->assertEquals($expectedSanitizedText, $sanitizedTourGuideStepTransfer->getText());
    }

    /**
     * @return void
     */
    public function testSanitizeTourGuideStepTransferSanitizesAttachToElement(): void
    {
        // Arrange
        $maliciousAttachToElement = '<script>alert("XSS Selector")</script>';
        $expectedSanitizedAttachToElement = '&lt;script&gt;alert(&quot;XSS Selector&quot;)&lt;/script&gt;';

        $tourGuideStepTransfer = new TourGuideStepTransfer();
        $tourGuideStepTransfer->setAttachToElement($maliciousAttachToElement);

        $tourGuideSanitizer = new TourGuideSanitizer();

        // Act
        $sanitizedTourGuideStepTransfer = $tourGuideSanitizer->sanitizeTourGuideStepTransfer($tourGuideStepTransfer);

        // Assert
        $this->assertEquals($expectedSanitizedAttachToElement, $sanitizedTourGuideStepTransfer->getAttachToElement());
    }

    /**
     * @return void
     */
    public function testSanitizeTourGuideStepTransferHandlesNullValues(): void
    {
        // Arrange
        $tourGuideStepTransfer = new TourGuideStepTransfer();
        // Not setting any values, they will be null

        $tourGuideSanitizer = new TourGuideSanitizer();

        // Act
        $sanitizedTourGuideStepTransfer = $tourGuideSanitizer->sanitizeTourGuideStepTransfer($tourGuideStepTransfer);

        // Assert
        $this->assertNull($sanitizedTourGuideStepTransfer->getTitle());
        $this->assertNull($sanitizedTourGuideStepTransfer->getText());
        $this->assertNull($sanitizedTourGuideStepTransfer->getAttachToElement());
    }

    /**
     * @return void
     */
    public function testSanitizeTourGuideStepTransferSanitizesAllFields(): void
    {
        // Arrange
        $maliciousTitle = '<script>alert("XSS Title")</script>';
        $maliciousText = '<script>alert("XSS Text")</script>';
        $maliciousAttachToElement = '<script>alert("XSS Selector")</script>';

        $expectedSanitizedTitle = '&lt;script&gt;alert(&quot;XSS Title&quot;)&lt;/script&gt;';
        $expectedSanitizedText = '&lt;script&gt;alert(&quot;XSS Text&quot;)&lt;/script&gt;';
        $expectedSanitizedAttachToElement = '&lt;script&gt;alert(&quot;XSS Selector&quot;)&lt;/script&gt;';

        $tourGuideStepTransfer = new TourGuideStepTransfer();
        $tourGuideStepTransfer
            ->setTitle($maliciousTitle)
            ->setText($maliciousText)
            ->setAttachToElement($maliciousAttachToElement);

        $tourGuideSanitizer = new TourGuideSanitizer();

        // Act
        $sanitizedTourGuideStepTransfer = $tourGuideSanitizer->sanitizeTourGuideStepTransfer($tourGuideStepTransfer);

        // Assert
        $this->assertEquals($expectedSanitizedTitle, $sanitizedTourGuideStepTransfer->getTitle());
        $this->assertEquals($expectedSanitizedText, $sanitizedTourGuideStepTransfer->getText());
        $this->assertEquals($expectedSanitizedAttachToElement, $sanitizedTourGuideStepTransfer->getAttachToElement());
    }
}
