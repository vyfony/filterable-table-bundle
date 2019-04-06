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

namespace Vyfony\Bundle\FilterableTableBundle\Form\Transformer;

/**
 * @author Anton Dyshkant <vyshkant@gmail.com>
 */
final class QueryParametersTransformer implements QueryParametersTransformerInterface
{
    /**
     * @param array $queryParameters
     * @param array $defaultQueryParameters
     *
     * @return array
     */
    public function transformQueryParametersForFormSubmission(
        array $queryParameters,
        array $defaultQueryParameters
    ): array {
        if (\array_key_exists('page', $defaultQueryParameters)) {
            $queryParameters['page'] = $defaultQueryParameters['page'];
        }

        return $queryParameters;
    }

    /**
     * @param array $formData
     * @param array $requestParameters
     *
     * @return array
     */
    public function transformFormDataForDataCollection(array $formData, array $requestParameters): array
    {
        if (\array_key_exists('page', $requestParameters)) {
            $formData['page'] = $requestParameters['page'];
        }

        return $formData;
    }
}
