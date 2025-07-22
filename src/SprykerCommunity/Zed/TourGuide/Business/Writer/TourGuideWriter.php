<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerCommunity\Zed\TourGuide\Business\Writer;

use Generated\Shared\Transfer\TourGuideStepTransfer;
use Generated\Shared\Transfer\TourGuideTransfer;
use SprykerCommunity\Zed\TourGuide\Persistence\TourGuideEntityManagerInterface;
use SprykerCommunity\Zed\TourGuide\Persistence\TourGuideRepositoryInterface;

class TourGuideWriter implements TourGuideWriterInterface
{
    protected TourGuideEntityManagerInterface $tourGuideEntityManager;

    protected TourGuideRepositoryInterface $tourGuideRepository;

    public function __construct(
        TourGuideEntityManagerInterface $tourGuideEntityManager,
        TourGuideRepositoryInterface $tourGuideRepository,
    ) {
        $this->tourGuideEntityManager = $tourGuideEntityManager;
        $this->tourGuideRepository = $tourGuideRepository;
    }

    public function createTourGuideStep(TourGuideStepTransfer $tourGuideStepTransfer): TourGuideStepTransfer
    {
        return $this->tourGuideEntityManager->saveTourGuideStep($tourGuideStepTransfer);
    }

    public function updateTourGuideStep(TourGuideStepTransfer $tourGuideStepTransfer): TourGuideStepTransfer
    {
        $existingTourGuideStepTransfer = $this->tourGuideRepository->findTourGuideStepById(
            (int)$tourGuideStepTransfer->getIdTourGuideStep(),
        );

        if ($existingTourGuideStepTransfer === null) {
            return $this->createTourGuideStep($tourGuideStepTransfer);
        }

        return $this->tourGuideEntityManager->saveTourGuideStep($tourGuideStepTransfer);
    }

    public function deleteTourGuideStep(int $idTourGuideStep): bool
    {
        return $this->tourGuideEntityManager->deleteTourGuideStep($idTourGuideStep);
    }

    public function createTourGuide(TourGuideTransfer $tourGuideTransfer): TourGuideTransfer
    {
        return $this->tourGuideEntityManager->saveTourGuide($tourGuideTransfer);
    }

    public function updateTourGuide(TourGuideTransfer $tourGuideTransfer): TourGuideTransfer
    {
        $existingTourGuideTransfer = $this->tourGuideRepository->findTourGuideById(
            (int)$tourGuideTransfer->getIdTourGuide(),
        );

        if ($existingTourGuideTransfer === null) {
            return $this->createTourGuide($tourGuideTransfer);
        }

        return $this->tourGuideEntityManager->saveTourGuide($tourGuideTransfer);
    }

    public function deleteTourGuide(int $idTourGuide): bool
    {
        return $this->tourGuideEntityManager->deleteTourGuide($idTourGuide);
    }
}
