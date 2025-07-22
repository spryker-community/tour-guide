<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerCommunity\Zed\TourGuide\Persistence;

use Generated\Shared\Transfer\TourGuideStepTransfer;
use Generated\Shared\Transfer\TourGuideTransfer;
use Orm\Zed\TourGuide\Persistence\PyzTourGuide;
use Orm\Zed\TourGuide\Persistence\PyzTourGuideStep;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \SprykerCommunity\Zed\TourGuide\Persistence\TourGuidePersistenceFactory getFactory()
 */
class TourGuideEntityManager extends AbstractEntityManager implements TourGuideEntityManagerInterface
{
    public function saveTourGuide(TourGuideTransfer $tourGuideTransfer): TourGuideTransfer
    {
        $tourGuideEntity = null;

        if ($tourGuideTransfer->getIdTourGuide() !== null) {
            $tourGuideEntity = $this->findTourGuideEntityById((int)$tourGuideTransfer->getIdTourGuide());
        }

        if ($tourGuideEntity === null) {
            $tourGuideEntity = new PyzTourGuide();
        }

        $tourGuideEntity = $this->getFactory()
            ->createTourGuideMapper()
            ->mapTourGuideTransferToTourGuideEntity($tourGuideTransfer, $tourGuideEntity);

        $tourGuideEntity->save();

        return $this->getFactory()
            ->createTourGuideMapper()
            ->mapTourGuideEntityToTourGuideTransfer($tourGuideEntity, $tourGuideTransfer);
    }

    public function deleteTourGuide(int $idTourGuide): bool
    {
        $tourGuideEntity = $this->findTourGuideEntityById($idTourGuide);

        if ($tourGuideEntity === null) {
            return false;
        }

        $tourGuideEntity->delete();

        return true;
    }

    public function saveTourGuideStep(TourGuideStepTransfer $tourGuideStepTransfer): TourGuideStepTransfer
    {
        $tourGuideStepEntity = null;

        if ($tourGuideStepTransfer->getIdTourGuideStep() !== null) {
            $tourGuideStepEntity = $this->findTourGuideStepEntityById((int)$tourGuideStepTransfer->getIdTourGuideStep());
        }

        if ($tourGuideStepEntity === null) {
            $tourGuideStepEntity = new PyzTourGuideStep();
        }

        $tourGuideStepEntity = $this->getFactory()
            ->createTourGuideStepMapper()
            ->mapTourGuideStepTransferToTourGuideStepEntity($tourGuideStepTransfer, $tourGuideStepEntity);

        $tourGuideStepEntity->save();

        return $this->getFactory()
            ->createTourGuideStepMapper()
            ->mapTourGuideStepEntityToTourGuideStepTransfer($tourGuideStepEntity, $tourGuideStepTransfer);
    }

    public function deleteTourGuideStep(int $idTourGuideStep): bool
    {
        $tourGuideStepEntity = $this->findTourGuideStepEntityById($idTourGuideStep);

        if ($tourGuideStepEntity === null) {
            return false;
        }

        $tourGuideStepEntity->delete();

        return true;
    }

    protected function findTourGuideEntityById(int $idTourGuide): ?PyzTourGuide
    {
        return $this->getFactory()
            ->createTourGuideQuery()
            ->findOneByIdTourGuide($idTourGuide);
    }

    protected function findTourGuideStepEntityById(int $idTourGuideStep): ?PyzTourGuideStep
    {
        return $this->getFactory()
            ->createTourGuideStepQuery()
            ->findOneByIdTourGuideStep($idTourGuideStep);
    }
}
