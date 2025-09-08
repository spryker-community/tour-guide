<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerCommunity\Zed\TourGuide\Business\Sanitizer;

use Generated\Shared\Transfer\TourGuideStepTransfer;

class TourGuideSanitizer implements TourGuideSanitizerInterface
{
    /**
     * Sanitizes the TourGuideStepTransfer to prevent XSS attacks.
     *
     * @param \Generated\Shared\Transfer\TourGuideStepTransfer $tourGuideStepTransfer
     *
     * @return \Generated\Shared\Transfer\TourGuideStepTransfer
     */
    public function sanitizeTourGuideStepTransfer(TourGuideStepTransfer $tourGuideStepTransfer): TourGuideStepTransfer
    {
        if ($tourGuideStepTransfer->getTitle() !== null) {
            $tourGuideStepTransfer->setTitle(
                htmlspecialchars($tourGuideStepTransfer->getTitle(), ENT_QUOTES, 'UTF-8')
            );
        }

        if ($tourGuideStepTransfer->getText() !== null) {
            $tourGuideStepTransfer->setText(
                htmlspecialchars($tourGuideStepTransfer->getText(), ENT_QUOTES, 'UTF-8')
            );
        }

        if ($tourGuideStepTransfer->getAttachToElement() !== null) {
            $tourGuideStepTransfer->setAttachToElement(
                htmlspecialchars($tourGuideStepTransfer->getAttachToElement(), ENT_QUOTES, 'UTF-8')
            );
        }

        return $tourGuideStepTransfer;
    }
}
