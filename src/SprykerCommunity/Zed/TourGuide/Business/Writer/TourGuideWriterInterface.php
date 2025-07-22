<?php

declare(strict_types=1);

namespace SprykerCommunity\Zed\TourGuide\Business\Writer;

use Generated\Shared\Transfer\TourGuideStepTransfer;
use Generated\Shared\Transfer\TourGuideTransfer;

interface TourGuideWriterInterface
{
    public function createTourGuideStep(TourGuideStepTransfer $tourGuideStepTransfer): TourGuideStepTransfer;

    public function updateTourGuideStep(TourGuideStepTransfer $tourGuideStepTransfer): TourGuideStepTransfer;

    public function deleteTourGuideStep(int $idTourGuideStep): bool;

    public function createTourGuide(TourGuideTransfer $tourGuideTransfer): TourGuideTransfer;

    public function updateTourGuide(TourGuideTransfer $tourGuideTransfer): TourGuideTransfer;

    public function deleteTourGuide(int $idTourGuide): bool;
}
