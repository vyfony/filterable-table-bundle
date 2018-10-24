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
interface FilterConfiguratorInterface
{
    /**
     * @return FilterRestrictionInterface[]
     */
    public function getFilterRestrictions(): array;

    /**
     * @return FilterParameterInterface[]
     */
    public function getFilterParameters(): array;

    /**
     * @return TableParameterInterface[]
     */
    public function getTableParameters(): array;

    /**
     * @return array
     */
    public function createSubmitButtonOptions(): array;

    /**
     * @return array
     */
    public function createResetButtonOptions(): array;

    /**
     * @return array
     */
    public function createDefaults(): array;

    /**
     * @return array
     */
    public function getDefaultTableParameters(): array;
}
