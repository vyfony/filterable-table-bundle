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

namespace Vyfony\Bundle\FilterableTableBundle\Table\Metadata\Column;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * @author Anton Dyshkant <vyshkant@gmail.com>
 */
final class ColumnMetadata implements ColumnMetadataInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var callable
     */
    private $valueExtractor;

    /**
     * @var string
     */
    private $label;

    /**
     * @var bool
     */
    private $isIdentifier = false;

    /**
     * @var bool
     */
    private $isSortable = false;

    /**
     * @var array|null
     */
    private $sortParameters;

    /**
     * @param string $name
     *
     * @return ColumnMetadata
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param callable $valueExtractor
     *
     * @return ColumnMetadata
     */
    public function setValueExtractor(callable $valueExtractor): self
    {
        $this->valueExtractor = $valueExtractor;

        return $this;
    }

    /**
     * @param $rowData
     *
     * @return string
     */
    public function getValue(object $rowData): string
    {
        if (null === $this->valueExtractor) {
            $this->valueExtractor = function ($rowData) {
                return (new PropertyAccessor())->getValue($rowData, $this->name);
            };
        }

        return \call_user_func($this->valueExtractor, $rowData);
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     *
     * @return ColumnMetadata
     */
    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @param bool $isIdentifier
     *
     * @return ColumnMetadata
     */
    public function setIsIdentifier(bool $isIdentifier): self
    {
        $this->isIdentifier = $isIdentifier;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsIdentifier(): bool
    {
        return $this->isIdentifier;
    }

    /**
     * @param bool $isSortable
     *
     * @return ColumnMetadata
     */
    public function setIsSortable(bool $isSortable): self
    {
        $this->isSortable = $isSortable;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsSortable(): bool
    {
        return $this->isSortable;
    }

    /**
     * @param array $sortParameters
     *
     * @return ColumnMetadataInterface
     */
    public function setSortParameters(array $sortParameters): ColumnMetadataInterface
    {
        $newSortParameters = [];
        foreach ($sortParameters as $sortParameterName => $sortParameterValue) {
            $newSortParameterValue = $sortParameterValue;

            if ($sortParameterValue instanceof Collection) {
                $newSortParameterValue = [];

                foreach ($sortParameterValue as $sortParameterValueElement) {
                    $newSortParameterValue[] = $sortParameterValueElement->getId();
                }
            }

            $newSortParameters[$sortParameterName] = $newSortParameterValue;
        }

        $this->sortParameters = $newSortParameters;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getSortParameters(): ?array
    {
        return $this->sortParameters;
    }
}
