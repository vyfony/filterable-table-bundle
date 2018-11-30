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
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * @author Anton Dyshkant <vyshkant@gmail.com>
 */
final class TextFilterParameter extends AbstractFilterParameter implements ExpressionBuilderInterface
{
    /**
     * @var string[]
     */
    private $searchFieldNames = [];

    /**
     * @return string
     */
    public function getType(): string
    {
        return TextType::class;
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
        $filterValue = $formData[$this->getQueryParameterName()];

        if (null === $filterValue) {
            return null;
        }

        if (0 === \count($this->searchFieldNames)) {
            return null;
        }

        $queryBuilder->setParameter($this->getQueryParameterName(), '%'.mb_strtolower($filterValue).'%');

        $fieldNameToExpressionConverter = function (string $fieldName) use ($queryBuilder, $entityAlias): string {
            return (string) $queryBuilder->expr()->like(
                'LOWER('.$entityAlias.'.'.$fieldName.')',
                ':'.$this->getQueryParameterName()
            );
        };

        $whereArguments = array_map($fieldNameToExpressionConverter, $this->searchFieldNames);

        return (string) \call_user_func_array([$queryBuilder->expr(), 'orX'], $whereArguments);
    }

    /**
     * @param string $searchFieldName
     *
     * @return TextFilterParameter
     */
    public function addSearchField(string $searchFieldName): self
    {
        $this->searchFieldNames[] = $searchFieldName;

        return $this;
    }
}
