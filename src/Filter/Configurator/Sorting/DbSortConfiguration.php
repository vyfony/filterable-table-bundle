<?php

declare(strict_types=1);

/*
 * This file is part of VyfonyFilterableTableBundle project.
 *
 * (c) Anton Dyshkant <vyshkant@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\Sorting;

final class DbSortConfiguration implements DbSortConfigurationInterface
{
    private string $defaultSortBy;
    private bool $isDefaultSortAsc;
    private int $pageSize;
    private int $paginatorTailLength;
    private string $disablePaginationLabel;

    public function __construct(
        string $defaultSortBy,
        bool $isDefaultSortAsc,
        int $pageSize,
        int $paginatorTailLength,
        string $disablePaginationLabel
    ) {
        $this->defaultSortBy = $defaultSortBy;
        $this->isDefaultSortAsc = $isDefaultSortAsc;
        $this->pageSize = $pageSize;
        $this->paginatorTailLength = $paginatorTailLength;
        $this->disablePaginationLabel = $disablePaginationLabel;
    }

    public function getDefaultSortBy(): string
    {
        return $this->defaultSortBy;
    }

    public function getIsDefaultSortAsc(): bool
    {
        return $this->isDefaultSortAsc;
    }

    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    public function getPaginatorTailLength(): int
    {
        return $this->paginatorTailLength;
    }

    public function getDisablePaginationLabel(): string
    {
        return $this->disablePaginationLabel;
    }
}
