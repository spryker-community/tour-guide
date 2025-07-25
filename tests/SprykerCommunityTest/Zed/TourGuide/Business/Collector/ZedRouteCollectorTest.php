<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerCommunityTest\Zed\TourGuide\Business\Collector;

use PHPUnit\Framework\TestCase;
use SprykerCommunity\Zed\TourGuide\Business\Collector\ZedRouteCollector;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;

final class ZedRouteCollectorTest extends TestCase
{
    public function testGetAllZedRoutesReturnsValidRoutesWithoutParameters(): void
    {
        // Arrange
        $routeCollection = new RouteCollection();
        $routeCollection->add('route1', new Route('/route1'));
        $routeCollection->add('route2', new Route('/route2'));
        $routeCollection->add('route3', new Route('/route3/table'));
        $routeCollection->add('route4', new Route('/route4/{param}'));

        $router = $this->createMock(RouterInterface::class);
        $router->expects($this->once())
            ->method('getRouteCollection')
            ->willReturn($routeCollection);

        $zedRouteCollector = new ZedRouteCollector($router);

        // Act
        $routes = $zedRouteCollector->getAllZedRoutes();

        // Assert
        $this->assertCount(2, $routes);
        $this->assertContains('/route1', $routes);
        $this->assertContains('/route2', $routes);
        $this->assertNotContains('/route3/table', $routes);
        $this->assertNotContains('/route4/{param}', $routes);
    }
}
