<?php

declare(strict_types=1);

namespace SprykerCommunity\Zed\TourGuide\Persistence;

use Generated\Shared\Transfer\TourGuideStepTransfer;
use Generated\Shared\Transfer\TourGuideTransfer;

interface TourGuideEntityManagerInterface
{
    public function saveTourGuide(TourGuideTransfer $tourGuideTransfer): TourGuideTransfer;

    public function deleteTourGuide(int $idTourGuide): bool;

    public function saveTourGuideStep(TourGuideStepTransfer $tourGuideStepTransfer): TourGuideStepTransfer;

    public function deleteTourGuideStep(int $idTourGuideStep): bool;
}
