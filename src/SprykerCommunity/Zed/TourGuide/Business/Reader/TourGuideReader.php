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
use Spryker\Zed\Acl\Business\AclFacadeInterface;
use Spryker\Zed\User\Business\UserFacadeInterface;
use SprykerCommunity\Zed\TourGuide\Persistence\TourGuideRepositoryInterface;

class TourGuideReader implements TourGuideReaderInterface
{
    protected TourGuideRepositoryInterface $tourGuideRepository;
    protected UserFacadeInterface $userFacade;
    protected AclFacadeInterface $aclFacade;

    public function __construct(
        TourGuideRepositoryInterface $tourGuideRepository,
        UserFacadeInterface $userFacade,
        AclFacadeInterface $aclFacade
    ) {
        $this->tourGuideRepository = $tourGuideRepository;
        $this->userFacade = $userFacade;
        $this->aclFacade = $aclFacade;
    }

    public function getTourGuideCollection(
        TourGuideCriteriaTransfer $tourGuideCriteriaTransfer,
    ): TourGuideCollectionTransfer {
        return $this->tourGuideRepository->getTourGuideCollection($tourGuideCriteriaTransfer);
    }

    public function findTourGuideById(int $idTourGuide): ?TourGuideTransfer
    {
        return $this->tourGuideRepository->findTourGuideById($idTourGuide);
    }

    public function findTourGuideByRoute(string $route): ?TourGuideTransfer
    {
        $tourGuideTransfer = $this->tourGuideRepository->findTourGuideByRoute($route);

        if ($tourGuideTransfer === null) {
            return null;
        }

        $currentUser = $this->userFacade->getCurrentUser();

        if ($tourGuideTransfer->getAclGroup() === null || $tourGuideTransfer->getFkAclGroup() === null) {
            return $tourGuideTransfer;
        }

        $userGroups = $this->aclFacade->getUserGroups($currentUser->getIdUser());

        foreach ($userGroups->getGroups() as $userGroup) {
            if ($userGroup->getIdAclGroup() === $tourGuideTransfer->getFkAclGroup()) {
                return $tourGuideTransfer;
            }
        }

        return null;
    }

    public function getTourGuideStepCollection(
        TourGuideStepCriteriaTransfer $tourGuideStepCriteriaTransfer,
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
