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

namespace Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\Restriction;

use Doctrine\ORM\Query\Expr;

/**
 * @author Anton Dyshkant <vyshkant@gmail.com>
 */
interface FilterRestrictionInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param Expr   $expressionBuilder
     * @param string $entityAlias
     *
     * @return string
     */
    public function buildWhereExpression(Expr $expressionBuilder, string $entityAlias): string;
}
