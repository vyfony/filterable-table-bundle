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
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

/**
 * @author Anton Dyshkant <vyshkant@gmail.com>
 */
abstract class AbstractEntityChoiceParameter extends AbstractFilterParameter implements ExpressionBuilderInterface
{
    /**
     * @var string
     */
    private $choiceLabel;

    /**
     * @var bool
     */
    private $isExpanded = true;

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
     * @param EntityRepository $repository
     *
     * @return array
     */
    protected function createOptions(EntityRepository $repository): array
    {
        return array_merge(parent::createOptions($repository), [
            'class' => $this->getClass(),
            'choice_label' => $this->choiceLabel,
            'multiple' => true,
            'expanded' => $this->isExpanded,
        ]);
    }
}
