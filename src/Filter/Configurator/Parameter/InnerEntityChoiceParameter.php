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
final class InnerEntityChoiceParameter extends AbstractEntityChoiceParameter
{
    /**
     * @var string
     */
    private $innerEntityClass;

    /**
     * @var string
     */
    private $innerPropertyName;

    /**
     * @param string $innerEntityClass
     *
     * @return InnerEntityChoiceParameter
     */
    public function setInnerEntityClass(string $innerEntityClass): self
    {
        $this->innerEntityClass = $innerEntityClass;

        return $this;
    }

    /**
     * @param string $innerPropertyName
     *
     * @return InnerEntityChoiceParameter
     */
    public function setInnerPropertyName(string $innerPropertyName): self
    {
        $this->innerPropertyName = $innerPropertyName;

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
        if (0 === \count($formData[$this->getPropertyName()])) {
            return null;
        }

        $ids = [];

        foreach ($formData[$this->getPropertyName()] as $entity) {
            $ids[] = $entity->getId();
        }

        $innerEntityAlias = 'innerEntity';

        $queryBuilder->join($entityAlias.'.'.$this->getPropertyName(), $innerEntityAlias);

        return (string) $queryBuilder->expr()->in($innerEntityAlias.'.'.$this->innerPropertyName, $ids);
    }

    /**
     * @return string
     */
    protected function getClass(): string
    {
        return $this->innerEntityClass;
    }
}
