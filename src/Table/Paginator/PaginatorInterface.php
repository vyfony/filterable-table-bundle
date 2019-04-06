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

namespace Vyfony\Bundle\FilterableTableBundle\Table\Paginator;

use Vyfony\Bundle\FilterableTableBundle\Table\Paginator\Page\PageInterface;

/**
 * @author Anton Dyshkant <vyshkant@gmail.com>
 */
interface PaginatorInterface
{
    /**
     * @return int
     */
    public function getCurrentPageIndex(): int;

    /**
     * @return array|PageInterface[]
     */
    public function getPages(): array;

    /**
     * @return array|PageInterface[]
     */
    public function getVisiblePages(): array;

    /**
     * @param PageInterface $page
     *
     * @return bool
     */
    public function isPageCurrent(PageInterface $page): bool;
}
