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
        ScopeConfigInterface $scopeConfig,
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
        array $items,
        ...$args
    ) {
        $preventOpenReservation = $this->scopeConfig->getValue(
            'cataloginventory/reservation/prevent_open_reservations',
            ScopeInterface::SCOPE_STORES
        );
        $preventClosedReservation = $this->scopeConfig->getValue(
            'cataloginventory/reservation/prevent_closed_reservations',
            ScopeInterface::SCOPE_STORES
        );

        if ($preventOpenReservation && $preventClosedReservation) {
            return;
        }

        if ($preventOpenReservation) {
            $items = array_filter($items, function ($item) {
                return $item->getQuantity() > 0;
            });
        } elseif ($preventClosedReservation) {
            $items = array_filter($items, function ($item) {
                return $item->getQuantity() < 0;
            });
        }

        $proceed($items, ...$args);
    }
}
