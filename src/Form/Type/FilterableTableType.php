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
     * @param FilterConfiguratorInterface $filterConfigurator
     */
    public function __construct(FilterConfiguratorInterface $filterConfigurator)
    {
        $this->filterConfigurator = $filterConfigurator;
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
            ->add('limit', HiddenType::class)
            ->add('offset', HiddenType::class)
        ;

        foreach ($this->filterConfigurator->getFilterParameters() as $filterParameter) {
            $builder->add(
                $filterParameter->getPropertyName(),
                $filterParameter->getType(),
                $filterParameter->getOptions()
            );
        }

        foreach ($this->filterConfigurator->getTableParameters() as $tableParameter) {
            $builder->add(
                $tableParameter->getPropertyName(),
                $tableParameter->getType(),
                $tableParameter->getOptions()
            );
        }

        $builder
            ->add('submit', SubmitType::class, $this->filterConfigurator->factorySubmitButtonOptions())
        ;

        $builder
            ->add('reset', ResetType::class, $this->filterConfigurator->factoryResetButtonOptions())
        ;
    }

    /**
     * @param OptionsResolver $resolver
     *
     * @throws AccessException
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults($this->filterConfigurator->factoryDefaultOptions());
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return '';
    }
}
