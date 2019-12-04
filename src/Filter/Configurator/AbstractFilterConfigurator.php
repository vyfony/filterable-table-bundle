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

namespace Vyfony\Bundle\FilterableTableBundle\Filter\Configurator;

use Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\Parameter\FilterParameterInterface;
use Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\Parameter\Table\TableParameterInterface;
use Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\Restriction\FilterRestrictionInterface;

/**
 * @author Anton Dyshkant <vyshkant@gmail.com>
 */
abstract class AbstractFilterConfigurator implements FilterConfiguratorInterface
{
    /**
     * @var FilterRestrictionInterface[]
     */
    private $filterRestrictions;

    /**
     * @var FilterParameterInterface[]
     */
    private $filterParameters;

    /**
     * @var TableParameterInterface[]
     */
    private $tableParameters;

    /**
     * @return FilterRestrictionInterface[]
     */
    final public function getFilterRestrictions(): array
    {
        if (null === $this->filterRestrictions) {
            $this->filterRestrictions = $this->createFilterRestrictions();
        }

        return $this->filterRestrictions;
    }

    /**
     * @return FilterParameterInterface[]
     */
    final public function getFilterParameters(): array
    {
        if (null === $this->filterParameters) {
            $this->filterParameters = $this->createFilterParameters();
        }

        return $this->filterParameters;
    }

    /**
     * @return TableParameterInterface[]
     */
    final public function getTableParameters(): array
    {
        if (null === $this->tableParameters) {
            $this->tableParameters = $this->createTableParameters();
        }

        return $this->tableParameters;
    }

    final public function getDefaultTableParameters(): array
    {
        $defaultTableParameters = [];
        foreach ($this->getTableParameters() as $tableParameter) {
            $defaultTableParameters[$tableParameter->getQueryParameterName()] = $tableParameter->getDefaultValue();
        }

        return $defaultTableParameters;
    }

    /**
     * @return FilterRestrictionInterface[]
     */
    abstract protected function createFilterRestrictions(): array;

    /**
     * @return FilterParameterInterface[]
     */
    abstract protected function createFilterParameters(): array;

    /**
     * @return TableParameterInterface[]
     */
    abstract protected function createTableParameters(): array;
}
