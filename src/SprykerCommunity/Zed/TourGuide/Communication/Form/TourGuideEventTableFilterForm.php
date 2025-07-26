<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerCommunity\Zed\TourGuide\Communication\Form;

use Generated\Shared\Transfer\TourGuideEventCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use SprykerCommunity\Zed\TourGuide\Communication\Form\DataProvider\TourGuideEventTableFilterFormDataProvider;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerCommunity\Zed\TourGuide\Communication\TourGuideCommunicationFactory getFactory()
 */
class TourGuideEventTableFilterForm extends AbstractType
{
    /**
     * @var string
     */
    protected const FIELD_EVENT_TYPE = 'eventType';

    /**
     * @var string
     */
    protected const FIELD_FK_TOUR_GUIDE = 'fkTourGuide';

    /**
     * @var string
     */
    protected const PLACEHOLDER_EVENT_TYPE = 'Select Event Type';

    /**
     * @var string
     */
    protected const PLACEHOLDER_ROUTE = 'Select Route';

    /**
     * @var string
     */
    protected const LABEL_EVENT_TYPE = 'Event Type';

    /**
     * @var string
     */
    protected const LABEL_ROUTE = 'Route';

    public function getBlockPrefix(): string
    {
        return '';
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired([
            TourGuideEventTableFilterFormDataProvider::OPTION_EVENT_TYPES,
            TourGuideEventTableFilterFormDataProvider::OPTION_TOUR_GUIDES,
        ]);

        $resolver->setDefaults([
            'data_class' => TourGuideEventCriteriaTransfer::class,
            'csrf_protection' => false,
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->setMethod(Request::METHOD_GET);

        $this
            ->addEventTypeField($builder, $options)
            ->addRouteField($builder, $options);
    }

    protected function addEventTypeField(FormBuilderInterface $builder, array $options = []): self
    {
        $builder->add(static::FIELD_EVENT_TYPE, ChoiceType::class, [
            'label' => static::LABEL_EVENT_TYPE,
            'placeholder' => static::PLACEHOLDER_EVENT_TYPE,
            'required' => false,
            'choices' => $options[TourGuideEventTableFilterFormDataProvider::OPTION_EVENT_TYPES] ?? [],
            'attr' => [
                'class' => 'form-control',
            ],
        ]);

        return $this;
    }

    protected function addRouteField(FormBuilderInterface $builder, array $options): self
    {
        $builder->add(static::FIELD_FK_TOUR_GUIDE, ChoiceType::class, [
            'label' => static::LABEL_ROUTE,
            'placeholder' => static::PLACEHOLDER_ROUTE,
            'required' => false,
            'choices' => $options[TourGuideEventTableFilterFormDataProvider::OPTION_TOUR_GUIDES] ?? [],
            'attr' => [
                'class' => 'form-control',
            ],
        ]);

        return $this;
    }
}
