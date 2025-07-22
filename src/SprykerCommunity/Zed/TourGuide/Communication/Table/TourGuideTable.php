<?php

declare(strict_types=1);

namespace SprykerCommunity\Zed\TourGuide\Communication\Table;

use Generated\Shared\Transfer\TourGuideCriteriaTransfer;
use SprykerCommunity\Zed\TourGuide\Business\TourGuideFacadeInterface;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class TourGuideTable extends AbstractTable
{
    /**
     * @var string
     */
    protected const COL_ID_TOUR_GUIDE = 'id_tour_guide';

    /**
     * @var string
     */
    protected const COL_ROUTE = 'route';

    /**
     * @var string
     */
    protected const COL_VERSION = 'version';

    /**
     * @var string
     */
    protected const COL_IS_ACTIVE = 'is_active';

    /**
     * @var string
     */
    protected const COL_ACTIONS = 'actions';

    public function __construct(
        protected TourGuideFacadeInterface $tourGuideFacade
    ) {
    }

    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setHeader([
            static::COL_ID_TOUR_GUIDE => 'ID',
            static::COL_ROUTE => 'Route',
            static::COL_VERSION => 'Version',
            static::COL_IS_ACTIVE => 'Active',
            static::COL_ACTIONS => 'Actions',
        ]);

        $config->setSortable([
            static::COL_ID_TOUR_GUIDE,
            static::COL_ROUTE,
            static::COL_VERSION,
            static::COL_IS_ACTIVE,
        ]);

        $config->setSearchable([
            static::COL_ROUTE,
        ]);

        $config->setDefaultSortField(static::COL_ID_TOUR_GUIDE, TableConfiguration::SORT_DESC);

        $config->addRawColumn(static::COL_IS_ACTIVE);
        $config->addRawColumn(static::COL_ACTIONS);

        return $config;
    }

    protected function prepareData(TableConfiguration $config): array
    {
        $tourGuideCollectionTransfer = $this->tourGuideFacade->getTourGuideCollection(new TourGuideCriteriaTransfer());
        $tableRows = [];

        foreach ($tourGuideCollectionTransfer->getTourGuides() as $tourGuideTransfer) {
            $tableRows[] = [
                static::COL_ID_TOUR_GUIDE => $tourGuideTransfer->getIdTourGuide(),
                static::COL_ROUTE => $tourGuideTransfer->getRoute(),
                static::COL_VERSION => $tourGuideTransfer->getVersion(),
                static::COL_IS_ACTIVE => $this->generateStatusLabel($tourGuideTransfer->getIsActive()),
                static::COL_ACTIONS => $this->generateActionsButton($tourGuideTransfer->getIdTourGuide()),
            ];
        }

        return $tableRows;
    }

    protected function generateStatusLabel(bool $isActive): string
    {
        if ($isActive) {
            return '<span class="label label-success">Active</span>';
        }

        return '<span class="label label-default">Inactive</span>';
    }

    protected function generateActionsButton(int $idTourGuide): string
    {
        $buttons = [];

        $buttons[] = $this->generateEditButton(
            Url::generate('/tour-guide/tour/edit', [
                'id-tour-guide' => $idTourGuide,
            ]),
            'Edit Tour'
        );

        $buttons[] = $this->generateRemoveButton(
            Url::generate('/tour-guide/tour/delete', [
                'id-tour-guide' => $idTourGuide,
            ]),
            'Delete Tour'
        );

        $buttons[] = $this->generateViewButton(
            Url::generate('/tour-guide/steps', [
                'id-tour-guide' => $idTourGuide,
            ]),
            'Edit Steps'
        );

        return implode(' ', $buttons);
    }
}
