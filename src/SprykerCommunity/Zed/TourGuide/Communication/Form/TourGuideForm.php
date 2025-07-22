<?php

declare(strict_types=1);

namespace SprykerCommunity\Zed\TourGuide\Communication\Form;

use Generated\Shared\Transfer\TourGuideTransfer;
use Generated\Shared\Transfer\RouteValidationRequestTransfer;
use SprykerCommunity\Zed\TourGuide\Business\TourGuideFacadeInterface;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

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

    /**
     * @return string
     */
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
        $builder->add(static::FIELD_ROUTE, TextType::class, [
            'label' => 'Route',
            'required' => true,
            'constraints' => [
                new NotBlank(),
                new Length(['max' => 255]),
                new Callback([
                    'callback' => [$this, 'validateZedRoute'],
                ]),
            ],
        ]);

        return $this;
    }

    protected function addVersionField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_VERSION, IntegerType::class, [
            'label' => 'Version',
            'required' => true,
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

    public function validateZedRoute(?string $value, ExecutionContextInterface $context): void
    {
        if (empty($value)) {
            return;
        }

        $validationRequestTransfer = (new RouteValidationRequestTransfer())
            ->setRoute($value)
            ->setValidRoutes($this->getFacade()->getAllZedUrls());

        if (!$this->getFacade()->validateZedUrl($validationRequestTransfer)) {
            $context->buildViolation('The route "{{ value }}" is not a valid ZED backoffice URL.')
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
