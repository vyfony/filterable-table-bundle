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

namespace Vyfony\Bundle\FilterableTableBundle\Table\Configurator;

use Vyfony\Bundle\FilterableTableBundle\Table\Metadata\TableMetadataInterface;

/**
 * @author Anton Dyshkant <vyshkant@gmail.com>
 */
interface TableConfiguratorInterface
{
    /**
     * @param iterable $rowDataCollection
     * @param array    $queryParameters
     *
     * @return TableMetadataInterface
     */
    public function getTableMetadata(
        iterable $rowDataCollection,
        array $queryParameters
    ): TableMetadataInterface;

    /**
     * @return array
     */
    public function getDefaultTableParameters(): array;
}
