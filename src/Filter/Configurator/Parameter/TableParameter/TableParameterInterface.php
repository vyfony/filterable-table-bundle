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

namespace Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\Parameter\TableParameter;

use Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\Parameter\FilterParameterInterface;
use Vyfony\Bundle\FilterableTableBundle\Table\Metadata\Column\ColumnMetadataInterface;

/**
 * @author Anton Dyshkant <vyshkant@gmail.com>
 */
interface TableParameterInterface extends FilterParameterInterface
{
    /**
     * @param array $queryParameters
     *
     * @return ColumnMetadataInterface[]
     */
    public function getColumnMetadataCollection(array $queryParameters): array;

    /**
     * @return string
     */
    public function getDefaultValue(): string;
}
