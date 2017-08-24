<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\FactFinder\DataProvider;

use Generated\Shared\Transfer\FactFinderSdkRecommendationRequestTransfer;
use SprykerEco\Yves\FactFinder\Dependency\Clients\FactFinderToFactFinderClientInterface;

class RecommendationsDataProvider implements RecommendationsDataProviderInterface
{

    /**
     * @var \SprykerEco\Yves\FactFinder\Dependency\Clients\FactFinderToFactFinderClientInterface
     */
    protected $factFinderClient;

    /**
     * @param \SprykerEco\Yves\FactFinder\Dependency\Clients\FactFinderToFactFinderClientInterface $factFinderClient
     */
    public function __construct(FactFinderToFactFinderClientInterface $factFinderClient)
    {
        $this->factFinderClient = $factFinderClient;
    }

    /**
     * @param array $parameters
     *
     * @return array|\Generated\Shared\Transfer\StorageProductAbstractRelationTransfer[]
     */
    public function buildTemplateData(array $parameters)
    {
        $factFinderRecommendationRequestTransfer = new FactFinderSdkRecommendationRequestTransfer();
        $factFinderRecommendationRequestTransfer->fromArray($parameters, true);

        $factFinderRecommendationsResponseTransfer = $this->factFinderClient
            ->getRecommendations($factFinderRecommendationRequestTransfer);

        $recommendations = $factFinderRecommendationsResponseTransfer->getRecommendations();

        return $recommendations;
    }

}
