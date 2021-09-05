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

namespace Vyfony\Bundle\FilterableTableBundle\DataCollection;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use Doctrine\Persistence\ManagerRegistry;
use Psr\SimpleCache\CacheInterface;
use RuntimeException;
use Vyfony\Bundle\FilterableTableBundle\DataCollection\Result\DataCollectionResult;
use Vyfony\Bundle\FilterableTableBundle\DataCollection\Result\DataCollectionResultInterface;
use Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\FilterConfiguratorInterface;
use Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\Parameter\ExpressionBuilderInterface;
use Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\Parameter\FilterParameterInterface;
use Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\Sorting\CustomSortConfigurationInterface;
use Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\Sorting\DbSortConfigurationInterface;
use Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\Sorting\SortConfigurationInterface;

final class DataCollector implements DataCollectorInterface
{
    /**
     * @var ManagerRegistry
     */
    private $doctrine;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var FilterConfiguratorInterface
     */
    private $filterConfigurator;

    public function __construct(
        ManagerRegistry $registry,
        CacheInterface $cache,
        FilterConfiguratorInterface $filterConfigurator
    ) {
        $this->doctrine = $registry;
        $this->cache = $cache;
        $this->filterConfigurator = $filterConfigurator;
    }

    public function getRowDataCollection(
        array $formData,
        string $entityClass,
        callable $entityIdGetter,
        SortConfigurationInterface $sortConfiguration
    ): DataCollectionResultInterface {
        $repository = $this->doctrine->getRepository($entityClass);

        if (!$repository instanceof EntityRepository) {
            throw new RuntimeException(sprintf('Unexpected repository class "%s"', \get_class($repository)));
        }

        $entityAlias = 'entity';

        $queryBuilder = $repository->createQueryBuilder($entityAlias);

        $whereArguments = [];

        foreach ($this->filterConfigurator->getFilterRestrictions() as $filterRestriction) {
            $whereArguments[] = $filterRestriction->buildWhereExpression($queryBuilder->expr(), $entityAlias);
        }

        $filterParametersByQueryParameterName = [];

        foreach ($this->filterConfigurator->getFilterParameters() as $filterParameter) {
            $filterParametersByQueryParameterName[$filterParameter->getQueryParameterName()] = $filterParameter;
        }

        $formDataCollection = $this->getAllFormDataCollection($formData);

        $formDataDependentArgumentsCollection = [];
        foreach ($formDataCollection as $formDateItem) {
            $formDataDependentArgumentsCollection[] = $this->handleFromData(
                $formDateItem,
                $queryBuilder,
                $entityAlias,
                $filterParametersByQueryParameterName
            );
        }

        $whereArguments = array_merge($whereArguments, ...$formDataDependentArgumentsCollection);

        if (\count($whereArguments) > 0) {
            $queryBuilder->where((string) $queryBuilder->expr()->andX(...$whereArguments));
        }

        $requestId = $this->saveFormDataToCache($formData);

        if ($sortConfiguration instanceof DbSortConfigurationInterface) {
            $queryBuilder
                ->orderBy($entityAlias.'.'.$formData['sortBy'], $formData['sortOrder'])
            ;

            if ($formData['disablePagination']) {
                return new DataCollectionResult(
                    $queryBuilder->getQuery()->getResult(),
                    false,
                    $requestId
                );
            }

            $queryBuilder
                ->setFirstResult($sortConfiguration->getPageSize() * ((int) $formData['page'] - 1))
                ->setMaxResults($sortConfiguration->getPageSize())
            ;

            $matchingEntitiesPaginator = new DoctrinePaginator($queryBuilder->getQuery(), true);

            return new DataCollectionResult(
                $matchingEntitiesPaginator,
                true,
                $requestId
            );
        }

        $data = $queryBuilder->getQuery()->getResult();

        return new DataCollectionResult(
            $sortConfiguration instanceof CustomSortConfigurationInterface ? $sortConfiguration->sort($data) : $data,
            false,
            $requestId
        );
    }

    private function saveFormDataToCache(array $formData): string
    {
        $requestId = uniqid();

        $this->cache->set($requestId, $formData);

        return $requestId;
    }

    private function getFormDataFromCache(string $requestId): array
    {
        return $this->cache->get($requestId);
    }

    /**
     * @param $filterParametersByQueryParameterName FilterParameterInterface[]
     *
     * @return string[]
     */
    private function handleFromData(
        array $formData,
        QueryBuilder $queryBuilder,
        string $entityAlias,
        array $filterParametersByQueryParameterName
    ): array {
        $whereArguments = [];

        foreach ($formData as $formDataItemKey => $formDataItem) {
            if (\array_key_exists($formDataItemKey, $filterParametersByQueryParameterName)) {
                $filterParameter = $filterParametersByQueryParameterName[$formDataItemKey];

                $queryParameterName = $filterParameter->getQueryParameterName();

                if ($filterParameter instanceof ExpressionBuilderInterface) {
                    $whereArgument = $filterParameter->buildWhereExpression(
                        $queryBuilder,
                        $formData[$queryParameterName],
                        $entityAlias
                    );

                    if (null !== $whereArgument) {
                        $whereArguments[] = $whereArgument;
                    }
                }
            }
        }

        return $whereArguments;
    }

    private function getPreviousRequestFormData(array $formData): ?array
    {
        if (\array_key_exists('requestId', $formData) && null !== $formData['requestId']) {
            return $this->getFormDataFromCache($formData['requestId']);
        }

        return null;
    }

    private function getAllFormDataCollection(array $formData): array
    {
        $collection = [];

        do {
            $collection[] = $formData;
        } while (null !== $formData = $this->getPreviousRequestFormData($formData));

        return $collection;
    }
}
