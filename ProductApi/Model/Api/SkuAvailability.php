<?php

namespace SomethingDigital\ProductApi\Model\Api;

use Psr\Log\LoggerInterface;
use SomethingDigital\ProductApi\Api\SkuAvailabilityInterface;

class SkuAvailability implements SkuAvailabilityInterface
{
    protected $logger;

    public function __construct(
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
    }

    /**
     * @inheritdoc
     */
    public function getAvailability(
        $sku,
        $storeSourceCode
    ) {

    }
}
