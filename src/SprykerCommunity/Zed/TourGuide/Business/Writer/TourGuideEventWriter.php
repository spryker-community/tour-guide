<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerCommunity\Zed\TourGuide\Business\Writer;

use Generated\Shared\Transfer\TourGuideEventTransfer;
use SprykerCommunity\Zed\TourGuide\Persistence\TourGuideEntityManagerInterface;
use SprykerCommunity\Zed\TourGuide\Persistence\TourGuideRepositoryInterface;

class TourGuideEventWriter implements TourGuideEventWriterInterface
{
    /**
     * @var \SprykerCommunity\Zed\TourGuide\Persistence\TourGuideEntityManagerInterface
     */
    protected TourGuideEntityManagerInterface $tourGuideEntityManager;

    /**
     * @var \SprykerCommunity\Zed\TourGuide\Persistence\TourGuideRepositoryInterface
     */
    protected TourGuideRepositoryInterface $tourGuideRepository;

    /**
     * @param \SprykerCommunity\Zed\TourGuide\Persistence\TourGuideEntityManagerInterface $tourGuideEntityManager
     * @param \SprykerCommunity\Zed\TourGuide\Persistence\TourGuideRepositoryInterface $tourGuideRepository
     */
    public function __construct(
        TourGuideEntityManagerInterface $tourGuideEntityManager,
        TourGuideRepositoryInterface $tourGuideRepository,
    ) {
        $this->tourGuideEntityManager = $tourGuideEntityManager;
        $this->tourGuideRepository = $tourGuideRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\TourGuideEventTransfer $tourGuideEventTransfer
     *
     * @return \Generated\Shared\Transfer\TourGuideEventTransfer
     */
    public function createTourGuideEvent(TourGuideEventTransfer $tourGuideEventTransfer): TourGuideEventTransfer
    {
        return $this->tourGuideEntityManager->saveTourGuideEvent($tourGuideEventTransfer);
    }

    /**
     * @param int $idTourGuide
     * @param string $eventType
     * @param int $tourVersion
     *
     * @return \Generated\Shared\Transfer\TourGuideEventTransfer
     */
    public function trackTourGuideEvent(int $idTourGuide, string $eventType, int $tourVersion): TourGuideEventTransfer
    {
        $tourGuideEventTransfer = new TourGuideEventTransfer();
        $tourGuideEventTransfer->setFkTourGuide($idTourGuide);
        $tourGuideEventTransfer->setEventType($eventType);
        $tourGuideEventTransfer->setTourVersion($tourVersion);

        return $this->createTourGuideEvent($tourGuideEventTransfer);
    }
}
