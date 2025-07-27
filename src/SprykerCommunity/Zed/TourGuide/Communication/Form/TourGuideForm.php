<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerCommunity\Zed\TourGuide\Communication\Form;

use Generated\Shared\Transfer\TourGuideTransfer;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \SprykerCommunity\Zed\TourGuide\Business\TourGuideFacadeInterface getFacade()
 * @method \SprykerCommunity\Zed\TourGuide\Communication\TourGuideCommunicationFactory getFactory()
 */
class TourGuideForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_ID_TOUR_GUIDE = 'idTourGuide';

    /**
     * @var string
     */
    public const FIELD_FK_ACL_GROUP = 'fkAclGroup';

    /**
     * @var string
     */
    public const FIELD_ROUTE = 'route';

    /**
     * @var string
     */
    public const FIELD_VERSION = 'version';

    /**
     * @var string
     */
    public const FIELD_IS_ACTIVE = 'isActive';

    public function getBlockPrefix(): string
    {
        return 'tour_guide';
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TourGuideTransfer::class,
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
            ->addIdTourGuideField($builder)
            ->addFkAclGroupField($builder)
            ->addRouteField($builder)
            ->addVersionField($builder)
            ->addIsActiveField($builder);
    }

    protected function addIdTourGuideField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_ID_TOUR_GUIDE, HiddenType::class);

        return $this;
    }

    protected function addFkAclGroupField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_FK_ACL_GROUP, ChoiceType::class, [
            'label' => 'User Group',
            'required' => false,
            'placeholder' => 'Select a group',
            'choices' => $this->getGroupChoices(),
        ]);

        return $this;
    }

    /**
     * @return array<int, string>
     */
    protected function getGroupChoices(): array
    {
        $choices = [];
        $groups = $this->getFactory()->getAclFacade()->getAllGroups();

        foreach ($groups->getGroups() as $group) {
            $choices[$group->getName()] = $group->getIdAclGroup();
        }

        return $choices;
    }

    protected function addRouteField(FormBuilderInterface $builder): self
    {
        $zedUrls = $this->getFacade()->getAllZedUrls();
        $choices = array_combine($zedUrls, $zedUrls);

        $builder->add(static::FIELD_ROUTE, Select2ComboBoxType::class, [
            'label' => 'Module Route',
            'required' => true,
            'choices' => $choices,
            'placeholder' => 'Select a route',
            'constraints' => [
                new NotBlank(),
                new Length(['max' => 255]),
            ],
        ]);

        return $this;
    }

    protected function addVersionField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_VERSION, IntegerType::class, [
            'label' => 'Version',
            'required' => true,
            'attr' => [
                'placeholder' => '1',
            ],
            'constraints' => [
                new NotBlank(),
                new GreaterThanOrEqual(['value' => 1]),
            ],
        ]);

        return $this;
    }

    protected function addIsActiveField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_IS_ACTIVE, CheckboxType::class, [
            'label' => 'Active',
            'required' => false,
        ]);

        return $this;
    }
}
