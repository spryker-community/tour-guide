<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerCommunityTest\Zed\TourGuide\Business\Validator;

use Generated\Shared\Transfer\RouteValidationRequestTransfer;
use PHPUnit\Framework\TestCase;
use SprykerCommunity\Zed\TourGuide\Business\Collector\ZedRouteCollectorInterface;
use SprykerCommunity\Zed\TourGuide\Business\Validator\RouteValidator;

final class RouteValidatorTest extends TestCase
{
    public function testRouteValidationSuccessfulWithValidRoute(): void
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
        static::assertTrue($isValid);
    }

    public function testRouteValidationNotSuccessfulWithInvalidRoute(): void
    {
        $zedRouteCollector = $this->createMock(ZedRouteCollectorInterface::class);

        $routeValidationService = new RouteValidator($zedRouteCollector);

        $routeValidationRequestTransfer = (new RouteValidationRequestTransfer())
            ->setRoute('/valid-url')
            ->setValidRoutes(['/invalid-url']);

        $assert = $routeValidationService->validateZedUrl($routeValidationRequestTransfer);

        static::assertFalse($assert);
    }
}
