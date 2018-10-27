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

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

/**
 * @author Anton Dyshkant <vyshkant@gmail.com>
 */
final class NotNullCheckboxParameter extends AbstractFilterParameter implements ExpressionBuilderInterface
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return CheckboxType::class;
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param array        $formData
     * @param string       $entityAlias
     *
     * @return string|null
     */
    public function buildWhereExpression(QueryBuilder $queryBuilder, array $formData, string $entityAlias): ?string
    {
        if (true === $formData[$this->getQueryParameterName()]) {
            return $queryBuilder->expr()->isNotNull($entityAlias.'.'.$this->getQueryParameterName());
        }

        return null;
    }
}
