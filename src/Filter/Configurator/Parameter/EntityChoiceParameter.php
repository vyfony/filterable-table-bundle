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

/**
 * @author Anton Dyshkant <vyshkant@gmail.com>
 */
final class EntityChoiceParameter extends AbstractEntityChoiceParameter
{
    /**
     * @var string
     */
    private $class;

    /**
     * @param string $class
     *
     * @return EntityChoiceParameter
     */
    public function setClass(string $class): self
    {
        $this->class = $class;

        return $this;
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
        if (0 === count($formData[$this->getPropertyName()])) {
            return null;
        }

        $ids = [];

        foreach ($formData[$this->getPropertyName()] as $entity) {
            $ids[] = $entity->getId();
        }

        return $queryBuilder->expr()->in($entityAlias.'.'.$this->getPropertyName(), $ids);
    }

    /**
     * @return string
     */
    protected function getClass(): string
    {
        return $this->class;
    }
}
