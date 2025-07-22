<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Generated\Shared\Transfer;

final class RouteValidationRequestTransfer
{
    private string $route = '';

    private array $validRoutes = [];

    public function setRoute(string $route): self
    {
        $this->route = $route;

        return $this;
    }

    public function setValidRoutes(array $routes): self
    {
        $this->validRoutes = $routes;

        return $this;
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    public function getValidRoutes(): array
    {
        return $this->validRoutes;
    }
}
