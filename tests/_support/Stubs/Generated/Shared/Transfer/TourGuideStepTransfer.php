<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace tests\_support\Stubs\Generated\Shared\Transfer;

final class TourGuideStepTransfer
{
    private string $title;

    private bool $isActive;

    private string $text;

    private int $stepIndex;

    private int $fkTourGuide;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function getFkTourGuide(): int
    {
        return $this->fkTourGuide;
    }

    public function setFkTourGuide(int $fkTourGuide): void
    {
        $this->fkTourGuide = $fkTourGuide;
    }
}
