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

namespace Vyfony\Bundle\FilterableTableBundle\Table\Metadata;

use Vyfony\Bundle\FilterableTableBundle\Table\Metadata\Column\ColumnMetadataInterface;

/**
 * @author Anton Dyshkant <vyshkant@gmail.com>
 */
interface TableMetadataInterface
{
    /**
     * @return ColumnMetadataInterface[]
     */
    public function getColumnMetadataCollection(): array;

    /**
     * @return array
     */
    public function getRowDataCollection(): array;

    /**
     * @return string
     */
    public function getListRoute(): string;

    /**
     * @return string
     */
    public function getShowRoute(): string;

    /**
     * @return array
     */
    public function getShowRouteParameters(): array;

    /**
     * @return array
     */
    public function getQueryParameters(): array;
}
