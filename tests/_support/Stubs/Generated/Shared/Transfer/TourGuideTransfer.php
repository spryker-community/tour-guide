<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Generated\Shared\Transfer;

namespace tests\_support\Stubs\Generated\Shared\Transfer;

final class TourGuideTransfer
{
    private string $route = '';

    private string $name = '';

    private bool $isActive;

    private int $idTourGuide;

    public function setRoute(string $route): self
    {
        $this->route = $route;

        return $this;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    public function getIdTourGuide(): int
    {
        return $this->idTourGuide;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
