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
final class CustomChoiceParameter extends AbstractFilterParameter implements ExpressionBuilderInterface
{
    /**
     * @var bool
     */
    private $isExpanded = false;

    /**
     * @var bool
     */
    private $isMultiple = true;

    /**
     * @var callable
     */
    private $choicesFactory;

    /**
     * @var callable
     */
    private $queryFactory;

    /**
     * @var string
     */
    private $type = ChoiceType::class;

    /**
     * @param bool $isExpanded
     *
     * @return CustomChoiceParameter
     */
    public function setIsExpanded(bool $isExpanded): self
    {
        $this->isExpanded = $isExpanded;

        return $this;
    }

    /**
     * @param bool $isMultiple
     *
     * @return CustomChoiceParameter
     */
    public function setIsMultiple(bool $isMultiple): self
    {
        $this->isMultiple = $isMultiple;

        return $this;
    }

    /**
     * @param callable $choicesFactory
     *
     * @return CustomChoiceParameter
     */
    public function setChoicesFactory(callable $choicesFactory): self
    {
        $this->choicesFactory = $choicesFactory;

        return $this;
    }

    /**
     * @param callable $queryFactory
     *
     * @return CustomChoiceParameter
     */
    public function setQueryFactory(callable $queryFactory): self
    {
        $this->queryFactory = $queryFactory;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return CustomChoiceParameter
     */
    public function setType(string $type): self
    {
        $this->type = $type;

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
        return \call_user_func(
            $this->queryFactory,
            $this->getQueryParameterName(),
            $queryBuilder,
            $formData,
            $entityAlias
        );
    }

    /**
     * @param EntityManager $entityManager
     *
     * @return array
     */
    protected function createOptions(EntityManager $entityManager): array
    {
        return array_merge(parent::createOptions($entityManager), [
            'expanded' => $this->isExpanded,
            'multiple' => $this->isMultiple,
            'choices' => \call_user_func($this->choicesFactory, $this->getQueryParameterName(), $entityManager),
        ]);
    }
}
