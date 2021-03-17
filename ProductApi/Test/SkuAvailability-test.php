<?php

// Designed to be placed in magento/var/

use Magento\Framework\App\Bootstrap;

require __DIR__ . '/../../../../bootstrap.php';

global $bootstrap;
$bootstrap = Bootstrap::create(BP, $_SERVER);

$obj = $bootstrap->getObjectManager();
$appState = $obj->get(\Magento\Framework\App\State::class);
$appState->setAreaCode('frontend');

class SkuAvailabilityTestApp extends \Magento\Framework\App\Http implements \Magento\Framework\AppInterface
{
    public function launch()
    {
        global $bootstrap;

        // It's bad to use the object manager normally, but this is a test script, so it's okay here.
        $obj = $bootstrap->getObjectManager();
        $appState = $obj->get(\Magento\Framework\App\State::class);

        $skuAvailability = $obj->create(\SomethingDigital\ProductApi\Model\Api\SkuAvailability::class);

        $availability1 = $skuAvailability->getAvailability('24-MB04', '1234');
        print_r($availability1);

        $availability2 = $skuAvailability->getAvailability('24-MB04', '1234');
        print_r($availability2);

        $availability3 = $skuAvailability->getAvailability('24-MB01', '');
        print_r($availability3);

        $availability4 = $skuAvailability->getAvailability('', '1234');
        print_r($availability4);

        printf("Test Complete\n");
        exit;
    }

    public function catchException(\Magento\Framework\App\Bootstrap $bootstrap, \Exception $exception): bool
    {
        return false;
    }
}

$app = $bootstrap->createApplication('SkuAvailabilityTestApp');
$bootstrap->run($app);
