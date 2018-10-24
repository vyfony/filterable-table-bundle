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

namespace Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\Parameter\TableParameter;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Vyfony\Bundle\FilterableTableBundle\Filter\Configurator\Parameter\TableParameter\RadioOption\RadioOptionInterface;
use Vyfony\Bundle\FilterableTableBundle\Table\Metadata\Column\ColumnMetadataInterface;

/**
 * @author Anton Dyshkant <vyshkant@gmail.com>
 */
final class RadioColumnChoiceTableParameter extends AbstractTableParameter
{
    /**
     * @var RadioOptionInterface[]
     */
    private $radioOptions = [];

    /**
     * @param RadioOptionInterface $radioOption
     *
     * @return RadioColumnChoiceTableParameter
     */
    public function addRadioOption(RadioOptionInterface $radioOption): self
    {
        $this->radioOptions[$radioOption->getName()] = $radioOption;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return ChoiceType::class;
    }

    /**
     * @param array $queryParameters
     *
     * @return ColumnMetadataInterface[]
     */
    public function getColumnMetadataCollection(array $queryParameters): array
    {
        return [$this->radioOptions[$queryParameters[$this->getPropertyName()]]->getColumnMetadata()];
    }

    /**
     * @return string
     */
    public function getDefaultValue(): string
    {
        reset($this->radioOptions);

        return key($this->radioOptions);
    }

    /**
     * @param EntityRepository $repository
     *
     * @return array
     */
    protected function createOptions(EntityRepository $repository): array
    {
        $choices = [];
        foreach ($this->radioOptions as $radioOption) {
            $choices[$radioOption->getLabel()] = $radioOption->getName();
        }

        return array_merge(parent::createOptions($repository), [
            'multiple' => false,
            'expanded' => true,
            'choices' => $choices,
        ]);
    }
}
