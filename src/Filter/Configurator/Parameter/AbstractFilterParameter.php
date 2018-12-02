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

/**
 * @author Anton Dyshkant <vyshkant@gmail.com>
 */
abstract class AbstractFilterParameter implements FilterParameterInterface
{
    /**
     * @var string
     */
    private $queryParameterName;

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
    final public function getQueryParameterName(): string
    {
        return $this->queryParameterName;
    }

    /**
     * @param EntityManager $entityManager
     *
     * @return array
     */
    final public function getOptions(EntityManager $entityManager): array
    {
        if (null === $this->options) {
            $this->options = $this->createOptions($entityManager);
        }

        return $this->options;
    }

    /**
     * @param string $queryParameterName
     *
     * @return AbstractFilterParameter
     */
    final public function setQueryParameterName(string $queryParameterName): self
    {
        $this->queryParameterName = $queryParameterName;

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
     * @param EntityManager $entityManager
     *
     * @return array
     */
    protected function createOptions(EntityManager $entityManager): array
    {
        return [
            'label' => $this->label,
            'attr' => [
                'class' => implode(' ', $this->cssClasses),
                'data-vyfony-filterable-table-filter-parameter' => true,
            ],
        ];
    }
}
