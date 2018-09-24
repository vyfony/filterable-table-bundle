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

namespace Vyfony\Bundle\FilterableTableBundle\Table\Metadata\Column;

/**
 * @author Anton Dyshkant <vyshkant@gmail.com>
 */
interface ColumnMetadataInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getLabel(): string;

    /**
     * @return bool
     */
    public function getIsSortable(): bool;

    /**
     * @param array $sortParameters
     *
     * @return ColumnMetadataInterface
     */
    public function setSortParameters(array $sortParameters): self;

    /**
     * @return array|null
     */
    public function getSortParameters(): ?array;

    /**
     * @param object $rowData
     *
     * @return string
     */
    public function getValue(object $rowData): string;
}
