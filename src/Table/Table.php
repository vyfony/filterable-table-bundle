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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Vyfony\Bundle\FilterableTableBundle\DataCollector\DataCollectorInterface;
use Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\FilterConfiguratorInterface;
use Vyfony\Bundle\FilterableTableBundle\Form\Type\FilterableTableType;
use Vyfony\Bundle\FilterableTableBundle\Table\Configurator\TableConfiguratorInterface;
use Vyfony\Bundle\FilterableTableBundle\Table\Metadata\TableMetadataInterface;

/**
 * @author Anton Dyshkant <vyshkant@gmail.com>
 */
final class Table implements TableInterface
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var DataCollectorInterface
     */
    private $dataCollector;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var FormInterface
     */
    private $form;

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
     * @param RequestStack                $requestStack
     * @param DataCollectorInterface      $dataCollector
     * @param FormFactoryInterface        $formFactory
     * @param TableConfiguratorInterface  $tableConfigurator
     * @param FilterConfiguratorInterface $filterConfigurator
     * @param string                      $entityClass
     */
    public function __construct(
        RequestStack $requestStack,
        DataCollectorInterface $dataCollector,
        FormFactoryInterface $formFactory,
        TableConfiguratorInterface $tableConfigurator,
        FilterConfiguratorInterface $filterConfigurator,
        string $entityClass
    ) {
        $this->request = $requestStack->getCurrentRequest();
        $this->dataCollector = $dataCollector;
        $this->formFactory = $formFactory;
        $this->tableConfigurator = $tableConfigurator;
        $this->filterConfigurator = $filterConfigurator;
        $this->entityClass = $entityClass;
    }

    /**
     * @throws InvalidOptionsException
     *
     * @return FormView
     */
    public function getFormView(): FormView
    {
        return $this->getForm()->createView();
    }

    /**
     * @throws InvalidOptionsException
     *
     * @return TableMetadataInterface
     */
    public function getTableMetadata(): TableMetadataInterface
    {
        return $this->tableConfigurator->getTableMetadata(
            $this->dataCollector->getRowDataPaginator(
                $this->transformFormDataForDataCollection($this->getForm()->getData()),
                $this->entityClass
            ),
            $this->getQueryParameters()
        );
    }

    /**
     * @throws InvalidOptionsException
     *
     * @return FormInterface
     */
    private function getForm(): FormInterface
    {
        if (null === $this->form) {
            $this->form = $this->formFactory
                ->create(FilterableTableType::class, $this->getDefaultQueryParameters())
                ->submit($this->transformQueryParametersForFormSubmission($this->getQueryParameters()));
        }

        return $this->form;
    }

    /**
     * @param $queryParameters
     *
     * @return array
     */
    private function transformQueryParametersForFormSubmission(array $queryParameters): array
    {
        $defaultQueryParameters = $this->getDefaultQueryParameters();

        $queryParameters['limit'] = $defaultQueryParameters['limit'];
        $queryParameters['offset'] = $defaultQueryParameters['offset'];

        return $queryParameters;
    }

    private function transformFormDataForDataCollection(array $queryParameters): array
    {
        $requestParameters = $this->request->query->all();

        $queryParameters['limit'] = $requestParameters['limit'];
        $queryParameters['offset'] = $requestParameters['offset'];

        return $queryParameters;
    }

    /**
     * @return array
     */
    private function getQueryParameters(): array
    {
        return array_merge($this->getDefaultQueryParameters(), $this->request->query->all());
    }

    /**
     * @return array
     */
    private function getDefaultQueryParameters(): array
    {
        return array_merge(
            $this->tableConfigurator->getDefaultTableParameters(),
            $this->filterConfigurator->getDefaultTableParameters()
        );
    }
}
