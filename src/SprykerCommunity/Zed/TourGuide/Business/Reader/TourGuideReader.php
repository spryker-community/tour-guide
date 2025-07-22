<?php

declare(strict_types=1);

namespace SprykerCommunity\Zed\TourGuide\Business\Reader;

use Generated\Shared\Transfer\TourGuideCollectionTransfer;
use Generated\Shared\Transfer\TourGuideCriteriaTransfer;
use Generated\Shared\Transfer\TourGuideStepCollectionTransfer;
use Generated\Shared\Transfer\TourGuideStepCriteriaTransfer;
use Generated\Shared\Transfer\TourGuideStepTransfer;
use Generated\Shared\Transfer\TourGuideTransfer;
use SprykerCommunity\Zed\TourGuide\Persistence\TourGuideRepositoryInterface;

class TourGuideReader implements TourGuideReaderInterface
{
    protected TourGuideRepositoryInterface $tourGuideRepository;

    public function __construct(TourGuideRepositoryInterface $tourGuideRepository)
    {
        $this->tourGuideRepository = $tourGuideRepository;
    }

    public function getTourGuideCollection(
        TourGuideCriteriaTransfer $tourGuideCriteriaTransfer
    ): TourGuideCollectionTransfer {
        return $this->tourGuideRepository->getTourGuideCollection($tourGuideCriteriaTransfer);
    }

    public function findTourGuideById(int $idTourGuide): ?TourGuideTransfer
    {
        return $this->tourGuideRepository->findTourGuideById($idTourGuide);
    }

    public function findTourGuideByRoute(string $route): ?TourGuideTransfer
    {
        return $this->tourGuideRepository->findTourGuideByRoute($route);
    }

    public function getTourGuideStepCollection(
        TourGuideStepCriteriaTransfer $tourGuideStepCriteriaTransfer
    ): TourGuideStepCollectionTransfer {
        return $this->tourGuideRepository->getTourGuideStepCollection($tourGuideStepCriteriaTransfer);
    }

    public function findTourGuideStepById(int $idTourGuideStep): ?TourGuideStepTransfer
    {
        return $this->tourGuideRepository->findTourGuideStepById($idTourGuideStep);
    }

    public function getTourGuideStepsByRoute(string $route): TourGuideStepCollectionTransfer
    {
        return $this->tourGuideRepository->getTourGuideStepsByRoute($route);
    }

    public function getTourGuideStepsByTourGuideId(int $idTourGuide): TourGuideStepCollectionTransfer
    {
        return $this->tourGuideRepository->getTourGuideStepsByTourGuideId($idTourGuide);
    }
}
