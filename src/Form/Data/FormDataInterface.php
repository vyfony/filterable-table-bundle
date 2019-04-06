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

namespace Vyfony\Bundle\FilterableTableBundle\Form\Data;

use Symfony\Component\Form\FormInterface;
use Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\FilterConfiguratorInterface;
use Vyfony\Bundle\FilterableTableBundle\Table\Configurator\TableConfiguratorInterface;

/**
 * @author Anton Dyshkant <vyshkant@gmail.com>
 */
interface FormDataInterface
{
    /**
     * @param TableConfiguratorInterface  $tableConfigurator
     * @param FilterConfiguratorInterface $filterConfigurator
     *
     * @return array
     */
    public function getForSubmission(
        TableConfiguratorInterface $tableConfigurator,
        FilterConfiguratorInterface $filterConfigurator
    ): array;

    /**
     * @param FormInterface $form
     *
     * @return array
     */
    public function getForDataCollection(FormInterface $form): array;

    /**
     * @param TableConfiguratorInterface  $tableConfigurator
     * @param FilterConfiguratorInterface $filterConfigurator
     *
     * @return array
     */
    public function getQueryParameters(
        TableConfiguratorInterface $tableConfigurator,
        FilterConfiguratorInterface $filterConfigurator
    ): array;

    /**
     * @param TableConfiguratorInterface  $tableConfigurator
     * @param FilterConfiguratorInterface $filterConfigurator
     *
     * @return array
     */
    public function getDefaultQueryParameters(
        TableConfiguratorInterface $tableConfigurator,
        FilterConfiguratorInterface $filterConfigurator
    ): array;
}
