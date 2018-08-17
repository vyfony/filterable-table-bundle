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

namespace Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\Parameter;

/**
 * @author Anton Dyshkant <vyshkant@gmail.com>
 */
interface FilterParameterInterface
{
    /**
     * @return string
     */
    public function getPropertyName(): string;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return array
     */
    public function getOptions(): array;

    /**
     * @param array $commonOptions
     */
    public function applyCommonOptions(array $commonOptions): void;
}
