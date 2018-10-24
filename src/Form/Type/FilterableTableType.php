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

namespace Vyfony\Bundle\FilterableTableBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Exception\AccessException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\FilterConfiguratorInterface;

/**
 * @author Anton Dyshkant <vyshkant@gmail.com>
 */
final class FilterableTableType extends AbstractType
{
    /**
     * @var FilterConfiguratorInterface
     */
    private $filterConfigurator;

    /**
     * @var EntityRepository
     */
    private $repository;

    /**
     * @param FilterConfiguratorInterface $filterConfigurator
     * @param RegistryInterface           $doctrine
     * @param string                      $entityClass
     */
    public function __construct(
        FilterConfiguratorInterface $filterConfigurator,
        RegistryInterface $doctrine,
        string $entityClass
    ) {
        $this->filterConfigurator = $filterConfigurator;
        $this->repository = $doctrine->getRepository($entityClass);
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sortBy', HiddenType::class)
            ->add('sortOrder', HiddenType::class)
            ->add('page', HiddenType::class)
        ;

        foreach ($this->filterConfigurator->getFilterParameters() as $filterParameter) {
            $builder->add(
                $filterParameter->getPropertyName(),
                $filterParameter->getType(),
                $filterParameter->getOptions($this->repository)
            );
        }

        foreach ($this->filterConfigurator->getTableParameters() as $tableParameter) {
            $builder->add(
                $tableParameter->getPropertyName(),
                $tableParameter->getType(),
                $tableParameter->getOptions($this->repository)
            );
        }

        $builder
            ->add('submit', SubmitType::class, $this->filterConfigurator->createSubmitButtonOptions())
        ;

        $builder
            ->add('reset', ResetType::class, $this->filterConfigurator->createResetButtonOptions())
        ;
    }

    /**
     * @param OptionsResolver $resolver
     *
     * @throws AccessException
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults($this->filterConfigurator->createDefaults());
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return '';
    }
}
