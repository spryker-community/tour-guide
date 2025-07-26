<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerCommunity\Zed\TourGuide\Business;

use Generated\Shared\Transfer\RouteValidationRequestTransfer;
use Generated\Shared\Transfer\TourGuideCollectionTransfer;
use Generated\Shared\Transfer\TourGuideCriteriaTransfer;
use Generated\Shared\Transfer\TourGuideEventCollectionTransfer;
use Generated\Shared\Transfer\TourGuideEventCriteriaTransfer;
use Generated\Shared\Transfer\TourGuideEventTransfer;
use Generated\Shared\Transfer\TourGuideStepCollectionTransfer;
use Generated\Shared\Transfer\TourGuideStepCriteriaTransfer;
use Generated\Shared\Transfer\TourGuideStepTransfer;
use Generated\Shared\Transfer\TourGuideTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \SprykerCommunity\Zed\TourGuide\Business\TourGuideBusinessFactory getFactory()
 */
final class TourGuideFacade extends AbstractFacade implements TourGuideFacadeInterface
{
    /**
     * @api
     */
    public function getTourGuideStepCollection(
        TourGuideStepCriteriaTransfer $tourGuideStepCriteriaTransfer,
    ): TourGuideStepCollectionTransfer {
        return $this->getFactory()
            ->createTourGuideReader()
            ->getTourGuideStepCollection($tourGuideStepCriteriaTransfer);
    }

    /**
     * @api
     */
    public function findTourGuideStepById(int $idTourGuideStep): ?TourGuideStepTransfer
    {
        return $this->getFactory()
            ->createTourGuideReader()
            ->findTourGuideStepById($idTourGuideStep);
    }

    /**
     * @api
     */
    public function getTourGuideStepsByRoute(string $route): TourGuideStepCollectionTransfer
    {
        return $this->getFactory()
            ->createTourGuideReader()
            ->getTourGuideStepsByRoute($route);
    }

    /**
     * @api
     */
    public function createTourGuideStep(TourGuideStepTransfer $tourGuideStepTransfer): TourGuideStepTransfer
    {
        return $this->getFactory()
            ->createTourGuideWriter()
            ->createTourGuideStep($tourGuideStepTransfer);
    }

    /**
     * @api
     */
    public function updateTourGuideStep(TourGuideStepTransfer $tourGuideStepTransfer): TourGuideStepTransfer
    {
        return $this->getFactory()
            ->createTourGuideWriter()
            ->updateTourGuideStep($tourGuideStepTransfer);
    }

    /**
     * @api
     */
    public function deleteTourGuideStep(int $idTourGuideStep): bool
    {
        return $this->getFactory()
            ->createTourGuideWriter()
            ->deleteTourGuideStep($idTourGuideStep);
    }

    /**
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
     * @api
     */
    public function getTourGuideCollection(
        TourGuideCriteriaTransfer $tourGuideCriteriaTransfer,
    ): TourGuideCollectionTransfer {
        return $this->getFactory()
            ->createTourGuideReader()
            ->getTourGuideCollection($tourGuideCriteriaTransfer);
    }

    /**
     * @api
     */
    public function findTourGuideById(int $idTourGuide): ?TourGuideTransfer
    {
        return $this->getFactory()
            ->createTourGuideReader()
            ->findTourGuideById($idTourGuide);
    }

    /**
     * @api
     */
    public function findTourGuideByRoute(string $route): ?TourGuideTransfer
    {
        return $this->getFactory()
            ->createTourGuideReader()
            ->findTourGuideByRoute($route);
    }

    /**
     * @api
     */
    public function createTourGuide(TourGuideTransfer $tourGuideTransfer): TourGuideTransfer
    {
        return $this->getFactory()
            ->createTourGuideWriter()
            ->createTourGuide($tourGuideTransfer);
    }

    /**
     * @api
     */
    public function updateTourGuide(TourGuideTransfer $tourGuideTransfer): TourGuideTransfer
    {
        return $this->getFactory()
            ->createTourGuideWriter()
            ->updateTourGuide($tourGuideTransfer);
    }

    /**
     * @api
     */
    public function deleteTourGuide(int $idTourGuide): bool
    {
        return $this->getFactory()
            ->createTourGuideWriter()
            ->deleteTourGuide($idTourGuide);
    }

    /**
     * @api
     */
    public function getTourGuideStepsByTourGuideId(int $idTourGuide): TourGuideStepCollectionTransfer
    {
        return $this->getFactory()
            ->createTourGuideReader()
            ->getTourGuideStepsByTourGuideId($idTourGuide);
    }

    /**
     * @api
     */
    public function createTourGuideEvent(TourGuideEventTransfer $tourGuideEventTransfer): TourGuideEventTransfer
    {
        return $this->getFactory()
            ->createTourGuideEventWriter()
            ->createTourGuideEvent($tourGuideEventTransfer);
    }

    /**
     * @api
     */
    public function trackTourGuideEvent(int $idTourGuide, string $eventType, int $tourVersion): TourGuideEventTransfer
    {
        return $this->getFactory()
            ->createTourGuideEventWriter()
            ->trackTourGuideEvent($idTourGuide, $eventType, $tourVersion);
    }

    /**
     * @api
     */
    public function getTourGuideEventCollection(
        TourGuideEventCriteriaTransfer $tourGuideEventCriteriaTransfer,
    ): TourGuideEventCollectionTransfer {
        return $this->getRepository()->getTourGuideEventCollection($tourGuideEventCriteriaTransfer);
    }
}
