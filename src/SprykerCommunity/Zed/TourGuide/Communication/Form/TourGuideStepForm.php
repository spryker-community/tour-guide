<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerCommunity\Zed\TourGuide\Communication\Form;

use Generated\Shared\Transfer\TourGuideStepTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \SprykerCommunity\Zed\TourGuide\Communication\TourGuideCommunicationFactory getFactory()
 * @method \SprykerCommunity\Zed\TourGuide\Business\TourGuideFacadeInterface getFacade()
 */
class TourGuideStepForm extends AbstractType
{
    public const FIELD_ID_TOUR_GUIDE_STEP = 'idTourGuideStep';

    public const FIELD_FK_TOUR_GUIDE = 'fkTourGuide';

    public const FIELD_STEP_INDEX = 'stepIndex';

    public const FIELD_TITLE = 'title';

    public const FIELD_TEXT = 'text';

    public const FIELD_ATTACH_TO_ELEMENT = 'attachToElement';

    public const FIELD_ATTACH_TO_POSITION = 'attachToPosition';

    public const FIELD_IS_ACTIVE = 'isActive';

    public const POSITION_TOP = 'top';

    public const POSITION_BOTTOM = 'bottom';

    public const POSITION_LEFT = 'left';

    public const POSITION_RIGHT = 'right';

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TourGuideStepTransfer::class,
        ]);
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     *
     * @param array<string, mixed> $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addIdTourGuideStepField($builder)
            ->addFkTourGuideField($builder)
            ->addStepIndexField($builder)
            ->addTitleField($builder)
            ->addTextField($builder)
            ->addAttachToElementField($builder)
            ->addAttachToPositionField($builder)
            ->addIsActiveField($builder);
    }

    protected function addIdTourGuideStepField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_ID_TOUR_GUIDE_STEP, HiddenType::class);

        return $this;
    }

    protected function addFkTourGuideField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_FK_TOUR_GUIDE, HiddenType::class);

        return $this;
    }

    protected function addStepIndexField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_STEP_INDEX, IntegerType::class, [
            'label' => 'Step Index',
            'constraints' => [
                new NotBlank(),
                new GreaterThanOrEqual(['value' => 0]),
            ],
        ]);

        return $this;
    }

    protected function addTitleField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_TITLE, TextType::class, [
            'label' => 'Title',
            'constraints' => [
                new NotBlank(),
                new Length(['max' => 255]),
            ],
        ]);

        return $this;
    }

    protected function addTextField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_TEXT, TextareaType::class, [
            'label' => 'Text',
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }

    protected function addAttachToElementField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_ATTACH_TO_ELEMENT, TextType::class, [
            'label' => 'Attach To Element (CSS Selector)',
            'constraints' => [
                new NotBlank(),
                new Length(['max' => 255]),
            ],
        ]);

        return $this;
    }

    protected function addAttachToPositionField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_ATTACH_TO_POSITION, ChoiceType::class, [
            'label' => 'Attach To Position',
            'choices' => [
                'Top' => static::POSITION_TOP,
                'Bottom' => static::POSITION_BOTTOM,
                'Left' => static::POSITION_LEFT,
                'Right' => static::POSITION_RIGHT,
            ],
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }

    protected function addIsActiveField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_IS_ACTIVE, CheckboxType::class, [
            'label' => 'Is Active',
            'required' => false,
        ]);

        return $this;
    }
}
