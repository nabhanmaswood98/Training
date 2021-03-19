<?php

namespace SomethingDigital\ReservationProcess\Plugin;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\InventorySalesApi\Api\PlaceReservationsForSalesEventInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Plugin class to intercept PlaceReservationsForSalesEventInterface
 *
 * Class ToggleReservationPlugin
 */
class ToggleReservationPlugin
{
    /**
     * @var ScopeConfig
     */
    protected $scopeConfig;

    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param PlaceReservationsForSalesEventInterface $subject
     * @param callable $proceed
     * @return void
     */
    public function aroundExecute(
        PlaceReservationsForSalesEventInterface $subject,
        callable $proceed,
        ...$args
    ) {
        $proceed(...$args);
    }
}
