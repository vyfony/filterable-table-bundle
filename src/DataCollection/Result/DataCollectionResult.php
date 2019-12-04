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

namespace Vyfony\Bundle\FilterableTableBundle\DataCollection\Result;

/**
 * @author Anton Dyshkant <vyshkant@gmail.com>
 */
final class DataCollectionResult implements DataCollectionResultInterface
{
    /**
     * @var iterable
     */
    private $data;

    /**
     * @var int
     */
    private $dataCount;

    /**
     * @var bool
     */
    private $hasPagination;

    /**
     * @var string
     */
    private $requestId;

    public function __construct(iterable $data, int $dataCount, bool $hasPagination, string $requestId)
    {
        $this->data = $data;
        $this->dataCount = $dataCount;
        $this->hasPagination = $hasPagination;
        $this->requestId = $requestId;
    }

    public function getData(): iterable
    {
        return $this->data;
    }

    public function getDataCount(): int
    {
        return $this->dataCount;
    }

    public function getHasPagination(): bool
    {
        return $this->hasPagination;
    }

    public function getRequestId(): string
    {
        return $this->requestId;
    }
}
