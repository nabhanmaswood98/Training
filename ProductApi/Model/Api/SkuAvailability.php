<?php

namespace SomethingDigital\ProductApi\Model\Api;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\FilterGroupBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\InventoryApi\Api\SourceItemRepositoryInterface;
use SomethingDigital\ProductApi\Api\SkuAvailabilityInterface;

/**
 * Class to retrieve the Quantity Available for a Product
 *
 * Class SkuAvailability
 */
class SkuAvailability implements SkuAvailabilityInterface
{
    /**
     * @var SourceItemRepository
     */
    protected $sourceItemRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var FilterBuilder
     */
    protected $filterBuilder;

    /**
     * @var FilterGroupBuilder
     */
    protected $filterGroupBuilder;

    public function __construct(
        SourceItemRepositoryInterface $sourceItemRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FilterBuilder $filterBuilder,
        FilterGroupBuilder $filterGroupBuilder
    ) {
        $this->sourceItemRepository = $sourceItemRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->filterGroupBuilder = $filterGroupBuilder;
    }

    /**
     * @inheritdoc
     */
    public function getAvailability(
        $sku,
        $storeSourceCode
    ) {
        $skuFilter = $this->filterBuilder
            ->setField('sku')
            ->setConditionType('eq')
            ->setValue($sku)
            ->create();

        $filterGroup1 = $this->filterGroupBuilder
            ->setFilters([$skuFilter])
            ->create();

        $sourceCodeFilter = $this->filterBuilder
            ->setField('source_code')
            ->setConditionType('eq')
            ->setValue([$storeSourceCode])
            ->create();

        $defaultCodeFilter = $this->filterBuilder
            ->setField('source_code')
            ->setConditionType('eq')
            ->setValue('default')
            ->create();

        $filterGroup2 = $this->filterGroupBuilder
            ->setFilters([$sourceCodeFilter, $defaultCodeFilter])
            ->create();

        $this->searchCriteriaBuilder->setFilterGroups([$filterGroup1, $filterGroup2]);
        $searchCriteria = $this->searchCriteriaBuilder->create();

        $sourceItems = $this->sourceItemRepository->getList($searchCriteria)->getItems();

        return $this->mapSourceItems($sourceItems);
    }

    /**
     * Maps each SourceItem's source_code to its quantity
     *
     * @param SourceItem[] $storeSourceCode
     * @return array
     */
    private function mapSourceItems($sourceItems)
    {
        $mappedSourceItems = [];
        foreach ($sourceItems as $sourceItem) {
            $mappedSourceItems[$sourceItem->getSourceCode()] = $sourceItem->getQuantity();
        }
        return $mappedSourceItems;
    }
}
