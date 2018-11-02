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
     * @param CheckboxHandlerInterface[] $checkboxHandlers
     * @param ColumnMetadataInterface[]  $columnMetadataCollection
     * @param iterable                   $rowDataCollection
     * @param string                     $listRoute
     * @param string                     $showRoute
     * @param array                      $showRouteParameters
     * @param array                      $queryParameters
     * @param PaginatorInterface|null    $paginator
     */
    public function __construct(
        array $checkboxHandlers,
        array $columnMetadataCollection,
        iterable $rowDataCollection,
        string $listRoute,
        string $showRoute,
        array $showRouteParameters,
        array $queryParameters,
        ?PaginatorInterface $paginator
    ) {
        $this->checkboxHandlers = $checkboxHandlers;
        $this->columnMetadataCollection = $columnMetadataCollection;
        $this->rowDataCollection = $rowDataCollection;
        $this->listRoute = $listRoute;
        $this->showRoute = $showRoute;
        $this->showRouteParameters = $showRouteParameters;
        $this->queryParameters = $queryParameters;
        $this->paginator = $paginator;
    }

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
     * @return ColumnMetadataInterface[]
     */
    public function getColumnMetadataCollection(): array
    {
        return $this->columnMetadataCollection;
    }

    /**
     * @return iterable
     */
    public function getRowDataCollection(): iterable
    {
        return $this->rowDataCollection;
    }

    /**
     * @return bool
     */
    public function hasPaginator(): bool
    {
        return null !== $this->paginator;
    }

    /**
     * @return PaginatorInterface
     */
    public function getPaginator(): PaginatorInterface
    {
        return $this->paginator;
    }

    /**
     * @return string
     */
    public function getListRoute(): string
    {
        return $this->listRoute;
    }

    /**
     * @return string
     */
    public function getShowRoute(): string
    {
        return $this->showRoute;
    }

    /**
     * @return array
     */
    public function getShowRouteParameters(): array
    {
        return $this->showRouteParameters;
    }

    /**
     * @return array
     */
    public function getQueryParameters(): array
    {
        return $this->queryParameters;
    }
}
