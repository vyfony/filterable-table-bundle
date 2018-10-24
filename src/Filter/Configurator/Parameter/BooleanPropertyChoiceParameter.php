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
use Vyfony\Bundle\FilterableTableBundle\Form\Type\BooleanExclusiveChoiceType;

/**
 * @author Anton Dyshkant <vyshkant@gmail.com>
 */
final class BooleanPropertyChoiceParameter extends AbstractFilterParameter implements ExpressionBuilderInterface
{
    /**
     * @var string
     */
    private $trueValueLabel;

    /**
     * @var string
     */
    private $falseValueLabel;

    /**
     * @param string $trueValueLabel
     *
     * @return BooleanPropertyChoiceParameter
     */
    public function setTrueValueLabel(string $trueValueLabel): self
    {
        $this->trueValueLabel = $trueValueLabel;

        return $this;
    }

    /**
     * @param string $falseValueLabel
     *
     * @return BooleanPropertyChoiceParameter
     */
    public function setFalseValueLabel(string $falseValueLabel): self
    {
        $this->falseValueLabel = $falseValueLabel;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return BooleanExclusiveChoiceType::class;
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
        return array_merge(parent::createOptions($repository), [
            'expanded' => true,
            'choice_value_false_label' => $this->falseValueLabel,
            'choice_value_true_label' => $this->trueValueLabel,
        ]);
    }
}
