<?php

namespace SomethingDigital\AuctionItem\Api;

use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Api\SearchCriteriaInterface;
use SomethingDigital\AuctionItem\Model\AuctionItem;
/**
 * AuctionItem CRUD interface
 * @api
 */
interface AuctionItemRepositoryInterface
{
    /**
     * Saves AuctionItem for insertions and updates
     *
     * @param AuctionItem $auctionItem
     * @return AuctionItem
     * @throws CouldNotSaveException
     */
    public function save(AuctionItem $auctionItem);

    /**
     * Retrieve AuctionItem by Id
     *
     * @param string $auctionItemId
     * @return AuctionItem
     */
    public function getById($auctionItemId);

    /**
     * Retrieve AuctionItems matching the SearchCriteria
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return array
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete AuctionItem
     *
     * @param AuctionItem $auctionItem
     * @return boolean
     */
    public function delete(AuctionItem $auctionItem);

    /**
     * Delete AuctionItem by Id
     *
     * @param string $auctionItemId
     * @return boolean
     */
    public function deleteById($auctionItemId);
}