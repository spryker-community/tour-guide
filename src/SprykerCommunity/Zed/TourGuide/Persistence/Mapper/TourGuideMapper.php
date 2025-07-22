<?php

declare(strict_types=1);

namespace SprykerCommunity\Zed\TourGuide\Persistence\Mapper;

use Generated\Shared\Transfer\GroupTransfer;
use Generated\Shared\Transfer\TourGuideTransfer;
use Orm\Zed\TourGuide\Persistence\PyzTourGuide;

class TourGuideMapper
{
    public function mapTourGuideEntityToTourGuideTransfer(
        PyzTourGuide $tourGuideEntity,
        TourGuideTransfer $tourGuideTransfer
    ): TourGuideTransfer {
        $tourGuideTransfer = $tourGuideTransfer->fromArray($tourGuideEntity->toArray(), true);

        if ($tourGuideEntity->getAclGroup() !== null) {
            $aclGroupData = $tourGuideEntity->getAclGroup()->toArray();
            $tourGuideTransfer->setAclGroup((new GroupTransfer())->fromArray($aclGroupData, true));
        }

        return $tourGuideTransfer;
    }

    public function mapTourGuideTransferToTourGuideEntity(
        TourGuideTransfer $tourGuideTransfer,
        PyzTourGuide $tourGuideEntity
    ): PyzTourGuide {
        $tourGuideEntity->fromArray($tourGuideTransfer->toArray());

        return $tourGuideEntity;
    }
}
