<?php

namespace SomethingDigital\AuctionItem\Model;

use Magento\Framework\Model\AbstractModel;
use SomethingDigital\AuctionItem\Model\ResourceModel\AuctionItem as ResourceModel;

/**
 * Auction Item Model
 *
 * Class AuctionItem
 * @package SomethingDigital\AuctionItem\Model
 */
class AuctionItem extends AbstractModel
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

}