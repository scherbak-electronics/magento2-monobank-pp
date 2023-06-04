<?php
/**
 * Copyright © Scherbak Electronics All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Shch\Mono\Model\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;
use Shch\Mono\Gateway\Http\Client\Mono;

/**
 * Class ConfigProvider
 */
final class ConfigProvider implements ConfigProviderInterface
{
    const CODE = 'mono_pp';

    /**
     * Retrieve assoc array of checkout configuration
     *
     * @return array
     */
    public function getConfig(): array
    {
        return [
            'payment' => [
                self::CODE => [
                    'transactionResults' => [
                        Mono::SUCCESS => __('Success'),
                        Mono::FAILURE => __('Fraud')
                    ]
                ]
            ]
        ];
    }
}
