<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerCommunity\Zed\TourGuide\Business\Reader;

use Generated\Shared\Transfer\TourGuideCollectionTransfer;
use Generated\Shared\Transfer\TourGuideCriteriaTransfer;
use Generated\Shared\Transfer\TourGuideStepCollectionTransfer;
use Generated\Shared\Transfer\TourGuideStepCriteriaTransfer;
use Generated\Shared\Transfer\TourGuideStepTransfer;
use Generated\Shared\Transfer\TourGuideTransfer;

interface TourGuideReaderInterface
{
    public function getTourGuideCollection(
        TourGuideCriteriaTransfer $tourGuideCriteriaTransfer,
    ): TourGuideCollectionTransfer;

    public function findTourGuideById(int $idTourGuide): ?TourGuideTransfer;

    public function findTourGuideByRoute(string $route): ?TourGuideTransfer;

    public function getTourGuideStepCollection(
        TourGuideStepCriteriaTransfer $tourGuideStepCriteriaTransfer,
    ): TourGuideStepCollectionTransfer;

    public function findTourGuideStepById(int $idTourGuideStep): ?TourGuideStepTransfer;

    public function getTourGuideStepsByRoute(string $route): TourGuideStepCollectionTransfer;

    public function getTourGuideStepsByTourGuideId(int $idTourGuide): TourGuideStepCollectionTransfer;
}
