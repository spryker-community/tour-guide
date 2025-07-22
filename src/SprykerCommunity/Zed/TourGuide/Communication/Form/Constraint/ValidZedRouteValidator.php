<?php

declare(strict_types=1);

namespace SprykerCommunity\Zed\TourGuide\Communication\Form\Constraint;

use Generated\Shared\Transfer\RouteValidationRequestTransfer;
use SprykerCommunity\Zed\TourGuide\Business\TourGuideFacadeInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ValidZedRouteValidator extends ConstraintValidator
{
    public function __construct(
        protected TourGuideFacadeInterface $tourGuideFacade
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof ValidZedRoute) {
            throw new UnexpectedTypeException($constraint, ValidZedRoute::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        $validationRequestTransfer = (new RouteValidationRequestTransfer())
            ->setRoute($value)
            ->setValidRoutes($this->tourGuideFacade->getAllZedUrls());

        $isValid = $this->tourGuideFacade->validateZedUrl($validationRequestTransfer);

        if (!$isValid) {
            $this->context->buildViolation($constraint::MESSAGE)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
