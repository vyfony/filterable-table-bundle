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

namespace Vyfony\Bundle\FilterableTableBundle\Table;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Vyfony\Bundle\FilterableTableBundle\DataCollection\DataCollectorInterface;
use Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\FilterConfiguratorInterface;
use Vyfony\Bundle\FilterableTableBundle\Form\Data\FormDataInterface;
use Vyfony\Bundle\FilterableTableBundle\Form\Type\FilterableTableType;
use Vyfony\Bundle\FilterableTableBundle\Table\Configurator\TableConfiguratorInterface;
use Vyfony\Bundle\FilterableTableBundle\Table\Metadata\TableMetadataInterface;

/**
 * @author Anton Dyshkant <vyshkant@gmail.com>
 */
final class Table implements TableInterface
{
    /**
     * @var DataCollectorInterface
     */
    private $dataCollector;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var FormDataInterface
     */
    private $formData;

    /**
     * @var TableConfiguratorInterface
     */
    private $tableConfigurator;

    /**
     * @var FilterConfiguratorInterface
     */
    private $filterConfigurator;

    /**
     * @var string
     */
    private $entityClass;

    /**
     * @var FormInterface
     */
    private $form;

    public function __construct(
        DataCollectorInterface $dataCollector,
        FormFactoryInterface $formFactory,
        FormDataInterface $formData,
        TableConfiguratorInterface $tableConfigurator,
        FilterConfiguratorInterface $filterConfigurator,
        string $entityClass
    ) {
        $this->dataCollector = $dataCollector;
        $this->formFactory = $formFactory;
        $this->formData = $formData;
        $this->tableConfigurator = $tableConfigurator;
        $this->filterConfigurator = $filterConfigurator;
        $this->entityClass = $entityClass;
    }

    /**
     * @throws InvalidOptionsException
     */
    public function getFormView(): FormView
    {
        return $this->getForm()->createView();
    }

    /**
     * @throws InvalidOptionsException
     */
    public function getTableMetadata(): TableMetadataInterface
    {
        $dataCollectionResult = $this->dataCollector->getRowDataCollection(
            $this->formData->getForDataCollection($this->getForm()),
            $this->entityClass,
            function ($entity) {
                return $this->filterConfigurator->getEntityId($entity);
            }
        );

        return $this->tableConfigurator->getTableMetadata(
            $dataCollectionResult,
            $this->formData->getQueryParameters($this->tableConfigurator, $this->filterConfigurator)
        );
    }

    /**
     * @throws InvalidOptionsException
     */
    private function getForm(): FormInterface
    {
        if (null === $this->form) {
            $this->form = $this->formFactory
                ->create(
                    FilterableTableType::class,
                    $this->formData->getDefaultQueryParameters($this->tableConfigurator, $this->filterConfigurator)
                )
                ->submit($this->formData->getForSubmission($this->tableConfigurator, $this->filterConfigurator))
            ;
        }

        return $this->form;
    }
}
