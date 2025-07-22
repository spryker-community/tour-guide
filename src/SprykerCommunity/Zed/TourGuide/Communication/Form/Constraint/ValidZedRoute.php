<?php

declare(strict_types=1);

namespace SprykerCommunity\Zed\TourGuide\Communication\Form\Constraint;

use Symfony\Component\Validator\Constraint;

class ValidZedRoute extends Constraint
{
    public const MESSAGE = 'The route "{{ value }}" is not a valid ZED backoffice URL.';

    public function validatedBy(): string
    {
        return ValidZedRouteValidator::class;
    }
}
