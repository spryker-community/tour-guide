<?php

declare(strict_types=1);

namespace SprykerCommunity\Zed\TourGuide\Persistence;

use Generated\Shared\Transfer\TourGuideCollectionTransfer;
use Generated\Shared\Transfer\TourGuideCriteriaTransfer;
use Generated\Shared\Transfer\TourGuideStepCollectionTransfer;
use Generated\Shared\Transfer\TourGuideStepCriteriaTransfer;
use Generated\Shared\Transfer\TourGuideStepTransfer;
use Generated\Shared\Transfer\TourGuideTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \SprykerCommunity\Zed\TourGuide\Persistence\TourGuidePersistenceFactory getFactory()
 */
class TourGuideRepository extends AbstractRepository implements TourGuideRepositoryInterface
{
    public function getTourGuideCollection(
        TourGuideCriteriaTransfer $tourGuideCriteriaTransfer
    ): TourGuideCollectionTransfer {
        $tourGuideQuery = $this->getFactory()->createTourGuideQuery();

        if ($tourGuideCriteriaTransfer->getIdTourGuide() !== null) {
            $tourGuideQuery->filterByIdTourGuide($tourGuideCriteriaTransfer->getIdTourGuide());
        }

        if ($tourGuideCriteriaTransfer->getFkAclGroup() !== null) {
            $tourGuideQuery->filterByFkAclGroup($tourGuideCriteriaTransfer->getFkAclGroup());
        }

        if ($tourGuideCriteriaTransfer->getRoute() !== null) {
            $tourGuideQuery->filterByRoute($tourGuideCriteriaTransfer->getRoute());
        }

        if ($tourGuideCriteriaTransfer->getIsActive() !== null) {
            $tourGuideQuery->filterByIsActive($tourGuideCriteriaTransfer->getIsActive());
        }

        $tourGuideQuery->orderByRoute();

        $tourGuideEntityCollection = $tourGuideQuery->find();
        $tourGuideCollectionTransfer = new TourGuideCollectionTransfer();

        foreach ($tourGuideEntityCollection as $tourGuideEntity) {
            $tourGuideTransfer = $this->getFactory()
                ->createTourGuideMapper()
                ->mapTourGuideEntityToTourGuideTransfer($tourGuideEntity, new TourGuideTransfer());

            $tourGuideCollectionTransfer->addTourGuide($tourGuideTransfer);
        }

        return $tourGuideCollectionTransfer;
    }

    public function findTourGuideById(int $idTourGuide): ?TourGuideTransfer
    {
        $tourGuideEntity = $this->getFactory()
            ->createTourGuideQuery()
            ->findOneByIdTourGuide($idTourGuide);

        if ($tourGuideEntity === null) {
            return null;
        }

        return $this->getFactory()
            ->createTourGuideMapper()
            ->mapTourGuideEntityToTourGuideTransfer($tourGuideEntity, new TourGuideTransfer());
    }

    public function findTourGuideByRoute(string $route): ?TourGuideTransfer
    {
        $tourGuideEntity = $this->getFactory()
            ->createTourGuideQuery()
            ->filterByRoute($route)
            ->filterByIsActive(true)
            ->findOne();

        if ($tourGuideEntity === null) {
            return null;
        }

        return $this->getFactory()
            ->createTourGuideMapper()
            ->mapTourGuideEntityToTourGuideTransfer($tourGuideEntity, new TourGuideTransfer());
    }

    public function getTourGuideStepCollection(
        TourGuideStepCriteriaTransfer $tourGuideStepCriteriaTransfer
    ): TourGuideStepCollectionTransfer {
        $tourGuideStepQuery = $this->getFactory()->createTourGuideStepQuery();

        if ($tourGuideStepCriteriaTransfer->getIdTourGuideStep() !== null) {
            $tourGuideStepQuery->filterByIdTourGuideStep($tourGuideStepCriteriaTransfer->getIdTourGuideStep());
        }

        if ($tourGuideStepCriteriaTransfer->getFkTourGuide() !== null) {
            $tourGuideStepQuery->filterByFkTourGuide($tourGuideStepCriteriaTransfer->getFkTourGuide());
        }

        if ($tourGuideStepCriteriaTransfer->getIsActive() !== null) {
            $tourGuideStepQuery->filterByIsActive($tourGuideStepCriteriaTransfer->getIsActive());
        }

        $tourGuideStepQuery->orderByStepIndex();

        $tourGuideStepEntityCollection = $tourGuideStepQuery->find();
        $tourGuideStepCollectionTransfer = new TourGuideStepCollectionTransfer();

        foreach ($tourGuideStepEntityCollection as $tourGuideStepEntity) {
            $tourGuideStepTransfer = $this->getFactory()
                ->createTourGuideStepMapper()
                ->mapTourGuideStepEntityToTourGuideStepTransfer($tourGuideStepEntity, new TourGuideStepTransfer());

            $tourGuideStepCollectionTransfer->addTourGuideStep($tourGuideStepTransfer);
        }

        return $tourGuideStepCollectionTransfer;
    }

    public function findTourGuideStepById(int $idTourGuideStep): ?TourGuideStepTransfer
    {
        $tourGuideStepEntity = $this->getFactory()
            ->createTourGuideStepQuery()
            ->findOneByIdTourGuideStep($idTourGuideStep);

        if ($tourGuideStepEntity === null) {
            return null;
        }

        return $this->getFactory()
            ->createTourGuideStepMapper()
            ->mapTourGuideStepEntityToTourGuideStepTransfer($tourGuideStepEntity, new TourGuideStepTransfer());
    }

    public function getTourGuideStepsByRoute(string $route): TourGuideStepCollectionTransfer
    {
        $tourGuideTransfer = $this->findTourGuideByRoute($route);

        if ($tourGuideTransfer === null) {
            return new TourGuideStepCollectionTransfer();
        }

        return $this->getTourGuideStepsByTourGuideId($tourGuideTransfer->getIdTourGuide());
    }

    public function getTourGuideStepsByTourGuideId(int $idTourGuide): TourGuideStepCollectionTransfer
    {
        $tourGuideStepCriteriaTransfer = new TourGuideStepCriteriaTransfer();
        $tourGuideStepCriteriaTransfer->setFkTourGuide($idTourGuide);
        $tourGuideStepCriteriaTransfer->setIsActive(true);

        return $this->getTourGuideStepCollection($tourGuideStepCriteriaTransfer);
    }
}
