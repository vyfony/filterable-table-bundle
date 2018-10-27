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

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * @author Anton Dyshkant <vyshkant@gmail.com>
 */
final class IntegerChoiceParameter extends AbstractFilterParameter implements ExpressionBuilderInterface
{
    /**
     * @var string
     */
    private $class;

    /**
     * @param string $class
     *
     * @return IntegerChoiceParameter
     */
    public function setClass(string $class): self
    {
        $this->class = $class;

        return $this;
    }

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
        if (0 === \count($formData[$this->getQueryParameterName()])) {
            return null;
        }

        $values = [];

        foreach ($formData[$this->getQueryParameterName()] as $value) {
            $values[] = $value;
        }

        return (string) $queryBuilder->expr()->in($entityAlias.'.'.$this->getQueryParameterName(), $values);
    }

    /**
     * @param EntityManager $entityManager
     *
     * @return array
     */
    protected function createOptions(EntityManager $entityManager): array
    {
        $entityCollection = $entityManager->getRepository($this->class)
            ->createQueryBuilder('entity')
            ->select('entity.'.$this->getQueryParameterName())
            ->distinct()
            ->orderBy('entity.'.$this->getQueryParameterName(), 'ASC')
            ->getQuery()
            ->getResult();

        $convertEntityToPropertyValue = function (array $entity): int {
            return $entity[$this->getQueryParameterName()];
        };

        $properties = array_map($convertEntityToPropertyValue, $entityCollection);

        return array_merge(parent::createOptions($entityManager), [
            'expanded' => false,
            'multiple' => true,
            'choices' => array_combine($properties, $properties),
        ]);
    }
}
