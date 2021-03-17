<?php

namespace SomethingDigital\ProductApi\Api;

/**
 * @api
 */
interface SkuAvailabilityInterface
{
    /**
     * @param string $sku
     * @param string $storeSourceCode
     * @return string
     */
    public function getAvailability($sku, $storeSourceCode);
}
