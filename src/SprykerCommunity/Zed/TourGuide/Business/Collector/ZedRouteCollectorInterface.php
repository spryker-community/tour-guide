<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerCommunity\Zed\TourGuide\Business\Collector;

interface ZedRouteCollectorInterface
{
    /**
     * @return array<string>
     */
    public function getAllZedRoutes(): array;
}
