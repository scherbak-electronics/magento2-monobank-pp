<?php
/**
 * Copyright © Scherbak Electronics All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Shch\Mono\Gateway\Request;

use Magento\Payment\Gateway\ConfigInterface;
use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;

class OrderCreateRequest implements BuilderInterface
{
    /**
     * @var ConfigInterface
     */
    private ConfigInterface $config;

    /**
     * @param ConfigInterface $config
     */
    public function __construct(
        ConfigInterface $config
    ) {
        $this->config = $config;
    }

    /**
     * Builds Подання заявки на оформлення Покупки Частинами request
     * https://u2-demo-ext.mono.st4g3.com/docs/index.html#operation/createOrderUsingPOST
     *
     * @param array $buildSubject
     * @return array
     */
    public function build(array $buildSubject): array
    {
        if (!isset($buildSubject['payment'])
            || !$buildSubject['payment'] instanceof PaymentDataObjectInterface
        ) {
            throw new \InvalidArgumentException('Payment data object should be provided');
        }

        /** @var PaymentDataObjectInterface $payment */
        $payment = $buildSubject['payment'];
        $order = $payment->getOrder();
        $address = $order->getShippingAddress();

        return [
            'store_order_id' => 'A',
            'client_phone' => '+380500000000',
            'total_sum' => '',
            'invoice' => [
                'date' => '2018-01-23',
                'number' => '1234-1234',
                'point_id' => 1234,
                'source' => 'INTERNET'
            ]
//            'INVOICE' => $order->getOrderIncrementId(),
//            'AMOUNT' => $order->getGrandTotalAmount(),
//            'CURRENCY' => $order->getCurrencyCode(),
//            'EMAIL' => $address->getEmail(),
//            'MERCHANT_KEY' => $this->config->getValue(
//                'merchant_gateway_key',
//                $order->getStoreId()
//            )
        ];
    }
}
