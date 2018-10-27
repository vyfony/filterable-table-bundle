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

namespace Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\Parameter\EntityChoice;

use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\Parameter\AbstractFilterParameter;
use Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\Parameter\ExpressionBuilderInterface;

/**
 * @author Anton Dyshkant <vyshkant@gmail.com>
 */
abstract class AbstractEntityChoiceParameter extends AbstractFilterParameter implements ExpressionBuilderInterface
{
    /**
     * @var bool
     */
    private $isMultiple = true;

    /**
     * @var bool
     */
    private $isExpanded = false;

    /**
     * @var callable
     */
    private $choiceLabel;

    /**
     * @param bool $isMultiple
     *
     * @return AbstractEntityChoiceParameter
     */
    public function setIsMultiple(bool $isMultiple): self
    {
        $this->isMultiple = $isMultiple;

        return $this;
    }

    /**
     * @param bool $isExpanded
     *
     * @return AbstractEntityChoiceParameter
     */
    final public function setIsExpanded(bool $isExpanded): self
    {
        $this->isExpanded = $isExpanded;

        return $this;
    }

    /**
     * @param callable $choiceLabelFactory
     *
     * @return AbstractEntityChoiceParameter
     */
    final public function setChoiceLabelFactory(callable $choiceLabelFactory): self
    {
        $this->choiceLabel = $choiceLabelFactory;

        return $this;
    }

    /**
     * @param string $choiceLabel
     *
     * @return AbstractEntityChoiceParameter
     */
    final public function setChoiceLabel(string $choiceLabel): self
    {
        $this->choiceLabel = $choiceLabel;

        return $this;
    }

    /**
     * @return string
     */
    final public function getType(): string
    {
        return EntityType::class;
    }

    /**
     * @return string
     */
    abstract protected function getClass(): string;

    /**
     * @param EntityManager $entityManager
     *
     * @return array
     */
    protected function createOptions(EntityManager $entityManager): array
    {
        return array_merge(parent::createOptions($entityManager), [
            'class' => $this->getClass(),
            'choice_label' => $this->choiceLabel,
            'multiple' => $this->isMultiple,
            'expanded' => $this->isExpanded,
        ]);
    }
}
