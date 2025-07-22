<?php

declare(strict_types=1);

namespace SprykerCommunity\Zed\TourGuide\Business\Collector;

use Symfony\Component\Routing\RouterInterface;

class ZedRouteCollector implements ZedRouteCollectorInterface
{
    public function __construct(
        protected RouterInterface $router
    ) {
    }

    /**
     * @return array<string>
     */
    public function getAllZedRoutes(): array
    {
        $routes = $this->router->getRouteCollection();
        $urls = [];

        foreach ($routes as $route) {
            $path = $route->getPath();

            if (strpos($path, '{') === false) {
                $urls[] = $path;
            }
        }

        return $urls;
    }
}
