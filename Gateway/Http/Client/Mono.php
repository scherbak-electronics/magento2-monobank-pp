<?php
/**
 * Copyright © Scherbak Electronics All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Shch\Mono\Gateway\Http\Client;

use Laminas\Http\Exception\RuntimeException;
use Magento\Framework\HTTP\LaminasClientFactory;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Payment\Gateway\Http\ClientException;
use Magento\Payment\Gateway\Http\ClientInterface;
use Magento\Payment\Gateway\Http\TransferInterface;
use Magento\Payment\Model\Method\Logger;

class Mono implements ClientInterface
{
    const SUCCESS = 1;
    const FAILURE = 0;

    private array $results = [
        self::SUCCESS,
        self::FAILURE
    ];

    private LaminasClientFactory $clientFactory;
    private Logger $logger;
    private Json $json;

    /**
     * @param LaminasClientFactory $clientFactory
     * @param Json $json
     * @param Logger $logger
     */
    public function __construct(
        LaminasClientFactory $clientFactory,
        Json   $json,
        Logger $logger
    ) {
        $this->logger = $logger;
        $this->json = $json;
        $this->clientFactory = $clientFactory;
    }

    /**
     * Places request to gateway. Returns result array
     *
     * @param TransferInterface $transferObject
     * @return array
     * @throws ClientException
     */
    public function placeRequest(TransferInterface $transferObject): array
    {
        $log = [
            'request' => $transferObject->getBody(),
            'request_uri' => $transferObject->getUri()
        ];
        $result = [];
        $client = $this->clientFactory->create();
        $client->setOptions($transferObject->getClientConfig());
        $client->setMethod($transferObject->getMethod());
        $client->setHeaders($transferObject->getHeaders());
        $client->setUri($transferObject->getUri());

        try {
            $response = $client->send();
            $result = $response->getBody();
            $log['response'] = $result;
        } catch (RuntimeException $e) {
            throw new ClientException(__($e->getMessage()));
        } finally {
            $this->logger->debug($log);
        }

        return $this->json->unserialize($result);
    }
}
