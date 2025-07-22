<?php

declare(strict_types=1);

namespace SprykerCommunity\Zed\TourGuide\Business\Collector;

interface ZedRouteCollectorInterface
{
    /**
     * @return array<string>
     */
    public function getAllZedRoutes(): array;
}
