<?php

namespace SomethingDigital\AuctionItem\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Auction Item Model
 *
 * Class AuctionItem
 * @package SomethingDigital\AuctionItem\Model\ResourceModel
 */
class AuctionItem extends AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('rp_auction_items', 'entity_id');
    }
}