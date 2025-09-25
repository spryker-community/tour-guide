<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerCommunity\Zed\TourGuide\Business\Sanitizer;

use Generated\Shared\Transfer\TourGuideStepTransfer;

interface TourGuideSanitizerInterface
{
    /**
     * Sanitizes the TourGuideStepTransfer to prevent XSS attacks.
     *
     * @param \Generated\Shared\Transfer\TourGuideStepTransfer $tourGuideStepTransfer
     *
     * @return \Generated\Shared\Transfer\TourGuideStepTransfer
     */
    public function sanitizeTourGuideStepTransfer(TourGuideStepTransfer $tourGuideStepTransfer): TourGuideStepTransfer;
}
