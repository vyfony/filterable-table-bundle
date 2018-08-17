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

/**
 * @author Anton Dyshkant <vyshkant@gmail.com>
 */
abstract class AbstractFilterParameter implements FilterParameterInterface
{
    /**
     * @var string
     */
    private $propertyName;

    /**
     * @var string
     */
    private $label;

    /**
     * @var array
     */
    private $options;

    /**
     * @var string[]
     */
    private $cssClasses = [];

    /**
     * @return string
     */
    public function getPropertyName(): string
    {
        return $this->propertyName;
    }

    /**
     * @param array $commonOptions
     */
    public function applyCommonOptions(array $commonOptions): void
    {
        $this->options = array_merge($commonOptions, $this->getOptions());
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        if (null === $this->options) {
            $this->options = array_merge($this->factoryOptions(), $this->factoryAdditionalOptions());
        }

        return $this->options;
    }

    /**
     * @param string $propertyName
     *
     * @return AbstractFilterParameter
     */
    public function setPropertyName(string $propertyName): self
    {
        $this->propertyName = $propertyName;

        return $this;
    }

    /**
     * @param string $label
     *
     * @return AbstractFilterParameter
     */
    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @param string $cssClass
     *
     * @return AbstractFilterParameter
     */
    public function addCssClass(string $cssClass): self
    {
        $this->cssClasses[] = $cssClass;

        return $this;
    }

    /**
     * @return array
     */
    abstract protected function factoryAdditionalOptions(): array;

    /**
     * @return array
     */
    private function factoryOptions(): array
    {
        return [
            'label' => $this->label,
            'attr' => ['class' => implode(' ', $this->cssClasses)],
        ];
    }
}
