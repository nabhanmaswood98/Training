<?php

namespace SomethingDigital\AuctionItem\Controller\Adminhtml\Index;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\PageFactory;

/**
 * Custom Controller for Auction Item Grid
 *
 * Class Index
 */
class Index implements HttpGetActionInterface
{
    /**
     * ACL id for resource
     *
     * @const string
     */
    const ADMIN_RESOURCE = 'SomethingDigital_AuctionItem::grid';

    /**
     * @var PageFactory
     */
    protected $pageFactory;

    /**
     * @param PageFactory $pageFactory
     */
    public function __construct(
        PageFactory $pageFactory
    ) {
        $this->pageFactory = $pageFactory;
    }

    /**
     * @return ResultInterface
     */
    public function execute()
    {
        $resultPage = $this->pageFactory->create();
        $resultPage->setActiveMenu(ADMIN_RESOURCE);
        $resultPage->getConfig()->getTitle()->prepend(__('Auction Item Grid'));
        return $resultPage;
    }
}
