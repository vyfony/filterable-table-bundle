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

namespace Vyfony\Bundle\FilterableTableBundle\Persistence\QueryBuilder\Alias;

/**
 * @author Anton Dyshkant <vyshkant@gmail.com>
 */
final class AliasFactory implements AliasFactoryInterface
{
    public function createAlias(string $className, string $alias): string
    {
        $classNameParts = explode('\\', $className);

        $classShortName = array_pop($classNameParts);

        return strtolower($classShortName.'_'.$alias.'_'.uniqid());
    }
}
