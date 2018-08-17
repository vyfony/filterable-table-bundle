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
use Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\Parameter\TableParameter\TableParameterInterface;
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
    public function getFilterRestrictions(): array
    {
        if (null === $this->filterRestrictions) {
            $this->filterRestrictions = $this->factoryFilterRestrictions();
        }

        return $this->filterRestrictions;
    }

    /**
     * @return FilterParameterInterface[]
     */
    public function getFilterParameters(): array
    {
        if (null === $this->filterParameters) {
            $this->filterParameters = $this->factoryFilterParameters();

            foreach ($this->filterParameters as $filterParameter) {
                $filterParameter->applyCommonOptions($this->factoryCommonFilterParameterOptions());
            }
        }

        return $this->filterParameters;
    }

    /**
     * @return TableParameterInterface[]
     */
    public function getTableParameters(): array
    {
        if (null === $this->tableParameters) {
            $this->tableParameters = $this->factoryTableParameters();

            foreach ($this->tableParameters as $tableParameter) {
                $tableParameter->applyCommonOptions($this->factoryCommonTableParameterOptions());
            }
        }

        return $this->tableParameters;
    }

    /**
     * @return array
     */
    public function getDefaultTableParameters(): array
    {
        $defaultTableParameters = [];
        foreach ($this->getTableParameters() as $tableParameter) {
            $defaultTableParameters[$tableParameter->getPropertyName()] = $tableParameter->getDefaultValue();
        }

        return $defaultTableParameters;
    }

    /**
     * @return FilterRestrictionInterface[]
     */
    abstract protected function factoryFilterRestrictions(): array;

    /**
     * @return FilterParameterInterface[]
     */
    abstract protected function factoryFilterParameters(): array;

    /**
     * @return TableParameterInterface[]
     */
    abstract protected function factoryTableParameters(): array;

    /**
     * @return array
     */
    abstract protected function factoryCommonFilterParameterOptions(): array;

    /**
     * @return array
     */
    abstract protected function factoryCommonTableParameterOptions(): array;
}
