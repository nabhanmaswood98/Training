<?php

namespace SomethingDigital\AuctionItem\Controller\Request;

use Composer\DependencyResolver\Request;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Message\ManagerInterface;
use SomethingDigital\AuctionItem\Model\AuctionItemFactory;
use SomethingDigital\AuctionItem\Model\AuctionItemRepository;
use Magento\Framework\Stdlib\DateTime\DateTimeFactory;

/**
 * Custom Controller Auction Item Post
 *
 * Class Index
 */
class Post implements HttpPostActionInterface
{

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var RedirectFactory
     */
    protected $redirectFactory;

    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * @var AuctionItemRepository
     */
    protected $auctionItemRepository;

    /**
     * @var AuctionItemFactory
     */
    protected $auctionItemFactory;

    /**
     * @var DateTimeFactory
     */
    protected $dateTimeFactory;

    /**
     * @param RequestInterface $request
     */
    public function __construct(
        RequestInterface $request,
        RedirectFactory $redirectFactory,
        ManagerInterface $messageManager,
        AuctionItemRepository $auctionItemRepository,
        AuctionItemFactory $auctionItemFactory,
        DateTimeFactory $dateTimeFactory
    ) {
        $this->request = $request;
        $this->redirectFactory = $redirectFactory;
        $this->messageManager = $messageManager;
        $this->auctionItemRepository = $auctionItemRepository;
        $this->auctionItemFactory = $auctionItemFactory;
        $this->dateTimeFactory = $dateTimeFactory;
    }

    /**
     * @return ResultInterface
     */
    public function execute()
    {
        $post = $this->request->getParams();
        if ($post) {
            $this->handlePost($post);
        }

        $redirect = $this->redirectFactory->create();
        $redirect->setUrl('/auction/request/form');

        return $redirect;
    }

    /**
     * @return Void
     */
    public function handlePost($post)
    {
        if (!$this->validatedParams($post)) {
            $this->messageManager->addErrorMessage("Invalid parameters!");
            return;
        }

        $auctionItem = $this->auctionItemFactory->create();

        $auctionItem->setItemName($post["item-name"]);
        $auctionItem->setItemSku($post["item-sku"]);
        $auctionItem->setItemBaseAuctionPrice($post["item-base-auction-price"]);
        $auctionItem->setItemOwnerEmail($post["item-owner-email"]);

        $currentDateTime = $this->dateTimeFactory->create()->gmtDate();

        $auctionItem->setCreatedAt($currentDateTime);
        $auctionItem->setModifiedAt($currentDateTime);

        try {
            $this->auctionItemRepository->save($auctionItem);
            $this->messageManager->addSuccessMessage("Successfully saved Auction Item!");
        } catch (CouldNotSaveException $exception) {
            $this->messageManager->addErrorMessage("Unable to save Auction Item!");
        }
    }

    /**
     * @return boolean
     */
    private function validatedParams($post)
    {
        if (trim($post['item-name']) === '') {
            return false;
        }

        if (!is_numeric($post['item-base-auction-price']) || $post['item-base-auction-price'] < 0) {
            return false;
        }

        if (false === \strpos($post['item-owner-email'], '@')) {
            return false;
        }

        return true;
    }
}
