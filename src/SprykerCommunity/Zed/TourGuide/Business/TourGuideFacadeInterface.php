<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerCommunity\Zed\TourGuide\Business;

use Generated\Shared\Transfer\TourGuideCollectionTransfer;
use Generated\Shared\Transfer\TourGuideCriteriaTransfer;
use Generated\Shared\Transfer\TourGuideEventTransfer;
use Generated\Shared\Transfer\TourGuideStepCollectionTransfer;
use Generated\Shared\Transfer\TourGuideStepCriteriaTransfer;
use Generated\Shared\Transfer\TourGuideStepTransfer;
use Generated\Shared\Transfer\TourGuideTransfer;

interface TourGuideFacadeInterface
{
    /**
     * Specification:
     * - Returns a collection of tour guides based on the provided criteria.
     *
     * @param \Generated\Shared\Transfer\TourGuideCriteriaTransfer $tourGuideCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\TourGuideCollectionTransfer
     */
    public function getTourGuideCollection(
        TourGuideCriteriaTransfer $tourGuideCriteriaTransfer,
    ): TourGuideCollectionTransfer;

    /**
     * Specification:
     * - Finds a tour guide by its ID.
     * - Returns null if no tour guide is found.
     */
    public function findTourGuideById(int $idTourGuide): ?TourGuideTransfer;

    /**
     * Specification:
     * - Finds a tour guide by its route.
     * - Returns null if no tour guide is found.
     */
    public function findTourGuideByRoute(string $route): ?TourGuideTransfer;

    /**
     * Specification:
     * - Creates a new tour guide.
     * - Persists the tour guide to the database.
     * - Returns the created tour guide with the ID set.
     */
    public function createTourGuide(TourGuideTransfer $tourGuideTransfer): TourGuideTransfer;

    /**
     * Specification:
     * - Updates an existing tour guide.
     * - Creates a new tour guide if the ID is not found.
     * - Persists the tour guide to the database.
     * - Returns the updated tour guide.
     */
    public function updateTourGuide(TourGuideTransfer $tourGuideTransfer): TourGuideTransfer;

    /**
     * Specification:
     * - Deletes a tour guide by its ID.
     * - Returns true if the tour guide was deleted, false otherwise.
     */
    public function deleteTourGuide(int $idTourGuide): bool;

    /**
     * Specification:
     * - Returns a collection of tour guide steps based on the provided criteria.
     */
    public function getTourGuideStepCollection(
        TourGuideStepCriteriaTransfer $tourGuideStepCriteriaTransfer,
    ): TourGuideStepCollectionTransfer;

    /**
     * Specification:
     * - Finds a tour guide step by its ID.
     * - Returns null if no tour guide step is found.
     */
    public function findTourGuideStepById(int $idTourGuideStep): ?TourGuideStepTransfer;

    /**
     * Specification:
     * - Returns a collection of tour guide steps for the specified route.
     * - Only returns active steps.
     */
    public function getTourGuideStepsByRoute(string $route): TourGuideStepCollectionTransfer;

    /**
     * Specification:
     * - Returns a collection of tour guide steps for the specified tour guide ID.
     * - Only returns active steps.
     */
    public function getTourGuideStepsByTourGuideId(int $idTourGuide): TourGuideStepCollectionTransfer;

    /**
     * Specification:
     * - Creates a new tour guide step.
     * - Persists the step to the database.
     * - Returns the created tour guide step with the ID set.
     */
    public function createTourGuideStep(TourGuideStepTransfer $tourGuideStepTransfer): TourGuideStepTransfer;

    /**
     * Specification:
     * - Updates an existing tour guide step.
     * - Creates a new tour guide step if the ID is not found.
     * - Persists the step to the database.
     * - Returns the updated tour guide step.
     */
    public function updateTourGuideStep(TourGuideStepTransfer $tourGuideStepTransfer): TourGuideStepTransfer;

    /**
     * Specification:
     * - Deletes a tour guide step by its ID.
     * - Returns true if the step was deleted, false otherwise.
     */
    public function deleteTourGuideStep(int $idTourGuideStep): bool;

    /**
     * Specification:
     * - Creates a new tour guide event.
     * - Persists the event to the database.
     * - Returns the created tour guide event with the ID set.
     *
     * @param \Generated\Shared\Transfer\TourGuideEventTransfer $tourGuideEventTransfer
     *
     * @return \Generated\Shared\Transfer\TourGuideEventTransfer
     */
    public function createTourGuideEvent(TourGuideEventTransfer $tourGuideEventTransfer): TourGuideEventTransfer;

    /**
     * Specification:
     * - Tracks a tour guide event with the given tour guide ID, event type, and tour version.
     * - Creates a new tour guide event.
     * - Persists the event to the database.
     * - Returns the created tour guide event with the ID set.
     *
     * @param int $idTourGuide
     * @param string $eventType
     * @param int $tourVersion
     *
     * @return \Generated\Shared\Transfer\TourGuideEventTransfer
     */
    public function trackTourGuideEvent(int $idTourGuide, string $eventType, int $tourVersion): TourGuideEventTransfer;
}
