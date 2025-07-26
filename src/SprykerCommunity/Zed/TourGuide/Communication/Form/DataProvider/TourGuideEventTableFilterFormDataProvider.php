<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerCommunity\Zed\TourGuide\Communication\Form\DataProvider;

use Generated\Shared\Transfer\TourGuideCriteriaTransfer;
use Generated\Shared\Transfer\TourGuideEventCriteriaTransfer;
use SprykerCommunity\Zed\TourGuide\Business\TourGuideFacadeInterface;

class TourGuideEventTableFilterFormDataProvider
{
    /**
     * @var string
     */
    public const OPTION_EVENT_TYPES = 'event_types';

    /**
     * @var string
     */
    public const OPTION_TOUR_GUIDES = 'tour_guides';

    public function __construct(
        protected TourGuideFacadeInterface $tourGuideFacade,
    ) {
    }

    public function getData(): TourGuideEventCriteriaTransfer
    {
        return new TourGuideEventCriteriaTransfer();
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        return [
            static::OPTION_EVENT_TYPES => $this->getEventTypeChoices(),
            static::OPTION_TOUR_GUIDES => $this->getTourGuideChoices(),
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function getEventTypeChoices(): array
    {
        return [
            'start' => 'start',
            'pause' => 'pause',
            'finish' => 'finish',
        ];
    }

    /**
     * @return array<int, string>
     */
    protected function getTourGuideChoices(): array
    {
        $tourGuideChoices = [];
        $tourGuideCollectionTransfer = $this->tourGuideFacade->getTourGuideCollection(new TourGuideCriteriaTransfer());

        foreach ($tourGuideCollectionTransfer->getTourGuides() as $tourGuideTransfer) {
            $tourGuideChoices[$tourGuideTransfer->getRoute()] = $tourGuideTransfer->getIdTourGuide();
        }

        return $tourGuideChoices;
    }
}
