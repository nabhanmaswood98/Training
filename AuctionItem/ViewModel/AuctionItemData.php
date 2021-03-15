<?php

namespace SomethingDigital\AuctionItem\ViewModel;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use SomethingDigital\AuctionItem\Model\AuctionItemRepository;

/**
 * View Model to display Auction Items
 *
 * Class AuctionItemCollectionRenderer
 */
class AuctionItemData implements ArgumentInterface
{
    /**
     * @var AuctionItemRepository
     */
    protected $auctionItemRepository;

    /**
     * @var AuctionItemRepository
     */
    protected $searchCriteriaBuilder;

    /**
     * @param AuctionItemRepository $auctionItemRepository
     */
    public function __construct(
        AuctionItemRepository $auctionItemRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->auctionItemRepository = $auctionItemRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * Returns collection of all Contacts
     *
     * @return AuctionItem[]
     */
    public function getAllAuctionItems()
    {
        return $this->auctionItemRepository->getList($this->searchCriteriaBuilder->create())->getItems();
    }
}
