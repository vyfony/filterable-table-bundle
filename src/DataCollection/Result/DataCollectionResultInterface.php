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
interface DataCollectionResultInterface
{
    /**
     * @return iterable
     */
    public function getData(): iterable;

    /**
     * @return int
     */
    public function getDataCount(): int;

    /**
     * @return bool
     */
    public function getHasPagination(): bool;

    /**
     * @return string
     */
    public function getRequestId(): string;
}
