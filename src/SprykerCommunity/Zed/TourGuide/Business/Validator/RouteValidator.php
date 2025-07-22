<?php

declare(strict_types=1);

namespace SprykerCommunity\Zed\TourGuide\Business\Validator;

use Generated\Shared\Transfer\RouteValidationRequestTransfer;
use SprykerCommunity\Zed\TourGuide\Business\Collector\ZedRouteCollectorInterface;

class RouteValidator implements RouteValidatorInterface
{
    public function __construct(
        protected ZedRouteCollectorInterface $zedRouteCollector
    ) {
    }

    public function validateZedUrl(RouteValidationRequestTransfer $routeValidationRequestTransfer): bool
    {
        $route = $routeValidationRequestTransfer->getRoute();
        $validRoutes = $routeValidationRequestTransfer->getValidRoutes();

        return in_array($route, $validRoutes, true);
    }
}
