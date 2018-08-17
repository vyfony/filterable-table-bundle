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
     * @return InnerEntityChoiceParameter
     */
    public function setChoiceLabel(string $choiceLabel): self
    {
        $this->choiceLabel = $choiceLabel;

        return $this;
    }

    /**
     * @param bool $isExpanded
     *
     * @return AbstractEntityChoiceParameter
     */
    public function setIsExpanded(bool $isExpanded): self
    {
        $this->isExpanded = $isExpanded;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return EntityType::class;
    }

    /**
     * @return string
     */
    abstract protected function getClass(): string;

    /**
     * @return array
     */
    protected function factoryAdditionalOptions(): array
    {
        return [
            'class' => $this->getClass(),
            'choice_label' => $this->choiceLabel,
            'multiple' => true,
            'expanded' => $this->isExpanded,
        ];
    }
}
