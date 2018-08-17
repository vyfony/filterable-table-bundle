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

namespace Vyfony\Bundle\FilterableTableBundle\Tests;

use PHPUnit\Framework\TestCase;

/**
 * @author Anton Dyshkant <vyshkant@gmail.com>
 */
final class VyfonyFilterableTableBundleTest extends TestCase
{
    public function testBundleClassExists(): void
    {
        $this->assertTrue(class_exists(\Vyfony\Bundle\FilterableTableBundle\VyfonyFilterableTableBundle::class));
    }
}
