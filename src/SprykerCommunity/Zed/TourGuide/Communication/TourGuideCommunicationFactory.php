<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerCommunity\Zed\TourGuide\Communication;

use Generated\Shared\Transfer\TourGuideStepTransfer;
use Generated\Shared\Transfer\TourGuideTransfer;
use Spryker\Zed\Acl\Business\AclFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use SprykerCommunity\Zed\TourGuide\Communication\Form\DataProvider\TourGuideEventTableFilterFormDataProvider;
use SprykerCommunity\Zed\TourGuide\Communication\Form\TourGuideEventTableFilterForm;
use SprykerCommunity\Zed\TourGuide\Communication\Form\TourGuideForm;
use SprykerCommunity\Zed\TourGuide\Communication\Form\TourGuideStepForm;
use SprykerCommunity\Zed\TourGuide\Communication\Table\TourGuideEventTable;
use SprykerCommunity\Zed\TourGuide\Communication\Table\TourGuideTable;
use SprykerCommunity\Zed\TourGuide\TourGuideDependencyProvider;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @method \SprykerCommunity\Zed\TourGuide\Business\TourGuideFacadeInterface getFacade()
 */
class TourGuideCommunicationFactory extends AbstractCommunicationFactory
{
    public function createTourGuideTable(): TourGuideTable
    {
        return new TourGuideTable(
            $this->getFacade(),
        );
    }

    /**
     * @return \SprykerCommunity\Zed\TourGuide\Communication\Table\TourGuideEventTable
     */
    public function createTourGuideEventTable(): TourGuideEventTable
    {
        return new TourGuideEventTable(
            $this->getFacade(),
            $this->getRequestStack()->getCurrentRequest(),
        );
    }

    public function createTourGuideForm(?TourGuideTransfer $tourGuideTransfer = null): FormInterface
    {
        return $this->getFormFactory()->create(
            TourGuideForm::class,
            $tourGuideTransfer,
            [
                'data_class' => TourGuideTransfer::class,
            ],
        );
    }

    public function createTourGuideStepForm(?TourGuideStepTransfer $tourGuideStepTransfer = null): FormInterface
    {
        return $this->getFormFactory()->create(
            TourGuideStepForm::class,
            $tourGuideStepTransfer,
            [
                'data_class' => TourGuideStepTransfer::class,
            ],
        );
    }

    /**
     * @uses \Spryker\Zed\Http\Communication\Plugin\Application\HttpApplicationPlugin::SERVICE_REQUEST_STACK
     */
    protected const SERVICE_REQUEST_STACK = 'request_stack';

    public function getRequestStack(): RequestStack
    {
        return $this->getProvidedDependency(static::SERVICE_REQUEST_STACK);
    }

    public function getAclFacade(): AclFacadeInterface
    {
        return $this->getProvidedDependency(TourGuideDependencyProvider::FACADE_ACL);
    }

    public function createTourGuideEventTableFilterForm(): FormInterface
    {
        $dataProvider = $this->createTourGuideEventTableFilterFormDataProvider();

        return $this->getFormFactory()->create(
            TourGuideEventTableFilterForm::class,
            $dataProvider->getData(),
            $dataProvider->getOptions(),
        );
    }

    protected function createTourGuideEventTableFilterFormDataProvider(): TourGuideEventTableFilterFormDataProvider
    {
        return new TourGuideEventTableFilterFormDataProvider(
            $this->getFacade(),
        );
    }
}
