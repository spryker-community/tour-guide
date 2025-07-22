<?php

declare(strict_types=1);

namespace SprykerCommunity\Zed\TourGuide\Business\Validator;

use Generated\Shared\Transfer\RouteValidationRequestTransfer;

interface RouteValidatorInterface
{
    public function validateZedUrl(RouteValidationRequestTransfer $routeValidationRequestTransfer): bool;
}
