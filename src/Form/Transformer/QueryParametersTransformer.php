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

class QueryParametersTransformer implements QueryParametersTransformerInterface
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
        if (array_key_exists('limit', $defaultQueryParameters)) {
            $queryParameters['limit'] = $defaultQueryParameters['limit'];
        }

        if (array_key_exists('offset', $defaultQueryParameters)) {
            $queryParameters['offset'] = $defaultQueryParameters['offset'];
        }

        return $queryParameters;
    }

    /**
     * @param array $formData
     *
     * @return array
     */
    public function transformFormDataForDataCollection(array $formData, array $requestParameters): array
    {
        if (array_key_exists('limit', $requestParameters)) {
            $formData['limit'] = $requestParameters['limit'];
        }

        if (array_key_exists('offset', $requestParameters)) {
            $formData['offset'] = $requestParameters['offset'];
        }

        return $formData;
    }
}
