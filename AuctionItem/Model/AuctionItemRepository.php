<?php

namespace SomethingDigital\AuctionItem\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use SomethingDigital\AuctionItem\Api\AuctionItemRepositoryInterface;
use SomethingDigital\AuctionItem\Model\ResourceModel\AuctionItem as AuctionItemResource;
use SomethingDigital\AuctionItem\Model\ResourceModel\AuctionItem\CollectionFactory as AuctionItemCollectionFactory;
use Psr\Log\LoggerInterface;
/**
 * Repository for AuctionItem
 *
 * Class AuctionItemRepository
 * @package SomethingDigital\AuctionItem\Model
 */
class AuctionItemRepository implements AuctionItemRepositoryInterface
{
    /**
     * @var AuctionItemResource
     */
    protected $resource;

    /**
     * @var AuctionItemFactory
     */
    protected $auctionItemFactory;

    /**
     * @var AuctionItemCollectionFactory
     */
    protected $auctionItemCollectionFactory;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var SearchResultsFactory
     */
    protected $searchResultsFactory;

    /**
     * @var CollectionProcessor
     */
    protected $collectionProcessor;

    /**
     * @param AuctionItemResource $resource
     * @param AuctionItemFactory $auctionItemFactory
     * @param AuctionItemCollectionFactory $auctionItemCollectionFactory
     * @param LoggerInterface $logger
     * @param CollectionProcessorInterface $collectionProcessor
     * @param SearchResultsFactory $searchResultsFactory
     */
    public function __construct(
        AuctionItemResource $resource,
        AuctionItemFactory $auctionItemFactory,
        AuctionItemCollectionFactory $auctionItemCollectionFactory,
        LoggerInterface $logger,
        CollectionProcessorInterface $collectionProcessor,
        SearchResultsFactory $searchResultsFactory
    ) {
        $this->resource = $resource;
        $this->auctionItemFactory = $auctionItemFactory;
        $this->auctionItemCollectionFactory = $auctionItemCollectionFactory;
        $this->logger = $logger;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    /**
     * Saves AuctionItem for insertions and updates
     *
     * @param AuctionItem $auctionItem
     * @return AuctionItem
     * @throws CouldNotSaveException
     */
    public function save(AuctionItem $auctionItem)
    {
        try {
            $this->resource->save($auctionItem);
        } catch (\Exception $exception) {
            $this->logger->critical($exception);
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $auctionItem;
    }

    /**
     * Retrieve AuctionItem by Id
     *
     * @param string $auctionItemId
     * @return AuctionItem
     */
    public function getById($auctionItemId)
    {
        $auctionItem = $this->auctionItemFactory->create();
        $this->resource->load($auctionItem, $auctionItemId);
        if (!$auctionItem->getId()) {
            throw new NoSuchEntityException(__('The Clue with the "%1" ID doesn\'t exist.', $auctionItemId));
        }
        return $auctionItem;
    }

    /**
     * Retrieve AuctionItems matching the SearchCriteria
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return array
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->auctionItemCollectionFactory->create();

        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * Delete AuctionItem
     *
     * @param AuctionItem $auctionItem
     * @return boolean
     */
    public function delete(AuctionItem $auctionItem)
    {
        try {
            $this->resource->delete($auctionItem);
        } catch (\Exception $exception) {
            $this->logger->critical($exception);
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * Delete AuctionItem by Id
     *
     * @param string $auctionItemId
     * @return boolean
     */
    public function deleteById($auctionItemId)
    {
        return $this->delete($this->getById($auctionItemId));
    }
}