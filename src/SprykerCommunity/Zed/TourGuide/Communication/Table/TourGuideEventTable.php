<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerCommunity\Zed\TourGuide\Communication\Table;

use _PHPStan_5473b6701\Nette\Utils\DateTime;
use Generated\Shared\Transfer\TourGuideEventCriteriaTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use SprykerCommunity\Zed\TourGuide\Business\TourGuideFacadeInterface;

class TourGuideEventTable extends AbstractTable
{
    /**
     * @var string
     */
    protected const COL_ID_TOUR_GUIDE_EVENT = 'id_tour_guide_event';

    /**
     * @var string
     */
    protected const COL_EVENT_TYPE = 'event_type';

    /**
     * @var string
     */
    protected const COL_TOUR_NAME = 'tour_name';

    /**
     * @var string
     */
    protected const COL_TOUR_VERSION = 'tour_version';

    /**
     * @var string
     */
    protected const COL_CREATED_AT = 'created_at';

    public function __construct(
        protected TourGuideFacadeInterface $tourGuideFacade,
    ) {
    }

    protected TourGuideEventCriteriaTransfer $tourGuideEventCriteriaTransfer;

    public function applyCriteria(TourGuideEventCriteriaTransfer $tourGuideEventCriteriaTransfer): void
    {
        $this->tourGuideEventCriteriaTransfer = $tourGuideEventCriteriaTransfer;
    }

    /**
     * @var string
     */
    protected const URL_TABLE = '/table';

    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setHeader([
            static::COL_ID_TOUR_GUIDE_EVENT => 'ID',
            static::COL_EVENT_TYPE => 'Event Type',
            static::COL_TOUR_NAME => 'Tour Name',
            static::COL_TOUR_VERSION => 'Version',
            static::COL_CREATED_AT => 'Date',
        ]);

        $config->setSortable([
            static::COL_ID_TOUR_GUIDE_EVENT,
            static::COL_EVENT_TYPE,
            static::COL_TOUR_NAME,
            static::COL_TOUR_VERSION,
            static::COL_CREATED_AT,
        ]);

        $config->setSearchable([
            static::COL_EVENT_TYPE,
            static::COL_TOUR_NAME,
        ]);

        $config->setDefaultSortField(static::COL_CREATED_AT, TableConfiguration::SORT_DESC);

        $config->setUrl($this->getTableUrl());

        return $config;
    }

    protected function getTableUrl(): string
    {
        return Url::generate(
            static::URL_TABLE,
            $this->getRequest()->query->all(),
        )->build();
    }

    /**
     * @var string
     */
    protected const PARAM_EVENT_TYPE_FILTER = 'event-type-filter';

    /**
     * @var string
     */
    protected const PARAM_ROUTE_FILTER = 'route-filter';

    /**
     * @return array<string, mixed>
     */
    protected function getFilters(): array
    {
        $request = $this->getRequest();
        $eventTypeFilter = $request->query->get(static::PARAM_EVENT_TYPE_FILTER);
        $routeFilter = $request->query->get(static::PARAM_ROUTE_FILTER);

        return [
            static::PARAM_EVENT_TYPE_FILTER => $eventTypeFilter,
            static::PARAM_ROUTE_FILTER => $routeFilter,
        ];
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $tourGuideEventCriteriaTransfer = $this->tourGuideEventCriteriaTransfer ?? new TourGuideEventCriteriaTransfer();

        if ($this->tourGuideEventCriteriaTransfer === null) {
            $filters = $this->getFilters();

            if (!empty($filters[static::PARAM_EVENT_TYPE_FILTER])) {
                $tourGuideEventCriteriaTransfer->setEventType($filters[static::PARAM_EVENT_TYPE_FILTER]);
            }

            if (!empty($filters[static::PARAM_ROUTE_FILTER])) {
                $tourGuide = $this->tourGuideFacade->findTourGuideByRoute($filters[static::PARAM_ROUTE_FILTER]);
                if ($tourGuide !== null) {
                    $tourGuideEventCriteriaTransfer->setFkTourGuide($tourGuide->getIdTourGuide());
                }
            }
        }

        $tourGuideEventCollectionTransfer = $this->tourGuideFacade->getTourGuideEventCollection($tourGuideEventCriteriaTransfer);
        $tableRows = [];

        foreach ($tourGuideEventCollectionTransfer->getTourGuideEvents() as $tourGuideEventTransfer) {
            $tourGuide = $tourGuideEventTransfer->getTourGuide();
            $tourName = $tourGuide ? $tourGuide->getRoute() : 'N/A';

            $dateTime = new DateTime($tourGuideEventTransfer->getCreatedAt());

            $tableRows[] = [
                static::COL_ID_TOUR_GUIDE_EVENT => $tourGuideEventTransfer->getIdTourGuideEvent(),
                static::COL_EVENT_TYPE => $this->formatEventType($tourGuideEventTransfer->getEventType()),
                static::COL_TOUR_NAME => $tourName,
                static::COL_TOUR_VERSION => $tourGuideEventTransfer->getTourVersion(),
                static::COL_CREATED_AT => $dateTime->format('Y-m-d H:i:s'),
            ];
        }

        return $tableRows;
    }

    protected function formatEventType(string $eventType): string
    {
        return ucfirst($eventType);
    }
}
