<?php

declare(strict_types=1);

namespace SprykerCommunity\Zed\TourGuide\Communication\Table;

use Generated\Shared\Transfer\TourGuideStepCriteriaTransfer;
use Orm\Zed\TourGuide\Persistence\Map\PyzTourGuideStepTableMap;
use SprykerCommunity\Zed\TourGuide\Business\TourGuideFacadeInterface;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class TourGuideStepTable extends AbstractTable
{
    private const COL_ACTIONS = 'Actions';

    /**
     * @var \SprykerCommunity\Zed\TourGuide\Business\TourGuideFacadeInterface
     */
    protected $tourGuideFacade;

    public function __construct(TourGuideFacadeInterface $tourGuideFacade)
    {
        $this->tourGuideFacade = $tourGuideFacade;
    }

    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setHeader([
            PyzTourGuideStepTableMap::COL_ID_TOUR_GUIDE_STEP => 'ID',
            PyzTourGuideStepTableMap::COL_STEP_INDEX => 'Index',
            PyzTourGuideStepTableMap::COL_TITLE => 'Title',
            PyzTourGuideStepTableMap::COL_IS_ACTIVE => 'Active',
            self::COL_ACTIONS => 'Actions',
        ]);

        $config->setSortable([
            PyzTourGuideStepTableMap::COL_ID_TOUR_GUIDE_STEP,
            PyzTourGuideStepTableMap::COL_STEP_INDEX,
            PyzTourGuideStepTableMap::COL_TITLE,
            PyzTourGuideStepTableMap::COL_IS_ACTIVE,
        ]);

        $config->setSearchable([
            PyzTourGuideStepTableMap::COL_TITLE,
        ]);

        $config->addRawColumn(static::COL_ACTIONS);
        $config->addRawColumn(PyzTourGuideStepTableMap::COL_IS_ACTIVE);

        return $config;
    }

    protected function prepareData(TableConfiguration $config): array
    {
        $tourGuideStepCriteriaTransfer = new TourGuideStepCriteriaTransfer();
        $tourGuideStepCollection = $this->tourGuideFacade->getTourGuideStepCollection($tourGuideStepCriteriaTransfer);
        $results = [];

        foreach ($tourGuideStepCollection->getTourGuideSteps() as $tourGuideStepTransfer) {
            $results[] = [
                PyzTourGuideStepTableMap::COL_ID_TOUR_GUIDE_STEP => $tourGuideStepTransfer->getIdTourGuideStep(),
                PyzTourGuideStepTableMap::COL_STEP_INDEX => $tourGuideStepTransfer->getStepIndex(),
                PyzTourGuideStepTableMap::COL_TITLE => $tourGuideStepTransfer->getTitle(),
                PyzTourGuideStepTableMap::COL_IS_ACTIVE => $this->generateStatusLabel($tourGuideStepTransfer->getIsActive()),
                'Actions' => $this->generateActionButtons($tourGuideStepTransfer->getIdTourGuideStep()),
            ];
        }

        return $results;
    }

    protected function generateStatusLabel(bool $isActive): string
    {
        if ($isActive) {
            return $this->generateLabel('Active', 'label-info');        }

        return $this->generateLabel('Active', 'label-default');    }

    protected function generateActionButtons(int $idTourGuideStep): string
    {
        $buttons = [];

        $buttons[] = $this->generateEditButton(
            Url::generate('/tour-guide/steps/edit', ['id-tour-guide-step' => $idTourGuideStep]),
            'Edit'
        );

        $buttons[] = $this->generateRemoveButton(
            Url::generate('/tour-guide/steps/delete', ['id-tour-guide-step' => $idTourGuideStep]),
            'Delete'
        );

        return implode(' ', $buttons);
    }
}
