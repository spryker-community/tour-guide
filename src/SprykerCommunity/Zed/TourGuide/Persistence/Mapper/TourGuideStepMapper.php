<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerCommunity\Zed\TourGuide\Persistence\Mapper;

use Generated\Shared\Transfer\TourGuideStepTransfer;
use Generated\Shared\Transfer\TourGuideTransfer;
use Orm\Zed\TourGuide\Persistence\PyzTourGuideStep;

class TourGuideStepMapper
{
    public function __construct(protected TourGuideMapper $tourGuideMapper)
    {
    }

    public function mapTourGuideStepEntityToTourGuideStepTransfer(
        PyzTourGuideStep $tourGuideStepEntity,
        TourGuideStepTransfer $tourGuideStepTransfer,
    ): TourGuideStepTransfer {
        $tourGuideStepTransfer = $tourGuideStepTransfer->fromArray($tourGuideStepEntity->toArray(), true);

        if ($tourGuideStepEntity->getPyzTourGuide() !== null) {
            $tourGuideTransfer = $this->tourGuideMapper->mapTourGuideEntityToTourGuideTransfer(
                $tourGuideStepEntity->getPyzTourGuide(),
                new TourGuideTransfer(),
            );
            $tourGuideStepTransfer->setTourGuide($tourGuideTransfer);
        }

        return $tourGuideStepTransfer;
    }

    public function mapTourGuideStepTransferToTourGuideStepEntity(
        TourGuideStepTransfer $tourGuideStepTransfer,
        PyzTourGuideStep $tourGuideStepEntity,
    ): PyzTourGuideStep {
        $tourGuideStepEntity->fromArray($tourGuideStepTransfer->toArray());

        return $tourGuideStepEntity;
    }
}
