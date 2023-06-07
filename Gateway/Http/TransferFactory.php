<?php
/**
 * Copyright © Scherbak Electronics All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Shch\Mono\Gateway\Http;

use Magento\Payment\Gateway\Http\TransferBuilder;
use Magento\Payment\Gateway\Http\TransferFactoryInterface;
use Magento\Payment\Gateway\Http\TransferInterface;
use Magento\Payment\Gateway\Config\Config;


class TransferFactory implements TransferFactoryInterface
{
    /**
     * @var TransferBuilder
     */
    private TransferBuilder $transferBuilder;
    private Config $config;

    /**
     * @param TransferBuilder $transferBuilder
     * @param Config $config
     */
    public function __construct(
        TransferBuilder $transferBuilder,
        Config $config
    ) {
        $this->transferBuilder = $transferBuilder;
        $this->config = $config;
    }

    /**
     * Builds gateway transfer object
     *
     * @param array $request
     * @return TransferInterface
     */
    public function create(array $request): TransferInterface
    {
        $content = json_encode($request);
        $signature = base64_encode(hash_hmac("sha256", $content, $this->config->getSecretKey(), true));
        return $this->transferBuilder
            ->setBody($content)
            ->setMethod('POST')
            ->setHeaders(
                [
                    'store-id: ' . $this->config->getStoreId(),
                    'signature: ' . $signature,
                    'Content-Type: application/json',
                    'Accept: application/json',
                ]
            )
            ->build();
    }
}
