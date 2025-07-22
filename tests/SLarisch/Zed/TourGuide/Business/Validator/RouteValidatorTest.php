<?php

declare(strict_types=1);

namespace SprykerCommunityTest\Zed\TourGuide\Business\Validator;

use Generated\Shared\Transfer\RouteValidationRequestTransfer;
use PHPUnit\Framework\TestCase;
use SprykerCommunity\Zed\TourGuide\Business\Collector\ZedRouteCollectorInterface;
use SprykerCommunity\Zed\TourGuide\Business\Validator\RouteValidator;

final class RouteValidatorTest extends TestCase
{
    public function test_route_validation_successful_with_valid_route(): void
    {
        // Arrange
        $zedRouteCollector = $this->createMock(ZedRouteCollectorInterface::class);
        $routeValidationService = new RouteValidator($zedRouteCollector);

        $routeValidationRequestTransfer = (new RouteValidationRequestTransfer())
            ->setRoute('/valid-url')
            ->setValidRoutes(['/valid-url']);

        // Act
        $isValid = $routeValidationService->validateZedUrl($routeValidationRequestTransfer);

        // Assert
        self::assertTrue($isValid);
    }

    public function test_route_validation_not_successful_with_invalid_route(): void
    {
        $zedRouteCollector = $this->createMock(ZedRouteCollectorInterface::class);

        $routeValidationService = new RouteValidator($zedRouteCollector);

        $routeValidationRequestTransfer = (new RouteValidationRequestTransfer())
            ->setRoute('/valid-url')
            ->setValidRoutes(['/invalid-url']);

        $assert = $routeValidationService->validateZedUrl($routeValidationRequestTransfer);

        self::assertFalse($assert);
    }
}
