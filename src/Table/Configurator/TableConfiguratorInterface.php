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
     * @param array  $formData
     * @param array  $queryParameters
     * @param string $entityClass
     *
     * @return TableMetadataInterface
     */
    public function getTableMetadata(
        array $formData,
        array $queryParameters,
        string $entityClass
    ): TableMetadataInterface;

    /**
     * @return array
     */
    public function getDefaultTableParameters(): array;
}
