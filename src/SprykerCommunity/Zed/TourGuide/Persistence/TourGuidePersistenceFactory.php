<?php

declare(strict_types=1);

namespace SprykerCommunity\Zed\TourGuide\Persistence;

use Orm\Zed\TourGuide\Persistence\PyzTourGuideQuery;
use Orm\Zed\TourGuide\Persistence\PyzTourGuideStepQuery;
use SprykerCommunity\Zed\TourGuide\Persistence\Mapper\TourGuideMapper;
use SprykerCommunity\Zed\TourGuide\Persistence\Mapper\TourGuideStepMapper;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \SprykerCommunity\Zed\TourGuide\Persistence\TourGuideRepositoryInterface getRepository()
 * @method \SprykerCommunity\Zed\TourGuide\Persistence\TourGuideEntityManagerInterface getEntityManager()
 */
class TourGuidePersistenceFactory extends AbstractPersistenceFactory
{
    public function createTourGuideQuery(): PyzTourGuideQuery
    {
        return PyzTourGuideQuery::create();
    }

    public function createTourGuideMapper(): TourGuideMapper
    {
        return new TourGuideMapper();
    }

    public function createTourGuideStepQuery(): PyzTourGuideStepQuery
    {
        return PyzTourGuideStepQuery::create();
    }

    public function createTourGuideStepMapper(): TourGuideStepMapper
    {
        return new TourGuideStepMapper(
            $this->createTourGuideMapper()
        );
    }
}
