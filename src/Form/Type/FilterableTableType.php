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

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @param FilterConfiguratorInterface $filterConfigurator
     * @param EntityManager               $entityManager
     */
    public function __construct(
        FilterConfiguratorInterface $filterConfigurator,
        EntityManager $entityManager
    ) {
        $this->filterConfigurator = $filterConfigurator;
        $this->entityManager = $entityManager;
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
                $filterParameter->getQueryParameterName(),
                $filterParameter->getType(),
                $filterParameter->getOptions($this->entityManager)
            );
        }

        foreach ($this->filterConfigurator->getTableParameters() as $tableParameter) {
            $builder->add(
                $tableParameter->getQueryParameterName(),
                $tableParameter->getType(),
                $tableParameter->getOptions($this->entityManager)
            );
        }

        $builder
            ->add('disablePagination', CheckboxType::class, [
                'label' => $this->filterConfigurator->getDisablePaginationLabel(),
                'required' => false,
            ])
            ->add('submit', SubmitType::class, $this->filterConfigurator->createSubmitButtonOptions())
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
