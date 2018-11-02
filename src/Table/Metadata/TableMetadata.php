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

namespace Vyfony\Bundle\FilterableTableBundle\Table\Metadata;

use Vyfony\Bundle\FilterableTableBundle\Table\Checkbox\CheckboxHandlerInterface;
use Vyfony\Bundle\FilterableTableBundle\Table\Metadata\Column\ColumnMetadataInterface;
use Vyfony\Bundle\FilterableTableBundle\Table\Paginator\PaginatorInterface;

/**
 * @author Anton Dyshkant <vyshkant@gmail.com>
 */
final class TableMetadata implements TableMetadataInterface
{
    /**
     * @var CheckboxHandlerInterface[]
     */
    private $checkboxHandlers;

    /**
     * @var ColumnMetadataInterface[]
     */
    private $columnMetadataCollection;

    /**
     * @var iterable
     */
    private $rowDataCollection;

    /**
     * @var string
     */
    private $listRoute;

    /**
     * @var string
     */
    private $showRoute;

    /**
     * @var array
     */
    private $showRouteParameters;

    /**
     * @var array
     */
    private $queryParameters;

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * @return bool
     */
    public function hasCheckboxColumn(): bool
    {
        return \count($this->checkboxHandlers) > 0;
    }

    /**
     * @return CheckboxHandlerInterface[]
     */
    public function getCheckboxHandlers(): array
    {
        return $this->checkboxHandlers;
    }

    /**
     * @param CheckboxHandlerInterface[] $checkboxHandlers
     *
     * @return TableMetadata
     */
    public function setCheckboxHandlers(array $checkboxHandlers): self
    {
        $this->checkboxHandlers = $checkboxHandlers;

        return $this;
    }

    /**
     * @return ColumnMetadataInterface[]
     */
    public function getColumnMetadataCollection(): array
    {
        return $this->columnMetadataCollection;
    }

    /**
     * @param ColumnMetadataInterface[] $columnMetadataCollection
     *
     * @return TableMetadata
     */
    public function setColumnMetadataCollection(array $columnMetadataCollection): self
    {
        $this->columnMetadataCollection = $columnMetadataCollection;

        return $this;
    }

    /**
     * @return iterable
     */
    public function getRowDataCollection(): iterable
    {
        return $this->rowDataCollection;
    }

    /**
     * @param iterable $rowDataCollection
     *
     * @return TableMetadata
     */
    public function setRowDataCollection(iterable $rowDataCollection): self
    {
        $this->rowDataCollection = $rowDataCollection;

        return $this;
    }

    /**
     * @return PaginatorInterface
     */
    public function getPaginator(): PaginatorInterface
    {
        return $this->paginator;
    }

    /**
     * @param PaginatorInterface $paginator
     *
     * @return TableMetadata
     */
    public function setPaginator(PaginatorInterface $paginator): self
    {
        $this->paginator = $paginator;

        return $this;
    }

    /**
     * @return string
     */
    public function getListRoute(): string
    {
        return $this->listRoute;
    }

    /**
     * @param string $listRoute
     *
     * @return TableMetadata
     */
    public function setListRoute(string $listRoute): self
    {
        $this->listRoute = $listRoute;

        return $this;
    }

    /**
     * @return string
     */
    public function getShowRoute(): string
    {
        return $this->showRoute;
    }

    /**
     * @param string $showRoute
     *
     * @return TableMetadata
     */
    public function setShowRoute(string $showRoute): self
    {
        $this->showRoute = $showRoute;

        return $this;
    }

    /**
     * @return array
     */
    public function getShowRouteParameters(): array
    {
        return $this->showRouteParameters;
    }

    /**
     * @param array $showRouteParameters
     *
     * @return TableMetadata
     */
    public function setShowRouteParameters(array $showRouteParameters): self
    {
        $this->showRouteParameters = $showRouteParameters;

        return $this;
    }

    /**
     * @return array
     */
    public function getQueryParameters(): array
    {
        return $this->queryParameters;
    }

    /**
     * @param array $queryParameters
     *
     * @return TableMetadata
     */
    public function setQueryParameters(array $queryParameters): self
    {
        $this->queryParameters = $queryParameters;

        return $this;
    }
}
