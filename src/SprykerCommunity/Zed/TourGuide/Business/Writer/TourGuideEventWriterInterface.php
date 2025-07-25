<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerCommunity\Zed\TourGuide\Business\Writer;

use Generated\Shared\Transfer\TourGuideEventTransfer;

interface TourGuideEventWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\TourGuideEventTransfer $tourGuideEventTransfer
     *
     * @return \Generated\Shared\Transfer\TourGuideEventTransfer
     */
    public function createTourGuideEvent(TourGuideEventTransfer $tourGuideEventTransfer): TourGuideEventTransfer;

    /**
     * @param int $idTourGuide
     * @param string $eventType
     * @param int $tourVersion
     *
     * @return \Generated\Shared\Transfer\TourGuideEventTransfer
     */
    public function trackTourGuideEvent(int $idTourGuide, string $eventType, int $tourVersion): TourGuideEventTransfer;
}
