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

namespace Vyfony\Bundle\FilterableTableBundle\DataCollector;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use RuntimeException;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\FilterConfiguratorInterface;
use Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\Parameter\ExpressionBuilderInterface;

/**
 * @author Anton Dyshkant <vyshkant@gmail.com>
 */
final class DataCollector implements DataCollectorInterface
{
    /**
     * @var RegistryInterface
     */
    private $doctrine;

    /**
     * @var FilterConfiguratorInterface
     */
    private $filterConfigurator;

    /**
     * @var int
     */
    private $pageSize;

    /**
     * @param RegistryInterface           $registry
     * @param FilterConfiguratorInterface $filterConfigurator
     * @param int                         $pageSize
     */
    public function __construct(
        RegistryInterface $registry,
        FilterConfiguratorInterface $filterConfigurator,
        int $pageSize
    ) {
        $this->doctrine = $registry;
        $this->filterConfigurator = $filterConfigurator;
        $this->pageSize = $pageSize;
    }

    /**
     * @param array  $formData
     * @param string $entityClass
     *
     * @throws RuntimeException
     *
     * @return DoctrinePaginator
     */
    public function getRowDataPaginator(array $formData, string $entityClass): DoctrinePaginator
    {
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

        foreach ($this->filterConfigurator->getFilterParameters() as $filterParameter) {
            if ($filterParameter instanceof ExpressionBuilderInterface
                && array_key_exists($filterParameter->getPropertyName(), $formData)
            ) {
                $whereArgument = $filterParameter->buildWhereExpression($queryBuilder, $formData, $entityAlias);

                if (null !== $whereArgument) {
                    $whereArguments[] = $whereArgument;
                }
            }
        }

        if (\count($whereArguments) > 0) {
            $queryBuilder->where(\call_user_func_array([$queryBuilder->expr(), 'andX'], $whereArguments));
        }

        $queryBuilder
            ->orderBy($entityAlias.'.'.$formData['sortBy'], $formData['sortOrder'])
            ->setFirstResult($this->pageSize * ($formData['page'] - 1))
            ->setMaxResults($this->pageSize)
        ;

        return new DoctrinePaginator($queryBuilder->getQuery(), $fetchJoinCollection = true);
    }
}
