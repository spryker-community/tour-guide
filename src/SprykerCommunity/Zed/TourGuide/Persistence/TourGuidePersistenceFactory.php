<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerCommunity\Zed\TourGuide\Persistence;

use Orm\Zed\TourGuide\Persistence\PyzTourGuideQuery;
use Orm\Zed\TourGuide\Persistence\PyzTourGuideStepQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use SprykerCommunity\Zed\TourGuide\Persistence\Mapper\TourGuideMapper;
use SprykerCommunity\Zed\TourGuide\Persistence\Mapper\TourGuideStepMapper;

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
            $this->createTourGuideMapper(),
        );
    }
}
