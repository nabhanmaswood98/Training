<?php

namespace SomethingDigital\AuctionItem\Controller\Adminhtml\Item;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\NotFoundException;
use Magento\Ui\Component\MassAction\Filter;
use SomethingDigital\AuctionItem\Model\AuctionItemRepository;
use SomethingDigital\AuctionItem\Model\ResourceModel\AuctionItem\CollectionFactory;

/**
 * Custom Controller for AuctionItem MassDelete
 *
 * Class MassDelete
 */
class MassDelete extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level
     */
    const ADMIN_RESOURCE = 'SomethingDigital_AuctionItem::grid';

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var AuctionItemRepository
     */
    private $auctionItemRepository;

    /**
     * @var Filter
     */
    protected $filter;

    /**
     * Constructor
     *
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param AuctionItemRepository $auctionItemRepository
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        AuctionItemRepository $auctionItemRepository
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->auctionItemRepository = $auctionItemRepository;
        parent::__construct($context);
    }

    /**
     * AuctionItem delete action
     *
     * @return Redirect
     */
    public function execute(): Redirect
    {
        if (!$this->getRequest()->isPost()) {
            throw new NotFoundException(__('Page not found'));
        }
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $auctionItemsDeleted = 0;
        foreach ($collection->getItems() as $auctionItem) {
            $this->auctionItemRepository->delete($auctionItem);
            $auctionItemsDeleted++;
        }

        if ($auctionItemsDeleted) {
            $this->messageManager->addSuccessMessage(
                __('A total of %1 record(s) have been deleted.', $auctionItemsDeleted)
            );
        }
        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('rp_auction/index/index');
    }
}
