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

namespace Vyfony\Bundle\FilterableTableBundle\DataCollector;

/**
 * @author Anton Dyshkant <vyshkant@gmail.com>
 */
interface DataCollectorInterface
{
    /**
     * @param array  $formData
     * @param string $entityClass
     *
     * @return iterable
     */
    public function getRowDataCollection(array $formData, string $entityClass): iterable;
}
