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

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * @author Anton Dyshkant <vyshkant@gmail.com>
 */
final class IntegerChoiceParameter extends AbstractFilterParameter implements ExpressionBuilderInterface
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return ChoiceType::class;
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
        if (0 === \count($formData[$this->getPropertyName()])) {
            return null;
        }

        $values = [];

        foreach ($formData[$this->getPropertyName()] as $value) {
            $values[] = $value;
        }

        return (string) $queryBuilder->expr()->in($entityAlias.'.'.$this->getPropertyName(), $values);
    }

    /**
     * @param EntityRepository $repository
     *
     * @return array
     */
    protected function createOptions(EntityRepository $repository): array
    {
        $entityCollection = $repository
            ->createQueryBuilder('entity')
            ->select('entity.'.$this->getPropertyName())
            ->distinct()
            ->getQuery()
            ->getResult();

        $convertEntityToPropertyValue = function (array $entity): int {
            return $entity[$this->getPropertyName()];
        };

        $properties = array_map($convertEntityToPropertyValue, $entityCollection);

        return array_merge(parent::createOptions($repository), [
            'expanded' => false,
            'multiple' => true,
            'choices' => array_combine($properties, $properties),
        ]);
    }
}
