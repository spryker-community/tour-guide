<?php

declare(strict_types=1);

namespace SprykerCommunity\Zed\TourGuide\Business;

use Generated\Shared\Transfer\TourGuideCollectionTransfer;
use Generated\Shared\Transfer\TourGuideCriteriaTransfer;
use Generated\Shared\Transfer\TourGuideStepCollectionTransfer;
use Generated\Shared\Transfer\TourGuideStepCriteriaTransfer;
use Generated\Shared\Transfer\TourGuideStepTransfer;
use Generated\Shared\Transfer\TourGuideTransfer;
use Generated\Shared\Transfer\RouteValidationRequestTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \SprykerCommunity\Zed\TourGuide\Business\TourGuideBusinessFactory getFactory()
 */
final class TourGuideFacade extends AbstractFacade implements TourGuideFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function getTourGuideStepCollection(
        TourGuideStepCriteriaTransfer $tourGuideStepCriteriaTransfer
    ): TourGuideStepCollectionTransfer {
        return $this->getFactory()
            ->createTourGuideReader()
            ->getTourGuideStepCollection($tourGuideStepCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function findTourGuideStepById(int $idTourGuideStep): ?TourGuideStepTransfer
    {
        return $this->getFactory()
            ->createTourGuideReader()
            ->findTourGuideStepById($idTourGuideStep);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function getTourGuideStepsByRoute(string $route): TourGuideStepCollectionTransfer
    {
        return $this->getFactory()
            ->createTourGuideReader()
            ->getTourGuideStepsByRoute($route);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function createTourGuideStep(TourGuideStepTransfer $tourGuideStepTransfer): TourGuideStepTransfer
    {
        return $this->getFactory()
            ->createTourGuideWriter()
            ->createTourGuideStep($tourGuideStepTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function updateTourGuideStep(TourGuideStepTransfer $tourGuideStepTransfer): TourGuideStepTransfer
    {
        return $this->getFactory()
            ->createTourGuideWriter()
            ->updateTourGuideStep($tourGuideStepTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function deleteTourGuideStep(int $idTourGuideStep): bool
    {
        return $this->getFactory()
            ->createTourGuideWriter()
            ->deleteTourGuideStep($idTourGuideStep);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array<string>
     */
    public function getAllZedUrls(): array
    {
        return $this->getFactory()
            ->createZedRouteCollector()
            ->getAllZedRoutes();
    }

    public function validateZedUrl(RouteValidationRequestTransfer $routeValidationRequestTransfer): bool
    {
        return $this->getFactory()
            ->createRouteValidator()
            ->validateZedUrl($routeValidationRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function getTourGuideCollection(
        TourGuideCriteriaTransfer $tourGuideCriteriaTransfer
    ): TourGuideCollectionTransfer {
        return $this->getFactory()
            ->createTourGuideReader()
            ->getTourGuideCollection($tourGuideCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function findTourGuideById(int $idTourGuide): ?TourGuideTransfer
    {
        return $this->getFactory()
            ->createTourGuideReader()
            ->findTourGuideById($idTourGuide);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function findTourGuideByRoute(string $route): ?TourGuideTransfer
    {
        return $this->getFactory()
            ->createTourGuideReader()
            ->findTourGuideByRoute($route);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function createTourGuide(TourGuideTransfer $tourGuideTransfer): TourGuideTransfer
    {
        return $this->getFactory()
            ->createTourGuideWriter()
            ->createTourGuide($tourGuideTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function updateTourGuide(TourGuideTransfer $tourGuideTransfer): TourGuideTransfer
    {
        return $this->getFactory()
            ->createTourGuideWriter()
            ->updateTourGuide($tourGuideTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function deleteTourGuide(int $idTourGuide): bool
    {
        return $this->getFactory()
            ->createTourGuideWriter()
            ->deleteTourGuide($idTourGuide);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function getTourGuideStepsByTourGuideId(int $idTourGuide): TourGuideStepCollectionTransfer
    {
        return $this->getFactory()
            ->createTourGuideReader()
            ->getTourGuideStepsByTourGuideId($idTourGuide);
    }
}
