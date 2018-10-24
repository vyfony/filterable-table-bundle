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
    final public function getPropertyName(): string
    {
        return $this->propertyName;
    }

    /**
     * @param EntityRepository $repository
     *
     * @return array
     */
    final public function getOptions(EntityRepository $repository): array
    {
        if (null === $this->options) {
            $this->options = $this->createOptions($repository);
        }

        return $this->options;
    }

    /**
     * @param string $propertyName
     *
     * @return AbstractFilterParameter
     */
    final public function setPropertyName(string $propertyName): self
    {
        $this->propertyName = $propertyName;

        return $this;
    }

    /**
     * @param string $label
     *
     * @return AbstractFilterParameter
     */
    final public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @param string $cssClass
     *
     * @return AbstractFilterParameter
     */
    final public function addCssClass(string $cssClass): self
    {
        $this->cssClasses[] = $cssClass;

        return $this;
    }

    /**
     * @param EntityRepository $repository
     *
     * @return array
     */
    protected function createOptions(EntityRepository $repository): array
    {
        return [
            'label' => $this->label,
            'attr' => ['class' => implode(' ', $this->cssClasses)],
        ];
    }
}
