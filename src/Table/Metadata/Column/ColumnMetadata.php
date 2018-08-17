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
