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

use Doctrine\ORM\EntityRepository;
use Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\Parameter\AbstractFilterParameter;

/**
 * @author Anton Dyshkant <vyshkant@gmail.com>
 */
abstract class AbstractTableParameter extends AbstractFilterParameter implements TableParameterInterface
{
    /**
     * @param EntityRepository $repository
     *
     * @return array
     */
    protected function createOptions(EntityRepository $repository): array
    {
        return array_merge(parent::createOptions($repository), [
            'label_attr' => ['class' => ''],
            'required' => true,
        ]);
    }
}
