<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerCommunity\Zed\TourGuide\Persistence\Mapper;

use Generated\Shared\Transfer\TourGuideEventTransfer;
use Generated\Shared\Transfer\TourGuideTransfer;
use Orm\Zed\TourGuide\Persistence\PyzTourGuideEvent;

class TourGuideEventMapper
{
    public function __construct(protected TourGuideMapper $tourGuideMapper)
    {
    }

    public function mapTourGuideEventEntityToTourGuideEventTransfer(
        PyzTourGuideEvent $tourGuideEventEntity,
        TourGuideEventTransfer $tourGuideEventTransfer,
    ): TourGuideEventTransfer {
        $tourGuideEventTransfer = $tourGuideEventTransfer->fromArray($tourGuideEventEntity->toArray(), true);

        if ($tourGuideEventEntity->getPyzTourGuide() !== null) {
            $tourGuideTransfer = $this->tourGuideMapper->mapTourGuideEntityToTourGuideTransfer(
                $tourGuideEventEntity->getPyzTourGuide(),
                new TourGuideTransfer(),
            );
            $tourGuideEventTransfer->setTourGuide($tourGuideTransfer);
        }

        return $tourGuideEventTransfer;
    }

    public function mapTourGuideEventTransferToTourGuideEventEntity(
        TourGuideEventTransfer $tourGuideEventTransfer,
        PyzTourGuideEvent $tourGuideEventEntity,
    ): PyzTourGuideEvent {
        $tourGuideEventEntity->fromArray($tourGuideEventTransfer->toArray());
        $tourGuideEventEntity->setEventType($tourGuideEventTransfer->getEventType());

        return $tourGuideEventEntity;
    }
}
