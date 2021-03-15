<?php

namespace SomethingDigital\AuctionItem\Model\ResourceModel\AuctionItem;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use SomethingDigital\AuctionItem\Model\AuctionItem as Model;
use SomethingDigital\AuctionItem\Model\ResourceModel\AuctionItem as ResourceModel;

/**
 * Auction Item Collection
 *
 * Class Collection
 * @package SomethingDigital\AuctionItem\Model\ResourceModel\AuctionItem
 */
class Collection extends AbstractCollection
{
    /**
     * Initialize resource model and model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
