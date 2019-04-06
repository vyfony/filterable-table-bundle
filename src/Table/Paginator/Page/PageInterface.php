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

namespace Vyfony\Bundle\FilterableTableBundle\Table\Paginator\Page;

/**
 * @author Anton Dyshkant <vyshkant@gmail.com>
 */
interface PageInterface
{
    /**
     * @return int
     */
    public function getIndex(): int;

    /**
     * @return string
     */
    public function getUrl(): string;
}
