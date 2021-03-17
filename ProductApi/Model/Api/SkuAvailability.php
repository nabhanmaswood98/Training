<?php

namespace SomethingDigital\ProductApi\Model\Api;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Inventory\Model\ResourceModel\StockSourceLink;
use Magento\InventoryApi\Api\GetStockSourceLinksInterface;
use Magento\InventoryApi\Api\SourceItemRepositoryInterface;
use Magento\InventorySalesApi\Api\GetProductSalableQtyInterface;
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
     * @var GetStockSourceLinksInterface
     */
    protected $getStockSourceLinks;

    /**
     * @var GetProductSalableQtyInterface
     */
    protected $getProductSalableQty;

    public function __construct(
        SourceItemRepositoryInterface $sourceItemRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FilterBuilder $filterBuilder,
        GetStockSourceLinksInterface $getStockSourceLinks,
        GetProductSalableQtyInterface $getProductSalableQty
    ) {
        $this->sourceItemRepository = $sourceItemRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->getStockSourceLinks = $getStockSourceLinks;
        $this->getProductSalableQty = $getProductSalableQty;
    }

    /**
     * @inheritdoc
     */
    public function getAvailability(
        $sku,
        $storeSourceCode
    ) {
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

        $this->searchCriteriaBuilder->addFilters([$sourceCodeFilter, $defaultCodeFilter]);
        $searchCriteria = $this->searchCriteriaBuilder->create();

        $stockSourceLinks = $this->getStockSourceLinks->execute($searchCriteria)->getItems();

        return $this->mapSourceToQuantity($sku, $stockSourceLinks);
    }

    /**
     * Maps the source code to the sku's salable quantity
     *
     * @param string $sku
     * @param StockSourceLink[] $stockSourceLinks
     * @return array
     */
    private function mapSourceToQuantity($sku, $stockSourceLinks)
    {
        $mappedSourceItems = [];
        foreach ($stockSourceLinks as $stockSourceLink) {
            $stockId = $stockSourceLink->getStockId();
            if ($stockId && $sku) {
                $salableQty = $this->getProductSalableQty->execute($sku, $stockId);
                $mappedSourceItems[$stockSourceLink->getSourceCode()] = $salableQty;
            }
        }
        return $mappedSourceItems;
    }
}
