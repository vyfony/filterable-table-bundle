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
     * @return ColumnMetadata
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return ColumnMetadata
     */
    public function setValueExtractor(callable $valueExtractor): self
    {
        $this->valueExtractor = $valueExtractor;

        return $this;
    }

    /**
     * @param $rowData
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

    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return ColumnMetadata
     */
    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return ColumnMetadata
     */
    public function setIsIdentifier(bool $isIdentifier): self
    {
        $this->isIdentifier = $isIdentifier;

        return $this;
    }

    public function getIsIdentifier(): bool
    {
        return $this->isIdentifier;
    }

    /**
     * @return ColumnMetadata
     */
    public function setIsSortable(bool $isSortable): self
    {
        $this->isSortable = $isSortable;

        return $this;
    }

    public function getIsSortable(): bool
    {
        return $this->isSortable;
    }

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

    public function getSortParameters(): ?array
    {
        return $this->sortParameters;
    }
}
